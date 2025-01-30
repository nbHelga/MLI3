@props(['rooms' => [], 'currentRoom' => 'all', 'tempat' => null])

{{-- <div class="bg-white rounded-lg shadow mb-4"> --}}
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-4 px-4" aria-label="Tabs">
            @foreach($rooms as $room)
                @php
                    $isActive = strtolower($currentRoom) === strtolower($room);
                    $roomText = strtolower($room) === 'all' ? 'Semua' : $room;
                    $routeName = "warehouse.barangpallet-{$tempat}-list";
                    
                    // Format room parameter dengan benar
                    $roomParam = str_replace(' ', '-', strtolower($room));
                @endphp
                
                <a href="{{ strtolower($room) === 'all' 
                    ? route($routeName) 
                    : route($routeName . '.room', ['room' => $roomParam]) }}"
                   class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm
                          {{ $isActive 
                             ? 'border-blue-500 text-blue-600' 
                             : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' 
                          }}">
                    {{ $roomText }}
                </a>
            @endforeach
        </nav>
    </div>
{{-- </div> --}}