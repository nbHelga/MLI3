<div class="container mx-auto px-4 py-8">
    @if(session('add_success'))
        <x-pop-up-message type="success" message="Data berhasil ditambahkan" />
    @endif

    @if(session('error'))
    <x-pop-up-message type="error" message="Your actions did not complete successfully. Please try again." />
@endif

    <form action="{{ isset($barangPallet) ? route('warehouse.barangpallet.update', $barangPallet->id) : route('warehouse.barangpallet.store') }}" 
          method="POST" 
          class="space-y-4">
        @csrf
        @if(isset($barangPallet))
            @method('PUT')
        @endif
        
        {{-- Error Message untuk Update - Pindahkan ke bagian paling atas form --}}
        @if(session('error_message'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error_message') }}</span>
            </div>
        @endif

        <input type="hidden" name="tempat" value="{{ $tempat }}">

        <!-- Ruangan -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Ruangan</label>
            <x-dropdown-ruangan 
                :tempat="$tempat"
                :value="old('ruangan', isset($barangPallet) ? $barangPallet->tempat->ruangan : '')"
            />
            @error('ruangan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Kode Pallet -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Kode Pallet</label>
            <x-elements.input-text 
                name="kode_pallet"
                placeholder="Masukkan kode pallet"
                :value="old('kode_pallet', isset($barangPallet) ? $barangPallet->kode_pallet : '')"
                :searchable="true"
            />
            @error('kode_pallet')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Kode Barang -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Kode Barang</label>
            <x-dropdown-kode-barang 
                :kodeBarang="$kodeBarang"
                :selected="old('id_barang', isset($barangPallet) ? $barangPallet->id_barang : '')"
            />
            @error('id_barang')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('warehouse.barangpallet-' . strtolower($tempat) . '-list') }}"
               class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                {{ isset($barangPallet) ? 'Update' : 'Simpan' }}
            </button>
        </div>
    </form>
</div>

@if(!isset($barangPallet))
    <!-- Success Dialog -->
    <x-dialog id="successDialog" title="Success!">
        <x-slot name="message">Data berhasil ditambahkan</x-slot>
        <x-slot name="actions">
            <button onclick="window.location.href='{{ route('warehouse.barangpallet-' . $tempat) }}'"
                    class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Back
            </button>
            <button onclick="window.location.href='{{ route('warehouse.barangpallet-' . $tempat . '-list') }}'"
                    class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Continue
            </button>
        </x-slot>
    </x-dialog>

    @if(session('showDialog'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const dialog = document.getElementById('successDialog');
                dialog.classList.remove('hidden');
            });
        </script>
    @endif
@endif
