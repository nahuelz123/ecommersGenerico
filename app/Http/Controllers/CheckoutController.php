<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
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

   /*  public function store(Request $request)
    {
        $cart = session('cart', []);

        // Aquí deberías guardar la orden en la base de datos
        // Solo simularemos la compra por ahora y vaciaremos el carrito

        session()->forget('cart');
        session()->forget('direct_purchase'); // Aseguramos que también se elimine la compra directa

        return redirect()->route('cart.index')->with('success', 'Compra realizada con éxito.');
    }
 */



public function store(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'address_id' => 'required|exists:shipping_addresses,id',
        'shipping_method_id' => 'required|exists:shipping_methods,id',
        'payment_method_id' => 'required|exists:payment_methods,id',
    ]);

    $cart = session('cart', []);
    $directPurchase = session('direct_purchase');

    if ($directPurchase) {
        $product = Product::findOrFail($directPurchase['product_id']);
        $cart = [
            $product->id => [
                'product' => $product,
                'quantity' => $directPurchase['quantity']
            ]
        ];
    }

    if (empty($cart)) {
        return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
    }

    // Calcular el total
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['product']->price * $item['quantity'];
    }

    // Crear la orden
    $order = Order::create([
        'user_id' => $user->id,
        'shipping_address_id' => $request->address_id,
        'shipping_method_id' => $request->shipping_method_id,
        'payment_method_id' => $request->payment_method_id,
        'total' => $total,
        'status' => 'pendiente', // puedes usar un enum o constantes
    ]);

    // Crear los productos asociados a la orden
    foreach ($cart as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item['product']->id,
            'quantity' => $item['quantity'],
            'price' => $item['product']->price,
        ]);
    }

    // Limpiar el carrito y la compra directa
    session()->forget('cart');
    session()->forget('direct_purchase');

    return redirect()->route('orders.show', $order->id)->with('success', 'Compra realizada con éxito.');
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
