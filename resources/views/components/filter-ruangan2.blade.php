<div x-data="{ 
    selectedRuangan: '',
    options: [],
    async updateOptions(tempat) {
        if (!tempat) {
            this.options = [];
            return;
        }
        
        const rooms = tempat === 'cs01' 
            ? ['Room 1', 'Room 2', 'Room 3']
            : ['Room 1', 'Room 2', 'Room 3', 'Room 4'];
            
        this.options = rooms;
        this.selectedRuangan = '';
    }
}" 
x-init="$watch('selectedRuangan', value => $el.dispatchEvent(new CustomEvent('ruangan-changed', { detail: { value } })))"
@tempat-changed.window="updateOptions($event.detail.value)">
    <select x-model="selectedRuangan"
            {{ $attributes->merge(['class' => 'inline-flex w-full justify-between gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-regular text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50']) }}>
        <option value="" hidden>Pilih Ruangan</option>
        <template x-for="room in options" :key="room">
            <option :value="room" x-text="room"></option>
        </template>
    </select>
</div> 