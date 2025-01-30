@extends('layouts.layout')

@section('content')
    <div class="mx-auto max-w-7xl py-2 sm:px-6 md:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Daftar Perpindahan Barang Gudang CS02</h1>
        @include('warehouse.barangpallet-list', [
            'barangPallets' => $barangPallets,
            'categories' => $categories,
            'rooms' => $rooms,
            'room' => $room ?? 'all',
            'perPage' => $perPage,
            'tempat' => 'cs02'
        ])

        @if(session('error'))
            <x-pop-up-message type="error" message="Your actions did not complete successfully. Please try again." />
        @endif
    </div>
@endsection
