@props([
    'label' => '',
    'menuName' => '',
    'hasSubmenu' => false,
    'isChecked' => false,
    'submenus' => [],
    'menuId' => null
])

<div class="border p-4 rounded-lg">
    <div class="flex justify-between items-center">
        <span class="text-lg font-medium">{{ $label }}</span>
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" 
                   class="menu-toggle w-11 h-6 opacity-0 absolute cursor-pointer"
                   data-menu="{{ $menuName }}"
                   data-menu-id="{{ $menuId }}"
                   @if($hasSubmenu) data-has-submenu="true" @endif
                   {{ $isChecked ? 'checked' : '' }}>
            <div class="toggle-bg w-11 h-6 bg-gray-200 rounded-full 
                        after:content-[''] after:absolute after:top-0.5 after:left-0.5 
                        after:bg-white after:rounded-full after:h-5 after:w-5 
                        after:transition-all peer-checked:bg-blue-600 
                        peer-checked:after:translate-x-full">
            </div>
        </label>
    </div>

    @if($hasSubmenu)
        <div class="ml-8 space-y-3 submenu-container mt-4" style="display: none">
            @foreach($submenus as $submenu)
                <div class="flex justify-between items-center">
                    <span>{{ $submenu['label'] }}</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" 
                               class="submenu-toggle w-11 h-6 opacity-0 absolute cursor-pointer"
                               data-menu="{{ $menuName }}"
                               data-submenu="{{ $submenu['name'] }}"
                               data-menu-id="{{ $submenu['menuId'] }}"
                               {{ $submenu['isChecked'] ? 'checked' : '' }}>
                        <div class="toggle-bg w-11 h-6 bg-gray-200 rounded-full 
                                    after:content-[''] after:absolute after:top-0.5 after:left-0.5 
                                    after:bg-white after:rounded-full after:h-5 after:w-5 
                                    after:transition-all peer-checked:bg-blue-600 
                                    peer-checked:after:translate-x-full">
                        </div>
                    </label>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.toggle-bg:after {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

input:checked + .toggle-bg {
    background-color: #2563eb;
}

input:checked + .toggle-bg:after {
    transform: translateX(100%);
}
</style> 