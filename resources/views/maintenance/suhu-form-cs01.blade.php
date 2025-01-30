@extends('layouts.layout')

@section('title', 'Form Suhu CS01')

@section('content')
<div class="container mx-auto px-4 py-8">
    @include('maintenance.suhu-form', [
        'ruanganOptions' => $ruanganOptions,
        'tempat' => 'CS01'
    ])
</div>
@endsection 