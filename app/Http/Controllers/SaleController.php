<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\SaleDetail;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('user')->get();
        return view('admin.sales.index', compact('sales'));
    }

    public function showDetails(Sale $sale)
    {
        $sale->load('details.product'); // Load details and related products
        return view('admin.sales.details', compact('sale'));
    }

    public function create()
    {
        $products = Product::where('status', 'in stock')->get();
        $users = User::all(); // Ambil semua pengguna
        return view('admin.sales.create', compact('products', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.quantity' => [
                function ($attribute, $value, $fail) use ($request) {
                    $productId = $request->input('products.' . explode('.', $attribute)[1] . '.product_id');
                    $product = Product::find($productId);
                    if ($product->stok < $value) {
                        $fail('Stok produk ' . $product->name . ' tidak cukup.');
                    }
                }
            ],
        ]);

        // Calculate total price
        $total = 0;
        foreach ($request->products as $productData) {
            $total += Product::find($productData['product_id'])->price * $productData['quantity'];
        }

        // Create Sale
        $sale = Sale::create([
            'user_id' => $request->user_id,
            'total' => $total,
        ]);


        // Create Sale Details
        foreach ($request->products as $productData) {
            $product = Product::find($productData['product_id']);

            // Kurangi stok produk
            $product->stok -= $productData['quantity'];
            $product->save();

            // Perbarui status produk
            $product->status = $product->stok <= 0 ? 'out of stock' : 'in stock';
            $product->save();

            $sale->details()->create([
                'product_id' => $productData['product_id'],
                'quantity' => $productData['quantity'],
                'price' => Product::find($productData['product_id'])->price,
                'total' => Product::find($productData['product_id'])->price * $productData['quantity'],
            ]);
        }

        Alert::success('Berhasil!', 'Penjualan berhasil ditambahkan.');
        return redirect()->route('sales.index');
    }


    public function edit(Sale $sale)
    {
        $products = Product::where('status', 'in stock')->get();
        $users = User::all(); // Ambil semua pengguna
        return view('admin.sales.edit', compact('sale', 'products', 'users'));
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.quantity' => [
                function ($attribute, $value, $fail) use ($request) {
                    $productId = $request->input('products.' . explode('.', $attribute)[1] . '.product_id');
                    $product = Product::find($productId);
                    if ($product->stok < $value) {
                        $fail('Stok produk ' . $product->name . ' tidak cukup.');
                    }
                }
            ],
        ]);

        // Hitung total harga
        $total = 0;
        foreach ($request->products as $productData) {
            $total += Product::find($productData['id'])->price * $productData['quantity'];
        }

        // Perbarui data penjualan
        $sale->update([
            'user_id' => $request->user_id,
            'total' => $total,
        ]);

        // Hapus detail lama dan simpan detail baru
        $sale->details()->delete();
        foreach ($request->products as $productData) {
            $sale->details()->create([
                'product_id' => $productData['id'],
                'quantity' => $productData['quantity'],
                'price' => Product::find($productData['id'])->price,
                'total' => Product::find($productData['id'])->price * $productData['quantity'],
            ]);
        }

        Alert::success('Berhasil!', 'Penjualan berhasil diperbarui.');
        return redirect()->route('sales.index');
    }


    public function destroy(Sale $sale)
    {

        foreach ($sale->details as $detail) {
            $product = Product::find($detail->product_id);

            // Tambahkan stok produk
            $product->stok += $detail->quantity;
            $product->save();

            // Perbarui status produk
            $product->status = $product->stok <= 0 ? 'out of stock' : 'in stock';
            $product->save();
        }


        $sale->details()->delete();
        $sale->delete();

        Alert::success('Berhasil!', 'Penjualan berhasil dihapus.');
        return redirect()->route('sales.index');
    }

    public function showSaleDetails(Request $request)
    {
        $month = $request->input('month', date('n')); // Default to current month
        $year = $request->input('year', date('Y')); // Default to current year

        // Fetch sale details for the selected month and year
        $saleDetails = SaleDetail::with('sale', 'product')
            ->whereMonth('sales.created_at', $month)
            ->whereYear('sales.created_at', $year)
            ->join('sales', 'sales.id', '=', 'sale_details.sale_id')
            ->get();

        return view('admin.sales.details', [
            'saleDetails' => $saleDetails,
            'selectedMonth' => $month,
            'selectedYear' => $year,
        ]);
    }

    public function exportPdf()
    {
        // Ambil semua data penjualan dengan detailnya
        $sales = Sale::with('details')->get();

        // Generate PDF
        $pdf = Pdf::loadView('admin.sales.details_pdf', compact('sales'));
        return $pdf->stream('sales_details_report.pdf');
    }
}
