@extends('admin.layouts.app')

@section('content')
<div id="app">

    <div class=" bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-800">Informasi Pesanan</h2>
            @php
            $statusColorMap = [
                'pending' => 'bg-yellow-100 text-yellow-800',
                'diproses' => 'bg-blue-100 text-blue-800',
                'ditolak' => 'bg-red-100 text-red-800',
                'dikirim' => 'bg-green-100 text-green-800',
                'selesai' => 'bg-emerald-100 text-emerald-800',
                'dibatalkan' => 'bg-gray-100 text-gray-800',
            ];
            $statusColor = $statusColorMap[$transaction->status] ?? 'bg-gray-100 text-gray-800';
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
                    <p class="text-sm font-semibold text-gray-900">{{ $transaction->dapur ? $transaction->dapur->nama_dapur : '-' }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Supplier</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $transaction->supplier ? $transaction->supplier->nama_supplier : '-' }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Anggaran</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $transaction->anggaran ? $transaction->anggaran->nama_anggaran : '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-800">Detail Item - Edit Qty & Keterangan</h2>
        </div>

        <div class="p-6">
            <form @submit.prevent="updateOrder">
                <div class="overflow-x-auto mb-6">
                    <table class="min-w-full text-sm text-left text-gray-700">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500 border-b border-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-3 font-medium">No</th>
                                <th scope="col" class="px-6 py-3 font-medium">Nama Item</th>
                                <th scope="col" class="px-6 py-3 font-medium text-right">Harga</th>
                                <th scope="col" class="px-6 py-3 font-medium text-center">Qty</th>
                                <th scope="col" class="px-6 py-3 font-medium text-right">Total</th>
                                <th scope="col" class="px-6 py-3 font-medium">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="(detail, index) in details" :key="detail.id" class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">@{{ index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                    @{{ detail.nama }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">Rp @{{ formatCurrency(detail.harga) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <input
                                        type="number"
                                        v-model.number="detail.qty"
                                        min="0"
                                        class="w-16 px-2 py-1 text-center border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        @change="recalculateTotal(detail)">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-medium text-gray-900">
                                    Rp @{{ formatCurrency(detail.total) }}
                                </td>
                                <td class="px-6 py-4">
                                    <textarea
                                        v-model="detail.keterangan"
                                        placeholder="Tambahkan catatan..."
                                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        rows="2"></textarea>
                                </td>
                            </tr>
                            <tr v-if="details.length === 0">
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    Tidak ada detail item untuk transaksi ini.
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <th colspan="4" class="px-6 py-4 text-right text-sm font-bold text-gray-900">Grand Total</th>
                                <th class="px-6 py-4 text-right text-sm font-bold text-green-700">Rp @{{ formatCurrency(grandTotal) }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if ($transaction->status == 'diproses')
                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <div class="flex gap-2">
                        <button type="button" @click="rejectOrder()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                            Tolak Pesanan
                        </button>
                        <button type="button" @click="shipOrder()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                            Tandai Dikirim
                        </button>
                    </div>
                    <button type="submit" :disabled="isLoading" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition disabled:opacity-50">
                        @{{ isLoading ? 'Menyimpan...' : 'Simpan Perubahan' }}
                    </button>
                </div>
                @else
                <div class="pt-4 border-t border-gray-200 text-center text-gray-600">
                    <p class="text-sm">Pesanan tidak dapat diedit karena status: <strong>{{ ucfirst($transaction->status) }}</strong></p>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
   
    createApp({
        data() {
            return {
                id: {{ $transaction->id }},
                details: [],
                isLoading: false,
                transactionStatus: '{{ $transaction->status }}'
            }
        },
        computed: {
            grandTotal() {
                return this.details.reduce((sum, detail) => sum + (detail.total || 0), 0);
            }
        },
        mounted() {
            this.initializeDetails();
        },
        methods: {
            initializeDetails() {
                const detailsData = @json($transaction->details);
                this.details = detailsData.map(detail => ({
                    id: detail.id,
                    nama: detail.katalog ? detail.katalog.nama : 'Item ID: ' + detail.katalog_id,
                    qty: detail.qty,
                    harga: parseFloat(detail.harga),
                    total: parseFloat(detail.total),
                    keterangan: detail.keterangan || ''
                }));
            },

            recalculateTotal(detail) {
                detail.total = detail.qty * detail.harga;
            },

            formatCurrency(value) {
                return new Intl.NumberFormat('id-ID').format(Math.round(value));
            },

            async updateOrder() {
                this.isLoading = true;
                try {
                    const payload = {
                        details: {}
                    };

                    // Build details payload
                    this.details.forEach(detail => {
                        payload.details[detail.id] = {
                            qty: detail.qty,
                            keterangan: detail.keterangan
                        };
                    });

                    const response = await fetch(`/daftar-pesanan/${this.id}/update-supplier`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify(payload),
                    });

                    const data = await response.json();
                    if (data.status === 'success') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan: ' + error.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } finally {
                    this.isLoading = false;
                }
            },

            async rejectOrder() {
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

                if (result.isConfirmed) {
                    this.updateStatusSupplier('ditolak');
                }
            },

            async shipOrder() {
                const result = await Swal.fire({
                    title: 'Tandai Dikirim?',
                    text: 'Apakah pesanan ini sudah dikirim?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#22c55e',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Dikirim',
                    cancelButtonText: 'Batal'
                });

                if (result.isConfirmed) {
                    // First save changes
                    await this.updateOrder();
                    // Then update status
                    this.updateStatusSupplier('dikirim');
                }
            },

            async updateStatusSupplier(status) {
                this.isLoading = true;
                try {
                    const response = await fetch(`/daftar-pesanan/${this.id}/update-status-supplier`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            status: status
                        }),
                    });

                    const data = await response.json();
                    if (data.status === 'success') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(() => {
                            window.location.href = '/daftar-pesanan';
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan: ' + error.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } finally {
                    this.isLoading = false;
                }
            }
        }
    }).mount('#app')
</script>
@endsection

