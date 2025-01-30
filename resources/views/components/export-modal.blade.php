@props(['title', 'route'])

<div x-data="{ 
    showModal: false,
    activeTab: 'filter',
    format: 'excel',
    filters: [],
    sorts: [],
    
    addFilter(type, value) {
        if (!value) return;
        
        let filterText = '';
        let filterValue = value;
        
        switch(type) {
            case 'tanggal':
                if (typeof value === 'object' && value.display) {
                    filterText = `tanggal ${value.display}`;
                    filterValue = value.value;
                }
                break;
            case 'ruangan':
                filterText = `ruangan ${value}`;
                break;
            case 'pallet':
                filterText = `pallet ${value}`;
                break;
        }
        
        if (filterText) {
            console.log('Adding filter:', { type, value: filterValue, text: filterText }); // Debug log
            this.filters.push({ 
                type, 
                value: filterValue, 
                text: filterText 
            });
        }
    },
    
    addSort(value) {
        if (!value) return;
        
        const sortText = {
            'tanggal': 'tanggal',
            'ruangan': 'ruangan',
            'pallet': 'pallet'
        }[value] || value;
        
        this.sorts.push({ value, text: `Mengurutkan berdasarkan ${sortText}` });
    }
}">
    <!-- Trigger Button -->
    <button @click="showModal = true" 
            type="button"
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
        Export
    </button>

    <!-- Modal -->
    <div x-show="showModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="relative bg-white rounded-lg max-w-2xl w-full">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">{{ $title }}</h3>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Tabs -->
                    <div class="border-b border-gray-200 mb-4">
                        <nav class="-mb-px flex space-x-8">
                            <button @click="activeTab = 'filter'"
                                    :class="{'border-blue-500 text-blue-600': activeTab === 'filter'}"
                                    class="py-2 px-1 border-b-2 font-medium text-sm">
                                Filter
                            </button>
                            <button @click="activeTab = 'format'"
                                    :class="{'border-blue-500 text-blue-600': activeTab === 'format'}"
                                    class="py-2 px-1 border-b-2 font-medium text-sm">
                                Format
                            </button>
                        </nav>
                    </div>

                    <!-- Content -->
                    <div x-show="activeTab === 'filter'">
                        {{ $filter }}
                    </div>

                    <div x-show="activeTab === 'format'">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Format</label>
                                <select x-model="format" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="excel">Excel</option>
                                    <option value="pdf">PDF</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-6 flex justify-end space-x-3">
                        <button @click="showModal = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Cancel
                        </button>
                        <form :action="'{{ $route }}'" method="POST">
                            @csrf
                            <input type="hidden" name="format" :value="format">
                            <input type="hidden" name="filters" :value="JSON.stringify(filters)">
                            <input type="hidden" name="sorts" :value="JSON.stringify(sorts)">
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                                Export
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 