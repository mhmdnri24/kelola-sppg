@extends('admin.layouts.app')

@section('content')
<div id="app">

    <div class=" bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-800">Informasi Pesanan</h2>
            @php
            $statusColor = $transaction->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($transaction->status == 'approved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800');
            @endphp
            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusColor }}">
                {{ ucfirst($transaction->status) }}
            </span>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">No Transaksi</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $transaction->no_transaksi }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Tanggal Transaksi</p>
                    <p class="text-sm font-semibold text-gray-900">{{ date('d M Y', strtotime($transaction->tanggal_transaksi)) }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Dapur</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $transaction->dapur ? $transaction->dapur->name : '-' }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Supplier</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $transaction->supplier ? $transaction->supplier->name : '-' }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Anggaran</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $transaction->anggaran ? $transaction->anggaran->nama_anggaran : '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-800">Detail Item</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-3 font-medium">No</th>
                        <th scope="col" class="px-6 py-3 font-medium">Nama Item</th>
                        <th scope="col" class="px-6 py-3 font-medium text-right">Harga</th>
                        <th scope="col" class="px-6 py-3 font-medium text-center">Qty</th>
                        <th scope="col" class="px-6 py-3 font-medium text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transaction->details as $index => $detail)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                            {{ $detail->katalog ? $detail->katalog->nama : 'Item ID: ' . $detail->katalog_id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $detail->qty }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-medium text-gray-900">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            Tidak ada detail item untuk transaksi ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50 border-t border-gray-200">
                    <tr>
                        <th colspan="4" class="px-6 py-4 text-right text-sm font-bold text-gray-900">Grand Total</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-green-700">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        @if ($transaction->status == 'pending')
        <div class="flex justify-end gap-2 py-5">
            <button @click="reject()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Tolak</button>
            <button @click="approve()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Setujui</button>
        </div>
        @endif
    </div>
</div>
@endsection

@section('js')
<script>
    createApp({
        data() {
            return {
                id: '{{ $transaction->id }}',
                transaction: {}
            }
        },
        mounted() {
            this.getTransaction();
        },
        methods: {
            async getTransaction() {

            },

            async approve() {
                try {

                    const response = await fetch(`/daftar-pesanan/${this.id}/update-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            status: 'diproses',
                        }),
                    });

                    const data = await response.json();
                    if (data.status === 'success') {
                        Swal.fire({
                            title: 'Transaksi berhasil disetujui!',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });
                        window.location.reload()
                    }
                } catch (error) {
                    console.error(error);
                }
            },

            async reject() {
                try {
                    const response = await fetch(`/daftar-pesanan/${this.id}/update-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            status: 'ditolak',
                        }),
                    });

                    const data = await response.json();
                    if (data.status === 'success') {

                        Swal.fire({
                            title: 'Transaksi berhasil ditolak!',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });

                        window.location.reload()
                    }
                } catch (error) {
                    console.error(error);
                }
            }

        }
    }).mount('#app')
</script>
@endsection