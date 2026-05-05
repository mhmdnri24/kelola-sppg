<?php

namespace App\Http\Controllers;

use App\Helpers\General;
use App\Models\AnggaranHistory;
use App\Models\Katalog;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    //
    public function index()
    {
        $cart = session()->get('cart', []);
        $payload = [
            'title' => 'Checkout',
            'cart' => $cart,
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'Checkout', 'url' => route('checkout')],
            ]
        ];

        return view('admin.checkout.index', $payload);
    }

    public function data()
    {

        $cart = session()->get('cart', []);

        if (!$cart) {
            return response()->json([
                'data' => [],
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
            ]);
        }

        $result = [];

        foreach ($cart as $key => $value) {

            $katalog = Katalog::with('supplier')->where('id', $value['produk_id'])->first();

            $item = [
                'nama' => $value['nama'],
                'harga' => $value['harga'],
                'qty' => $value['qty'],
                'total' => $value['harga'] * $value['qty'],
                'katalog' => $katalog,
            ];

            $result[] = $item;
        }

        return response()->json([
            'data' => $result,
            'recordsTotal' => count($result),
            'recordsFiltered' => count($result),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'suppliers' => 'required|array',
            'grand_total' => 'required|numeric',
        ]);

        $user = auth()->user();
        $dapur_id = $user->dapur_id;

        if (!$dapur_id) {
            return response()->json(['message' => 'User tidak terkait dengan dapur. Tidak dapat melakukan pembelian.'], 403);
        }

        try {

            $helper = new General();

            $anggaran = $helper->getActiveAnggaranByDapur();

            DB::beginTransaction();

            $suppliers = $request->suppliers;

            foreach ($suppliers as $supplier) {
                // Buat transaksi per supplier
                $noTransaksi = 'PO-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

                $transaction = Transaction::create([
                    'no_transaksi' => $noTransaksi,
                    'dapur_id' => $dapur_id,
                    'supplier_id' => $supplier['supplier_id'],
                    'tanggal_transaksi' => date('Y-m-d'),
                    'subtotal' => $supplier['subtotal'],
                    'status' => 'pending',
                    'created_by' => $user->id,
                    'anggaran_id' => $anggaran->id
                ]);

                $total = 0;

                foreach ($supplier['items'] as $item) {
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'katalog_id' => $item['katalog_id'],
                        'qty' => $item['qty'],
                        'harga' => $item['harga'],
                        'total' => $item['total'],
                    ]);

                    $total +=  $item['total'];

                    // Kurangi stok katalog
                    $katalog = Katalog::find($item['katalog_id']);
                    if ($katalog) {
                        $katalog->stok = $katalog->stok - $item['qty'];
                        if ($katalog->stok < 0) {
                            throw new \Exception("Stok tidak mencukupi untuk item " . $katalog->nama);
                        }
                        $katalog->save();
                    }
                }
            }

            AnggaranHistory::insert([
                'dapur_id' => $dapur_id,
                'date' => now(),
                'trans_type' => 'OUT',
                'status' => 'draft',
                'pagu' => $anggaran->pagu,
                'limit' => $anggaran->limit,
                'module' => 'transactions',
                'notes' => 'Pembelanjaan Anggaran',
                'trans_id' => $transaction->id,
                'jumlah' => $total
            ]);

            // Kosongkan keranjang
            session()->forget('cart');

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pesanan berhasil dibuat.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
