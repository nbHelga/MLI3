<x-export-modal2 :title="'Export Data Suhu'" :route="route('maintenance.suhu.export')" method="GET">
    <x-slot name="filter">
        <!-- Tanggal -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
            <div class="flex space-x-2 mt-1">
                <x-filter-kalender x-ref="tanggal" />
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

        <!-- Tempat -->
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Tempat</label>
            <div class="flex space-x-2 mt-1">
                <x-filter-tempat x-ref="tempat" />
                <button @click="addFilter('tempat', $refs.tempat.value)"
                    class="inline-flex items-center p-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    +
                </button>
            </div>
        </div>

        <!-- Ruangan -->
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Ruangan</label>
            <div class="flex space-x-2 mt-1">
                <x-filter-ruangan2 x-ref="ruangan" />
                <button @click="addFilter('ruangan', $refs.ruangan.value)"
                    class="inline-flex items-center p-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    +
                </button>
            </div>
        </div>

        <!-- Sort -->
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Pengurutan</label>
            <div class="flex space-x-2 mt-1">
                <x-filter-sort x-ref="sort" />
                <button @click="addSort($refs.sort.value)"
                    class="inline-flex items-center p-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    +
                </button>
            </div>
        </div>

        <!-- Keterangan Filter -->
        <x-filter-keterangan />
    </x-slot>
</x-export-modal> 