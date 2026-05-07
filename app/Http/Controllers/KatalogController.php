<?php

namespace App\Http\Controllers;

use App\Models\Katalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KatalogController extends Controller
{
    public function index()
    {
        $payload = [
            'title' => 'E-Katalog',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'E-Katalog', 'url' => route('katalog')],
            ]
        ];
        return view('admin.katalog.index', $payload);
    }

        public function etalase()
    {
        $payload = [
            'title' => 'Etalase',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'Etalase', 'url' => route('katalog.etalase')],
            ]
        ];
        return view('admin.katalog.etalase', $payload);
    }



    public function dataEtalase(Request $request)
    {
        $columns = ['id', 'nama', 'supplier_id', 'harga', 'stok'];

        $query = Katalog::with('supplier')->where('is_terbit', true);

        // 🔍 SEARCH
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('supplier_id', 'like', "%{$search}%");
            });
        }

        // 📊 TOTAL
        $recordsTotal = Katalog::count();
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

        $user = Auth()->user();

        $manage = $user->hasAnyPermission(['manage katalog']);
        $pilihItem = $user->hasAnyRole(['dapur']);
        $result = [];
        foreach ($data as $item) {
            $result[] = [
                $item->id,
                $item->nama,
                $item->supplier->name ?? 'N/A',
                'Rp ' . number_format($item->harga, 0, ',', '.'),
                $item->stok,
                '


                <div class="flex items-center gap-2">
                ' . ($pilihItem ? '
                 <button class="flex justify-center items-center btn-cart px-4 py-2 rounded-md bg-green-500 text-white hover:bg-green-600 text-xs" 
                 data-harga="' . $item->harga . '"
                 data-stok="' . $item->stok . '" data-id="' . $item->id . '">
                    <span>Pilih</span>
                </button>
                ' : '') . '
                ' . ($manage ? '
                
                ' : '') . '
                ' . ($manage ? '
                
                ' : '') . '
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
    public function data(Request $request)
    {
        $columns = ['id', 'nama', 'supplier_id', 'harga', 'stok'];

        $query = Katalog::with('supplier');

        // 🔍 SEARCH
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('supplier_id', 'like', "%{$search}%");
            });
        }

        // 📊 TOTAL
        $recordsTotal = Katalog::count();
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

        $user = Auth()->user();

        $manage = $user->hasAnyPermission(['manage katalog']);
        $pilihItem = $user->hasAnyRole(['dapur']);
        $result = [];
        foreach ($data as $item) {
            $result[] = [
                $item->id,
                $item->nama,
                $item->supplier->name ?? 'N/A',
                'Rp ' . number_format($item->harga, 0, ',', '.'),
                $item->stok,
                '


                <div class="flex items-center gap-2">
                ' . ($pilihItem ? '
                
                ' : '') . '
                ' . ($manage ? '
                <button class="flex justify-center items-center btn-edit px-4 py-2 rounded-md bg-blue-500 text-white hover:bg-blue-600 text-xs" data-id="' . $item->id . '">
                    <span>Edit</span>
                </button>
                ' : '') . '
                ' . ($manage ? '
                <button class="flex justify-center items-center btn-delete px-4 py-2 rounded-md bg-red-500 text-white hover:bg-red-600 text-xs" data-id="' . $item->id . '">
                    <span>Hapus</span>
                </button>
                ' : '') . '
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
            'title' => 'Tambah Katalog',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'E-Katalog', 'url' => route('katalog')],
                ['name' => 'Tambah', 'url' => route('katalog.create')],
            ]
        ];
        return view('admin.katalog.create', $payload);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|min:3',
            'deskripsi' => 'nullable|string',
            'supplier_id' => 'required|numeric',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'is_terbit' => 'nullable|boolean',
        ]);

        try {
            Katalog::create($validated);
            return response()->json([
                'success' => true,
                'message' => 'Katalog berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan katalog: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $katalog = Katalog::findOrFail($id);

        $payload = [
            'title' => 'Edit Katalog',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'E-Katalog', 'url' => route('katalog')],
                ['name' => 'Edit', 'url' => route('katalog.edit', $id)],
            ],
            'katalog' => $katalog
        ];
        return view('admin.katalog.edit', $payload);
    }

    public function update(Request $request, $id)
    {
        $katalog = Katalog::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|min:3',
            'deskripsi' => 'nullable|string',
            'supplier_id' => 'required|numeric',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'is_terbit' => 'nullable|boolean',
        ]);

        try {
            $katalog->update($validated);
            return response()->json([
                'success' => true,
                'message' => 'Katalog berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui katalog: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $katalog = Katalog::findOrFail($id);
            $katalog->delete();

            return response()->json([
                'success' => true,
                'message' => 'Katalog berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus katalog: ' . $e->getMessage()
            ], 500);
        }
    }
}
