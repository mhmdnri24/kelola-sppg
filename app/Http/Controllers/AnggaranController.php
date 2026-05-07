<?php

namespace App\Http\Controllers;

use App\Helpers\General;
use App\Models\Anggaran;
use App\Models\AnggaranHistory;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnggaranController extends Controller
{
    //
    public function index()
    {
        $payload = [
            'title' => 'Anggaran',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'Anggaran', 'url' => route('anggaran')],
            ]
        ];
        return view('admin.anggaran.index', $payload);
    }


    public function data(Request $request)
    {
        $columns = [
            'id',
            'location',
            'kategori',
            'nama_anggaran',
            'pm_pb',
            'pm_pk',
            'pagu_pb',
            'pagu_pk',
            'hpp_pb',
            'hpp_pk',
        ];

        // $query = Anggaran::query();
        $query = Anggaran::with('dapur')->dapur()->active();


        // 🔍 SEARCH
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_anggaran', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // 📊 TOTAL
        $recordsTotal = Anggaran::count();
        $recordsFiltered = $query->count();

        // 🔃 ORDER
        if ($request->has('order')) {
            $orderColIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir');

            $query->orderBy($columns[$orderColIndex], $orderDir);
        }

        // 📄 PAGINATION
        $data = $query
            ->skip($request->start)
            ->take($request->length)
            ->get();

        // 🎯 FORMAT DATA
        $result = [];
        foreach ($data as $item) {
            $result[] = [
                $item->id,
                $item->kategori,
                $item->nama_anggaran,
                $item->pm_pb,
                $item->pm_pk,
                'Rp ' . number_format($item->pagu_pb, 0, ',', '.'),
                'Rp ' . number_format($item->pagu_pk, 0, ',', '.'),
                'Rp ' . number_format($item->hpp_pb, 0, ',', '.'),
                'Rp ' . number_format($item->hpp_pk, 0, ',', '.'),
                $item->active_date,
                $item->expire_date,
                '
                <div class="flex items-center gap-2">
                <button class="flex justify-center items-center btn-edit px-4 py-2 rounded-md bg-blue-500 text-white hover:bg-blue-600 text-xs" data-id="' . $item->id . '">
                    <span>Edit</span>
                </button>
                 <button class="flex justify-center items-center btn-delete px-4 py-2 rounded-md bg-red-500 text-white hover:bg-red-600 text-xs" data-id="' . $item->id . '">
                    <span>Hapus</span>
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

    public function create()
    {
        $payload = [
            'title' => 'Tambah Anggaran',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'Anggaran', 'url' => route('anggaran')],
                ['name' => 'Tambah', 'url' => route('anggaran.create')],
            ]
        ];
        return view('admin.anggaran.create', $payload);
    }

    public function store(Request $request)
    {


        // Validate request
        $validated = $request->validate([
            'dapur_id' => 'required|exists:dapurs,id',
            'kategori' => 'required|string',
            'nama_anggaran' => 'required|string|min:3',
            'pm_pb' => 'required|numeric|min:0',
            'pm_pk' => 'nullable|numeric|min:0',
            'pagu_pb' => 'required|numeric|min:0',
            'pagu_pk' => 'nullable|numeric|min:0',
            'hpp_pb' => 'required|numeric|min:0',
            'hpp_pk' => 'nullable|numeric|min:0',
            'active_date' => 'nullable|date',
            'jumlah_hari' => 'nullable|numeric|min:0',
        ]);

        $now = now();
        if (empty($validated['active_date'])) {
            $validated['active_date'] = now();
        }
        $validated['expire_date'] = $now->addDays($validated['jumlah_hari']);
        $existingAnggaran = Anggaran::where('dapur_id', $validated['dapur_id'])
            ->where('kategori', $validated['kategori'])
            ->where('active_date', explode(' ', $validated['active_date'])[0])
            ->where('expire_date', explode(' ', $validated['expire_date'])[0])
            ->first();

        if ($existingAnggaran) {
            return response()->json([
                'success' => false,
                'message' => 'Anggaran dengan tanggal aktif dan tanggal expired yang sama sudah ada untuk dapur ini'
            ], 422);
        }

        $pagu = ($validated['pm_pb'] * $validated['pagu_pb']) + ($validated['pm_pk'] * $validated['pagu_pk']);
        $limit = ($validated['pm_pb'] * $validated['hpp_pb']) + ($validated['pm_pk'] * $validated['hpp_pk']);

        $validated['pagu'] = $pagu;
        $validated['limit'] = $limit;
        $validated['anggaran_sisa'] = $limit;
        $validated['anggaran_terpakai'] = 0;


        try {
            DB::beginTransaction();

            $anggaran = Anggaran::create($validated);

            AnggaranHistory::insert([
                'dapur_id' => $validated['dapur_id'],
                'date' => now(),
                'trans_type' => 'IN',
                'status' => 'release',
                'pagu' => $pagu,
                'limit' => $limit,
                'module' => 'anggarans',
                'notes' => 'Create Anggaran',
                'trans_id' => $anggaran->id,
                'jumlah' => $limit
            ]);



            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Anggaran berhasil ditambahkan',
                'data' => $validated
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan anggaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $anggaran = Anggaran::findOrFail($id);

        $anggaran->jumlah_hari = Carbon::parse($anggaran->active_date)->diffInDays($anggaran->expire_date);

        $payload = [
            'title' => 'Edit Anggaran',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'Anggaran', 'url' => route('anggaran')],
                ['name' => 'Edit', 'url' => route('anggaran.edit', $id)],
            ],
            'anggaran' => $anggaran
        ];
        return view('admin.anggaran.edit', $payload);
    }

    public function update(Request $request, $id)
    {
        $anggaran = Anggaran::findOrFail($id);

        // Validate request
        $validated = $request->validate([
            'dapur_id' => 'required|exists:dapurs,id',
            'kategori' => 'required|string',
            'nama_anggaran' => 'required|string|min:3',
            'pm_pb' => 'required|numeric|min:0',
            'pm_pk' => 'nullable|numeric|min:0',
            'pagu_pb' => 'required|numeric|min:0',
            'pagu_pk' => 'nullable|numeric|min:0',
            'hpp_pb' => 'required|numeric|min:0',
            'hpp_pk' => 'nullable|numeric|min:0',
        ]);

        try {
            $anggaran->update($validated);
            return response()->json([
                'success' => true,
                'message' => 'Anggaran berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui anggaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $anggaran = Anggaran::findOrFail($id);
            $anggaran->delete();

            return response()->json([
                'success' => true,
                'message' => 'Anggaran berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus anggaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAnggaranByDapur($dapur_id)
    {
        $anggaran = Anggaran::where('dapur_id', $dapur_id)->get();
        return response()->json($anggaran);
    }

    public function getActiveAnggaranByDapur()
    {
        $helper = new General();

        $anggaran = $helper->getActiveAnggaranByDapur();
        $anggaran->pemakaian = Transaction::where('anggaran_id', $anggaran->id)->get();

        return response()->json($anggaran);
    }
}
