@extends('layouts.layout')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 pb-6">Super Admin Dashboard</h1>
        {{-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-16">
            <!-- Statistics Cards -->
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="font-semibold text-blue-800">Total Users</h3>
                <p class="text-2xl font-bold text-blue-600">0</p>
            </div>
            
            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="font-semibold text-green-800">Active Users</h3>
                <p class="text-2xl font-bold text-green-600">0</p>
            </div>
            
            <div class="bg-purple-50 p-4 rounded-lg">
                <h3 class="font-semibold text-purple-800">Total Departments</h3>
                <p class="text-2xl font-bold text-purple-600">0</p>
            </div>
        </div>
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">User Management</h1>
            <button id="saveChanges" class="hidden bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Simpan
            </button>
        </div> --}}

        <!-- Tabel User Management -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($users as $index => $user)
                    <tr>
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">{{ $user->employee->nama }}</td>
                        <td class="px-6 py-4">{{ $user->id }}</td>
                        <td class="px-6 py-4">
                            <form id="statusForm{{ $user->id }}" 
                                  action="{{ route('admin.update-status', $user->id) }}" 
                                  method="POST" 
                                  style="display: inline;">
                                @csrf
                                @method('PUT')
                                <select name="status" 
                                        onchange="document.getElementById('statusForm{{ $user->id }}').submit()"
                                        class="block w-full rounded-md px-3 py-1.5 text-sm outline outline-1 -outline-offset-1 outline-gray-300
                                        {{ $user->status === 'Super Admin' ? 'text-red-600' : '' }}
                                        {{ $user->status === 'Administrator' ? 'text-blue-600' : '' }}
                                        {{ $user->status === 'Operator' ? 'text-green-600' : '' }}
                                        {{ !$user->status ? 'text-gray-500' : '' }}">
                                    <option value="" 
                                            class="text-gray-500" 
                                            {{ !$user->status ? 'selected' : '' }}>
                                        (Belum Dipilih)
                                    </option>
                                    <option value="Super Admin" 
                                            class="text-red-600"
                                            {{ $user->status === 'Super Admin' ? 'selected' : '' }}>
                                        Super Admin
                                    </option>
                                    <option value="Administrator" 
                                            class="text-blue-600"
                                            {{ $user->status === 'Administrator' ? 'selected' : '' }}>
                                        Administrator
                                    </option>
                                    <option value="Operator" 
                                            class="text-green-600"
                                            {{ $user->status === 'Operator' ? 'selected' : '' }}>
                                        Operator
                                    </option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.editform', $user->id) }}" >
                                    <img src="{{ asset('setting.png') }}" alt="setting" class="w-6 h-6">
                                </a>
                                @if($user->status !== 'Super Admin')
                                <button 
                                    onclick="deleteUser('{{ $user->id }}')">
                                    <img src="{{ asset('delete.png') }}" alt="delete" class="w-6 h-6">
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center mt-6">
            {{-- <h1 class="text-2xl font-bold">User Management</h1> --}}
            <a href="{{ route('admin.add-users') }}" 
               class="px-4 py-2 bg-gray-200 text-black border border-gray-300 rounded-md hover:bg-gray-300">
                Add User
            </a>
        </div>
    </div>
</div>

@if(session('add_success'))
    <x-pop-up-message 
        type="success" 
        message="{{ session('add_success') }}" />
@endif

@if(session('add_error'))
    <x-pop-up-message 
        type="error" 
        message="{{ session('add_error') }}" />
@endif

@if(session('status_success'))
    <x-pop-up-message 
        type="success" 
        message="{{ session('status_success') }}" />
@endif

@if(session('status_error'))
    <x-pop-up-message 
        type="error" 
        message="{{ session('status_error') }}" />
@endif

@push('scripts')
<script>
    let statusChanges = {};
    const saveButton = document.getElementById('saveChanges');

    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function() {
            const userId = this.dataset.userId;
            const originalStatus = this.dataset.originalStatus;
            const newStatus = this.value;

            if (newStatus !== originalStatus) {
                statusChanges[userId] = newStatus;
            } else {
                delete statusChanges[userId];
            }

            // Tampilkan atau sembunyikan tombol Save berdasarkan ada tidaknya perubahan
            saveButton.style.display = Object.keys(statusChanges).length > 0 ? 'block' : 'none';
        });
    });

    saveButton.addEventListener('click', async function() {
        try {
            const response = await fetch('{{ route("admin.users.status") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ changes: statusChanges })
            });

            if (response.ok) {
                alert('Status berhasil diperbarui');
                location.reload();
            } else {
                throw new Error('Gagal memperbarui status');
            }
        } catch (error) {
            alert(error.message);
        }
    });

    async function deleteUser(userId) {
        if (!confirm('Apakah Anda yakin ingin menghapus user ini?')) {
            return;
        }

        try {
            const response = await fetch(`/admin/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            if (response.ok) {
                alert('User berhasil dihapus');
                location.reload();
            } else {
                throw new Error('Gagal menghapus user');
            }
        } catch (error) {
            alert(error.message);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Handle status changes
        const statusSelects = document.querySelectorAll('select[name="status"]');
        statusSelects.forEach(select => {
            select.addEventListener('change', async function() {
                const userId = this.closest('tr').dataset.userId;
                try {
                    const response = await fetch(`/admin/users/${userId}/status`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ status: this.value })
                    });

                    const data = await response.json();
                    if (response.ok) {
                        alert('Status berhasil diperbarui');
                    } else {
                        throw new Error(data.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert(error.message);
                    // Reset to previous value if failed
                    this.value = this.dataset.originalValue;
                }
            });
        });
    });

    function updateUserStatus(selectElement, userId) {
        const newStatus = selectElement.value;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/users/${userId}/status`;
        
        // CSRF Token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Method Spoofing untuk PUT request
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PUT';
        form.appendChild(methodField);
        
        // Status field
        const statusField = document.createElement('input');
        statusField.type = 'hidden';
        statusField.name = 'status';
        statusField.value = newStatus;
        form.appendChild(statusField);
        
        document.body.appendChild(form);
        form.submit();
    }
</script>
@endpush
@endsection
