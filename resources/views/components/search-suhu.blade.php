@props(['placeholder' => 'Search...'])

<div x-data="{ 
    query: '', 
    results: [],
    showResults: false,
    selectedCategory: null,
    categories: {
        'Ruangan': [],
        'Suhu': [],
        'Keterangan': []
    },
    async search() {
        if (this.query.length > 2) {
            try {
                const response = await fetch(`/api/suhu/search?q=${this.query}`);
                const data = await response.json();
                this.categories = data;
                this.showResults = true;
            } catch (error) {
                console.error('Search error:', error);
            }
        } else {
            this.showResults = false;
        }
    }
}" 
class="relative">
    <div class="relative">
        <input type="text"
               x-model="query"
               @input.debounce.300ms="search()"
               @click="showResults = query.length > 2"
               @click.away="showResults = false"
               placeholder="{{ $placeholder }}"
               class="w-full rounded-md border-0 py-2 pl-4 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
            </svg>
        </div>
    </div>

    <!-- Dropdown Results -->
    <div x-show="showResults"
         class="absolute z-10 mt-1 w-full rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5">
        <div class="py-1">
            <template x-for="(items, category) in categories" :key="category">
                <div>
                    <div class="px-4 py-2 text-sm font-semibold text-gray-900 bg-gray-100" x-text="category"></div>
                    <template x-for="item in items" :key="item">
                        <a href="#"
                           @click.prevent="window.location.href = `/maintenance/suhu?search=${item}&category=${category.toLowerCase()}`"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                           x-text="item"></a>
                    </template>
                </div>
            </template>
        </div>
    </div>
</div> 