<div class="mt-4 p-4 bg-gray-50 rounded-md">
    <h4 class="text-sm font-medium text-gray-900 mb-2">Keterangan Filter:</h4>
    <div class="space-y-2">
        <template x-for="(filter, index) in filters" :key="index">
            <div class="flex items-center justify-between text-sm text-gray-600">
                <span x-text="'Menambahkan filter berdasarkan ' + filter.text"></span>
                <button @click="filters.splice(index, 1)" 
                        class="text-red-500 hover:text-red-700">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </template>
        <template x-for="(sort, index) in sorts" :key="index">
            <div class="flex items-center justify-between text-sm text-gray-600">
                <span x-text="sort.text"></span>
                <button @click="sorts.splice(index, 1)" 
                        class="text-red-500 hover:text-red-700">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </template>
    </div>
</div> 