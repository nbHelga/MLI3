@props(['tempat', 'value' => null])

@php
$options = [];
if ($tempat === 'cs01') {
    $options = [
        'Room 1' => 'Room 1',
        'Room 2' => 'Room 2',
        'Room 3' => 'Room 3'
    ];
} else if ($tempat === 'cs02') {
    $options = [
        'Room 1' => 'Room 1',
        'Room 2' => 'Room 2',
        'Room 3' => 'Room 3',
        'Room 4' => 'Room 4'
    ];
} else if ($tempat === 'masal') {
    $options = [
        'Room 1' => 'Room 1'
    ];
}
@endphp

<x-elements.dropdown 
    id="ruangan"
    name="ruangan"
    :options="$options"
    :value="$value"
    buttonText="Pilih Ruangan"
    {{-- class="font-semibold" --}}
/> 