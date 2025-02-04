@extends('layouts.layout')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8">
    <h1 class="pt-4 text-2xl font-semibold text-gray-900">Daftar Suhu</h1>
    <div class="py-6">
        <div class="container mx-auto">
            <!-- Tabs -->
            <x-tab-suhu 
                :activeParentTab="request('tempat', 'all')"
                :activeChildTab="request('ruangan', 'all')"
            />
    
            <!-- Search & Filter -->
            <div class="flex justify-between items-center my-4">
                <div class="flex-1 max-w-lg">
                    {{-- <x-search-suhu placeholder="Cari ruangan, suhu, atau keterangan..." /> --}}
                    <x-filter-tanggal />
                </div>

                @php
                    $user = auth()->user();
                    $isAdminOrSuperAdmin = in_array($user->status, ['Administrator', 'Super Admin']);
                @endphp

                <div class="flex items-center space-x-4">
                    @if($isAdminOrSuperAdmin)
                        <a href="{{ route('suhu.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white text-gray-900 rounded-md hover:bg-gray-400">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="font-semibold text-gray-600">Input Suhu</span>
                    </a>
                    @endif
                </div>
            </div>
    
            <!-- Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tempat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Suhu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pukul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PIC</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                            <th class="w-12 px-3 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($suhuList as $suhu)
                            <tr>
                                <td class="px-6 py-4">{{ $suhu->tempat->nama }}</td>
                                <td class="px-6 py-4">{{ $suhu->tempat->ruangan }}</td>
                                <td class="px-6 py-4">{{ $suhu->suhu }}°C</td>
                                <td class="px-6 py-4">{{ $suhu->keterangan }}</td>
                                <td class="px-6 py-4">{{ $suhu->jam }}</td>
                                <td class="px-6 py-4">{{ $suhu->employee->nama }}</td>
                                <td class="px-6 py-4">
                                    @if($suhu->gambar)
                                        <img src="{{ Storage::url($suhu->gambar) }}" 
                                             alt="Suhu" 
                                             class="h-10 w-10 object-cover rounded">
                                    @endif
                                </td>
                                <td class="px-3 py-4">
                                    <x-elements.advanced-suhu :id="$suhu->id" :suhu="$suhu" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- <div class="flex justify-between items-center mb-4">
                <div class="flex pt-4 space-x-2">
                    <x-export-modal-suhu />
                </div>
                <div class="flex space-x-4">
                </div>
            </div> --}}
    
            <!-- Pagination -->
            <div class="mt-4">
                {{ $suhuList->links('components.pagination', ['perPage' => $perPage]) }}
            </div>

            
            @if(session('add_success'))
                <x-pop-up-message type="success" message="Data has been successfully added" />
            @endif

            @if(session('add_error'))
                <x-pop-up-message type="error" message="Data cannot be added, please check again." />
            @endif
        </div>
    </div>
</div>
@endsection 