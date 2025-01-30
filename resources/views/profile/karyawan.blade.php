@extends('layouts.layout1')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6">Profil Karyawan</h2>
        
        @if(Auth::check() && Auth::user()->employee)
            <div class="grid grid-cols-2 gap-4">
                <div class="font-bold">ID Karyawan:</div>
                <div>{{ Auth::user()->employee->id }}</div>
                
                <div class="font-bold">Nama:</div>
                <div>{{ Auth::user()->employee->nama }}</div>
                
                <!-- Tambahkan informasi karyawan lainnya sesuai kebutuhan -->
            </div>
        @else
            <p>Data karyawan tidak ditemukan.</p>
        @endif
    </div>
</div>
@endsection
