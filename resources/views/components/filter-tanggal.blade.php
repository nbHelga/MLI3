@props(['route'])

<div x-data="{ 
    showDatePicker: false,
    selectedDate: '{{ request('tanggal', now()->format('Y-m-d')) }}',
    formatDate(date) {
        return new Date(date).toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    },
    updateData() {
        window.location.href = this.buildUrl();
    },
    buildUrl() {
        const currentUrl = new URL(window.location.href);
        const searchParams = new URLSearchParams(currentUrl.search);
        searchParams.set('tanggal', this.selectedDate);
        return `${currentUrl.pathname}?${searchParams.toString()}`;
    }
}" class="relative inline-block">
    <div class="relative">
        <button @click="showDatePicker = !showDatePicker"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span x-text="formatDate(selectedDate)"></span>
        </button>

        <!-- Datepicker dropdown -->
        <div x-show="showDatePicker"
             @click.away="showDatePicker = false"
             class="absolute left-0 mt-1 w-full bg-white rounded-lg shadow-lg p-4 z-50"
             style="min-width: 250px;">
            <div class="flatpickr">
                <input type="date"
                       x-model="selectedDate"
                       @change="updateData()"
                       class="form-input block w-full sm:text-sm border-gray-300 rounded-md">
            </div>
        </div>
    </div>
</div> 