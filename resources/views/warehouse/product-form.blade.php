@extends('layouts.layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <form action="{{ isset($barang) ? route('warehouse.product.update', ['id' => $barang->kode]) : route('warehouse.product.store') }}" 
          method="POST" 
          class="space-y-4"
          id="productForm">
        @csrf
        @if(isset($barang))
            @method('PUT')
        @endif

        <!-- Kode -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Kode Barang</label>
            <x-input-nama 
                name="kode"
                :value="old('kode', $barang->kode ?? '')"
                placeholder="Masukkan kode, contoh: CEKAK - SP - JUMBO"
            />
            @error('kode')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nama -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Barang</label>
            <x-input-nama 
                name="nama"
                :value="old('nama', $barang->nama ?? '')"
                placeholder="Masukkan Nama, contoh: CEKAK"
            />
            @error('nama')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Kualitas -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Kualitas</label>
            <x-dropdown-kualitas :value="old('kualitas', $barang->kualitas ?? '')" />
                
            {{-- <x-dropdown-kualitas />  --}}
            @error('kualitas')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div> 

        <!-- Size -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Size</label>
            <x-dropdown-size :value="old('size', $barang->size ?? '')" />
            @error('size')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Jumlah -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
            <x-input-nama 
                name="jumlah"
                type="number"
                :value="old('jumlah', $barang->jumlah ?? 0)"
                placeholder="Masukkan Jumlah, contoh: 20"
            />
            @error('jumlah')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('warehouse.product-list') }}"
               class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                {{ isset($barang) ? 'Update' : 'Simpan' }}
            </button>
        </div>
    </form>
</div>

@if(!isset($barang))
    <!-- Success Dialog hanya untuk create -->
    <x-dialog id="successDialog" title="Success!">
        <x-slot name="message">Data berhasil ditambahkan</x-slot>
        <x-slot name="actions">
            <button onclick="window.location.href='{{ route('warehouse.product-form') }}'"
                    class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Back
            </button>
            <button onclick="window.location.href='{{ route('warehouse.product-list') }}'"
                    class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Continue
            </button>
        </x-slot>
    </x-dialog>

    @if(session('showDialog'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const dialog = document.getElementById('successDialog');
                dialog.classList.remove('hidden');
            });
        </script>
    @endif
@endif

@endsection
