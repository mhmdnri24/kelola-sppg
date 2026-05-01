@extends('admin.layouts.app')

@section('content')

<!-- 🔥 SUMMARY -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-5">

  <div class="bg-white border rounded-xl p-4 shadow-sm">
    <div class="text-xs text-gray-500">Total PO</div>
    <div class="text-2xl font-bold text-green-700 mt-1">128</div>
  </div>

  <div class="bg-white border rounded-xl p-4 shadow-sm">
    <div class="text-xs text-gray-500">Pending</div>
    <div class="text-2xl font-bold text-yellow-500 mt-1">12</div>
  </div>

  <div class="bg-white border rounded-xl p-4 shadow-sm">
    <div class="text-xs text-gray-500">Approved</div>
    <div class="text-2xl font-bold text-green-600 mt-1">96</div>
  </div>

  <div class="bg-white border rounded-xl p-4 shadow-sm">
    <div class="text-xs text-gray-500">Rejected</div>
    <div class="text-2xl font-bold text-red-500 mt-1">20</div>
  </div>

</div>


<!-- ⚡ ACTION BAR -->
<!-- <div class="bg-white border rounded-xl p-4 mb-4 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3">

  <div class="flex items-center gap-2">
    <input type="text" placeholder="Search PO..." 
      class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
    
    <select class="border rounded-lg px-3 py-2 text-sm">
      <option>All Status</option>
      <option>Pending</option>
      <option>Approved</option>
    </select>
  </div>

  <button class="bg-green-700 hover:bg-green-800 text-white text-sm px-4 py-2 rounded-lg">
    + Create PO
  </button>

</div> -->


<!-- 📊 TABLE -->
<div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">

  <table class="w-full border-collapse text-sm">
    
    <thead>
      <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
        <th class="px-4 py-3 text-center w-10">
          <input type="checkbox">
        </th>
        <th class="px-4 py-3 text-left">Reference</th>
        <th class="px-4 py-3 text-left">Vendor</th>
        <th class="px-4 py-3 text-left">Buyer</th>
        <th class="px-4 py-3 text-left">Deadline</th>
        <th class="px-4 py-3 text-left">Total</th>
        <th class="px-4 py-3 text-left">Status</th>
      </tr>
    </thead>

    <tbody class="divide-y">

      <tr class="hover:bg-gray-50 cursor-pointer">
        <td class="px-4 py-3 text-center"><input type="checkbox"></td>
        <td class="px-4 py-3 font-semibold text-green-700">P00001</td>
        <td class="px-4 py-3">Erha Logistic</td>
        <td class="px-4 py-3">Erha</td>
        <td class="px-4 py-3">25 Apr 2024</td>
        <td class="px-4 py-3 font-medium">Rp 1,000</td>
        <td class="px-4 py-3">
          <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
            Approved
          </span>
        </td>
      </tr>

      <tr class="hover:bg-gray-50 cursor-pointer">
        <td class="px-4 py-3 text-center"><input type="checkbox"></td>
        <td class="px-4 py-3 font-semibold text-green-700">P00002</td>
        <td class="px-4 py-3">Erha Logistic</td>
        <td class="px-4 py-3">Erha</td>
        <td class="px-4 py-3">25 Apr 2024</td>
        <td class="px-4 py-3 font-medium">Rp 5,000</td>
        <td class="px-4 py-3">
          <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
            Pending
          </span>
        </td>
      </tr>

    </tbody>

  </table>

</div>

@endsection