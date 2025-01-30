@props([
    'id' => null,
    'isHeader' => false,
    'deleteRoute' => '',
    'reloadRoute' => ''
])

<div x-data="checkList({
    id: '{{ $id }}',
    isHeader: {{ $isHeader ? 'true' : 'false' }},
    deleteRoute: '{{ $deleteRoute }}',
    reloadRoute: '{{ $reloadRoute }}'
})"
    class="flex items-center"
    x-init="
        $watch('showDeleteDialog', value => {
            console.log('Check-list Dialog State:', value);
            console.log('Selected Items:', selected);
        })
    ">
    
    <input type="checkbox" 
           x-show="isSelecting"
           :value="id"
           x-model="checked"
           @change="isHeader ? toggleAll($event) : toggleItem($event)"
           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">

    <div x-show="showDeleteDialog"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto">
        <x-elements.dialog-warning 
            message="Are you sure you want to delete selected data?"
            :confirm-action="'confirmDelete()'"
            :cancel-action="'showDeleteDialog = false'"
        />
    </div>

    <script>
        console.log('Check-list Component Initialized:', {
            id: '{{ $id }}',
            isHeader: {{ $isHeader ? 'true' : 'false' }},
            deleteRoute: '{{ $deleteRoute }}',
            reloadRoute: '{{ $reloadRoute }}'
        });
    </script>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('checkList', ({ id, isHeader, deleteRoute, reloadRoute }) => ({
        id: id,
        isHeader: isHeader,
        selected: [],
        checked: false,
        showDeleteDialog: false,
        isSelecting: false,

        init() {
            window.addEventListener('toggle-select-mode', (e) => {
                console.log('Toggle Select Mode:', e.detail);
                this.isSelecting = e.detail.isSelecting;
                if (!this.isSelecting) {
                    this.resetSelection();
                }
            });

            window.addEventListener('delete-selected', () => {
                console.log('Delete Selected Event Triggered');
                console.log('Selected Items:', this.selected);
                if (this.selected.length > 0) {
                    console.log('Showing Delete Dialog');
                    this.showDeleteDialog = true;
                }
            });
        },
        
        toggleAll(e) {
            const checkboxes = document.querySelectorAll('tbody input[type=checkbox]');
            this.selected = [];
            
            checkboxes.forEach(cb => {
                cb.checked = e.target.checked;
                if (e.target.checked) {
                    this.selected.push(cb.value);
                }
            });
        },
        
        toggleItem(e) {
            if (e.target.checked) {
                this.selected.push(e.target.value);
            } else {
                this.selected = this.selected.filter(id => id !== e.target.value);
            }
        },

        resetSelection() {
            this.selected = [];
            this.checked = false;
            const checkboxes = document.querySelectorAll('input[type=checkbox]');
            checkboxes.forEach(cb => {
                cb.checked = false;
            });
            this.showDeleteDialog = false;
        },
        
        async confirmDelete() {
            try {
                const response = await fetch(this.deleteRoute, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ ids: this.selected })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.location.href = this.reloadRoute + '?delete_success=true';
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Delete error:', error);
                window.location.href = this.reloadRoute + '?error=true';
            }
        }
    }));
});
</script>
@if(session('delete_success'))
    <x-pop-up-message type="success" message="Data has been successfully deleted" />
@endif

@if(session('error'))
    <x-pop-up-message type="error" message="Your actions did not complete successfully. Please try again." />
@endif
@endpush 