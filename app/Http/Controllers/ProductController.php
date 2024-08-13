<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stok' => 'required|integer',
            'price' => 'required|numeric',
            'status' => 'required|string',
        ]);

        Product::create($request->all());

        Alert::success('Berhasil!', 'Produk berhasil ditambahkan.');
        return redirect()->route('products.index');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stok' => 'required|integer',
            'price' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $product->update($request->all());

        Alert::success('Berhasil!', 'Produk berhasil diperbarui.');
        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        Alert::success('Berhasil!', 'Produk berhasil dihapus.');
        return redirect()->route('products.index');
    }
}
