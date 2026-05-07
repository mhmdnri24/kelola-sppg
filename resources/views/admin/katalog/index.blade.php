@extends('admin.layouts.app')


@section('css')
<style>
    #table-katalog tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }

    #table-katalog tbody tr:nth-child(even) {
        background-color: #f9fafb;
    }
</style>
@endsection
@section('content')

@section('header_actions')
<a href="{{ route('katalog.create') }}" class="bg-green-main hover:bg-green-light text-white border-0 rounded-lg px-5 py-2.5 text-sm font-semibold cursor-pointer flex items-center gap-2 shadow-[0_2px_8px_rgba(40,167,69,0.35)] transition-all hover:-translate-y-px">
    <span class="text-lg font-normal">+</span> New
</a>
@endsection

@if(auth()->user()->hasAnyRole('dapur|admin'))
<div class="p-6 bg-white mb-3" id="app">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Informasi Anggaran</h1>
        <p class="text-gray-500">Ringkasan dan detail penggunaan anggaran</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        <div class="bg-white p-4 rounded-xl shadow">
            <p class="text-gray-500 text-sm">Total Anggaran</p>
            <h2 class="text-xl font-bold text-blue-600"> @{{ formatcurrency(anggaran.pagu) }}</h2>
        </div>

        <div class="bg-white p-4 rounded-xl shadow">
            <p class="text-gray-500 text-sm">anggaran_terpakai</p>
            <h2 class="text-xl font-bold text-red-500"> @{{ formatcurrency(anggaran.anggaran_terpakai) }}</h2>
        </div>

        <div class="bg-white p-4 rounded-xl shadow">
            <p class="text-gray-500 text-sm">anggaran_sisa Anggaran</p>
            <h2 class="text-xl font-bold text-green-600"> @{{ formatcurrency(anggaran.anggaran_sisa) }}</h2>
        </div>

    </div>

    <div>
        <h2 class="text-2xl mb-3">Pemakaian Anggaran</h2>

        <table class="min-w-full text-sm text-gray-700">
            <thead>
                <tr class="bg-gray-100 text-xs uppercase tracking-wider text-gray-600">
                    <th class="px-4 py-3 text-left">ID</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Total</th>
                    <th class="px-4 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="pemakaianAnggaran in pemakaianAngarans">
                    <td>@{{ pemakaianAnggaran.id }}</td>
                    <td>@{{ pemakaianAnggaran.tanggal_transaksi }}</td>
                    <td>@{{ formatcurrency(pemakaianAnggaran.subtotal) }}</td>
                    <td>@{{ pemakaianAnggaran.status }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="bg-white p-4 rounded-xl shadow mb-6">
        <p class="text-sm text-gray-600 mb-2">Penggunaan Anggaran</p>
        <div class="w-full bg-gray-200 rounded-full h-4">
            <div class="bg-blue-500 h-4 rounded-full" :style="{width: progress + '%'}"></div>
        </div>
        <p class="text-xs text-gray-500 mt-2">@{{ progress }}% digunakan</p>
    </div>

</div>
@endif


<!-- 📊 TABLE -->
<div class="bg-white p-4 rounded shadow">
    <table id="table-katalog" class="min-w-full text-sm text-gray-700">
        <thead>
            <tr class="bg-gray-100 text-xs uppercase tracking-wider text-gray-600">
                <th class="px-4 py-3 text-left">ID</th>
                <th class="px-4 py-3 text-left">Nama</th>
                <th class="px-4 py-3 text-left">Supplier</th>
                <th class="px-4 py-3 text-left">Harga</th>
                <th class="px-4 py-3 text-left">Stok</th>
                <th class="px-4 py-3 text-left">Action</th>
            </tr>
        </thead>
    </table>
</div>

@endsection

@section('js')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    createApp({
        data() {
            return {
                anggaran: {
                    pagu: 0,
                    limit: 0,
                    anggaran_sisa: 0,
                    anggaran_terpakai: 0
                },
                pemakaianAngarans: []

            }
        },
        mounted() {
            this.getAnggaran();
            this.getCartTotal();

            window.addEventListener('dari-jquery', (e) => {
                // //console.log(e.detail.id);
                this.getCartTotal()
                setTimeout(() => {
                    window.location.reload()
                }, 1000)

            });
            window.vueInstance = this;
        },
        computed: {
            progress() {
                return (this.anggaran.anggaran_terpakai / this.anggaran.pagu) * 100
            }
        },
        methods: {
            async getAnggaran() {
                let response = await fetch("{{ route('anggaran.dapur') }}")
                let data = await response.json()
                // this.anggaran.pagu = (data.pm_pb * data.pagu_pb) + (data.pm_pk * data.pagu_pk)
                this.anggaran.pagu = (data.pm_pb * data.hpp_pb) + (data.pm_pk * data.hpp_pk) // instead of pagu
                this.anggaran.anggaran_sisa = data.anggaran_sisa < 1 ? this.anggaran.pagu - this.anggaran.anggaran_terpakai : data.anggaran_sisa;
                this.anggaran.anggaran_terpakai = data.anggaran_terpakai;
                this.pemakaianAngarans = data.pemakaian;

                console.log(data, 'data')
            },
            async getCartTotal() {
                let response = await fetch("{{ route('cart.total') }}")
                let data = await response.json()
                this.anggaran.anggaran_terpakai = parseFloat(data) + parseFloat(this.anggaran.anggaran_terpakai);
                this.anggaran.anggaran_sisa = this.anggaran.pagu - this.anggaran.anggaran_terpakai

                //console.log(this.anggaran, data)
            },
            formatcurrency(value) {
                if (isNaN(value)) return value;
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(value);
            }
        }
    }).mount('#app')
</script>
<script>
    $(function() {
        function formatcurrency(value) {
            if (isNaN(value)) return value;
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(value);
        }
        console.log(window.vueInstance)
        //console.log("Initializing DataTable...");
        let table = $('#table-katalog').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('katalog.data') }}",
            columns: [{
                    data: 0
                },
                {
                    data: 1
                },
                {
                    data: 2
                },
                {
                    data: 3
                },
                {
                    data: 4
                },
                {
                    data: 5,
                    orderable: false,
                    searchable: false
                }

            ]
        });

        $(document).on('click', '.btn-cart', function() {
            let id = $(this).data('id');
            let stok = $(this).data('stok');
            let harga = $(this).data('harga');

            Swal.fire({
                title: 'Masukkan Jumlah',
                input: 'number',
                inputAttributes: {
                    min: 1,
                    step: 1
                },
                inputValue: 1,
                showCancelButton: true,
                confirmButtonText: 'Tambah',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: async (qty) => {
                    if (!qty || qty <= 0) {
                        Swal.showValidationMessage('Jumlah harus lebih dari 0');
                        return false;
                    }

                    const response = await fetch(`/dapur/cart-item-count/${id}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const cartItemCount = await response.json();

                    if (qty > stok) {
                        Swal.showValidationMessage(`Jumlah tidak boleh melebihi stok yang tersedia, stok tersedia: ${stok}`);
                        return false;
                    }
                    if (parseInt(cartItemCount) + parseInt(qty) > stok) {
                        Swal.showValidationMessage(`Jumlah tidak boleh melebihi stok, stok tersedia: ${stok}, total jumlah keranjang anda saat ini: ${cartItemCount}`);
                        return false;
                    }

                    console.log(harga, stok, qty)
                    console.log(window.vueInstance)
                    console.log(window.vueInstance.anggaran)

                    if (harga * qty > window.vueInstance.anggaran.anggaran_sisa) {
                        Swal.showValidationMessage(`Jumlah tidak boleh melebihi anggaran, anggaran tersedia: ${formatcurrency(window.vueInstance.anggaran.anggaran_sisa)}`);
                        return false;
                    }

                    return fetch('/dapur/cart/add', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                id: id,
                                qty: qty
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(response.statusText)
                            }
                            return response.text();
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`Request failed: ${error}`);
                        });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Produk ditambahkan ke cart',
                        icon: 'success',
                        confirmButtonColor: '#28a745',
                        confirmButtonText: 'OK'
                    });
                }

                window.dispatchEvent(new CustomEvent('dari-jquery', {
                    detail: {
                        id: 123
                    }
                }));

            });
        });

        // Edit button handler
        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            window.location.href = `/katalog/${id}/edit`;
        });

        // Delete button handler
        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data akan dihapus dan tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/katalog/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    'Terhapus!',
                                    data.message,
                                    'success'
                                ).then(() => {
                                    table.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    data.message,
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            Swal.fire(
                                'Error!',
                                error.message,
                                'error'
                            );
                        });
                }
            });
        });
    });
</script>
@endsection