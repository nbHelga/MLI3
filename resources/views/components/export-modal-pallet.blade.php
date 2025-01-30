@props(['tempat', 'route', 'palletCodes'])

<x-export-modal :title="'Export Data Pencatatan Pallet dan Kode Ikan'" :route="$route">
    <x-slot name="filter">
        <div class="space-y-4">
            <!-- Tanggal -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                <div class="flex space-x-2 mt-1">
                    <x-filter-kalender-export x-ref="tanggal" />
                    <button @click="
                        const input = $refs.tanggal;
                        if (input && input.hasAttribute('data-display')) {
                            addFilter('tanggal', {
                                display: input.getAttribute('data-display'),
                                value: input.getAttribute('data-value')
                            });
                        }"
                            class="inline-flex items-center p-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        +
                    </button>
                </div>
            </div>

            <!-- Ruangan -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Ruangan</label>
                <div class="flex space-x-2 mt-1">
                    <x-filter-ruangan :tempat="$tempat" x-ref="ruangan" />
                    <button @click="addFilter('ruangan', $refs.ruangan.value, $refs.ruangan)"
                            class="inline-flex items-center p-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        +
                    </button>
                </div>
            </div>

            <!-- Pallet -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Pallet</label>
                <div class="flex space-x-2 mt-1">
                    <x-filter-pallet :palletCodes="$palletCodes" x-ref="pallet" />
                    <button @click="addFilter('pallet', $refs.pallet.value, $refs.pallet)"
                            class="inline-flex items-center p-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        +
                    </button>
                </div>
            </div>

            <!-- Sort -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Pengurutan</label>
                <div class="flex space-x-2 mt-1">
                    <x-filter-sort :options="['pallet' => 'Pallet']" x-ref="sort" />
                    <button @click="addSort($refs.sort.value)"
                            class="inline-flex items-center p-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        +
                    </button>
                </div>
            </div>

            <!-- Keterangan -->
            <x-filter-keterangan />
        </div>
    </x-slot>
</x-export-modal> 