@extends('admin.layouts.app')

@section('css')
<style>
    #table-katalog tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }

    #table-katalog tbody tr:nth-child(even) {
        background-color: #f9fafb;
    }

    .supplier-card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 1.25rem;
    }

    .supplier-head {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .supplier-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 600;
        flex-shrink: 0;
    }

    .av-blue {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .av-green {
        background: #dcfce7;
        color: #15803d;
    }

    .av-orange {
        background: #ffedd5;
        color: #c2410c;
    }

    .av-purple {
        background: #ede9fe;
        color: #7c3aed;
    }

    .supplier-badge {
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 999px;
        font-weight: 500;
    }

    .badge-business {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .badge-individual {
        background: #dcfce7;
        color: #15803d;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13.5px;
    }

    .items-table th {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #6b7280;
        padding: 10px 16px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }

    .items-table th.r,
    .items-table td.r {
        text-align: right;
    }

    .items-table td {
        padding: 12px 16px;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
    }

    .items-table tr:last-child td {
        border-bottom: none;
    }

    .supplier-foot {
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
        padding: 10px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .summary-box {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 1rem 1.25rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 13.5px;
        padding: 6px 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .summary-row:last-of-type {
        border-bottom: none;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        font-size: 16px;
        font-weight: 600;
        padding-top: 12px;
        margin-top: 4px;
        border-top: 1px solid #e5e7eb;
    }

    .btn-checkout {
        width: 100%;
        margin-top: 12px;
        background: #1d4ed8;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 11px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background .15s;
    }

    .btn-checkout:hover {
        background: #1e40af;
    }

    .btn-checkout:disabled {
        background: #93c5fd;
        cursor: not-allowed;
    }

    [v-cloak] {
        display: none;
    }
</style>
@endsection

@section('header_actions')
<a href="{{ route('keranjang') }}" class="bg-gray-700 hover:bg-gray-light text-white border-0 rounded-lg px-5 py-2.5 text-sm font-semibold cursor-pointer flex items-center gap-2 shadow-md transition-all hover:-translate-y-px">
    <span class="text-lg font-normal">←</span> Back
</a>
@endsection

@section('content')
<div id="checkout-app" v-cloak>

    <!-- Loading -->
    <div v-if="loading" class="bg-white p-8 rounded shadow text-center text-gray-400 text-sm">
        Memuat data...
    </div>

    <template v-else>

        <!-- Page Header -->
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Konfirmasi Pesanan</h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    @{{ groupedSuppliers.length }} transaksi · @{{ totalItems }} item
                </p>
            </div>
        </div>

        <!-- Supplier Cards -->
        <div v-for="(group, idx) in groupedSuppliers" :key="group.supplier_id" class="supplier-card bg-white shadow-sm">

            <!-- Supplier Header -->
            <div class="supplier-head">
                <div class="supplier-avatar" :class="avatarClass(idx)">
                    @{{ initials(group.supplier_name) }}
                </div>
                <div style="flex:1">
                    <p style="font-weight:600;font-size:14px;margin:0 0 2px;">@{{ group.supplier_name }}</p>
                    <p style="font-size:12px;color:#6b7280;margin:0;">
                        @{{ group.contact_person }} &nbsp;·&nbsp; @{{ group.phone }}
                    </p>
                </div>
                <span class="supplier-badge" :class="group.supplier_type === 'business' ? 'badge-business' : 'badge-individual'">
                    @{{ group.supplier_type === 'business' ? 'Business' : 'Individual' }}
                </span>
            </div>

            <!-- Items Table -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="r">Harga</th>
                        <th class="r">Qty</th>
                        <th class="r">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in group.items" :key="item.nama">
                        <td>
                            <div style="font-weight:500;">@{{ item.nama }}</div>
                            <div style="font-size:12px;color:#9ca3af;">Stok: @{{ item.katalog.stok }}</div>
                        </td>
                        <td class="r">@{{ formatRp(item.harga) }}</td>
                        <td class="r">@{{ item.qty }}</td>
                        <td class="r" style="font-weight:600;">@{{ formatRp(item.total) }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Supplier Footer -->
            <div class="supplier-foot">
                <span style="font-size:12px;color:#6b7280;">
                    @{{ group.items.length }} item &nbsp;·&nbsp; @{{ group.address }}
                </span>
                <span style="font-weight:600;font-size:14px;">@{{ formatRp(group.subtotal) }}</span>
            </div>
        </div>

        <!-- Summary -->
        <div class="summary-box">
            <div v-for="group in groupedSuppliers" :key="'s-'+group.supplier_id" class="summary-row">
                <span style="color:#6b7280;">@{{ group.supplier_name }}</span>
                <span>@{{ formatRp(group.subtotal) }}</span>
            </div>
            <div class="summary-total">
                <span>Total keseluruhan</span>
                <span style="color:#1d4ed8;">@{{ formatRp(grandTotal) }}</span>
            </div>
            <button class="btn-checkout" :disabled="submitting" @click="submitCheckout">
                <span v-if="submitting">Memproses...</span>
                <span v-else>Proses Pembelian</span>
            </button>
        </div>

    </template>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.7.16/dist/vue.min.js"></script>

<script>
    new Vue({
        el: '#checkout-app',

        data() {
            return {
                loading: true,
                submitting: false,
                cartData: [],
            }
        },

        computed: {
            /* Group item berdasarkan supplier */
            groupedSuppliers() {
                const map = {}
                this.cartData.forEach(item => {
                    const sup = item.katalog.supplier
                    const sid = sup.id
                    if (!map[sid]) {
                        map[sid] = {
                            supplier_id: sid,
                            supplier_name: sup.name,
                            supplier_type: sup.supplier_type,
                            contact_person: sup.contact_person,
                            phone: sup.phone,
                            address: sup.address,
                            items: [],
                            subtotal: 0,
                        }
                    }
                    map[sid].items.push(item)
                    map[sid].subtotal += item.total
                })
                return Object.values(map)
            },

            grandTotal() {
                return this.groupedSuppliers.reduce((s, g) => s + g.subtotal, 0)
            },

            totalItems() {
                return this.cartData.length
            },
        },

        methods: {
            /* Format ke Rupiah */
            formatRp(val) {
                return 'Rp ' + Number(val).toLocaleString('id-ID')
            },

            /* Inisial nama supplier */
            initials(name) {
                return name.split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase()
            },

            /* Warna avatar bergantian */
            avatarClass(idx) {
                const classes = ['av-blue', 'av-green', 'av-orange', 'av-purple']
                return classes[idx % classes.length]
            },

            /* Fetch cart dari server */
            async fetchCart() {
                try {
                    const res = await fetch('{{ route("cart.data") }}', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    const json = await res.json()
                    this.cartData = json.data ?? []
                } catch (e) {
                    Swal.fire('Error', 'Gagal memuat data keranjang.', 'error')
                } finally {
                    this.loading = false
                }
            },

            /* Submit checkout → buat PO per supplier */
            async submitCheckout() {
                const confirm = await Swal.fire({
                    title: 'Proses pembelian?',
                    text: `${this.groupedSuppliers.length} transaksi akan dibuat`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, proses',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#1d4ed8',
                })

                if (!confirm.isConfirmed) return

                this.submitting = true
                try {
                    const res = await fetch('{{ route("checkout.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            suppliers: this.groupedSuppliers.map(g => ({
                                supplier_id: g.supplier_id,
                                items: g.items.map(i => ({
                                    katalog_id: i.katalog.id,
                                    nama: i.nama,
                                    harga: i.harga,
                                    qty: i.qty,
                                    total: i.total,
                                })),
                                subtotal: g.subtotal,
                            })),
                            grand_total: this.grandTotal,
                        })
                    })

                    const json = await res.json()

                    if (res.ok) {
                        await Swal.fire({
                            title: 'Berhasil!',
                            text: json.message ?? 'Pesanan berhasil dibuat.',
                            icon: 'success',
                            confirmButtonColor: '#1d4ed8',
                        })
                        window.location.reload()

                    } else {
                        Swal.fire('Gagal', json.message ?? 'Terjadi kesalahan.', 'error')
                    }
                } catch (e) {
                    Swal.fire('Error', 'Koneksi bermasalah.', 'error')
                } finally {
                    this.submitting = false
                }
            },
        },

        mounted() {
            this.fetchCart()
        },
    })
</script>
@endsection