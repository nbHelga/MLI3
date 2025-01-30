@extends('layouts.layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Pop-up Message hanya ditampilkan jika ada session type dan message -->
    @if(session('type') && session('message'))
        <x-pop-up-message :type="session('type')" :message="session('message')" />
    @endif

    <!-- Tabs -->
    <x-tab :tabs="$tabs" :activeTab="request('tab', 'all')" />

    <!-- Filter -->
    <div class="my-4">
        <x-filter :categories="[
            'pic' => 'Nama PIC',
            'keterangan' => 'Keterangan'
        ]" />
    </div>

    <!-- Table -->
    <x-table :headers="['PIC', 'Suhu', 'Keterangan', 'Pukul', 'Gambar']">
        @foreach($suhuList as $suhu)
            <tr>
                <td class="px-3 py-4">
                    <x-elements.check-list :id="$suhu->id" />
                </td>
                <td class="px-6 py-4">{{ $suhu->employee->nama }}</td>
                <td class="px-6 py-4">{{ $suhu->suhu }}°C</td>
                <td class="px-6 py-4">{{ $suhu->keterangan }}</td>
                <td class="px-6 py-4">{{ $suhu->jam }}</td>
                <td class="px-6 py-4">
                    @if($suhu->gambar)
                        <img src="{{ Storage::url($suhu->gambar) }}" alt="Suhu" class="h-10 w-10 object-cover rounded">
                    @endif
                </td>
                <td class="px-3 py-4">
                    <x-elements.advanced 
                        :id="$suhu->id"
                        :editRoute="route('maintenance.suhu.edit', ['suhu' => $suhu->id])"
                        :deleteRoute="route('maintenance.suhu.destroy', ['suhu' => $suhu->id])"
                    />
                </td>
            </tr>
        @endforeach
    </x-table>

    <!-- Pagination -->
    <div class="mt-4 flex items-center justify-between">
        <x-filter-pagination :perPage="request('perPage', 10)" />
        {{ $suhuList->links() }}
    </div>
</div>
@endsection