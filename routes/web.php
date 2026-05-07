<?php

use App\Http\Controllers\AnggaranController;
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
    return view('welcome');
});


use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DaftarPesananController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BankAccountController;
use App\Models\Anggaran;
use App\Models\Dapur;
use App\Models\Katalog;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Auth routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $payload = [
            'title' => 'Dashboard',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
            ]
        ];
        return view('dashboard', $payload);
    })->name('dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/katalog/data', [KatalogController::class, 'data'])->name('katalog.data');
    Route::get('/katalog/etalase', [KatalogController::class, 'dataEtalase'])->name('katalog.etalase');
    Route::get('/katalog/create', [KatalogController::class, 'create'])->name('katalog.create');
    Route::post('/katalog', [KatalogController::class, 'store'])->name('katalog.store');
    Route::get('/katalog/{id}/edit', [KatalogController::class, 'edit'])->name('katalog.edit');
    Route::put('/katalog/{id}', [KatalogController::class, 'update'])->name('katalog.update');
    Route::delete('/katalog/{id}', [KatalogController::class, 'destroy'])->name('katalog.destroy');
    Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog');
    Route::get('/etalase', [KatalogController::class, 'etalase'])->name('etalase');

    // supplier
    Route::get('/supplier/data', [SupplierController::class, 'getSupplier'])->name('suppliers.data');

    // daftar-pesanan
    Route::get('/daftar-pesanan', [DaftarPesananController::class, 'index'])->name('daftar-pesanan');
    Route::get('/tagihan', [DaftarPesananController::class, 'tagihan'])->name('tagihan');
    Route::get('/daftar-pesanan/data', [DaftarPesananController::class, 'data'])->name('daftar-pesanan.data');
    Route::get('/daftar-tagihan/data', [DaftarPesananController::class, 'dataTagihan'])->name('daftar-tagihan.data');
    Route::get('/daftar-pesanan/{id}', [DaftarPesananController::class, 'show'])->name('daftar-pesanan.show');
    Route::post('/daftar-pesanan/{id}/update-status', [DaftarPesananController::class, 'updateStatus'])->name('daftar-pesanan.update-status');

    // Supplier specific routes
    Route::middleware('role:supplier')->group(function () {
        Route::get('/daftar-pesanan/{id}/edit-supplier', [DaftarPesananController::class, 'editSupplier'])->name('daftar-pesanan.edit-supplier');
        Route::put('/daftar-pesanan/{id}/update-supplier', [DaftarPesananController::class, 'updateSupplier'])->name('daftar-pesanan.update-supplier');
        Route::post('/daftar-pesanan/{id}/update-status-supplier', [DaftarPesananController::class, 'updateStatusSupplier'])->name('daftar-pesanan.update-status-supplier');
    });

    // daftar-rekening
    Route::get('/daftar-rekening', [BankAccountController::class, 'index'])->name('daftar-rekening');
    Route::get('/daftar-rekening/data', [BankAccountController::class, 'data'])->name('daftar-rekening.data');
    Route::get('/daftar-rekening/create', [BankAccountController::class, 'create'])->name('daftar-rekening.create');
    Route::post('/daftar-rekening', [BankAccountController::class, 'store'])->name('daftar-rekening.store');
    Route::get('/daftar-rekening/{id}', [BankAccountController::class, 'show'])->name('daftar-rekening.show');
    Route::get('/daftar-rekening/{id}/edit', [BankAccountController::class, 'edit'])->name('daftar-rekening.edit');
    Route::put('/daftar-rekening/{id}', [BankAccountController::class, 'update'])->name('daftar-rekening.update');
    Route::delete('/daftar-rekening/{id}', [BankAccountController::class, 'destroy'])->name('daftar-rekening.destroy');



    Route::prefix('admin')->middleware('role:admin')->group(function () {});

    Route::prefix('dapur')->middleware('role:dapur|admin')->group(function () {

        // cart 
        Route::get('/cart', [CartController::class, 'index'])->name('keranjang');
        Route::get('/cart-count', [CartController::class, 'getCartCount'])->name('cart.count');
        Route::get('/cart-total', [CartController::class, 'getCartTotal'])->name('cart.total');
        Route::get('/cart-item-count/{id}', [CartController::class, 'getCartItemCount'])->name('cart.item.count');
        Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
        Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
        Route::post('/cart/delete', [CartController::class, 'delete'])->name('cart.delete');

        // anggaran
        Route::get('/anggaran', [AnggaranController::class, 'index'])->name('anggaran');
        Route::get('/anggaran/create', [AnggaranController::class, 'create'])->name('anggaran.create');
        Route::post('/anggaran', [AnggaranController::class, 'store'])->name('anggaran.store');
        Route::get('/anggaran/{id}/edit', [AnggaranController::class, 'edit'])->name('anggaran.edit');
        Route::put('/anggaran/{id}', [AnggaranController::class, 'update'])->name('anggaran.update');
        Route::delete('/anggaran/{id}', [AnggaranController::class, 'destroy'])->name('anggaran.destroy');
        Route::get('/anggaran/data', [AnggaranController::class, 'data'])->name('anggaran.data');
        Route::get('/anggaran/dapur', [AnggaranController::class, 'getActiveAnggaranByDapur'])->name('anggaran.dapur');

        Route::get('/list-dapur', function () {
            return Dapur::all();
        })->name('dapur.data');


        // chekcout
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
        Route::get('/checkout/data', [CheckoutController::class, 'data'])->name('cart.data');
        Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');
    });

    Route::prefix('supplier')->middleware('role:supplier|admin')->group(function () {});
});


Route::get('/test', function () {
    $validated = [
        'dapur_id' => 1,
        'kategori' => 'SISWA',
        'active_date' => '2026-05-01',
        'expire_date' => '2026-05-01'
    ];

    return Anggaran::where('dapur_id', $validated['dapur_id'])
        ->where('kategori', $validated['kategori'])
        ->where('active_date', $validated['active_date'])
        ->where('expire_date', $validated['expire_date'])
        ->first();
});
