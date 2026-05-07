@extends('admin.layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-800">Edit Rekening</h2>
        </div>

        <div class="p-6">
            @if ($errors->any())
            <div class="mb-4 p-4 rounded-lg bg-red-50 text-red-700 border border-red-200">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('daftar-rekening.update', $bankAccount->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Nama Bank <span class="text-red-500">*</span></label>
                    <input type="text" name="bank_name" value="{{ old('bank_name', $bankAccount->bank_name) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Contoh: Bank Central Asia">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Nomor Rekening <span class="text-red-500">*</span></label>
                    <input type="text" name="account_number" value="{{ old('account_number', $bankAccount->account_number) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Contoh: 1234567890">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Atas Nama Rekening <span class="text-red-500">*</span></label>
                    <input type="text" name="account_name" value="{{ old('account_name', $bankAccount->account_name) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Contoh: PT. Maju Jaya">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Supplier (Opsional)</label>
                    <select name="supplier_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach(\App\Models\Supplier::all() as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $bankAccount->supplier_id) == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->nama_supplier }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3 pt-4 border-t">
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('daftar-rekening') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
