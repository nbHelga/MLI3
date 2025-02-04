@extends('layouts.layout')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Card 2: Data Pegawai -->
    @php
        $userMenu = auth()->user()->menu ?? '';
        $showEmployeeCard = in_array($userMenu, ['HRD', 'Finance']);
    @endphp

    @if($showEmployeeCard)
    <div class="bg-white rounded-lg shadow-md p-6">
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
    @endif

    <!-- Card 1: Data Surat -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Detail Pengajuan Surat</h1>
            @if($surat->status === 'pending' && $surat->id_employees === auth()->id())
                <div class="flex space-x-2">
                    <a href="{{ route('hris.edit', $surat->id) }}" 
                       class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Edit
                    </a>
                    <button type="button"
                            onclick="showDeleteDialog()"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Delete
                    </button>
                </div>
            @endif
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

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
                <a href="{{ route('hris.download', $surat->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    <span>Download Dokumen</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Tombol aksi -->
    <div class="mt-6 flex justify-between items-center">
        <div class="flex space-x-2">
            <a href="{{ route('home') }}" 
                class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                Kembali
            </a>
        
            @if($surat->status === 'pending')
                <button type="button"
                        onclick="showSendDialog()"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Kirim
                </button> 
            @endif
        </div>
    </div>

    <!-- Dialog Konfirmasi Delete -->
    <x-dialog 
        id="deleteDialog" 
        title="Konfirmasi Hapus"
        class="bg-red-50 border-red-100"
        titleClass="text-gray-800">
        <div class="mt-2">
            <p class="text-base text-gray-600">Apakah Anda yakin ingin menghapus surat pengajuan ini?</p>
        </div>
        <x-slot name="actions">
            <form action="{{ route('hris.destroy', $surat->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                    Ya, Hapus
                </button>
                <button type="button" 
                        onclick="hideDeleteDialog()"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                    Batal
                </button>
            </form>
        </x-slot>
    </x-dialog>

    <!-- Dialog Konfirmasi Kirim -->
    <x-dialog 
        id="sendDialog" 
        title="Konfirmasi Pengiriman"
        class="bg-red-50 border-gray-100"
        titleClass="text-gray-800">
        <div class="mt-2">
            <p class="text-base text-gray-600">Apakah Anda yakin untuk mengirim surat pengajuan ini?</p>
            <div class="flex items-center justify-between mt-4">
                <p class="text-xs text-red-500">*Surat Pengajuan akan dikirim ke pihak terkait dan tidak dapat diubah setelah dikirim</p>
            </div>
        </div>
        <x-slot name="actions">
            <div class="flex justify-end space-x-2">
                <form action="{{ route('hris.send', $surat->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit"
                            class="inline-flex justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
                        Ya, Kirim
                    </button>
                </form>
                <button type="button" 
                        onclick="hideSendDialog()"
                        class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                    Batal
                </button>
            </div>
        </x-slot>
    </x-dialog>
</div>

<script>
function showDeleteDialog() {
    document.getElementById('deleteDialog').classList.remove('hidden');
}

function hideDeleteDialog() {
    document.getElementById('deleteDialog').classList.add('hidden');
}

function showSendDialog() {
    document.getElementById('sendDialog').classList.remove('hidden');
}

function hideSendDialog() {
    document.getElementById('sendDialog').classList.add('hidden');
}
</script>
@endsection 