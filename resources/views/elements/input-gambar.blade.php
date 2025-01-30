<div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
    <div class="space-y-1 text-center">
        <div class="flex flex-col items-center">
            <label for="gambar" class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                <span>Upload gambar</span>
                <input id="gambar" name="gambar" type="file" class="sr-only" 
                       accept="image/*" capture="environment">
            </label>
            <p class="text-xs text-gray-500">atau</p>
            <label for="gambar-camera" class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                <span>Ambil foto</span>
                <input id="gambar-camera" name="gambar" type="file" class="sr-only" 
                       accept="image/*" capture="camera">
            </label>
        </div>
        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
    </div>
</div>