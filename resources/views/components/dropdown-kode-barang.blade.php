@props(['selected' => null])

<div class="relative" x-data="{ 
    open: false,
    search: '',
    selected: '{{ $selected }}',
    suggestions: [],
    loading: false,

    init() {
        if (this.selected) {
            this.search = this.selected;
        }
        
        this.$watch('search', value => {
            if (value && value.length > 0) {
                this.getSuggestions(value);
            } else {
                this.suggestions = [];
                this.open = false;
            }
        });
    },

    async getSuggestions(query) {
        this.loading = true;
        try {
            const response = await fetch(`/warehouse/barangpallet/search-barang?q=${query}`);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const data = await response.json();
            console.log('Suggestions received:', data); // Debug log
            this.suggestions = Array.isArray(data) ? data : [];  // Memastikan suggestions adalah array
            this.open = this.suggestions.length > 0;
        } catch (error) {
            console.error('Error fetching suggestions:', error);
            this.suggestions = [];
        } finally {
            this.loading = false;
        }
    },

    selectItem(item) {
        this.selected = item.kode;  // Menggunakan kode sebagai nilai yang disimpan
        this.search = item.kode;
        this.open = false;
        // Trigger event untuk form validation jika diperlukan
        this.$dispatch('item-selected', item);
    }
}">
    @if(session('error'))
        <x-pop-up-message type="error" message="Your actions did not complete successfully. Please try again." />
    @endif

    <input type="hidden" name="id_barang" :value="selected">
    
    <div class="relative">
        <input type="text"
               x-model="search"
               @focus="open = true"
               @click="open = true"
               @keydown.escape="open = false"
               @keydown.tab="open = false"
               @keydown.enter.prevent="if (suggestions.length > 0) selectItem(suggestions[0])"
               class="block w-full rounded-md bg-white px-3 py-2 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600"
               placeholder="Cari kode barang...">

        <!-- Loading indicator -->
        <div x-show="loading" class="absolute inset-y-0 right-0 flex items-center pr-3">
            <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>

    <!-- Suggestions dropdown -->
    <div x-show="open && suggestions.length > 0"
         @click.away="open = false"
         class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95">
        
        <ul class="divide-y divide-gray-200">
            <template x-for="item in suggestions" :key="item.kode">
                <li class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white"
                    @click="selectItem(item)">
                    <div class="flex flex-col">
                        <div class="flex items-center">
                            <span x-text="item.kode" class="font-medium block truncate"></span>
                        </div>
                        {{-- <div class="text-sm text-gray-500" x-text="`${item.nama} - ${item.kualitas} - ${item.size}`"></div> --}}
                    </div>
                </li>
            </template>
        </ul>
    </div>
</div> 