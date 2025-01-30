@props([
    'placeholder' => 'Masukkan Kata Kunci, contoh: cekak',
    'isBarangPallet' => false
])

<div x-data="{ 
    query: '',
    recommendations: [],
    showDropdown: false,
    async search() {
        if (this.query.length < 2) {
            this.recommendations = [];
            return;
        }
        
        try {
            const endpoint = {{ $isBarangPallet ? "'/api/search-barangpallet'" : "'/api/search'" }};
            const response = await fetch(`${endpoint}?q=${encodeURIComponent(this.query)}`);
            if (!response.ok) throw new Error('Network response was not ok');
            const data = await response.json();
            this.recommendations = data;
            this.showDropdown = true;
        } catch (error) {
            console.error('Search error:', error);
            this.recommendations = [];
        }
    }
}" class="relative">
    <div class="flex">
        <input type="text"
               x-model="query"
               @input.debounce.300ms="search()"
               @keydown.enter="$dispatch('submit-search', { query: query })"
               @click.away="showDropdown = false"
               placeholder="{{ $placeholder }}"
               class="block w-full rounded-l-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
        <button @click="$dispatch('submit-search', { query: query })"
                class="px-4 py-2 bg-indigo-600 text-white rounded-r-md hover:bg-indigo-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </button>
    </div>

    <div x-show="showDropdown && recommendations.length > 0"
         class="absolute z-50 w-full mt-1 bg-white rounded-md shadow-lg max-h-60 overflow-y-auto">
        <template x-for="item in recommendations" :key="item.kode">
            <div @click="query = item.kode; showDropdown = false; $dispatch('submit-search', { query: item.kode })"
                 class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                <div class="font-medium" x-text="`${item.kode} - ${item.nama}`"></div>
                <div class="text-sm text-gray-600">
                    <span x-text="`Kualitas: ${item.kualitas || '-'}`"></span>
                    <span class="mx-2">|</span>
                    <span x-text="`Size: ${item.size || '-'}`"></span>
                </div>
            </div>
        </template>
    </div>
</div> 