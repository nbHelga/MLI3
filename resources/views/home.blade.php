<x-layout>
    {{-- <x-slot:title>{{ $title }}</x-slot> --}}
    <h3 class="text-x1">This is Home Page</h3>
</x-layout>

<x-layout>
    {{-- <x-slot:title>{{ $title }}</x-slot> --}}
    <h3 class="text-x1">This is Home Page</h3>
</x-layout>

@extends('layouts.layout')

@section('title', 'Home')

@section('content')
    @include('components.sidebar')
    <div id="content" class="p-4">
        <h1>Welcome to the Home Page</h1>
        <!-- Konten lainnya -->
    </div>
@endsection
