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
                        @if ($transaction->details->first()?->keterangan)
                        <th scope="col" class="px-6 py-3 font-medium">Keterangan</th>
                        @endif
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
                        @if ($transaction->details->first()?->keterangan)
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $detail->keterangan }}</td>
                        @endif
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
                        @if ($transaction->details->first()?->keterangan)
                        <th></th>
                        @endif
                    </tr>
                </tfoot>
            </table>
        </div>
        @if ($transaction->status == 'pending' && auth()->user()->hasAnyRole('admin'))
        <div class="flex justify-end gap-2 py-5">
            <button @click="reject()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Tolak</button>
            <button @click="approve()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Setujui</button>
        </div>
        @elseif ($transaction->status == 'diproses' && auth()->user()->hasRole('supplier') && $transaction->supplier_id == auth()->user()->supplier_id)
        <div class="flex justify-end gap-2 py-5 px-6 pb-6">
            <a href="{{ route('daftar-pesanan.edit-supplier', $transaction->id) }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Edit & Kelola Pesanan</a>
        </div>
        @elseif ($transaction->status == 'dikirim' && auth()->user()->hasRole('dapur'))
        <div class="flex justify-end gap-2 py-5 px-6 pb-6">
            <button @click="terima()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Terima</button>
        </div>
         @elseif ($transaction->status == 'selesai' && auth()->user()->hasAnyRole('dapur|admin'))
        <div class="flex justify-end gap-2 py-5 px-6 pb-6">
            <button @click="pelunasan()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Konfirmasi Pelunasan</button>
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
                const result = await Swal.fire({
                    title: 'Setujui Pesanan?',
                    text: 'Apakah Anda yakin ingin menyetujui pesanan ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Setujui',
                    cancelButtonText: 'Batal'
                });

                if (!result.isConfirmed) return;

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
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menyetujui pesanan',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },

            async reject() {
                const result = await Swal.fire({
                    title: 'Tolak Pesanan?',
                    text: 'Apakah Anda yakin ingin menolak pesanan ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Tolak',
                    cancelButtonText: 'Batal'
                });

                if (!result.isConfirmed) return;

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
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menolak pesanan',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            async terima() {
                const result = await Swal.fire({
                    title: 'Terima Pesanan?',
                    text: 'Apakah Anda yakin pesanan ini sudah diterima lengkap?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Terima',
                    cancelButtonText: 'Batal'
                });

                if (!result.isConfirmed) return;

                try {
                    const response = await fetch(`/daftar-pesanan/${this.id}/update-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            status: 'selesai',
                        }),
                    });

                    const data = await response.json();
                    if (data.status === 'success') {
                        Swal.fire({
                            title: 'Transaksi berhasil diselesaikan!',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });
                        window.location.reload()
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menerima pesanan',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
             async pelunasan() {
                const result = await Swal.fire({
                    title: 'Konfirmasi Pelunasan?',
                    text: 'Apakah Anda yakin pesanan ini sudah dilunasi?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Terima',
                    cancelButtonText: 'Batal'
                });

                if (!result.isConfirmed) return;

                try {
                    const response = await fetch(`/daftar-pesanan/${this.id}/update-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            status: 'lunas',
                        }),
                    });

                    const data = await response.json();
                    if (data.status === 'success') {
                        Swal.fire({
                            title: 'Pelunasan berhasil dikonfirmasi!',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });
                        window.location.reload()
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat mengkonfirmasi pelunasan',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },

        }
    }).mount('#app')
</script>
@endsection