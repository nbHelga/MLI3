@props(['route'])

<div x-data="{ 
    showModal: false,
    fileName: '',
    fileSize: '',
    fileImage: '',
    hasFile: false,
    dragOver: false,
    
    clearFile() {
        this.fileName = '';
        this.fileSize = '';
        this.fileImage = '';
        this.hasFile = false;
        document.getElementById('fileInput').value = '';
    },
    
    handleFile(event) {
        const file = event.target.files ? event.target.files[0] : event.dataTransfer.files[0];
        if (file) {
            this.fileName = file.name;
            this.fileSize = this.formatFileSize(file.size);
            this.fileImage = this.getFileIcon(file.name);
            this.hasFile = true;
            
            // Jika event dari drag & drop, perlu mengisi input file
            if (!event.target.files) {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                document.getElementById('fileInput').files = dataTransfer.files;
            }
        }
        this.dragOver = false;
    },
    
    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    },
    
    getFileIcon(filename) {
        const ext = filename.split('.').pop().toLowerCase();
        switch(ext) {
            case 'xlsx':
                return 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzODQgNTEyIj48IS0tIEZvbnQgQXdlc29tZSBQcm8gNi40LjAgYnkgQGZvbnRhd2Vzb21lIC0gaHR0cHM6Ly9mb250YXdlc29tZS5jb20gTGljZW5zZSAtIGh0dHBzOi8vZm9udGF3ZXNvbWUuY29tL2xpY2Vuc2UgKENvbW1lcmNpYWwgTGljZW5zZSkgQ29weXJpZ2h0IDIwMjMgRm9udGljb25zLCBJbmMuIC0tPjxwYXRoIGZpbGw9IiMyMTk2NTMiIGQ9Ik0zNjggMTI4aC0zNTJDNy4yIDEyOCAwIDEzNS4yIDAgMTQ0djIyNGMwIDguOCA3LjIgMTYgMTYgMTZoMzUyYzguOCAwIDE2LTcuMiAxNi0xNnYtMjI0YzAtOC44LTcuMi0xNi0xNi0xNnptLTE3Ni4xIDk2aDY0djMyaC02NHYtMzJ6bS04MC05NmgzMnY2NGgtMzJ2LTY0em0tODAgMGgzMnY2NGgtMzJ2LTY0em0xNjAgOTZoNjR2MzJoLTY0di0zMnptODAtOTZoMzJ2NjRoLTMydi02NHptLTI0MCAzMmgzMnYzMmgtMzJ2LTMyem0yNDAgMzJoMzJ2MzJoLTMydi0zMnptLTI0MCAzMmgzMnYzMmgtMzJ2LTMyem0yNDAgMzJoMzJ2MzJoLTMydi0zMnptLTI0MCAzMmgzMnYzMmgtMzJ2LTMyem0yNDAgMzJoMzJ2MzJoLTMydi0zMnoiLz48L3N2Zz4=';
            case 'xls':
                return 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzODQgNTEyIj48IS0tIEZvbnQgQXdlc29tZSBQcm8gNi40LjAgYnkgQGZvbnRhd2Vzb21lIC0gaHR0cHM6Ly9mb250YXdlc29tZS5jb20gTGljZW5zZSAtIGh0dHBzOi8vZm9udGF3ZXNvbWUuY29tL2xpY2Vuc2UgKENvbW1lcmNpYWwgTGljZW5zZSkgQ29weXJpZ2h0IDIwMjMgRm9udGljb25zLCBJbmMuIC0tPjxwYXRoIGZpbGw9IiMyMTk2NTMiIGQ9Ik0zNjggMTI4aC0zNTJDNy4yIDEyOCAwIDEzNS4yIDAgMTQ0djIyNGMwIDguOCA3LjIgMTYgMTYgMTZoMzUyYzguOCAwIDE2LTcuMiAxNi0xNnYtMjI0YzAtOC44LTcuMi0xNi0xNi0xNnptLTE3Ni4xIDk2aDY0djMyaC02NHYtMzJ6bS04MC05NmgzMnY2NGgtMzJ2LTY0em0tODAgMGgzMnY2NGgtMzJ2LTY0em0xNjAgOTZoNjR2MzJoLTY0di0zMnptODAtOTZoMzJ2NjRoLTMydi02NHptLTI0MCAzMmgzMnYzMmgtMzJ2LTMyem0yNDAgMzJoMzJ2MzJoLTMydi0zMnptLTI0MCAzMmgzMnYzMmgtMzJ2LTMyem0yNDAgMzJoMzJ2MzJoLTMydi0zMnptLTI0MCAzMmgzMnYzMmgtMzJ2LTMyem0yNDAgMzJoMzJ2MzJoLTMydi0zMnoiLz48L3N2Zz4=';
            case 'csv':
                return 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzODQgNTEyIj48IS0tIEZvbnQgQXdlc29tZSBQcm8gNi40LjAgYnkgQGZvbnRhd2Vzb21lIC0gaHR0cHM6Ly9mb250YXdlc29tZS5jb20gTGljZW5zZSAtIGh0dHBzOi8vZm9udGF3ZXNvbWUuY29tL2xpY2Vuc2UgKENvbW1lcmNpYWwgTGljZW5zZSkgQ29weXJpZ2h0IDIwMjMgRm9udGljb25zLCBJbmMuIC0tPjxwYXRoIGZpbGw9IiM2NjY2NjYiIGQ9Ik0yMjQgMTM2VjBINDhDMjEuNSAwIDAgMjEuNSAwIDQ4djQxNmMwIDI2LjUgMjEuNSA0OCA0OCA0OGgyODhjMjYuNSAwIDQ4LTIxLjUgNDgtNDhWMTYwSDI0OGMtMTMuMyAwLTI0LTEwLjctMjQtMjR6bTY1LjE4IDIxNi4yfC02LjE1IDUuNjZjLTEuMzkgMS4yOC0zLjQyIDEuNjktNS4yMiAxLjA3bC04LjAxLTIuNzljLTIuNzgtLjk3LTQuNjMtMy41NS00LjYzLTYuNDh2LTM1LjZjMC0zLjc3IDMuMDUtNi44MyA2LjgzLTYuODNoMzUuNmMyLjkzIDAgNS41MSAxLjg1IDYuNDggNC42M2wyLjc5IDguMDFjLjYyIDEuOC4yMSAzLjgzLTEuMDcgNS4yMmwtNS42NiA2LjE1IDEzLjg4IDEzLjg4YzEuNTYgMS41NiAxLjU2IDQuMDkgMCA1LjY2bC01LjY2IDUuNjZjLTEuNTYgMS41Ni00LjA5IDEuNTYtNS42NiAwbC0xMy44OC0xMy44OHoiLz48L3N2Zz4=';
            default:
                return 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzODQgNTEyIj48IS0tIEZvbnQgQXdlc29tZSBQcm8gNi40LjAgYnkgQGZvbnRhd2Vzb21lIC0gaHR0cHM6Ly9mb250YXdlc29tZS5jb20gTGljZW5zZSAtIGh0dHBzOi8vZm9udGF3ZXNvbWUuY29tL2xpY2Vuc2UgKENvbW1lcmNpYWwgTGljZW5zZSkgQ29weXJpZ2h0IDIwMjMgRm9udGljb25zLCBJbmMuIC0tPjxwYXRoIGZpbGw9IiM2NjY2NjYiIGQ9Ik0zNjkuOSA5Ny45TDI4NiAxNC42QzI3NyA1LjggMjY0LjggMSAyNTIuMS0uMUg0OEMyMS41IDAgMCAyMS41IDAgNDh2NDE2YzAgMjYuNSAyMS41IDQ4IDQ4IDQ4aDI4OGMyNi41IDAgNDgtMjEuNSA0OC00OFYxMzEuOWMwLTEyLjctNC44LTI1LTE0LjEtMzR6bS0yMi42IDIyLjdsLTU1LjYtNTUuNmMtNC44LTQuOC03LjYtMTEuMy03LjYtMTguMVY0OGgyOC45djE3LjNMMzY4IDEyMGgtMjAuN3oiLz48L3N2Zz4=';
        }
    }
}">
    <!-- Trigger Button -->
    <button @click="showModal = true" 
            type="button"
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
        Import Excel
    </button>

    <!-- Modal -->
    <div x-show="showModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="relative bg-white rounded-lg max-w-lg w-full">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Import Data</h3>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center"
                                 x-show="!hasFile"
                                 @dragover.prevent="dragOver = true"
                                 @dragleave.prevent="dragOver = false"
                                 @drop.prevent="handleFile($event)"
                                 :class="{ 'border-blue-500 bg-blue-50': dragOver }">
                                <input type="file" 
                                       id="fileInput"
                                       name="file"
                                       accept=".xlsx,.xls,.csv"
                                       class="hidden"
                                       @change="handleFile($event)">
                                <label for="fileInput" class="cursor-pointer">
                                    <span class="mt-2 block text-sm text-gray-600">
                                        Upload file atau tarik ke sini
                                    </span>
                                    <span class="mt-1 block text-xs text-gray-500">
                                        XLSX, XLS, CSV (max. 10MB)
                                    </span>
                                </label>
                            </div>

                            <!-- File Preview -->
                            <div x-show="hasFile" class="border rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <img :src="fileImage" class="w-8 h-8" alt="File icon">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900" x-text="fileName"></p>
                                            <p class="text-xs text-gray-500" x-text="fileSize"></p>
                                        </div>
                                    </div>
                                    <button type="button" 
                                            @click="clearFile"
                                            class="text-gray-400 hover:text-gray-500">
                                        <span class="sr-only">Remove file</span>
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3">
                                {{-- <button type="button"
                                        @click="showModal = false"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                    Cancel
                                </button> --}}
                                <button type="submit"
                                        :disabled="!hasFile"
                                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 disabled:opacity-50">
                                    Import
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 