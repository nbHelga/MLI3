@props(['categories', 'name' => 'filter'])

<div class="flex space-x-2 mt-4" 
     x-data="{ 
        category: '', 
        keyword: '',
        sortData() {
            if (!this.category) return;
            
            const params = new URLSearchParams(window.location.search);
            params.set('category', this.category);
            
            if (this.keyword) {
                params.set('keyword', this.keyword);
            } else {
                params.set('sort', this.category);
                params.delete('keyword');
            }
            
            window.location.href = `${window.location.pathname}?${params.toString()}`;
        }
     }">
    <div class="relative flex-2">
        <select x-model="category" 
                name="{{ $name }}_category"
                class="pl-4 w-full h-12 text-lg rounded-md border-gray-300 shadow-sm appearance-none">
            <option value="" disabled selected hidden>Pilih Kategori</option>
            @foreach($categories as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>
    
    <input type="text" 
           x-model="keyword"
           name="{{ $name }}_keyword"
           placeholder="Cari..."
           class="flex-1 pl-4 h-12 text-lg w-full rounded-md border-gray-300 shadow-sm">
    
    <button type="button" 
            @click="sortData()"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
        Filter
    </button>
</div> 