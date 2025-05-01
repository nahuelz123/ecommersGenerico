<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        // Obtén el carrito de la sesión o crea uno vacío si no existe
        $cart = session('cart', []);
        
        return view('cart.index', compact('cart'));
    }

    public function addToCart(Request $request, Product $product)
    {
        $cart = session('cart', []);
        $productId = $product->id;
        $quantity = max(1, (int) $request->input('quantity', 1));
    
        // Eliminar compra directa si la hay
        session()->forget('direct_purchase');
    
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product' => $product,
                'quantity' => $quantity
            ];
        }
    
        session(['cart' => $cart]);
    
        return redirect()->route('cart.index')->with('success', 'Producto agregado al carrito.');
    }
    

    public function update(Request $request, Product $product)
    {
        $cart = session('cart', []);

        // Verifica si el producto está en el carrito
        if (isset($cart[$product->id])) {
            // Si la cantidad es válida, actualiza la cantidad
            $quantity = $request->input('quantity', 1);
            if ($quantity > 0) {
                $cart[$product->id]['quantity'] = $quantity;
            } else {
                return redirect()->route('cart.index')->with('error', 'La cantidad debe ser mayor a 0.');
            }

            // Guarda el carrito actualizado en la sesión
            session(['cart' => $cart]);
        }

        return redirect()->route('cart.index')->with('success', 'Carrito actualizado.');
    }

    public function remove(Product $product)
    {
        $cart = session('cart', []);
        
        // Verifica si el producto está en el carrito y elimínalo
        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session(['cart' => $cart]);
        }

        return redirect()->route('cart.index')->with('success', 'Producto eliminado del carrito.');
    }

    public function clear()
    {
        // Elimina el carrito completo de la sesión
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Carrito vaciado.');
    }
}
