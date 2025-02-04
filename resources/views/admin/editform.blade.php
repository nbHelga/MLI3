@extends('layouts.layout')

@section('title', 'Suhu - MLI Store')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6">Management Menu Access for {{ $user->employee->nama }}</h2>
        <h3 class="text-xl font-regular mb-2">ID : {{ $user->id }}</h3>
        <div class="flex items-center mb-6">
            <h3 class="text-xl font-regular text-gray-900">Status : </h3>
            @if($user->status)
                <h3 class="text-xl font-regular text-gray-900 ml-2">{{ $user->status }}</h3>
            @else
                <h3 class="text-xl font-regular text-gray-500 ml-2">Belum Dipilih</h3>
            @endif
        </div>
        
        <form id="menuAccessForm">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            
            <div class="space-y-4">
                @foreach($menus as $menu)
                    <div class="border p-4 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-medium">
                                @if($menu->submenu)
                                    {{ $menu->nama }} - {{ $menu->submenu }}
                                @else
                                    {{ $menu->nama }}
                                @endif
                            </span>
                            <div class="relative inline-flex items-center">
                                <input type="checkbox" 
                                       name="menu_access[]" 
                                       value="{{ $menu->id }}"
                                       class="menu-toggle sr-only"
                                       id="menu_{{ $menu->id }}"
                                       {{ $userMenus->contains('id_menu', $menu->id) ? 'checked' : '' }}>
                                <label for="menu_{{ $menu->id }}" 
                                       class="toggle-label flex items-center cursor-pointer">
                                    <div class="relative">
                                        <div class="block bg-gray-300 w-14 h-8 rounded-full transition"></div>
                                        <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-300 ease-in-out"></div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </form>
    </div>
</div>

@if(session('menu_access_granted'))
    <x-pop-up-message 
        type="success" 
        message="Menu access is successfully granted for {{ $user->employee->nama }}" />
@endif

@if(session('menu_access_revoked'))
    <x-pop-up-message 
        type="success" 
        message="Menu access is successfully revoked for {{ $user->employee->nama }}" />
@endif

@if(session('menu_access_error'))
    <x-pop-up-message 
        type="error" 
        message="Menu access is failed to grant for {{ $user->employee->nama }}. Please try again." />
@endif

<style>
.menu-toggle:checked + .toggle-label .dot {
    transform: translateX(100%);
}
.menu-toggle:checked + .toggle-label .block {
    background-color: #4F46E5;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('menuAccessForm');
    const toggles = document.querySelectorAll('.menu-toggle');
    
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const menuId = this.value;
            const userId = form.querySelector('[name="user_id"]').value;
            const isChecked = this.checked;
            
            // Submit form langsung untuk update menu access
            const formData = new FormData();
            formData.append('user_id', userId);
            formData.append('menu_id', menuId);
            formData.append('status', isChecked ? '1' : '0'); // Pastikan status boolean
            formData.append('_token', document.querySelector('input[name="_token"]').value);

            // Buat form temporary untuk submit
            const tempForm = document.createElement('form');
            tempForm.method = 'POST';
            tempForm.action = '/admin/update-menu-access';
            
            for(let pair of formData.entries()) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = pair[0];
                input.value = pair[1];
                tempForm.appendChild(input);
            }

            document.body.appendChild(tempForm);
            tempForm.submit();
        });
    });
});
</script>
@endsection