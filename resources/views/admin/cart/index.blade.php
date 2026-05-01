@extends('admin.layouts.app')

@section('content')

{{-- DataTables CSS langsung di section, tidak pakai @push --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<style>
  /* Override DataTables default agar mirip referensi */
  #cartTable_wrapper {
    font-family: inherit;
    font-size: 13.5px;
  }
  #cartTable_wrapper .dataTables_length label,
  #cartTable_wrapper .dataTables_filter label {
    font-size: 13.5px;
    color: #374151;
  }
  #cartTable_wrapper .dataTables_length select,
  #cartTable_wrapper .dataTables_filter input {
    border: 1px solid #d1d5db;
    border-radius: 4px;
    padding: 3px 8px;
    font-size: 13px;
    margin: 0 4px;
    outline: none;
  }
  #cartTable_wrapper .dataTables_filter {
    float: right;
  }
  #cartTable_wrapper .dataTables_length {
    float: left;
  }

  /* Header */
  table#cartTable thead th {
    background: #fff;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #374151;
    border-top: 2px solid #e5e7eb;
    border-bottom: 2px solid #e5e7eb !important;
    padding: 10px 12px;
    white-space: nowrap;
  }

  /* Body */
  table#cartTable tbody td {
    padding: 10px 12px;
    color: #374151;
    border-bottom: 1px solid #f3f4f6 !important;
    vertical-align: middle;
  }
  table#cartTable tbody tr:hover {
    background-color: #f9fafb;
  }

  /* Footer info */
  #cartTable_wrapper .dataTables_info {
    font-size: 13px;
    color: #6b7280;
    padding-top: 12px;
  }

  /* Pagination */
  #cartTable_wrapper .dataTables_paginate {
    padding-top: 8px;
    float: right;
  }
  #cartTable_wrapper .dataTables_paginate .paginate_button {
    border: 1px solid #d1d5db !important;
    border-radius: 4px !important;
    padding: 3px 10px !important;
    font-size: 13px !important;
    color: #374151 !important;
    background: #fff !important;
    margin: 0 2px;
    cursor: pointer;
  }
  #cartTable_wrapper .dataTables_paginate .paginate_button.current {
    background: #fff !important;
    color: #111 !important;
    border-color: #9ca3af !important;
    font-weight: 600;
  }
  #cartTable_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) {
    background: #f3f4f6 !important;
    color: #111 !important;
  }
  #cartTable_wrapper .dataTables_paginate .paginate_button.disabled {
    color: #d1d5db !important;
    cursor: default;
  }

  /* Tombol aksi */
  .btn-dt-edit {
    background: #3b82f6;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 5px 14px;
    font-size: 12.5px;
    font-weight: 500;
    cursor: pointer;
    margin-right: 4px;
  }
  .btn-dt-edit:hover { background: #2563eb; }
  .btn-dt-hapus {
    background: #ef4444;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 5px 14px;
    font-size: 12.5px;
    font-weight: 500;
    cursor: pointer;
  }
  .btn-dt-hapus:hover { background: #dc2626; }

  /* Qty input */
  .qty-input {
    width: 58px;
    text-align: center;
    border: 1px solid #d1d5db;
    border-radius: 5px;
    padding: 4px 6px;
    font-size: 13px;
    outline: none;
  }
  .qty-input:focus { border-color: #6366f1; }
</style>

<div class="bg-white p-5 rounded-xl shadow-sm border">


  <table id="cartTable" class="min-w-full" style="width:100%; border-collapse:collapse;">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Harga</th>
        <th>Qty</th>
        <th>Total</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @php $grand = 0; @endphp
      @forelse($cart as $id => $item)
        @php
          $total = $item['harga'] * $item['qty'];
          $grand += $total;
        @endphp
        <tr>
          <td>{{ $id }}</td>
          <td class="font-medium">{{ $item['nama'] }}</td>
          <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
          <td>
            <form action="{{ route('cart.update') }}" method="POST">
              @csrf
              <input type="hidden" name="id" value="{{ $id }}">
              <input type="number" name="qty" value="{{ $item['qty'] }}" min="1" class="qty-input"
                onchange="this.closest('form').submit()">
            </form>
          </td>
          <td class="font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</td>
          <td>
           
            <form action="{{ route('cart.delete') }}" method="POST" style="display:inline">
              @csrf
              <input type="hidden" name="id" value="{{ $id }}">
              <button type="submit" class="btn-dt-hapus">Hapus</button>
            </form>
          </td>
        </tr>
      @empty
      @endforelse
    </tbody>
  </table>

  <!-- Grand Total -->
  <div class="flex justify-between items-center mt-4 pt-3 border-t">
    <div class="text-sm text-gray-500">Total Item: {{ count($cart) }}</div>
    <div class="text-lg font-bold text-green-700">
      Grand Total: Rp {{ number_format($grand, 0, ',', '.') }}
    </div>

    <div>
      <a href="{{ route('checkout') }}" class="bg-green-main hover:bg-green-light text-white border-0 rounded-lg px-5 py-2.5 text-sm font-semibold cursor-pointer flex items-center gap-2 shadow-[0_2px_8px_rgba(40,167,69,0.35)] transition-all hover:-translate-y-px">
        Checkout
      </a>
    </div>
    
  </div>

</div>

{{-- Script langsung di section, tidak perlu @stack --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
  $(function () {
    $('#cartTable').DataTable({
      pageLength: 10,
      lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
      language: {
        search: "Search:",
        lengthMenu: "Show _MENU_ entries",
        info: "Showing _START_ to _END_ of _TOTAL_ entries",
        paginate: { previous: "Previous", next: "Next" },
        emptyTable: "Cart kosong"
      },
      columnDefs: [
        { orderable: false, targets: [3, 5] }
      ]
    });
  });
</script>

@endsection