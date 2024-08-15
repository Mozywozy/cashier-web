<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class GudangController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('gudang.index', compact('products'));
    }

    public function create()
    {
        return view('gudang.create');
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
        return redirect()->route('gudang.index');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('gudang.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        Alert::success('Berhasil!', 'Produk berhasil di edit.');
        return redirect()->route('gudang.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        Alert::success('Berhasil!', 'Produk berhasil dihapus.');
        return redirect()->route('gudang.index');
    }
}
