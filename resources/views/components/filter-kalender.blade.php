<div x-data="{ 
    selectedDate: '',
    formattedDate: '',
    updateFormattedDate() {
        if (!this.selectedDate) return '';
        const date = new Date(this.selectedDate);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        this.formattedDate = `${day}/${month}/${year}`;
        return {
            display: this.formattedDate,
            value: `${year}-${month}-${day}`
        };
    }
}" 
x-init="$watch('selectedDate', value => {
    if (value) {
        const dates = updateFormattedDate();
        $el.setAttribute('data-display', dates.display);
        $el.setAttribute('data-value', dates.value);
    }
})">
    <input type="date"
           x-model="selectedDate"
           {{ $attributes }}
           class="inline-flex w-full justify-between gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-regular text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
</div> 