<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard(Request $request)
    {
        // Total Produk
        $totalProducts = Product::count();

        // Total Pengguna
        $totalUsers = User::count();

        // Total Penjualan Hari Ini
        $totalSalesToday = Sale::whereDate('created_at', Carbon::today())->sum('total');

        // Total Penjualan Kemarin
        $totalSalesYesterday = Sale::whereDate('created_at', Carbon::yesterday())->sum('total');

        // Grafik Pendapatan per Hari
        $selectedMonth = $request->input('month', date('m')); // Default to current month
        $selectedYear = $request->input('year', date('Y')); // Default to current year

        $dailySales = DB::table('sales')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Daftar Pengguna
        $users = User::all();

        return view('admin.dashboard', [
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers,
            'totalSalesToday' => $totalSalesToday,
            'totalSalesYesterday' => $totalSalesYesterday,
            'dailySales' => $dailySales,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'users' => $users,
        ]);
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        Alert::success('Berhasil!', 'Pengguna berhasil ditambahkan.');
        return redirect()->route('admin.dashboard')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function editUser($id)
    {
        $user = User::find($id);
        return view('admin.edit_user', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'role' => 'required|string',
        ]);

        $user = User::find($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        Alert::success('Berhasil!', 'Pengguna berhasil diperbarui.');
        return redirect()->route('admin.dashboard')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function deleteUser($id)
    {
        User::destroy($id);
        Alert::success('Berhasil!', 'Pengguna berhasil dihapus.');
        return redirect()->route('admin.dashboard')->with('success', 'Pengguna berhasil dihapus.');
    }
}
