<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::get('sales/{sale}/details', [SaleController::class, 'showDetails'])->name('sales.details');
    Route::get('/admin/sale-details', [SaleController::class, 'showSaleDetails'])->name('admin.sale_details');
    Route::get('/sales/details/export', [SaleController::class, 'exportPdf'])->name('sales.details.export');


    // Admin routes for products
    Route::prefix('admin')->middleware('auth')->group(function () {
        Route::resource('products', ProductController::class);
        Route::resource('sales', SaleController::class);
    });

    Route::prefix('petugas_gudang')->middleware('auth')->group(function () {
        Route::resource('gudang', GudangController::class);
    });

    Route::prefix('petugas_kasir')->middleware('auth')->group(function () {
        Route::get('/kasir/sale-details', [KasirController::class, 'showSaleDetails'])->name('kasir.sale_details');
        Route::get('/kasir/details/export', [KasirController::class, 'exportPdf'])->name('kasir.details.export');
        Route::resource('kasir', KasirController::class);
    });

    Route::prefix('pelanggan')->middleware(['auth'])->group(function () {
        Route::get('recent-transactions', [PelangganController::class, 'recentTransactions'])->name('pelanggan.recent_transactions');
        Route::get('pelanggan/transactions/{id}', [PelangganController::class, 'transactionDetails'])->name('pelanggan.transaction_details');

    });
});
