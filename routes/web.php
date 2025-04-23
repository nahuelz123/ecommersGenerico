<?php

use Illuminate\Support\Facades\Route;
use App\Models\City;
use App\Http\Controllers\{
    CartController,
    CheckoutController,
    OrderController,
    ProductController,
    ShippingAddressController
};
use Livewire\Volt\Volt;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('welcome'))->name('home');

Route::resource('products', ProductController::class)->only(['index', 'show']);

/*
|--------------------------------------------------------------------------
| Rutas Autenticadas Generales
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::view('dashboard', 'dashboard')->middleware(['verified'])->name('dashboard');

    // Configuración del usuario con Livewire Volt
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('products', ProductController::class);
        Route::resource('orders', OrderController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | Carrito de Compras
    |--------------------------------------------------------------------------
    */
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('add/{product}', [CartController::class, 'add'])->name('add');
        Route::post('update/{product}', [CartController::class, 'update'])->name('update');
        Route::delete('remove/{product}', [CartController::class, 'remove'])->name('remove');
        Route::post('clear', [CartController::class, 'clear'])->name('clear');
    });

    /*
    |--------------------------------------------------------------------------
    | Checkout
    |--------------------------------------------------------------------------
    */
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    /*
    |--------------------------------------------------------------------------
    | Direcciones de Envío
    |--------------------------------------------------------------------------
    */
    Route::prefix('addresses')->name('addresses.')->group(function () {
        Route::get('/', [ShippingAddressController::class, 'index'])->name('index');
        Route::get('/create', [ShippingAddressController::class, 'create'])->name('create');
        Route::post('/', [ShippingAddressController::class, 'store'])->name('store');
        Route::get('/{address}/edit', [ShippingAddressController::class, 'edit'])->name('edit');
        Route::put('/{address}', [ShippingAddressController::class, 'update'])->name('update');
        Route::delete('/{address}', [ShippingAddressController::class, 'destroy'])->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Endpoints Auxiliares
|--------------------------------------------------------------------------
*/

// Cargar ciudades según provincia (para JS dinámico)
Route::get('/get-cities/{provinceId}', function ($provinceId) {
    $cities = City::where('province_id', $provinceId)->orderBy('name')->get();
    return response()->json(['cities' => $cities]);
});

// API ciudades por provincia (alternativa)
Route::get('/api/provinces/{province}/cities', function ($provinceId) {
    return City::where('province_id', $provinceId)->get();
});

require __DIR__ . '/auth.php';
