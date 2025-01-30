@props(['type' => 'all'])

<select {{ $attributes->merge(['class' => 'inline-flex w-full justify-between gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-regular text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50']) }}
        @change="$dispatch('tempat-changed', { value: $event.target.value })">
    <option value="" hidden>Pilih Tempat</option>
    {{-- <option value="all">Semua Tempat</option> --}}
    <option value="cs01">CS01</option>
    <option value="cs02">CS02</option>
    <option value="masal">MASAL</option>
</select> 