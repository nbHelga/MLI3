@extends('layouts.layout')

@section('title', 'Suhu - MLI Store')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Edit Form Suhu</h1>
    {{-- <form action="{{ route('suhu.store') }}" method="post" enctype="multipart/form-data"> --}}
        @csrf
        @include('maintenance.suhu-form')
    {{-- </form> --}}
</div>
@endsection