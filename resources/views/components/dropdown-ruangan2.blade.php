@props(['tempat' => null, 'value' => null])

<div x-data="{ 
    open: false,
    tempat: '{{ $tempat }}',
    value: '{{ $value }}',
    options: [],
    async updateOptions() {
        if (!this.tempat) {
            this.options = [];
            return;
        }
        
        try {
            const response = await fetch(`/api/ruangan/${this.tempat}`);
            const data = await response.json();
            this.options = data;
        } catch (error) {
            console.error('Error fetching rooms:', error);
            this.options = [];
        }
    }
}" 
x-init="updateOptions()"
@tempat-changed.window="tempat = $event.detail.value; value = ''; updateOptions()">
    <div class="relative">
        <button type="button"
                @click="open = !open"
                class="relative w-full cursor-default rounded-md bg-white py-1.5 pl-3 pr-10 text-left text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm sm:leading-6"
                aria-haspopup="listbox"
                :aria-expanded="open">
            <span class="block truncate" x-text="value || 'Pilih Ruangan'"></span>
            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd" />
                </svg>
            </span>
        </button>

        <input type="hidden" name="ruangan" :value="value">

        <ul x-show="open"
            @click.away="open = false"
            class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
            tabindex="-1"
            role="listbox">
            <template x-for="room in options" :key="room">
                <li class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900 hover:bg-indigo-600 hover:text-white"
                    role="option"
                    @click="value = room; open = false">
                    <span class="block truncate" x-text="room"></span>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-4"
                          x-show="value === room">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </li>
            </template>
        </ul>
    </div>
</div>