@props([
    'name',
    'options' => [],
    'value' => '',
    'buttonText' => 'Select Option'
])

<div class="relative inline-block text-left w-full" x-data="{ open: false, selected: '{{ $value }}' }">
    <input type="hidden" name="{{ $name }}" :value="selected">
    
    <button type="button" 
            class="inline-flex w-full justify-between gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50" 
            @click.prevent="open = !open">
        <span x-text="selected || '{{ $buttonText }}'"></span>
        <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
        </svg>
    </button>

    <div x-show="open"
         @click.away="open = false"
         class="absolute z-10 mt-2 w-full rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
        <div class="py-1" role="none">
            @foreach($options as $option)
                <button type="button"
                        class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100"
                        @click.prevent="selected = '{{ $option }}'; open = false">
                    {{ ucfirst($option) }}
                </button>
            @endforeach
        </div>
    </div>
</div>
  