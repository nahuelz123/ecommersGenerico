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
        // Obtener el carrito de la sesión
        $cart = session('cart', []);
        
        // Obtener la compra directa si existe
        $directPurchase = session('direct_purchase');

        // Obtener la dirección de envío, métodos de envío y pago
        $user = Auth::user();
        $addresses = $user->shippingAddresses;
        $shippingMethods = ShippingMethod::all();
        $paymentMethods = PaymentMethod::all();

        // Si existe una compra directa, mostrar solo ese producto
        if ($directPurchase) {
            // Crear un array con la compra directa como único "producto"
            $cart = [
                $directPurchase['product_id'] => [
                    'product' => Product::findOrFail($directPurchase['product_id']),
                    'quantity' => $directPurchase['quantity']
                ]
            ];
        }

        return view('checkout.index', [
            'cart' => $cart,
            'addresses' => $addresses,
            'shippingMethods' => $shippingMethods,
            'paymentMethods' => $paymentMethods
        ]);
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

    public function directPurchase(Product $product)
    {
        // Guardamos la compra directa en la sesión
        session()->put('direct_purchase', [
            'product_id' => $product->id,
            'quantity' => 1 // Si quieres permitir elegir cantidad, cambia esta parte
        ]);

        // Redirigir al checkout
        return redirect()->route('checkout.index');
    }
}
