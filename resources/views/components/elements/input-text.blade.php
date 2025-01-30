@props([
    'name',
    'placeholder' => '',
    'value' => '',
    'type' => 'text',
    'searchable' => false,
    'items' => []
])

<div x-data="{
    search: '{{ $value }}',
    items: {{ json_encode($items) }},
    showDropdown: false,
    filteredItems: [],
    init() {
        this.$watch('search', (value) => {
            if (value.length > 0) {
                this.filteredItems = this.items.filter(item => 
                    item.toLowerCase().includes(value.toLowerCase())
                ).slice(0, 5);
                this.showDropdown = true;
            } else {
                this.filteredItems = [];
                this.showDropdown = false;
            }
        });
    },
    selectItem(item) {
        this.search = item;
        this.showDropdown = false;
    }
}" class="relative">
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $name }}" 
        x-model.debounce.300ms="search"
        @focus="if(searchable && search.length > 0) showDropdown = true"
        @click.away="showDropdown = false"
        class="block w-full rounded-md bg-white px-3 py-2 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600"
        placeholder="{{ $placeholder }}"
        autocomplete="off"
    >
    
    {{-- <!-- Dropdown untuk searchable input -->
    @if($searchable)
        <div x-show="showDropdown" 
             x-transition
             class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg overflow-y-auto"
             style="max-height: 200px;">
            <template x-if="filteredItems.length > 0">
                <div>
                    <template x-for="item in filteredItems" :key="item">
                        <div @click="selectItem(item)"
                             class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer"
                             x-text="item">
                        </div>
                    </template>
                </div>
            </template>
            <div x-show="filteredItems.length === 0 && search.length > 0" 
                 class="px-4 py-2 text-sm text-gray-500">
                Tidak ada data yang cocok
            </div>
        </div>
    @endif --}}
</div> 