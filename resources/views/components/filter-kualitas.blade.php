@props(['filters'])

<div x-data="{ 
    selectedKualitas: '',
    addFilter() {
        if (!this.selectedKualitas) return;
        
        const displayText = this.selectedKualitas === 'all' ? 'Semua Kualitas' : this.selectedKualitas;
        const text = 'kualitas untuk ' + displayText;
        
        // Mengambil filter yang sudah ada
        let existingFilters = $store.filters || [];
        
        // Update store filters
        $store.filters = [...existingFilters, {
            type: 'kualitas',
            value: this.selectedKualitas,
            text: text
        }];
        
        // Dispatch event dengan semua filter yang ada
        $dispatch('filters-updated', { filters: $store.filters });
        
        // Reset selection
        this.selectedKualitas = '';
    }
}">
    <div class="flex gap-x-2">
        <select {{ $attributes->merge(['class' => 'inline-flex w-full justify-between gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-regular text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50']) }}
                x-model="selectedKualitas">
            <option value="" hidden>Pilih Kualitas</option>
            <option value="all">Semua Kualitas</option>
            @foreach($filters['kualitas'] as $kualitas)
                <option value="{{ $kualitas }}">{{ $kualitas }}</option>
            @endforeach
        </select>
        <button @click="addFilter()"
                :disabled="!selectedKualitas"
                class="inline-flex items-center p-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400">
            +
        </button>
    </div>
</div>