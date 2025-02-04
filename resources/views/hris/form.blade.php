@extends('layouts.layout')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">
            {{ isset($surat) ? 'Edit Pengajuan Surat' : 'Form Pengajuan Surat' }}
        </h1>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        
        <form action="{{ isset($surat) ? route('hris.update', $surat->id) : route('hris.store') }}" 
              method="POST"
              enctype="multipart/form-data"
              id="suratForm">
            @csrf
            @if(isset($surat))
                @method('PUT')
            @endif
            
            <!-- Perihal -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Perihal
                </label>
                <x-elements.dropdown 
                    name="perihal"
                    :options="['Lembur', 'Izin', 'Cuti', 'Reimbursement', 'Perjalanan Dinas', 'Survey Pelanggan', 'Penagihan Piutang', 'Lainnya']"
                    :value="old('perihal', $surat->perihal ?? '')"
                    buttonText="Pilih Perihal"
                />
            </div>

            <!-- Tanggal -->
            <div class="mb-4">
                {{-- <label class="block text-gray-700 text-sm font-bold mb-2">
                    Tanggal Berlaku
                </label> --}}
                <x-elements.input-nama 
                    name="tgl"
                    label="Tanggal Berlaku (Opsional)"
                    type="date"
                    :value="old('tgl', isset($surat) ? $surat->tgl : '')"
                    placeholder="dd/mm/yyyy"
                    required
                />
            </div>

            <!-- Dana -->
            <div class="mb-4">
                {{-- <label class="block text-gray-700 text-sm font-bold mb-2">
                    Dana
                </label> --}}
                <x-elements.input-nama 
                    name="dana"
                    label="Dana (Opsional)"
                    type="number"
                    :value="old('dana', isset($surat) ? $surat->dana : '')"
                    placeholder="0"
                />
            </div>

            <!-- Durasi -->
            <div class="mb-4">
                <x-elements.input-nama 
                    name="durasi"
                    label="Durasi (hari)"
                    type="number"
                    :value="old('durasi', isset($surat) ? $surat->durasi : '')"
                    placeholder="0"
                    required
                />
            </div>

            <!-- Keterangan -->
            <div class="mb-4 mt-4">
                {{-- <label class="block text-gray-700 text-sm font-bold mb-2">
                    Keterangan
                </label> --}}
                @include('elements.input-keterangan', [
                    'name' => 'keterangan',
                    'label' => 'Keterangan (Opsional)',
                    'rows' => 3,
                    'value' => old('keterangan', isset($surat) ? $surat->keterangan : ''),
                    'placeholder' => '...'
                ])
            </div>

            <!-- Dokumen yang ada -->
            @if(isset($surat) && $surat->dokumen_pelengkap)
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Dokumen saat ini: {{ basename($surat->dokumen_pelengkap) }}</p>
                    <a href="{{ route('hris.download', $surat->id) }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm">
                        Lihat dokumen
                    </a>
                </div>
            @endif

            <!-- Dokumen Pelengkap -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    {{ isset($surat) ? 'Ganti Dokumen Pelengkap' : 'Dokumen Pelengkap' }}
                </label>
                <x-elements.file-upload 
                    name="dokumen_pelengkap"
                    :value="old('dokumen_pelengkap')"
                    accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
                />
                <p class="text-xs text-gray-500 mt-1">
                    Format yang diperbolehkan: PDF, DOC, DOCX, PNG, JPG, JPEG (Max: 2MB)
                </p>
            </div>

            <div class="flex justify-between items-center mt-6">
                <p class="text-sm text-gray-500">
                    {{ isset($surat) ? '' : '*Surat pengajuan masih dapat diubah nanti' }}
                </p>
                
                <div class="flex space-x-4">
                    <a href="{{ route('home') }}"
                       class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="button"
                            onclick="showConfirmDialog()"
                            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                        {{ isset($surat) ? 'Update' : 'Tambah' }}
                    </button>
                </div>
            </div>

            <!-- Dialog untuk tambah data -->
            @if(!isset($surat))
                <x-dialog id="confirmDialog" 
                    title="Konfirmasi Penambahan"
                    class="bg-white"
                    message="Apakah Anda yakin untuk menambahkan data surat pengajuan?">
                    <p class="text-sm text-gray-500 mt-2"></p>
                    <x-slot name="actions">
                        <button type="button" 
                                onclick="document.getElementById('suratForm').submit();"
                                class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">
                            Ya, Tambah
                        </button>
                        <button type="button" 
                                onclick="hideConfirmDialog()"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Batal
                        </button>
                    </x-slot>
                </x-dialog>
            @else
                <!-- Dialog untuk edit data -->
                <x-dialog id="confirmDialog" 
                    title="Konfirmasi Perubahan"
                    class="bg-white"
                    message="Apakah Anda yakin untuk dengan perubahan yang dilakukan?">
                    <x-slot name="actions">
                        <button type="button" 
                                onclick="document.getElementById('suratForm').submit();"
                                class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">
                            Ya, Update
                        </button>
                        <button type="button" 
                                onclick="hideConfirmDialog()"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Batal
                        </button>
                    </x-slot>
                </x-dialog>
            @endif
        </form>
    </div>
</div>


<script>
function showConfirmDialog() {
    document.getElementById('confirmDialog').classList.remove('hidden');
}

function hideConfirmDialog() {
    document.getElementById('confirmDialog').classList.add('hidden');
}
</script>
@endsection 