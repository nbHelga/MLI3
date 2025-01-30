@props(['value' => null])

<div x-data="{ 
    open: false,
    isCustom: false,
    customValue: '',
    selectedOption: '{{ $value }}',
    options: {
        'JUMBO': 'JUMBO',
        'BOM': 'BOM',
        'BESAR': 'BESAR',
        'TANGGUNG': 'TANGGUNG',
        'KECIL': 'KECIL',
        'MERCON': 'MERCON',
        'CUSTOM': 'CUSTOM',
        'KOIN': 'KOIN',
        'LEMBUT': 'LEMBUT',
        'RICEK': 'RICEK',
        '20 UP': '20 UP',
        '50 UP': '50 UP',
        'UYER': 'UYER',
        'RUT': 'RUT',
        'KASAR': 'KASAR',
        'UKURAN': 'UKURAN'
    }
}" class="relative">
    <input type="hidden" name="size" :value="isCustom ? customValue : selectedOption">
    
    <button @click="open = !open" 
            type="button"
            class="relative w-full cursor-default rounded-md bg-white py-1.5 pl-3 pr-10 text-left text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
        <span x-text="isCustom ? (customValue || 'CUSTOM') : (selectedOption || 'Pilih Size')"></span>
        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"/>
            </svg>
        </span>
    </button>

    <div x-show="open"
         @click.away="open = false"
         class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
        <template x-for="(label, value) in options" :key="value">
            <div @click="value === 'CUSTOM' ? (isCustom = true, open = false) : (selectedOption = value, isCustom = false, open = false)"
                 class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900 hover:bg-indigo-600 hover:text-white"
                 :class="{ 'bg-indigo-600 text-white': !isCustom && selectedOption === value }">
                <span x-text="label"></span>
            </div>
        </template>
    </div>

    <div x-show="isCustom" class="mt-2">
        <input type="text" 
               x-model="customValue"
               placeholder="Masukkan size custom (contoh: 16/20)"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>
</div>