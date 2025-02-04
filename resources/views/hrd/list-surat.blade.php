@extends('layouts.layout')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Daftar Pengajuan Surat (HRD)</h1>
        <x-table-surat :surats="$surats" />
    </div>
</div>
@endsection 