@extends('admin.layouts.app')


@section('css')
<style>
    #table-anggaran tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }

    #table-anggaran tbody tr:nth-child(even) {
        background-color: #f9fafb;
    }
</style>
@endsection
@section('header_actions')
    <a href="{{ route('anggaran.create') }}" class="bg-green-main hover:bg-green-light text-white border-0 rounded-lg px-5 py-2.5 text-sm font-semibold cursor-pointer flex items-center gap-2 shadow-[0_2px_8px_rgba(40,167,69,0.35)] transition-all hover:-translate-y-px">
        <span class="text-lg font-normal">+</span> New
    </a>
@endsection
@section('content') 
<div class="bg-white p-4 rounded shadow">
    <table id="table-anggaran" class="min-w-full text-sm text-gray-700">
        <thead>
            <tr class="bg-gray-100 text-xs uppercase tracking-wider text-gray-600">
                <th class="px-4 py-3 text-left">ID</th>                
                <th class="px-4 py-3 text-left">Kategori</th>
                <th class="px-4 py-3 text-left">Nama Anggaran</th>
                <th class="px-4 py-3 text-left">PM PB</th>
                <th class="px-4 py-3 text-left">PM PK</th>
                <th class="px-4 py-3 text-left">Pagu PB</th>
                <th class="px-4 py-3 text-left">Pagu PK</th>
                <th class="px-4 py-3 text-left">HPP PB</th>
                <th class="px-4 py-3 text-left">HPP PK</th>
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
        let table = $('#table-anggaran').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('anggaran.data') }}",
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
                    data: 8
                },
                
                {
                    data: 9,
                    orderable: false,
                    searchable: false
                }

            ]
        });

        // Edit button handler
        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            window.location.href = `/dapur/anggaran/${id}/edit`;
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
                    fetch(`/dapur/anggaran/${id}`, {
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