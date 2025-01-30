@props(['options' => []])

<select {{ $attributes->merge(['class' => 'inline-flex w-full justify-between gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-regular text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50']) }}>
    <option value="" hidden>Pilih Pengurutan</option>
    <option value="tempat">Tempat</option>
    <option value="ruangan">Ruangan</option>
    @foreach($options as $value => $label)
        <option value="{{ $value }}">{{ $label }}</option>
    @endforeach
</select>