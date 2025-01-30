@props(['palletCodes' => []])

<div x-data="{ 
    selectedPallet: '',
    init() {
        this.$watch('selectedPallet', value => {
            if (value) {
                this.$dispatch('pallet-changed', { value: value });
            }
        });
    }
}" class="flex gap-x-2 items-center w-full">
    <select x-model="selectedPallet"
            class="inline-flex w-full justify-between gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-regular text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
        <option value="" hidden>Pilih Pallet</option>
        <option value="all">Semua Pallet</option>
        @foreach(DB::table('pencatatan_barang_gudang')
                ->whereNotNull('kode_pallet')
                ->select(DB::raw('DISTINCT LEFT(kode_pallet, 1) as pallet_code'))
                ->orderBy('pallet_code')
                ->get() as $pallet)
            <option value="{{ $pallet->pallet_code }}">{{ $pallet->pallet_code }}</option>
        @endforeach
    </select>
    <button @click="addFilter('pallet', selectedPallet)"
            class="inline-flex items-center p-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
        +
    </button>
</div>