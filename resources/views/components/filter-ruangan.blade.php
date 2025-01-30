@props(['tempat' => null])

<div x-data="{ 
    selectedTempat: '',
    value: '',
    options: [],
    usedTempats: [],
    
    async updateOptions() {
        if (!this.selectedTempat) {
            this.options = [];
            return;
        }
        
        try {
            const response = await fetch(`/api/ruangan/${this.selectedTempat}`);
            const data = await response.json();
            this.options = ['all', ...data];
        } catch (error) {
            console.error('Error fetching rooms:', error);
            this.options = [];
        }
    },
    
    addFilter() {
        if (!this.selectedTempat || !this.value) return;
        
        // Cek apakah tempat sudah digunakan
        if (this.usedTempats.includes(this.selectedTempat)) {
            return;
        }
        
        let filterText = '';
        if (this.selectedTempat === 'all') {
            filterText = 'tempat untuk semua tempat';
            this.usedTempats = ['all'];
        } else {
            if (this.value === 'all') {
                filterText = `tempat untuk ${this.selectedTempat.toUpperCase()}`;
                this.usedTempats.push(this.selectedTempat);
            } else {
                filterText = `tempat untuk ${this.selectedTempat.toUpperCase()} - ${this.value}`;
            }
        }
        
        $dispatch('add-filter', {
            type: 'tempat-ruangan',
            tempat: this.selectedTempat,
            ruangan: this.value,
            text: filterText
        });
        
        // Reset selection
        if (this.selectedTempat === 'all' || this.value === 'all') {
            this.value = '';
        }
    }
}" 
@tempat-changed.window="
    selectedTempat = $event.detail.value;
    value = '';
    updateOptions();
">
    <div class="flex gap-x-2">
        <select {{ $attributes->merge(['class' => 'inline-flex w-full justify-between gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-regular text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50']) }}
                x-model="value"
                :disabled="!selectedTempat || usedTempats.includes(selectedTempat)">
            <option value="" hidden>Pilih Ruangan</option>
            <template x-for="room in options" :key="room">
                <option :value="room" x-text="room === 'all' ? 'Semua Ruangan' : room"></option>
            </template>
        </select>
        <div class="mt-1">
            <button @click="addFilter()"
                    :disabled="!selectedTempat || !value || (selectedTempat === 'all' && usedTempats.includes('all'))"
                    class="inline-flex items-center p-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400">
                +
            </button>
        </div>
    </div>
</div> 