<nav class="bg-white" x-data="{ isOpen: false }">
    <div class="max-w-full px-7">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center">
                <div class="shrink-0 pl-14">
                    <img class="w-30 h-10 object-cover" src="{{ asset('logomli.png') }}" alt="MLI">
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="{{ route('home') }}" class="rounded-lg px-3 py-2 text-sm font-bold text-black border-none w-20 h-auto">Home</a>
                    </div>
                </div>
            </div>
            {{-- Bagian kanan --}}
            <div class="hidden md:block">
                <div class="flex items-center space-x-4">
                    @auth
                        <span class="text-black">{{ Auth::user()->employee->nama ?? 'Guest' }}</span>
                        <a href="{{ route('profile.karyawan') }}">
                            <img class="h-8 w-8" src="{{ asset('profile-icon.png') }}" alt="Profile">
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="mr-2">
                            @csrf
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-500">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-2 rounded-md bg-green-400 text-sm font-medium text-white hover:bg-green-600">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>