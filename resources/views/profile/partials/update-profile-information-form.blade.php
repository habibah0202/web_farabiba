<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="foto" value="Foto Profil" />

            <div class="mt-2 flex flex-col items-start gap-3">
                @if ($user->foto)
                    <img id="foto-preview" src="{{ Storage::url($user->foto) }}" alt="Foto profil {{ $user->name }}" class="h-[300px] w-[300px] rounded-full object-cover ring-2 ring-gray-200">
                @else
                    <div id="foto-placeholder" class="flex h-[300px] w-[300px] items-center justify-center rounded-full bg-indigo-100 text-7xl font-semibold text-indigo-700">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <img id="foto-preview" class="hidden h-[300px] w-[300px] rounded-full object-cover ring-2 ring-gray-200" alt="Pratinjau foto profil">
                @endif

                <div class="flex items-center gap-3">
                    <input id="foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp" class="sr-only" />
                    <label for="foto" class="cursor-pointer rounded-md bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-100">Choose File</label>
                    <p class="text-sm text-gray-500">JPG, PNG, atau WEBP. Maksimal 2 MB.</p>
                </div>
            </div>

            <x-input-error class="mt-2" :messages="$errors->get('foto')" />
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <script>
        document.getElementById('foto')?.addEventListener('change', function (event) {
            const file = event.target.files[0];
            const preview = document.getElementById('foto-preview');
            const placeholder = document.getElementById('foto-placeholder');

            if (! file) return;

            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
            placeholder?.classList.add('hidden');
        });
    </script>
</section>
