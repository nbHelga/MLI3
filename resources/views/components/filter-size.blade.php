@props(['filters'])

<div x-data="{ 
    selectedSize: '',
    addFilter() {
        if (!this.selectedSize) return;
        
        const displayText = this.selectedSize === 'all' ? 'Semua Size' : this.selectedSize;
        const text = 'size untuk ' + displayText;
        
        // Mengambil filter yang sudah ada
        let existingFilters = $store.filters || [];
        
        // Update store filters
        $store.filters = [...existingFilters, {
            type: 'size',
            value: this.selectedSize,
            text: text
        }];
        
        // Dispatch event dengan semua filter yang ada
        $dispatch('filters-updated', { filters: $store.filters });
        
        // Reset selection
        this.selectedSize = '';
    }
}">
    <div class="flex gap-x-2">
        <select {{ $attributes->merge(['class' => 'inline-flex w-full justify-between gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-regular text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50']) }}
                x-model="selectedSize">
            <option value="" hidden>Pilih Size</option>
            <option value="all">Semua Size</option>
            @foreach($filters['size'] as $size)
                <option value="{{ $size }}">{{ $size }}</option>
            @endforeach
        </select>
        <button @click="addFilter()"
                :disabled="!selectedSize"
                class="inline-flex items-center p-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400">
            +
        </button>
    </div>
</div>