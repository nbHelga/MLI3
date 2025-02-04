@props(['surats' => null, 'showDana' => false])

@php
    // Pastikan $surats selalu berupa collection
    $surats = $surats ?? collect();
    if (!($surats instanceof \Illuminate\Support\Collection)) {
        $surats = collect($surats);
    }
@endphp

<div class="overflow-x-auto">
    @if($surats->isEmpty())
        <div class="text-center py-4 text-gray-500">
            Belum ada data pengajuan surat.
        </div>
    @else
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    @if(request()->routeIs('hrd.*') || request()->routeIs('finance.*'))
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Karyawan</th>
                    @endif
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Perihal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    @if($showDana)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dana</th>
                    @endif
                    @if(request()->routeIs('home'))
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    @endif
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($surats as $index => $surat)
                    <tr>
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        @if(request()->routeIs('hrd.*') || request()->routeIs('finance.*'))
                            <td class="px-6 py-4">{{ $surat->employees->nama }}</td>
                        @endif
                        <td class="px-6 py-4">{{ $surat->perihal }}</td>
                        <td class="px-6 py-4">
                            {{ $surat->created_at ? \Carbon\Carbon::parse($surat->created_at)->format('d M Y') : '-' }}
                        </td>
                        @if($showDana)
                            <td class="px-6 py-4">{{ $surat->dana ? 'Rp ' . number_format($surat->dana, 0, ',', '.') : '-' }}</td>
                        @endif
                        @if(request()->routeIs('home'))
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $surat->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucfirst($surat->status) }}
                                </span>
                            </td>
                        @endif
                        <td class="px-6 py-4">
                            @if(request()->routeIs('hris.*')|| request()->routeIs('home'))
                                <a href="{{ route('hris.detail', $surat->id) }}" 
                                   class="text-indigo-600 hover:text-indigo-900">Detail</a>
                            @elseif(request()->routeIs('finance.*'))
                                <a href="{{ route('finance.detail', $surat->id) }}" 
                                   class="text-indigo-600 hover:text-indigo-900">Detail</a>
                            @elseif(request()->routeIs('hrd.*'))
                                <a href="{{ route('hrd.detail', $surat->id) }}" 
                                   class="text-indigo-600 hover:text-indigo-900">Detail</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>