<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ShippingAddressController;
use App\Models\City;
use Livewire\Volt\Volt;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('products', ProductController::class);
});

Route::resource('products', ProductController::class);

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('orders', OrderController::class);
});



// Carrito
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');


Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

// web.php
Route::get('/addresses', [ShippingAddressController::class, 'index'])->name('addresses.index');
Route::get('/addresses/create', [ShippingAddressController::class, 'create'])->name('addresses.create');


Route::get('/get-cities/{provinceId}', function ($provinceId) {
    // Obtener las ciudades de la provincia seleccionada y ordenarlas por nombre
    $cities = City::where('province_id', $provinceId)->orderBy('name')->get();
    return response()->json(['cities' => $cities]);
});


Route::post('/addresses', [ShippingAddressController::class, 'store'])->name('addresses.store');

// Para API de ciudades
Route::get('/api/provinces/{province}/cities', function ($provinceId) {
    return \App\Models\City::where('province_id', $provinceId)->get();
});



require __DIR__ . '/auth.php';
