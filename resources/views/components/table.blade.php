@props([
    'headers' => [],
    'checkboxes' => true,
    'actions' => true,
    'maxRows' => 10
])

<div class="relative" 
     x-data="{ 
        selected: [],
        showDeleteAll: false,
        isFullSize: false,
        toggleAll(e) {
            const checked = e.detail.checked;
            this.selected = checked ? this.getAllIds() : [];
            this.showDeleteAll = checked;
        },
        getAllIds() {
            return Array.from(this.$el.querySelectorAll('tbody input[type=checkbox]')).map(cb => parseInt(cb.value));
        },
        toggleItem(id, checked) {
            if (checked) {
                this.selected.push(id);
            } else {
                this.selected = this.selected.filter(item => item !== id);
            }
            this.showDeleteAll = this.selected.length > 0;
        }
     }"
     @toggle-all.window="toggleAll">
    
    <div :class="{'max-h-[500px] overflow-y-auto': !isFullSize}" class="pb-9">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0">
                <tr>
                    @if($checkboxes)
                        <th scope="col" class="w-12 px-3 py-3">
                            <x-elements.check-list />
                        </th>
                    @endif
                    
                    @foreach($headers as $header)
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach

                    @if($actions)
                        <th scope="col" class="w-12 px-3 py-3">
                            <template x-if="showDeleteAll">
                                <button @click="$dispatch('open-dialog', {
                                            id: 'deleteAllDialog',
                                            action: '{{ route('warehouse.product.destroy-multiple') }}',
                                            method: 'DELETE',
                                            data: { ids: selected }
                                        })"
                                        class="text-red-600 hover:text-red-900">
                                    Delete
                                </button>
                            </template>
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                {{ $slot }}
            </tbody>
        </table>
    </div>

   


</div> 