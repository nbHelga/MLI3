@props(['title'])

<div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8" x-data="{ 
    activeTab: 'filter',
    format: 'excel',
    filters: [],
    sorts: [],
    selectedTempat: [],
    
    addFilter(type, value) {
        if (!value) return;
        
        let filterText = '';
        let filterValue = value;
        
        switch(type) {
            case 'tanggal':
                filterText = `tanggal untuk ${value.start} s/d ${value.end}`;
                // Hapus filter tanggal yang ada sebelumnya
                this.filters = this.filters.filter(f => f.type !== 'tanggal');
                break;
            case 'tempat':
                if (value === 'all') {
                    this.selectedTempat = ['all'];
                    filterText = 'tempat untuk semua tempat';
                } else {
                    if (this.selectedTempat.includes('all')) {
                        this.selectedTempat = [];
                    }
                    if (!this.selectedTempat.includes(value)) {
                        this.selectedTempat.push(value);
                    }
                    filterText = `tempat untuk ${value.toUpperCase()}`;
                }
                break;
            case 'ruangan':
                const tempat = this.selectedTempat[this.selectedTempat.length - 1];
                filterText = `ruangan untuk ${tempat.toUpperCase()} - ${value}`;
                break;
            case 'pallet':
                filterText = `pallet untuk ${value}`;
                break;
        }
        
        if (filterText) {
            this.filters.push({ type, value: filterValue, text: filterText });
        }
        
        // Urutkan filters berdasarkan kelompok
        this.filters.sort((a, b) => {
            const order = ['tanggal', 'tempat', 'ruangan', 'pallet'];
            return order.indexOf(a.type) - order.indexOf(b.type);
        });
    },
    
    addSort(value) {
        if (!value || this.sorts.some(s => s.value === value)) return;
        
        const sortText = `Mengurutkan berdasarkan ${value}`;
        this.sorts.push({ value, text: sortText });
    },
    
    removeFilter(index) {
        const filter = this.filters[index];
        if (filter.type === 'tempat') {
            this.selectedTempat = this.selectedTempat.filter(t => t !== filter.value);
            // Reset ruangan jika tidak ada tempat yang dipilih
            if (this.selectedTempat.length === 0) {
                this.filters = this.filters.filter(f => f.type !== 'ruangan');
            }
        }
        this.filters.splice(index, 1);
    }
}"
@add-filter.window="
    const detail = $event.detail;
    if (detail.type === 'tempat-ruangan') {
        // Hapus filter tempat/ruangan yang sudah ada jika all
        if (detail.tempat === 'all' || detail.ruangan === 'all') {
            filters = filters.filter(f => f.type !== 'tempat-ruangan');
        }
        
        filters.push({
            type: 'tempat-ruangan',
            tempat: detail.tempat,
            ruangan: detail.ruangan,
            text: detail.text
        });
        
        // Urutkan filters
        filters.sort((a, b) => {
            const order = ['tanggal', 'tempat-ruangan', 'pallet'];
            return order.indexOf(a.type) - order.indexOf(b.type);
        });
    }
">
    <h1 class="text-2xl font-semibold text-gray-900 mb-6">{{ $title }}</h1>

    <!-- Tabs -->
    <div class="border-b border-gray-200 mb-6">
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
    <div x-show="activeTab === 'filter'" class="space-y-6">
        {{ $filter }}
    </div>

    <div x-show="activeTab === 'format'" class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-700">Format</label>
            <select x-model="format" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                <option value="excel">Excel</option>
                <option value="pdf">PDF</option>
            </select>
        </div>
    </div>

    <!-- Export Button -->
    <div class="mt-8">
        <form :action="'{{ $attributes->get('action') }}'" :method="'{{ $attributes->get('method') ?? 'POST' }}'" class="flex justify-end">
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