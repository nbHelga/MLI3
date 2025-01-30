@props(['activeParentTab' => 'all', 'activeChildTab' => 'all'])

@php
// Ambil data tempat dari database (hanya CS01 dan CS02)
$tempat = \App\Models\Tempat::select('nama')
    ->whereIn('nama', ['CS01', 'CS02', 'MASAL'])
    ->distinct()
    ->get()
    ->pluck('nama')
    ->map(fn($nama) => strtolower($nama));

// Siapkan parent tabs (All + Tempat)
$parentTabs = collect(['all' => 'All'])
    ->merge($tempat->mapWithKeys(fn($nama) => [$nama => strtoupper($nama)]));

// Siapkan child tabs berdasarkan parent yang aktif
$childTabs = collect(['all' => 'All']);
if ($activeParentTab !== 'all') {
    $ruangan = \App\Models\Tempat::where('nama', strtoupper($activeParentTab))
        ->whereNotNull('ruangan')
        ->select('ruangan')
        ->distinct()
        ->get()
        ->pluck('ruangan');
    
    $childTabs = $childTabs->merge($ruangan->mapWithKeys(fn($ruangan) => [$ruangan => $ruangan]));
}
@endphp

<div class="border-b border-gray-200">
    <!-- Parent Tabs -->
    <nav class="-mb-px flex space-x-8">
        @foreach($parentTabs as $value => $label)
            <a href="{{ route('maintenance.suhu.list', ['tempat' => $value]) }}"
               class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm
                      {{ $activeParentTab === $value
                         ? 'border-indigo-500 text-indigo-600'
                         : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                {{ $label }}
            </a>
        @endforeach
    </nav>

    <!-- Child Tabs (hanya tampil jika parent tab bukan 'all') -->
    @if($activeParentTab !== 'all' && $activeParentTab !== 'masal')
        <nav class="mt-4 -mb-px flex space-x-8">
            @foreach($childTabs as $value => $label)
                <a href="{{ route('maintenance.suhu.list', [
                        'tempat' => $activeParentTab,
                        'ruangan' => $value
                    ]) }}"
                   class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm
                          {{ $activeChildTab === $value
                             ? 'border-indigo-500 text-indigo-600'
                             : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    {{ $label }}
                </a>
            @endforeach
        </nav>
    @endif
</div> 