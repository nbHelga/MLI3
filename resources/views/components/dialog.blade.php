@props([
    'id',
    'title',
    'message' => '',
    'class' => 'bg-white',
    'titleClass' => 'text-gray-900'
])

<div id="{{ $id }}" 
     class="hidden relative z-10" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg {{ $class }} text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-base font-semibold leading-6 {{ $titleClass }}" id="modal-title">
                                {{ $title }}
                            </h3>
                            @if($message)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">{{ $message }}</p>
                                </div>
                            @endif
                            {{ $slot }}
                        </div>
                    </div>
                </div>
                @if(isset($actions))
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        {{ $actions }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
  