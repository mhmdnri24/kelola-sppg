@extends('admin.layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800">Detail Rekening</h2>
            <a href="{{ route('daftar-rekening.edit', $bankAccount->id) }}" class="px-3 py-1.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-sm">
                Edit
            </a>
        </div>

        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">ID</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $bankAccount->id }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Nama Bank</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $bankAccount->bank_name }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Nomor Rekening</p>
                    <p class="text-sm font-semibold text-gray-900 font-mono">{{ $bankAccount->account_number }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Atas Nama Rekening</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $bankAccount->account_name }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Supplier</p>
                    <p class="text-sm font-semibold text-gray-900">
                        {{ $bankAccount->suppliers ? $bankAccount->suppliers->nama_supplier : '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Dibuat</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $bankAccount->created_at->format('d M Y H:i') }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Diperbarui</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $bankAccount->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t">
                <a href="{{ route('daftar-rekening.edit', $bankAccount->id) }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    Edit
                </a>
                <a href="{{ route('daftar-rekening') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
