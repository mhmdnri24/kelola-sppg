@extends('admin.layouts.app')

@section('header_actions')
<a href="{{ route('anggaran') }}" class="bg-gray-700 hover:bg-gray-light text-white border-0 rounded-lg px-5 py-2.5 text-sm font-semibold cursor-pointer flex items-center gap-2 shadow-md transition-all hover:-translate-y-px">
    <span class="text-lg font-normal">←</span> Back
</a>
@endsection

@section('content')

@include('admin.anggaran.form')

@endsection
