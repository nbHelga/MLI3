@extends('layouts.layout')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        {{-- <h1 class="text-2xl font-bold text-gray-800 mb-4">Laporan Barang</h1> --}}
        <!-- Content will be added later -->
        <div x-data="{ 
            filters: [],
            sorts: [],
            addToFilters(filter) {
                this.filters = [...this.filters, filter];
                $dispatch('filters-updated', { filters: this.filters });
            }
        }" x-init="
            $store.filters = [];
            $watch('$store.filters', value => {
                filters = value;
            })
        ">
            <x-laporan-layout 
                title="Laporan Barang"
                action="{{ route('laporan.barang.export') }}"
                method="POST">
                <x-slot name="filter">
                    <!-- Tanggal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                        <div class="flex space-x-2 mt-1">
                            <x-filter-kalender-export x-ref="tanggal" />
                        </div>
                    </div>

                    <!-- Kualitas -->
                    <div class="w-full">
                        <label class="block text-sm font-medium text-gray-700">Kualitas</label>
                        <div class="flex space-x-2 mt-1">
                            <x-filter-kualitas :filters="$filters" />
                        </div>
                    </div>

                    <!-- Size -->
                    <div class="w-full">
                        <label class="block text-sm font-medium text-gray-700">Size</label>
                        <div class="flex space-x-2 mt-1">
                            <x-filter-size :filters="$filters" />
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
</div>
@endsection 