<div x-data="{
    startDate: '',
    endDate: '',
    formatDate(date) {
        if (!date) return '';
        return new Date(date).toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }
}" class="flex gap-x-2 items-center">
    <div class="flex gap-x-2 items-center">
        <div class="flex">
            <label for="start_date" class="pr-4 pt-1 block text-sm font-medium text-gray-500">Dari</label>
            <input type="date" 
                   x-model="startDate"
                   class="mt-1 block w-full rounded-md border-gray-500 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
        </div>
        <div class="flex">
            <label for="end_date" class="pr-4 pt-1 block text-sm font-medium text-gray-500">Sampai</label>
            <input type="date" 
                   x-model="endDate"
                   class="mt-1 block w-full rounded-md border-gray-500 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
        </div>
    </div>
    <button @click="addFilter('tanggal', {start: startDate, end: endDate})"
            :disabled="!startDate || !endDate"
            class="inline-flex items-center h-[38px] px-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 self-end">
        +
    </button>
</div> 