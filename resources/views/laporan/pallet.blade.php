@extends('layouts.layout')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        {{-- <h1 class="text-2xl font-bold text-gray-800 mb-4">Laporan Barang</h1> --}}
        <!-- Content will be added later -->
        <x-laporan-layout 
            title="Laporan Kode Ikan dan Pallet"
            action="{{ route('laporan.pallet.export') }}"
            method="POST">
            <x-slot name="filter">
                <!-- Tanggal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <div class="flex space-x-2 mt-1">
                        <x-filter-kalender-export x-ref="tanggal" />
                        {{-- <button @click="
                            const input = $refs.tanggal;
                            if (input && input.hasAttribute('data-display')) {
                                addFilter('tanggal', {
                                    display: input.getAttribute('data-display'),
                                    value: input.getAttribute('data-value')
                                });
                            }"
                            class="inline-flex items-center p-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            +
                        </button> --}}
                    </div>
                </div>

                <!-- Tempat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tempat</label>
                    <div class="flex space-x-2 mt-1">
                        <x-filter-tempat x-ref="tempat" type="pallet" />
                    </div>
                </div>

                <!-- Ruangan -->
                <div class="w-full">
                    <label class="block text-sm font-medium text-gray-700">Ruangan</label>
                    {{-- <div class="flex space-x-2 mt-1"> --}}
                        <x-filter-ruangan x-ref="ruangan" />
                    {{-- </div> --}}
                </div>

                <!-- Pallet -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pallet</label>
                    <div class="flex space-x-2 mt-1">
                        {{-- @if(isset($palletCodes))
                            <x-filter-pallet :palletCodes="$palletCodes" x-ref="pallet" />
                        @else
                            <x-filter-pallet :palletCodes="[]" x-ref="pallet" />
                        @endif --}}
                        <x-filter-pallet x-ref="pallet" />
                        {{-- <button @click="addFilter('pallet', $refs.pallet.value)"
                            class="inline-flex items-center p-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            +
                        </button> --}}
                    </div>
                </div>

                <!-- Sort -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pengurutan</label>
                    <div class="flex space-x-2 mt-1">
                        <x-filter-sort :options="['pallet' => 'Pallet']" x-ref="sort" />
                        <button @click="addSort($refs.sort.value)"
                            class="inline-flex items-center p-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            +
                        </button>
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="w-full justify-between gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-regular text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50" 
                     x-data 
                     @filters-updated.window="filters = $event.detail.filters">
                    <x-filter-keterangan />
                </div>
            </x-slot>
        </x-laporan-layout>
    </div>
</div>
@endsection 