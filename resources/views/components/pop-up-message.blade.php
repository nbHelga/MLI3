@props([
    'type' => 'success',
    'message' => '',
    'errors' => []
])

<div id="popup-message" 
     class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-4 flex items-start space-x-4 max-w-sm">
        @if($type === 'success')
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">Success!</h3>
                <p class="mt-1 text-sm text-gray-500">{{ $message }}</p>
            </div>
        @else
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">Oops!</h3>
                <div class="mt-1 text-sm text-gray-500">
                    @if($message)
                        <p>{{ $message }}</p>
                    @else
                        @foreach($errors as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function showPopup() {
    const popup = document.getElementById('popup-message');
    popup.classList.remove('hidden');
    setTimeout(() => {
        popup.classList.add('hidden');
    }, 3000);
}

// Auto show if there's message or errors
document.addEventListener('DOMContentLoaded', () => {
    @if($message || !empty($errors))
        showPopup();
    @endif
});
</script> 