<div class="py-6" x-data="{ isSelecting: false }">
    <div class="container mx-auto">
        <!-- Tabs -->
        @if(isset($rooms) && count($rooms) > 0)
            <x-tab :rooms="$rooms" :currentRoom="$room ?? 'all'" :tempat="strtolower($tempat)" />
        @endif

        <!-- Search & Filter -->
        <div class="flex justify-between items-center my-4">
            <div class="flex-1 max-w-lg">
                <x-search-barang-gudang placeholder="Cari kode pallet atau barang..." :tempat="$tempat"/>
            </div>
            <div class="flex items-center space-x-4">
                <x-filter-barang-gudang :categories="$categories" />
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="pl-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        {{-- <th class="w-12 px-3 py-3">
                            <x-elements.check-list 
                                id="select-all"
                                :is-header="true"
                                delete-route="{{ route('warehouse.barangpallet.destroy-multiple') }}"
                                reload-route="{{ url()->current() }}"
                            />
                        </th> --}}
                        <th class="pr-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Pallet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PIC</th>
                        <th class="w-12 px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{-- <template x-if="!isSelecting">
                                <button @click="isSelecting = true; window.dispatchEvent(new CustomEvent('toggle-select-mode', { detail: { isSelecting: true } }))"
                                        class="text-blue-600 hover:text-blue-800">
                                    Select
                                </button>
                            </template>
                            <template x-if="isSelecting">
                                <div class="flex justify-end space-x-2">
                                    <button @click="$dispatch('delete-selected')"
                                            class="text-red-600 hover:text-red-800">
                                        Delete
                                    </button>
                                    <button @click="isSelecting = false; window.dispatchEvent(new CustomEvent('toggle-select-mode', { detail: { isSelecting: false } }))"
                                            class="text-gray-600 hover:text-gray-800">
                                        Cancel
                                    </button>
                                </div>
                            </template> --}}
                        </th> 
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($barangPallets as $item)
                        <tr>
                            <td class="pl-6 py-4">{{ $loop->iteration }}</td>
                            {{-- <td class="w-12 px-3 py-4">
                                <x-elements.check-list :id="$item->id" />
                            </td> --}}
                            <td class="pr-6 py-4">{{ $item->tempat->ruangan }}</td>
                            <td class="px-6 py-4">{{ $item->kode_pallet }}</td>
                            <td class="px-6 py-4">{{ $item->barang->kode }}</td>
                            <td class="px-6 py-4">{{ $item->employee->nama }}</td>
                            {{-- <td class="w-12 px-3 py-4">
                                <template x-if="!isSelecting">
                                    <x-elements.advanced-barang-gudang :id="$item->id" :barangPallet="$item" />
                                </template>
                            </td> --}}
                            <td class="w-12 px-3 py-4">
                                <x-elements.advanced-barang-gudang :id="$item->id" :barangPallet="$item" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center mb-4">
            <div class="flex pt-4 space-x-2">
                {{-- <x-export-modal-pallet 
                    :tempat="$tempat"
                    :route="route('warehouse.barangpallet.export')"
                    :palletCodes="$palletCodes"
                /> --}}
                <!-- Other components -->
                @php
                    $user = auth()->user();
                    $isAdminOrSuperAdmin = in_array($user->status, ['Administrator', 'Super Admin']);
                @endphp

                <div class="flex pt-4 space-x-2">
                    @if($isAdminOrSuperAdmin)
                        <x-import-modal :route="route('warehouse.barangpallet.import')" />
                    @endif
                </div>
            </div>
            <div class="flex space-x-4">
            </div>
        </div>
        <!-- Pagination -->
        <div class="mt-4">
            {{ $barangPallets->links('components.pagination', ['perPage' => $perPage]) }}
        </div>
    </div>

    {{-- @if(session('add_success'))
        <x-pop-up-message type="success" message="Data has been added successfully" />
    @endif --}}

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