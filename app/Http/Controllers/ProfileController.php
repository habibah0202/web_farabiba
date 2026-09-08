<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Menampilkan halaman profil
    public function edit()
    {
        $user = auth()->user();

        return view('profile.edit', compact('user'));
    }


    // Menyimpan perubahan profil dan foto
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email,' . $request->user()->id,
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Mengambil user yang sedang login
        $user = $request->user();


        // Jika ada foto yang diupload
        if ($request->hasFile('foto')) {

            // Hapus foto lama jika ada
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {

                Storage::disk('public')->delete($user->foto);
            }


            // Upload foto baru
            $path = $request->file('foto')
                ->store('foto-profil', 'public');


            // Simpan path foto baru
            $user->foto = $path;
        }


        // Update nama
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Simpan ke database
        $user->save();


        return redirect()
            ->back()
            ->with('status', 'profile-updated');
    }
}