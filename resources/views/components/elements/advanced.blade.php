@props(['id', 'editRoute', 'deleteRoute'])

<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="text-gray-400 hover:text-gray-600">
        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
        </svg>
    </button>

    <div x-show="open" 
         @click.away="open = false"
         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
        <div class="py-1">
            <a href="{{ $editRoute }}" 
               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                Edit
            </a>
            <form action="{{ $deleteRoute }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-gray-100"
                        onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div> 