@props(['name', 'label', 'rows', 'value', 'placeholder'])
<div>
    <label class="block text-gray-700 text-sm font-bold mb-2">
        {{ $label }}
    </label>
    <textarea name="{{ $name }}" id="{{ $name }}" rows="{{ $rows }}" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 border placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" placeholder="{{ $placeholder }}">
        {{ $value }}
    </textarea>
</div>


