@props([
    'type' => 'success',
    'message' => '',
    'errors' => []
])

<div id="popup-message" 
     class="fixed bottom-4 right-4 z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-4 max-w-sm">
        <div class="flex items-start space-x-4 mb-4">
            @if($type === 'success')
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="flex-grow">
                    <h3 class="font-semibold text-gray-900">Success!</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ $message }}</p>
                </div>
            @else
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div class="flex-grow">
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

        <!-- Progress Bar -->
        <div class="h-1 bg-gray-200 rounded-full mb-4">
            <div id="progress-bar" 
                 class="h-1 bg-gray-600 rounded-full transition-all duration-[3000ms] ease-linear"
                 style="width: 100%">
            </div>
        </div>

        <div class="flex justify-between items-end">
            <p class="text-xs text-gray-500">* Klik tombol OK untuk menutup pop-up</p>
            <button onclick="closePopup()" 
                    class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">
                OK
            </button>
        </div>
    </div>
</div>

<script>
let popupTimeout;

function showPopup() {
    const popup = document.getElementById('popup-message');
    const progressBar = document.getElementById('progress-bar');
    
    popup.classList.remove('hidden');
    progressBar.style.width = '100%';
    
    // Start progress bar animation
    setTimeout(() => {
        progressBar.style.width = '0%';
    }, 100);

    // Set timeout but don't auto-hide
    popupTimeout = setTimeout(() => {
        progressBar.style.width = '0%';
    }, 3000);
}

function closePopup() {
    const popup = document.getElementById('popup-message');
    popup.classList.add('hidden');
    clearTimeout(popupTimeout);
}

// Auto show if there's message or errors
document.addEventListener('DOMContentLoaded', () => {
    @if($message || !empty($errors))
        showPopup();
    @endif
});

// Close popup when navigating away
window.addEventListener('beforeunload', closePopup);
</script> 