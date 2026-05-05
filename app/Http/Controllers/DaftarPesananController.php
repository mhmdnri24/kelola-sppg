<?php

namespace App\Http\Controllers;

use App\Models\Anggaran;
use App\Models\AnggaranHistory;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DaftarPesananController extends Controller
{
    public function index()
    {
        $payload = [
            'title' => 'Daftar Pesanan',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'Daftar Pesanan', 'url' => route('daftar-pesanan')],
            ]
        ];
        return view('admin.daftar-pesanan.index', $payload);
    }

    public function data(Request $request)
    {
        $columns = [
            'id',
            'no_transaksi',
            'dapur_id',
            'supplier_id',
            'anggaran_id',
            'tanggal_transaksi',
            'subtotal',
            'status',
        ];

        $query = Transaction::with(['dapur', 'supplier', 'anggaran']);

        // 🔍 SEARCH
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('no_transaksi', 'like', "%{$search}%")
                    ->orWhereHas('dapur', function ($q) use ($search) {
                        $q->where('nama_dapur', 'like', "%{$search}%");
                    })
                    ->orWhereHas('supplier', function ($q) use ($search) {
                        $q->where('nama_supplier', 'like', "%{$search}%");
                    });
            });
        }

        // 📊 TOTAL
        $recordsTotal = Transaction::count();
        $recordsFiltered = $query->count();

        // 🔃 ORDER
        if ($request->has('order')) {
            $orderColIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir');

            // Adjust ordering logic if needed, skipping relations ordering for simplicity or handle it
            if (isset($columns[$orderColIndex]) && !in_array($columns[$orderColIndex], ['dapur_id', 'supplier_id', 'anggaran_id'])) {
                $query->orderBy($columns[$orderColIndex], $orderDir);
            } else {
                $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        // 📄 PAGINATION
        $data = $query
            ->skip($request->start)
            ->take($request->length)
            ->get();

        // 🎯 FORMAT DATA
        $result = [];
        foreach ($data as $item) {
            $statusColor = $item->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($item->status == 'approved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800');

            $result[] = [
                $item->id,
                $item->no_transaksi,
                $item->dapur ? $item->dapur->name : '-',
                $item->supplier ? $item->supplier->name : '-',
                $item->anggaran ? $item->anggaran->nama_anggaran : '-',
                date('d-m-Y', strtotime($item->tanggal_transaksi)),
                'Rp ' . number_format($item->subtotal, 0, ',', '.'),
                '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' . $statusColor . '">' . ucfirst($item->status) . '</span>',
                '
                <div class="flex items-center gap-2">
                 <button class="flex justify-center items-center btn-detail px-3 py-1.5 rounded-md bg-blue-500 text-white hover:bg-blue-600 text-xs" data-id="' . $item->id . '">
                    <span>Detail</span>
                </button>
                </div>
                '
            ];
        }

        return response()->json([
            "draw" => intval($request->draw),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result,
        ]);
    }

    public function show($id)
    {
        $transaction = Transaction::with(['dapur', 'supplier', 'anggaran', 'details.katalog'])->findOrFail($id);

        $payload = [
            'title' => 'Detail Pesanan',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'Daftar Pesanan', 'url' => route('daftar-pesanan')],
                ['name' => 'Detail', 'url' => route('daftar-pesanan.show', $id)],
            ],
            'transaction' => $transaction,
        ];

        return view('admin.daftar-pesanan.show', $payload);
    }

    public function updateStatus($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::findOrFail($id);
            $transaction->status = $request->status;
            $transaction->save();

            if ($request->status == "diproses") {
                AnggaranHistory::where('trans_id', $id)->where('module', 'transactions')->update([
                    'status' => 'release'
                ]);

                $anggaran = Anggaran::find($transaction->anggaran_id);
                $anggaran->anggaran_terpakai = $anggaran->anggaran_terpakai + $transaction->subtotal;
                $anggaran->anggaran_sisa = $anggaran->anggaran_sisa - $transaction->subtotal;
                $anggaran->save();
            }


            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Status berhasil diupdate',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengupdate status',
                'error' => $e
            ], 500);
        }
    }
}
