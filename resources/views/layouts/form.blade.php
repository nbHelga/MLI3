@extends('layouts.layout')

@section('title', 'Form - MLI Store')

@section('content')
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-7xl">
            <div class="bg-white p-8 rounded-lg shadow-md">
                <h2 class="text-center text-3xl font-bold tracking-tight text-gray-900">Profile Form</h2>
                <div class="mt-8">
                    @include('components.form')
                </div>
            </div>
        </div>
    </div>
@endsection
