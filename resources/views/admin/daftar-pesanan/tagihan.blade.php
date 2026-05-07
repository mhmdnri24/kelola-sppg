@extends('admin.layouts.app')


@section('css')
<style>
    #table-daftar-pesanan tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }

    #table-daftar-pesanan tbody tr:nth-child(even) {
        background-color: #f9fafb;
    }
</style>
@endsection
@section('header_actions')
@endsection
@section('content') 
<div class="bg-white p-4 rounded shadow">
    <table id="table-daftar-pesanan" class="min-w-full text-sm text-gray-700">
        <thead>
            <tr class="bg-gray-100 text-xs uppercase tracking-wider text-gray-600">
                <th class="px-4 py-3 text-left">ID</th>                
                <th class="px-4 py-3 text-left">No Transaksi</th>
                <th class="px-4 py-3 text-left">Dapur</th>
                <th class="px-4 py-3 text-left">Supplier</th>
                <th class="px-4 py-3 text-left">Anggaran</th>
                <th class="px-4 py-3 text-left">Tanggal</th>
                <th class="px-4 py-3 text-left">Subtotal</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">ACTION</th>
            </tr>
        </thead>
    </table>
</div>

@endsection

@section('js')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(function() {
        console.log("Initializing DataTable...");
        let table = $('#table-daftar-pesanan').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('daftar-tagihan.data') }}",
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
                    data: 5
                },
                {
                    data: 6
                },
                {
                    data: 7
                },
                {
                    data: 8,
                    orderable: false,
                    searchable: false
                }

            ]
        });

        // Detail button handler
        $(document).on('click', '.btn-detail', function() {
            let id = $(this).data('id');
            window.location.href = `/daftar-pesanan/${id}`;
        });
    });
</script>
@endsection
