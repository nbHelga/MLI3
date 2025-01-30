@extends('layouts.layout')

@section('title', 'Maintenance')


@section('content')
<div class="container mx-auto px-4">
    <h2 class="text-2xl font-bold mb-4">Warehouse</h2>
    <!-- Statistik atau Ringkasan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Suhu Gudang -->
        <div class="bg-white shadow-md rounded-lg p-4">
            <div class="text-center">
                <h5 class="text-lg font-semibold">Suhu Gudang</h5>
                <h2 class="text-l">(Jumlah Pencatatan Suhu)</h2>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('maintenance.suhu.list') }}" 
                       class="block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                        Lihat
                    </a>
                    <a href="{{ route('maintenance.suhu') }}" 
                       class="block bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">
                        Tambah
                    </a>
                </div>
            </div>
        </div>

        <!-- Jumlah Pallet -->
        {{-- <div class="bg-white shadow-md rounded-lg p-4">
            <div class="text-center">
                <h5 class="text-lg font-semibold">Maintenance Report</h5>
                {{-- <h2 class="text-2xl">{{ $jumlahMataKuliah }}</h2> --}}
                {{-- <h2 class="text-l">(Jumlah Pencatatan Maintenance)</h2> --}}
                {{-- <a href="{{ route('maintenance.report') }}" class="mt-2 inline-block bg-gray-500 text-white py-1 px-3 rounded hover:bg-blue-600">Lihat</a> --}}
            {{-- </div> --}}
        {{-- </div> --}}
    </div>

    <!-- Informasi atau Pengumuman -->
    {{-- <div class="bg-white shadow-md rounded-lg mt-4">
        <div class="bg-blue-500 text-white p-4 rounded-t-lg">
            Pengumuman
        </div>
        <div class="p-4">
            <p>Tidak ada pengumuman baru saat ini.</p>
        </div>
    </div> --}}
</div>
@endsection

