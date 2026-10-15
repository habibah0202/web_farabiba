<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\ProfileController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = Product::with('category')
        ->where('status', 'active')
        ->withAvg('reviews', 'rating')
        ->withCount('reviews')
        ->latest()
        ->get();

    return view('welcome', compact('products'));
})->name('home');

Route::view('/our-story', 'our-story')->name('our-story');

Route::middleware(['auth', 'verified', 'active'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/admin/customers', [\App\Http\Controllers\AdminCustomerController::class, 'index'])->name('admin.customers.index');
    Route::patch('/admin/customers/{user}/suspension', [\App\Http\Controllers\AdminCustomerController::class, 'toggleSuspension'])->name('admin.customers.suspension');
    Route::delete('/admin/customers/{user}', [\App\Http\Controllers\AdminCustomerController::class, 'destroy'])->name('admin.customers.destroy');
    Route::get('/admin/customers/transactions', [\App\Http\Controllers\AdminCustomerController::class, 'transactions'])->name('admin.customers.transactions');
    Route::get('/admin/transactions/sales/export', [\App\Http\Controllers\AdminTransactionController::class, 'salesPdf'])->name('admin.transactions.sales.pdf');
    Route::get('/admin/transactions/sales', [\App\Http\Controllers\AdminTransactionController::class, 'sales'])->name('admin.transactions.sales');
    Route::get('/admin/transactions/receipts', [\App\Http\Controllers\AdminTransactionController::class, 'receipts'])->name('admin.transactions.receipts');
    Route::get('/admin/orders/tracking', [\App\Http\Controllers\AdminTransactionController::class, 'tracking'])->name('admin.orders.tracking');
    Route::patch('/admin/orders/{transaction}/tracking', [\App\Http\Controllers\AdminTransactionController::class, 'updateTracking'])->name('admin.orders.tracking.update');
    Route::get('/admin/transactions/{transaction}/receipt', [\App\Http\Controllers\AdminTransactionController::class, 'receipt'])->name('admin.transactions.receipt');
    Route::get('/admin/reports', [\App\Http\Controllers\AdminReportController::class, 'index'])->name('admin.reports.index');

    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::get('/stock', [ProductController::class, 'stock'])->name('stock.index');
    Route::post('/stock/{product}/adjust', [ProductController::class, 'adjustStock'])->name('stock.adjust');
    Route::get('/stock/in', [ProductController::class, 'stockIn'])->name('stock.in');
    Route::post('/stock/in', [ProductController::class, 'storeStockIn'])->name('stock.in.store');
    Route::get('/stock/out', [ProductController::class, 'stockOut'])->name('stock.out');
    Route::post('/stock/out', [ProductController::class, 'storeStockOut'])->name('stock.out.store');
    Route::get('/stock/tracking', [ProductController::class, 'stockTracking'])->name('stock.tracking');
    Route::get('/transactions', [ProductController::class, 'transactions'])->name('transactions.index');
});

Route::middleware(['auth', 'active'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('index');
    Route::get('/categories', [CustomerController::class, 'categories'])->name('categories');
    Route::get('/search', [CustomerController::class, 'search'])->name('search');
    Route::get('/filter', [CustomerController::class, 'filter'])->name('filter');
    Route::get('/products/{product}', [CustomerController::class, 'show'])->name('products.show');
    Route::post('/products/{product}/reviews', [ProductReviewController::class, 'store'])->name('products.reviews.store');
    Route::post('/products/{product}/cart', [CustomerController::class, 'addToCart'])->name('products.addToCart');
    Route::get('/cart', [CustomerController::class, 'cart'])->name('cart');
    Route::patch('/cart/{product}', [CustomerController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/{product}', [CustomerController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/checkout', [CustomerController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [CustomerController::class, 'storeCheckout'])->name('checkout.store');
    Route::get('/transactions', [CustomerController::class, 'transactions'])->name('transactions');
    Route::get('/transactions/{id}', [CustomerController::class, 'transactionDetail'])->name('transactions.show');
    Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [CustomerProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
