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

    public function add(Request $request, Product $product)
    {
        $cart = session('cart', []);
        
        // Verifica si el producto ya está en el carrito
        if (isset($cart[$product->id])) {
            // Si ya está, incrementa la cantidad
            $cart[$product->id]['quantity'] += 1;
        } else {
            // Si no está, lo agrega con cantidad 1
            $cart[$product->id] = [
                'product' => $product,
                'quantity' => 1,
            ];
        }

        // Guarda el carrito en la sesión
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
