<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;


class ProductController extends Controller
{


    public function index()
    {
        $products = Product::with('category', 'brand', 'images')->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'color' => 'required|string|max:100',
            'size' => 'required|string|max:100',
            'main_image' => 'required|image',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'gallery.*' => 'image|nullable'
        ]);

        // Guardar imagen principal
        $data['main_image'] = $request->file('main_image')->store('products');

        $product = Product::create($data);

        // Galería de imágenes
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $img) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $img->store('products/gallery'),
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product created!');
    }

    public function show(Product $product)
    {
        $product->load('images', 'category', 'brand');
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $product->load('images');
        return view('products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'color' => 'required|string|max:100',
            'size' => 'required|string|max:100',
            'main_image' => 'image|nullable',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'gallery.*' => 'image|nullable'
        ]);

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('products');
        }

        $product->update($data);

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $img) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $img->store('products/gallery'),
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product updated!');
    }

    public function destroy(Product $product)
    {
        $product->images()->delete(); // borra las imágenes asociadas
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted!');
    }
}
