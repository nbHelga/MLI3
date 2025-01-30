@props(['categories'])

<form x-data="{
    showFilter: false,
    categories: {{ json_encode($categories) }},
    resetAllFilters() {
        const currentUrl = new URL(window.location.href);
        const tempat = currentUrl.searchParams.get('tempat');
        if (tempat) {
            window.location.href = `${window.location.pathname}?tempat=${tempat}`;
        } else {
            window.location.href = window.location.pathname;
        }
    }
}" class="relative">
    <!-- Filter Button -->
    <button type="button"
            @click="showFilter = !showFilter"
            class="inline-flex items-center px-4 py-2 bg-white text-gray-600 rounded-md hover:bg-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
        </svg>
        Filter
    </button>

    <!-- Filter Panel -->
    <div x-show="showFilter"
         x-cloak
         @click.away="showFilter = false"
         class="fixed right-4 top-1/2 transform -translate-y-1/2 z-50 w-max bg-white p-4 rounded-lg shadow-lg border border-gray-200"
         style="max-height: 90vh; overflow-y: auto;">
        <!-- Reset Filter -->
        <div class="mb-4 pb-4 border-b border-gray-200">
            <button type="button" 
                    @click="resetAllFilters()"
                    class="flex items-center font-medium text-indigo-600 hover:text-indigo-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                </svg>
                Reset Filter
            </button>
        </div>

        <div class="flex space-x-6">
            <!-- Keterangan -->
            <div class="w-64">
                <div class="font-medium mb-2">Keterangan</div>
                <div class="border-b border-gray-200 mb-4"></div>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               @click="categories['Keterangan'].items.forEach(item => item.checked = !categories['Keterangan'].selectAll)"
                               x-model="categories['Keterangan'].selectAll">
                        <span class="ml-2">Select All</span>
                    </label>
                    <template x-for="item in categories['Keterangan'].items" :key="item.name">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   x-model="item.checked" 
                                   name="keterangan[]" 
                                   :value="item.name">
                            <span class="ml-2" x-text="`${item.name} (${item.count})`"></span>
                        </label>
                    </template>
                </div>
            </div>

            <!-- PIC -->
            <div class="w-64">
                <div class="font-medium mb-2">PIC</div>
                <div class="border-b border-gray-200 mb-4"></div>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               @click="categories['PIC'].items.forEach(item => item.checked = !categories['PIC'].selectAll)"
                               x-model="categories['PIC'].selectAll">
                        <span class="ml-2">Select All</span>
                    </label>
                    <template x-for="item in categories['PIC'].items" :key="item.name">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   x-model="item.checked" 
                                   name="pic[]" 
                                   :value="item.name">
                            <span class="ml-2" x-text="`${item.name} (${item.count})`"></span>
                        </label>
                    </template>
                </div>
            </div>
        </div>

        <!-- Apply Filter Button -->
        <div class="mt-4 flex justify-end border-t border-gray-200 pt-4">
            <button type="button"
                    @click="showFilter = false"
                    class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                Cancel
            </button>
            <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700">
                Apply
            </button>
        </div>
    </div>
</form> 