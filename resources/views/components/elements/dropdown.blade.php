@props([
    'id',
    'name',
    'options' => [],
    'value' => '',
    'buttonText' => 'Select Option'
])

<div class="relative inline-block text-left w-full" x-data="{ open: false, selected: '{{ $value }}' }">
    <input type="hidden" name="{{ $name }}" :value="selected">
    
    <button type="button" 
            class="inline-flex w-full justify-between gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50" 
            @click.prevent="open = !open">
        <span x-text="selected ? ({{ json_encode($options) }}[selected] || '{{ $buttonText }}') : '{{ $buttonText }}'"></span>
        <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
        </svg>
    </button>

    <div x-show="open"
         @click.away="open = false"
         class="absolute z-10 mt-2 w-full rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95">
        <div class="py-1" role="none">
            @foreach($options as $optionValue => $label)
                <button type="button"
                        class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100"
                        @click.prevent="selected = '{{ $optionValue }}'; open = false">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>
</div>
  