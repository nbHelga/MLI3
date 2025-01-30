@extends('layouts.layout')

@section('title', 'Form Suhu CS02')

@section('content')
<div class="container mx-auto px-4 py-8">
    @include('maintenance.suhu-form', [
        'ruanganOptions' => $ruanganOptions,
        'tempat' => 'CS02'
    ])
</div>
@endsection 