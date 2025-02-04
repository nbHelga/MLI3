@extends('layouts.layout')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Card 2: Data Pegawai -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            <div class="flex-grow">
                <h2 class="text-xl font-semibold text-gray-800">{{ $surat->employees->nama_ktp }}</h2>
                <p class="text-gray-500">{{ $surat->employees->id }}</p>
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Nama Panggilan</p>
                        <p class="font-medium">{{ $surat->employees->nama }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Gender</p>
                        <p class="font-medium">{{ $surat->employees->gender }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Departemen</p>
                        <p class="font-medium">{{ $surat->employees->departemen }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Jabatan</p>
                        <p class="font-medium">{{ $surat->employees->jabatan }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <p class="font-medium">{{ $surat->employees->status_aktif }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 1: Data Surat -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Detail Pengajuan Surat</h1>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="font-semibold">Perihal</div>
            <div>{{ $surat->perihal }}</div>

            <div class="font-semibold">Tanggal</div>
            <div>{{ $surat->tgl ? \Carbon\Carbon::parse($surat->tgl)->format('d M Y') : '-' }}</div>

            <div class="font-semibold">Dana</div>
            <div>{{ $surat->dana ? 'Rp ' . number_format($surat->dana, 0, ',', '.') : '-' }}</div>

            <div class="font-semibold">Durasi</div>
            <div>{{ $surat->durasi ? $surat->durasi . ' hari' : '-' }}</div>

            <div class="font-semibold">Status</div>
            <div>
                <span class="px-2 py-1 text-xs rounded-full 
                    {{ $surat->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                    {{ ucfirst($surat->status) }}
                </span>
            </div>

            <div class="font-semibold">Keterangan</div>
            <div>{{ $surat->keterangan ?? '-' }}</div>
        </div>

        @if($surat->dokumen_pelengkap)
            <div class="mt-4">
                <a href="{{ route('hrd.download', $surat->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    <span>Download Dokumen</span>
                </a>
            </div>
        @endif
    </div>

    <div class="mt-6">
        <a href="{{ route('hrd.list-surat') }}" 
           class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
            Kembali
        </a>
    </div>
</div>
@endsection 