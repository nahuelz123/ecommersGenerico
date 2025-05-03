<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{

    public function index()
    {
        $cart = session('cart', []);
        $user = Auth::user();
        $addresses = $user->shippingAddresses;
        $shippingMethods = ShippingMethod::all();
        $paymentMethods = PaymentMethod::all();

        return view('checkout.index', compact('cart', 'addresses', 'shippingMethods', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $cart = session('cart', []);

        // Aquí deberías guardar la orden en la base de datos
        // Solo simularemos la compra por ahora y vaciaremos el carrito

        session()->forget('cart');
        session()->forget('direct_purchase'); // Aseguramos que también se elimine la compra directa

        return redirect()->route('cart.index')->with('success', 'Compra realizada con éxito.');
    }

    public function directPurchase(Product $product, Request $request)
    {
        //Reseteo la session
        session()->forget('direct_purchase');

        $quantity = $request->input('quantity');
        if ($quantity > $product->stock) {
            return back()->withErrors(['quantity' => 'No hay suficiente stock disponible.']);
        }
        // Guardamos la compra directa en la sesión
        session()->put('direct_purchase', [
            'product_id' => $product->id,
            'subtotal' => $quantity * $product->price,
            'quantity' => $quantity // Si quieres permitir elegir cantidad, cambia esta parte
        ]);

        $user = Auth::user();
        $addresses = $user->shippingAddresses;
        $shippingMethods = ShippingMethod::all();
        $paymentMethods = PaymentMethod::all();
        // Dirige al checkout
        session()->flash('success', 'Producto agregado con exito');
        return view('checkout.indexDirect', compact('product', 'addresses', 'shippingMethods', 'paymentMethods'));
    }
}
