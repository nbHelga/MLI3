@props(['name', 'value' => null])

<div x-data="{ 
    files: null,
    filename: '{{ $value }}',
    dragOver: false,
    
    removeFile() {
        this.files = null;
        this.filename = null;
        $refs.fileInput.value = '';
    }
}" 
class="relative">
    <div x-show="!filename"
         @dragover.prevent="dragOver = true"
         @dragleave.prevent="dragOver = false"
         @drop.prevent="
            dragOver = false;
            files = $event.dataTransfer.files;
            filename = files[0].name;
         "
         :class="{ 'border-blue-500 bg-blue-50': dragOver }"
         class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
        <div class="space-y-1 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="flex text-sm text-gray-600">
                <label class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                    <span>Upload file</span>
                    <input type="file" 
                           name="{{ $name }}"
                           x-ref="fileInput"
                           @change="
                               files = $event.target.files;
                               filename = files[0].name;
                           "
                           class="sr-only">
                </label>
                <p class="pl-1">atau tarik file kesini</p>
            </div>
            <p class="text-xs text-gray-500">
                PDF, DOC, DOCX hingga 10MB
            </p>
        </div>
    </div>
    
    <!-- Preview file yang diupload -->
    <div x-show="filename" 
         class="mt-1 flex items-center justify-between p-4 border-2 border-gray-300 rounded-md">
        <div class="flex items-center">
            <svg class="h-8 w-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path>
            </svg>
            <span x-text="filename" class="ml-2 text-sm text-gray-500"></span>
        </div>
        <button type="button" 
                @click="removeFile()"
                class="text-sm text-red-600 hover:text-red-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div> 