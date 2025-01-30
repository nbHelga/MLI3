@props([
    'name',
    'placeholder' => '',
    'value' => '',
    'type' => 'text'
])

<div>
    <input type="{{ $type }}" 
           name="{{ $name }}" 
           id="{{ $name }}"
           value="{{ $value }}"
           placeholder="{{ $placeholder }}"
           class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
</div> 