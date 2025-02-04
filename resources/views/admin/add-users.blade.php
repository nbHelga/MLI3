@extends('layouts.layout')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Add New User</h1>
        </div>

        <form id="userForm" action="{{ route('admin.store-user') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- ID (Employee) Dropdown -->
            <div>
                <label for="id" class="block text-sm font-medium text-gray-700 mb-1">ID Employee</label>
                <select name="id" id="id" required onchange="updateEmployeeName(this)"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline-indigo-600">
                    <option hidden value=""></option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" data-nama="{{ $employee->nama }}">
                            {{ $employee->id }} - {{ $employee->nama }}
                        </option>
                    @endforeach
                </select>
                @error('id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama (Auto-generated) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <div id="employeeName" class="block w-full rounded-md bg-gray-50 px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300">
                    -
                </div>
            </div>

            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <x-input-nama 
                    name="username"
                    placeholder="Enter username"
                    value="{{ old('username') }}"
                />
                @error('username')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <x-input-nama 
                    name="password"
                    type="password"
                    placeholder="Enter password"
                />
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Dropdown -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" required 
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline-indigo-600">
                    <option value="" hidden></option>
                    <option value="Super Admin">Super Admin</option>
                    <option value="Administrator">Administrator</option>
                    <option value="Operator">Operator</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.superadmin') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="button" 
                        onclick="showConfirmDialog()"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    Add User
                </button>
            </div>
        </form>
    </div>
</div>

<x-dialog id="confirmDialog" 
          title="Konfirmasi"
          message="Apakah Anda yakin ingin menambahkan user baru ini?">
    <x-slot name="actions">
        <button type="button" 
                data-confirm
                onclick="document.getElementById('userForm').submit();"
                class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">
            Ya
        </button>
        <button type="button" 
                onclick="this.closest('[role=dialog]').classList.add('hidden')"
                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
            Tidak
        </button>
    </x-slot>
</x-dialog>

<script>
function updateEmployeeName(select) {
    const nama = select.options[select.selectedIndex].dataset.nama;
    document.getElementById('employeeName').textContent = nama || '-';
}

function showConfirmDialog() {
    document.getElementById('confirmDialog').classList.remove('hidden');
}
</script>
@endsection 