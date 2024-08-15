<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{
    public function recentTransactions()
    {
        $userId = Auth::id(); // Ambil ID pengguna saat ini
        $transactions = Sale::where('user_id', $userId)
            ->latest() // Urutkan dari yang terbaru
            ->take(5) // Ambil 5 transaksi terbaru
            ->get();

        return view('pelanggan.recent_transactions', compact('transactions'));
    }

    public function transactionDetails($id)
    {
        $transaction = Sale::with('saleDetails.product')->findOrFail($id);
        return view('pelanggan.transaction_details', compact('transaction'));
    }
}
