@props(['placeholder' => 'Search...', 'tempat'])

<div class="relative" x-data="{ 
    search: '{{ request('search', '') }}',
    suggestions: [],
    showSuggestions: false,
    loading: false,
    
    init() {
        this.$watch('search', value => {
            if (value && value.length > 0) {
                this.getSuggestions(value);
            } else {
                this.suggestions = [];
                this.showSuggestions = false;
            }
        });
    },

    async getSuggestions(query) {
        this.loading = true;
        try {
            const response = await fetch(`/warehouse/barangpallet/search?q=${query}&tempat={{ $tempat }}`);
            const data = await response.json();
            this.suggestions = data;
            this.showSuggestions = this.suggestions.length > 0;
        } catch (error) {
            console.error('Error fetching suggestions:', error);
            this.suggestions = [];
        } finally {
            this.loading = false;
        }
    },

    selectSuggestion(item) {
        this.search = item.kode_pallet;
        this.showSuggestions = false;
        
        // Preserve existing query parameters except 'search' and 'page'
        const currentUrl = new URL(window.location.href);
        const params = new URLSearchParams(currentUrl.search);
        params.delete('search');
        params.delete('page');
        params.append('search', item.kode_pallet);
        
        window.location.href = `${window.location.pathname}?${params.toString()}`;
    },

    submitSearch() {
        const currentUrl = new URL(window.location.href);
        const params = new URLSearchParams(currentUrl.search);
        
        // Update or remove search parameter
        if (this.search) {
            params.set('search', this.search);
        } else {
            params.delete('search');
        }
        
        // Reset page parameter
        params.delete('page');
        
        // Redirect to the same page with updated parameters
        window.location.href = `${window.location.pathname}?${params.toString()}`;
    }
}">
    <form @submit.prevent="submitSearch" method="GET">
        <!-- Preserve existing query parameters -->
        @foreach(request()->except(['search', 'page']) as $key => $value)
            @if(is_array($value))
                @foreach($value as $arrayValue)
                    <input type="hidden" name="{{ $key }}[]" value="{{ $arrayValue }}">
                @endforeach
            @else
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endforeach

        <div class="relative w-full">
            <div class="flex">
                <input
                    type="text"
                    name="search"
                    x-model="search"
                    @keydown.enter.prevent="submitSearch"
                    placeholder="{{ $placeholder }}"
                    class="w-full pl-4 pr-4 py-2 border rounded-l-md focus:outline-none focus:border-blue-500"
                >
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-r-md hover:bg-indigo-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </div>
            
            <!-- Suggestions Dropdown -->
            <div x-show="showSuggestions" 
                 @click.away="showSuggestions = false"
                 class="absolute z-50 w-full mt-1 bg-white border rounded-lg shadow-lg max-h-60 overflow-y-auto">
                <template x-for="item in suggestions" :key="item.id">
                    <div class="p-3 hover:bg-gray-100 cursor-pointer"
                         @click="selectSuggestion(item)">
                        <div class="font-medium" x-text="`${item.kode_pallet} - ${item.barang.nama}`"></div>
                        <div class="text-sm text-gray-600" 
                             x-text="`Kualitas: ${item.barang.kualitas}, Size: ${item.barang.size}`">
                        </div>
                    </div>
                </template>
            </div>
            <!-- Loading indicator -->
            <div x-show="loading" class="absolute right-8 top-2">
                <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>
    </form>
</div> 