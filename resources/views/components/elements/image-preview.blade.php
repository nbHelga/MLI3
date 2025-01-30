@props(['src', 'deleteUrl' => null])

<div class="relative inline-block">
    <img src="{{ $src }}" 
         {{ $attributes->merge(['class' => 'rounded-lg max-w-xs']) }}>
    
    @if($deleteUrl)
        <button type="button"
                onclick="deleteImage('{{ $deleteUrl }}')"
                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    @endif
</div>

@push('scripts')
<script>
function deleteImage(url) {
    if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(response => {
            if (response.ok) {
                window.location.reload();
            }
        });
    }
}
</script>
@endpush 