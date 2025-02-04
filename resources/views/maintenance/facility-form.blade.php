@extends('layouts.layout')

@section('title', 'Fasilitas - MLI Store')

@section('content')
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-7xl">
            <form>
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <div class="sm:mx-auto sm:w-full sm:max-w-lg">
                        <h2 class="text-center text-2xl font-bold tracking-tight text-gray-900">Pencatatan Maintenance Fasilitas</h2>
                    </div>
                    <div class="items-center">
                        <label for="kode" class="block text-sm font-medium text-gray-900">Kode</label>
                        @include('elements.input-nama', ['name' => 'kode'])
                    </div>
                    <div class="items-center">
                        <label for="keterangan" class="block text-sm font-medium text-gray-900">Keterangan</label>
                        @include('elements.input-keterangan', ['name' => 'keterangan']) 
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <button type="button" class="text-sm font-semibold text-gray-900">Cancel</button>
                        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
