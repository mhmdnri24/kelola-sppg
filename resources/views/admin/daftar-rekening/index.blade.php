@extends('admin.layouts.app')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    #table-rekening tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }

    #table-rekening tbody tr:nth-child(even) {
        background-color: #f9fafb;
    }
</style>
@endsection

@section('header_actions')
<a href="{{ route('daftar-rekening.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
    <span class="flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Rekening
    </span>
</a>
@endsection

@section('content')
@if ($message = Session::get('success'))
<div class="mb-4 p-4 rounded-lg bg-green-50 text-green-700 border border-green-200">
    <button type="button" class="absolute top-3 right-3" onclick="this.parentElement.style.display='none';">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
    <strong>Berhasil!</strong> {{ $message }}
</div>
@endif

@if ($errors->any())
<div class="mb-4 p-4 rounded-lg bg-red-50 text-red-700 border border-red-200">
    <button type="button" class="absolute top-3 right-3" onclick="this.parentElement.style.display='none';">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
    <strong>Error!</strong>
    <ul class="mt-2 list-disc list-inside">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="bg-white p-4 rounded shadow">
    <table id="table-rekening" class="min-w-full text-sm text-gray-700">
        <thead>
            <tr class="bg-gray-100 text-xs uppercase tracking-wider text-gray-600">
                <th class="px-4 py-3 text-left">ID</th>
                <th class="px-4 py-3 text-left">Bank</th>
                <th class="px-4 py-3 text-left">No Rekening</th>
                <th class="px-4 py-3 text-left">Atas Nama</th>
                <th class="px-4 py-3 text-left">Supplier</th>
                <th class="px-4 py-3 text-left">ACTION</th>
            </tr>
        </thead>
    </table>
</div>

@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(function() {
        let table = $('#table-rekening').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('daftar-rekening.data') }}",
            columns: [
                { data: 0 },
                { data: 1 },
                { data: 2 },
                { data: 3 },
                { data: 4 },
                { data: 5, orderable: false, searchable: false }
            ]
        });

        // Delete button handler
        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');
            
            Swal.fire({
                title: 'Hapus Rekening?',
                text: 'Apakah Anda yakin ingin menghapus rekening ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/daftar-rekening/' + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function() {
                            Swal.fire('Berhasil!', 'Rekening berhasil dihapus', 'success');
                            table.ajax.reload();
                        },
                        error: function() {
                            Swal.fire('Error!', 'Gagal menghapus rekening', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
