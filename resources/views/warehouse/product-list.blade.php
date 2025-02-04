@extends('layouts.layout')

@section('content')
<div class="py-6" 
     x-data="{ 
        selected: [],
        showDeleteDialog: false,
        
        toggleAll(e) {
            const checkboxes = document.querySelectorAll('tbody input[type=checkbox]');
            checkboxes.forEach(cb => {
                cb.checked = e.target.checked;
                this.toggleItem(cb.value, e.target.checked);
            });
        },
        
        toggleItem(kode, checked) {
            if (checked) {
                this.selected.push(kode);
            } else {
                this.selected = this.selected.filter(item => item !== kode);
            }
        },
        
        {{-- deleteSelected() {
            if (this.selected.length > 0) {
                this.showDeleteDialog = true;
            }
        },
        
        confirmDelete() {
            axios.delete('{{ route('warehouse.product.destroy-multiple') }}', {
                data: { kodes: this.selected }
            })
            .then(response => {
                this.showDeleteDialog = false;
                // Flash message untuk delete success
                window.location.href = '/warehouse/product';
                session().flash('delete_success', true);
            })
            .catch(error => {
                console.error('Delete error:', error);
            });
        } --}}
     }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Daftar Barang Internasional</h1>

        <div class="mt-4 space-y-4">
            <!-- Search Bar -->
            <div class="max-w-xl">
                <x-search placeholder="Cari kode atau nama barang..." />
            </div>

            <!-- Filter -->
            <div class="flex justify-between items-center">
                <x-filter-barang :categories="$categories" />
                <!-- Import Button -->
                @php
                    $user = auth()->user();
                    $isAdminOrSuperAdmin = in_array($user->status, ['Administrator', 'Super Admin']);
                @endphp

                <div class="flex pt-4 space-x-2">
                    @if($isAdminOrSuperAdmin)
                        <x-import-modal :route="route('warehouse.product.import')" />
                    @endif
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="w-12 px-3 py-3">
                                <input type="checkbox" 
                                       @click="toggleAll($event)"
                                       class="h-4 w-4 rounded border-gray-300">
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Barang</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kualitas</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                            {{-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th> --}}
                            <th scope="col" class="w-12 px-3 py-3">
                                <template x-if="selected.length > 0">
                                    <button @click="deleteSelected()"
                                            class="text-red-600 hover:text-red-900">
                                        Delete
                                    </button>
                                </template>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($barang as $item)
                            <tr>
                                <td class="w-12 px-3 py-4">
                                    <input type="checkbox"
                                           value="{{ $item->kode }}"
                                           @click="toggleItem($event.target.value, $event.target.checked)"
                                           class="h-4 w-4 rounded border-gray-300">
                                </td>
                                <td class="px-6 py-4">{{ $item->kode }} (INT)</td>
                                <td class="px-6 py-4">{{ $item->nama }} (INT)</td>
                                <td class="px-6 py-4">{{ $item->kualitas }}</td>
                                <td class="px-6 py-4">{{ $item->size }}</td>
                                {{-- <td class="px-6 py-4">{{ $item->jumlah }}</td> --}}
                                <td class="w-12 px-3 py-4">
                                    <x-elements.advanced-barang :kode="$item->kode" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $barang->links('components.pagination', ['perPage' => $perPage]) }}
            </div>
        </div>
    </div>

    <!-- Pop-up Message -->
    @if(session('import_success'))
        <x-pop-up-message 
            type="success" 
            message="Data imported successfully. {{ session('import_count') }} records imported." />
    @endif

    @if(session('import_error'))
        <x-pop-up-message type="error" message="Data failed to import. Please check the file again." />
    @endif
    
    @if(session('error'))
        <x-pop-up-message type="error" message="Your actions did not complete successfully. Please try again." />
    @endif
</div>
@endsection