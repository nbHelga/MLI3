@extends('layouts.layout')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-800">Daftar Pengajuan Surat</h1>
            <a href="{{ route('hris.form') }}"
               class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                Tambah Pengajuan
            </a>
        </div>

        <x-table-surat :surats="$surats" />
    </div>
</div>

@if(session('success'))
    <x-pop-up-message type="success" message="{{ session('success') }}" />
@endif

@if(session('error'))
    <x-pop-up-message type="error" message="{{ session('error') }}" />
@endif
@endsection 