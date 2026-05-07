<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankAccountController extends Controller
{
    public function index()
    {
        $bankAccounts = BankAccount::with('suppliers')->get();

        $payload = [
            'title' => 'Daftar Rekening',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'Daftar Rekening', 'url' => route('daftar-rekening')],
            ]
        ];

        return view('admin.daftar-rekening.index', array_merge($payload, ['bankAccounts' => $bankAccounts]));
    }

    public function create()
    {
        $payload = [
            'title' => 'Tambah Rekening',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'Daftar Rekening', 'url' => route('daftar-rekening')],
                ['name' => 'Tambah', 'url' => route('daftar-rekening.create')],
            ]
        ];

        return view('admin.daftar-rekening.create', $payload);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50|unique:bank_accounts',
            'account_name' => 'required|string|max:100',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        try {
            BankAccount::create($validated);

            return redirect()->route('daftar-rekening')->with('success', 'Rekening berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->withErrors('Gagal menambahkan rekening: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $bankAccount = BankAccount::with('suppliers')->findOrFail($id);

        $payload = [
            'title' => 'Detail Rekening',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'Daftar Rekening', 'url' => route('daftar-rekening')],
                ['name' => 'Detail', 'url' => route('daftar-rekening.show', $id)],
            ],
            'bankAccount' => $bankAccount
        ];

        return view('admin.daftar-rekening.show', $payload);
    }

    public function edit($id)
    {
        $bankAccount = BankAccount::findOrFail($id);

        $payload = [
            'title' => 'Edit Rekening',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'Daftar Rekening', 'url' => route('daftar-rekening')],
                ['name' => 'Edit', 'url' => route('daftar-rekening.edit', $id)],
            ],
            'bankAccount' => $bankAccount
        ];

        return view('admin.daftar-rekening.edit', $payload);
    }

    public function update(Request $request, $id)
    {
        $bankAccount = BankAccount::findOrFail($id);

        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50|unique:bank_accounts,account_number,' . $id,
            'account_name' => 'required|string|max:100',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        try {
            $bankAccount->update($validated);

            return redirect()->route('daftar-rekening')->with('success', 'Rekening berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withErrors('Gagal memperbarui rekening: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $bankAccount = BankAccount::findOrFail($id);
            $bankAccount->delete();

            return redirect()->route('daftar-rekening')->with('success', 'Rekening berhasil dihapus');
        } catch (\Exception $e) {
            return back()->withErrors('Gagal menghapus rekening: ' . $e->getMessage());
        }
    }

    public function data(Request $request)
    {
        $query = BankAccount::with('suppliers');

        // Search
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('bank_name', 'like', "%{$search}%")
                    ->orWhere('account_number', 'like', "%{$search}%")
                    ->orWhere('account_name', 'like', "%{$search}%")
                    ->orWhereHas('suppliers', function ($q) use ($search) {
                        $q->where('nama_supplier', 'like', "%{$search}%");
                    });
            });
        }

        $recordsTotal = BankAccount::count();
        $recordsFiltered = $query->count();

        // Ordering
        if ($request->has('order')) {
            $orderColIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir');
            
            $columns = ['id', 'bank_name', 'account_number', 'account_name', 'supplier_id'];
            if (isset($columns[$orderColIndex])) {
                $query->orderBy($columns[$orderColIndex], $orderDir);
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        // Pagination
        $data = $query
            ->skip($request->start ?? 0)
            ->take($request->length ?? 10)
            ->get();

        // Format data
        $result = [];
        foreach ($data as $item) {
            $result[] = [
                $item->id,
                $item->bank_name,
                $item->account_number,
                $item->account_name,
                $item->suppliers ? $item->suppliers->nama_supplier : '-',
                '
                <div class="flex items-center gap-2">
                    <a href="' . route('daftar-rekening.edit', $item->id) . '" class="px-3 py-1.5 rounded-md bg-blue-500 text-white hover:bg-blue-600 text-xs">Edit</a>
                    <button class="btn-delete px-3 py-1.5 rounded-md bg-red-500 text-white hover:bg-red-600 text-xs" data-id="' . $item->id . '">Hapus</button>
                </div>
                '
            ];
        }

        return response()->json([
            "draw" => intval($request->draw ?? 1),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result,
        ]);
    }
}
