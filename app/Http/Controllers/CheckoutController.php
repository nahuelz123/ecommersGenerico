<?php
namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
{
    $cart = session('cart', []);
    $user = Auth::user();

    return view('checkout.index', [
        'cart' => $cart,
        'addresses' => $user->shippingAddresses, // relación del modelo User con direcciones
        'shippingMethods' => ShippingMethod::all(),
        'paymentMethods' => PaymentMethod::all(),
    ]);
}

    public function store(Request $request)
    {
        $cart = session('cart', []);

        // Acá deberías guardar la orden en la base de datos
        // Por ahora solo vamos a vaciar el carrito y simular una compra

        session()->forget('cart');

        return redirect()->route('cart.index')->with('success', 'Compra realizada con éxito.');
    }
}
