@extends('layouts.layout')

@section('content')
    @include('warehouse.barangpallet-form', [
        'tempat' => $tempat,
        'kodeBarang' => $kodeBarang,
        'barangPallet' => $barangPallet ?? null
    ])
@endsection