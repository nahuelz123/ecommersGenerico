<?php

use Illuminate\Support\Facades\Route;
use App\Models\City;
use App\Http\Controllers\{
    CartController,
    CheckoutController,
    OrderController,
    ProductController,
    ShippingAddressController,
    HomeController,
    UserController
};
use Livewire\Volt\Volt;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::resource('products', ProductController::class)->only(['index', 'show']);

/*
|--------------------------------------------------------------------------
| Rutas Autenticadas
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
    | Rutas de Usuario
    |--------------------------------------------------------------------------
    */
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('edit', [UserController::class, 'edit'])->name('edit');
        Route::put('update', [UserController::class, 'update'])->name('update');
    });

    /*
    |--------------------------------------------------------------------------
    | Rutas Admin
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
        Route::post('addToCart/{product}', [CartController::class, 'addToCart'])->name('addToCart');
        Route::post('update/{product}', [CartController::class, 'update'])->name('update');
        Route::delete('remove/{product}', [CartController::class, 'remove'])->name('remove');
        Route::post('clear', [CartController::class, 'clear'])->name('clear');
    });

    /*
    |--------------------------------------------------------------------------
    | Checkout
    |--------------------------------------------------------------------------
    */
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/', [CheckoutController::class, 'store'])->name('store');
        Route::post('direct-purchase/{product}', [CheckoutController::class, 'directPurchase'])->name('directPurchase');
    });

    /*
    |--------------------------------------------------------------------------
    | Órdenes del Usuario
    |--------------------------------------------------------------------------
    */
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('user', [OrderController::class, 'index'])->name('index');
        Route::get('user/{order}', [OrderController::class, 'show'])->name('show');
    });
    
    

    /*
    |--------------------------------------------------------------------------
    | Direcciones de Envío
    |--------------------------------------------------------------------------
    */
    Route::resource('addresses', ShippingAddressController::class)->except(['show']);
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

// API alternativa
Route::get('/api/provinces/{province}/cities', function ($provinceId) {
    return City::where('province_id', $provinceId)->get();
});

require __DIR__ . '/auth.php';
