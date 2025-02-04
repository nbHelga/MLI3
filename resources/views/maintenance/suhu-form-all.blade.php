@extends('layouts.layout')

@section('title', isset($suhu) ? 'Edit Suhu' : 'Form Suhu')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="space-y-4">
        <form method="POST" 
              action="{{ isset($suhu) ? route('suhu.update', $suhu->id) : route('suhu.store') }}" 
              enctype="multipart/form-data" 
              id="suhuForm" 
              onsubmit="return confirmSubmit(event)">
            @csrf
            @if(isset($suhu))
                @method('PUT')
            @endif
            
            <div class="bg-white p-8 rounded-lg shadow-md">
                <div class="sm:mx-auto sm:w-full sm:max-w-lg">
                    <h2 class="text-center text-2xl font-bold tracking-tight text-gray-900">
                        {{ isset($suhu) ? 'Edit Pencatatan Suhu Gudang' : 'Pencatatan Suhu Gudang' }}
                    </h2>
                </div>

                <!-- Tempat -->
                <label for="tempat" class="mt-4 block text-sm font-medium text-gray-900">Tempat</label>
                <x-dropdown-tempat :value="old('tempat', isset($suhu) ? strtolower($suhu->tempat->nama) : '')" />
                @error('tempat')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Ruangan -->
                <label for="ruangan" class="mt-4 block text-sm font-medium text-gray-900">Ruangan</label>
                <div x-data="{ tempat: '{{ old('tempat', isset($suhu) ? strtolower($suhu->tempat->nama) : '') }}' }" 
                     @tempat-changed.window="tempat = $event.detail.value">
                    <x-dropdown-ruangan2 
                        :tempat="old('tempat', isset($suhu) ? strtolower($suhu->tempat->nama) : '')"
                        :value="old('ruangan', isset($suhu) ? $suhu->tempat->ruangan : '')"
                    />
                </div>
                @error('ruangan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Suhu -->
                <label for="suhu" class="mt-4 block text-sm font-medium text-gray-900">Suhu</label>
                <div class="flex items-center">
                    @include('elements.input-nama', [
                        'name' => 'suhu',
                        'value' => old('suhu', isset($suhu) ? $suhu->suhu : '')
                    ])
                    <span class="ml-2">°C</span>
                </div>
                @error('suhu')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Keterangan -->
                <label for="keterangan" class="mt-4 block text-sm font-medium text-gray-900">Keterangan</label>
                <x-dropdown-keterangan :value="old('keterangan', isset($suhu) ? $suhu->keterangan : '')" />
                @error('keterangan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Gambar -->
                <label for="gambar" class="mt-4 block text-sm font-medium text-gray-900">Gambar</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                    <div class="space-y-1 text-center" id="upload-container">
                        <div class="flex flex-col items-center">
                            <img id="preview" 
                                 src="{{ isset($suhu) && $suhu->gambar ? asset('storage/' . $suhu->gambar) : '' }}" 
                                 class="{{ isset($suhu) && $suhu->gambar ? '' : 'hidden' }} mb-4 w-40 h-40 object-cover rounded">
                            <div id="upload-options" class="{{ isset($suhu) && $suhu->gambar ? 'hidden' : '' }} space-y-2">
                                <label class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                    <span>Upload gambar</span>
                                    <input id="gambar-upload" name="gambar" type="file" class="sr-only" 
                                        accept="image/*"
                                        onchange="previewImage(this)">
                                </label>
                                <p class="text-xs text-gray-500">atau</p>
                                <label class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                    <span>Ambil foto</span>
                                    <input id="gambar-camera" name="gambar" type="file" class="sr-only" 
                                        accept="image/*" capture="environment"
                                        onchange="previewImage(this)">
                                </label>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            </div>
                        </div>
                    </div>
                </div>
                @error('gambar')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Buttons -->
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <a href="{{ route('suhu.list') }}" 
                       class="text-sm font-semibold text-gray-900">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        {{ isset($suhu) ? 'Update' : 'Save' }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <x-dialog id="confirmDialog" 
              title="Konfirmasi"
              message="{{ isset($suhu) ? 'Apakah Anda yakin ingin mengupdate data suhu ini?' : 'Apakah Anda yakin ingin menyimpan data suhu ini?' }}">
        <x-slot name="actions">
            <button type="button" 
                    data-confirm
                    onclick="document.getElementById('suhuForm').submit();"
                    class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">
                Ya
            </button>
            <button type="button" 
                    onclick="this.closest('[role=dialog]').classList.add('hidden')"
                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                Tidak
            </button>
        </x-slot>
    </x-dialog>
</div>

<script>
function confirmSubmit(event) {
    event.preventDefault();
    const dialog = document.getElementById('confirmDialog');
    dialog.classList.remove('hidden');
    return false;
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const preview = document.getElementById('preview');
        const uploadOptions = document.getElementById('upload-options');
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            uploadOptions.classList.add('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection 