@php
    $user = Auth::user();
    $accessibleMenus = $user->getAccessibleMenus();
@endphp

<aside class="bg-white text-white w-16 min-h-screen fixed left-0 top-0 z-50">
    <nav class="flex flex-col items-center py-20 space-y-4" x-data="{ activeMenu: null }">
        {{-- <!-- Menu HRIS (Selalu tampil) --> Menu HRIS dipindah di home (tidak di sidebar)
        <div class="relative" x-data="{ open: false }">
            <button 
                @click="activeMenu = (activeMenu === 'hris' ? null : 'hris'); open = !open"
                class="p-2 hover:bg-gray-700 rounded-lg"
                :class="{'bg-gray-700': activeMenu === 'hris'}"
            >
                <img src="{{ asset('hris-icon.png') }}" alt="HRIS" class="w-8 h-8">
            </button>
            <div 
                x-show="activeMenu === 'hris'"
                class="absolute left-16 top-0 bg-white shadow-lg rounded-lg p-2 w-48 z-50"
            >
                <x-submenu.list-surat />
                <x-submenu.form-surat />
            </div>
        </div> --}}

        <!-- Menu HRD -->
        @if(in_array('HRD', $accessibleMenus))
        <div class="relative" x-data="{ open: false }">
            <button 
                @click="activeMenu = (activeMenu === 'hrd' ? null : 'hrd'); open = !open"
                class="p-2 hover:bg-gray-700 rounded-lg"
                :class="{'bg-gray-700': activeMenu === 'hrd'}"
            >
                <img src="{{ asset('hrd-icon.png') }}" alt="HRD" class="w-8 h-8">
            </button>
            <div 
                x-show="activeMenu === 'hrd'"
                class="absolute left-16 top-0 bg-white shadow-lg rounded-lg p-2 w-[600px] z-50"
            >
                <div class="flex space-x-4">
                    <!-- Data Karyawan -->
                    <div class="w-1/3">
                        <h3 class="font-bold text-gray-700 mb-2">Data Karyawan</h3>
                        <x-submenu.list-karyawan />
                        <x-submenu.form-karyawan />
                    </div>
                    <!-- Data Non Karyawan -->
                    <div class="w-1/3">
                        <h3 class="font-bold text-gray-700 mb-2">Data Non Karyawan</h3>
                        <x-submenu.list-nonkaryawan />
                        <x-submenu.form-nonkaryawan />
                    </div>
                    <!-- Daftar Pengajuan Surat -->
                    <div class="w-1/3">
                        <h3 class="font-bold text-gray-700 mb-2">Daftar Pengajuan Surat</h3>
                        <x-submenu.list-surat-hrd />
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Menu Finance -->
        @if(in_array('Finance', $accessibleMenus))
        <div class="relative" x-data="{ open: false }">
            <button 
                @click="activeMenu = (activeMenu === 'finance' ? null : 'finance'); open = !open"
                class="p-2 hover:bg-gray-700 rounded-lg"
                :class="{'bg-gray-700': activeMenu === 'finance'}"
            >
                <img src="{{ asset('finance-icon.png') }}" alt="Finance" class="w-8 h-8">
            </button>
            <div 
                x-show="activeMenu === 'finance'"
                class="absolute left-16 top-0 bg-white shadow-lg rounded-lg p-2 w-48 z-50"
            >
                <x-submenu.list-surat-fin />
            </div>
        </div>
        @endif

        <!-- Menu Warehouse -->
        @if(in_array('Warehouse', $accessibleMenus))
        <div class="relative" x-data="{ open: false }">
            <button 
                @click="activeMenu = (activeMenu === 'warehouse' ? null : 'warehouse'); open = !open"
                class="p-2 hover:bg-gray-700 rounded-lg"
                :class="{'bg-gray-700': activeMenu === 'warehouse'}"
            >
                <img src="{{ asset('warehouse-icon.png') }}" alt="Warehouse" class="w-8 h-8">
            </button>
            <div 
                x-show="activeMenu === 'warehouse'"
                class="absolute left-16 top-0 bg-white shadow-lg rounded-lg p-4 z-50"
                :class="{
                    'w-[300px]': hasOnlyOneAccess,
                    'w-[700px]': !hasOnlyOneAccess
                }"
            >
                <h3 class="font-bold text-gray-700 mb-4 text-center text-lg">Penempatan Ikan dan Kode Pallet</h3>
                <div class="flex" :class="{'space-x-6': !hasOnlyOneAccess}">
                    <!-- Barang Section -->
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-700 mb-3 text-center border-b-2 border-gray-200 pb-2">Barang</h3>
                        <div class="space-y-3 px-2">
                            @if($user->hasSubmenu('Warehouse', 'CS01'))
                                <x-submenu.list-barang2/>
                            @endif
                            @if($user->hasSubmenu('Warehouse', 'CS02'))
                                <x-submenu.list-barang/>
                            @endif
                            @if($user->status === 'Administrator' || $user->status === 'Super Admin')
                                <x-submenu.form-barang/>
                            @endif
                        </div>
                    </div>

                    <!-- Vertical Separator for CS01 -->
                    @if($user->hasSubmenu('Warehouse', 'CS01'))
                        <div class="w-px bg-gray-200 self-stretch mx-2"></div>
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-700 mb-3 text-center border-b-2 border-gray-200 pb-2">CS01</h3>
                            <div class="space-y-3 px-2">
                                <x-submenu.list-barang-gudang-cs01 />
                                @if($user->status === 'Administrator' || $user->status === 'Super Admin')
                                    <x-submenu.form-barang-gudang-cs01 />
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Vertical Separator for CS02 -->
                    @if($user->hasSubmenu('Warehouse', 'CS02'))
                        <div class="w-px bg-gray-200 self-stretch mx-2"></div>
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-700 mb-3 text-center border-b-2 border-gray-200 pb-2">CS02</h3>
                            <div class="space-y-3 px-2">
                                <x-submenu.list-barang-gudang-cs02 />
                                @if($user->status === 'Administrator' || $user->status === 'Super Admin')
                                    <x-submenu.form-barang-gudang-cs02 />
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

         <!-- Menu Suhu (terpisah) -->
         @if(in_array('Suhu', $accessibleMenus))
         <div class="relative" x-data="{ open: false }">
             <button 
                 @click="activeMenu = (activeMenu === 'suhu' ? null : 'suhu'); open = !open"
                 class="p-2 hover:bg-gray-700 rounded-lg"
                 :class="{'bg-gray-700': activeMenu === 'suhu'}"
             >
                 <img src="{{ asset('suhu-icon.png') }}" alt="Suhu" class="w-8 h-8">
             </button>
             <div 
                 x-show="activeMenu === 'suhu'"
                 class="absolute left-16 top-0 bg-white shadow-lg rounded-lg p-2 w-48 z-50"
             >
                 <div>
                     <x-submenu.list-suhu-all />
                     @if($user->status !== 'Operator')
                         <x-submenu.form-suhu-all />
                     @endif
                 </div>
             </div>
         </div>
         @endif

        <!-- Menu Maintenance (tanpa submenu suhu) -->
        @if(in_array('Maintenance', $accessibleMenus))
        <div class="relative" x-data="{ open: false }">
            <button 
                @click="activeMenu = (activeMenu === 'maintenance' ? null : 'maintenance'); open = !open"
                class="p-2 hover:bg-gray-700 rounded-lg"
                :class="{'bg-gray-700': activeMenu === 'maintenance'}"
            >
                <img src="{{ asset('maintenance-icon.png') }}" alt="Maintenance" class="w-8 h-8">
            </button>
            <div 
                x-show="activeMenu === 'maintenance'"
                class="absolute left-16 top-0 bg-white shadow-lg rounded-lg p-2 w-48 z-50"
            >
                <div class="space-y-4">
                    {{-- @if($user->hasSubmenu('Maintenance', 'Aset')) --}}
                        {{-- <h3 class="font-bold text-gray-700 mb-2 text-center">Maintenance</h3> --}}
                        <x-submenu.list-maintenance />
                        <x-submenu.form-maintenance />
                    {{-- @endif --}}
                </div>
            </div>
        </div>
        @endif

        <!-- Menu Security -->
        @if(in_array('Security', $accessibleMenus))
        <div class="relative" x-data="{ open: false }">
            <button 
                @click="activeMenu = (activeMenu === 'security' ? null : 'security'); open = !open"
                class="p-2 hover:bg-gray-700 rounded-lg"
                :class="{'bg-gray-700': activeMenu === 'security'}"
            >
                <img src="{{ asset('security.png') }}" alt="Security" class="w-8 h-8">
            </button>
            <div 
                x-show="activeMenu === 'security'"
                class="absolute left-16 top-0 bg-white shadow-lg rounded-lg p-2 w-[500px] z-50"
            >
                <div class="flex space-x-4">
                    <div class="w-1/2">
                        <x-submenu.list-satpam />
                    </div>
                    <div class="w-1/2">
                        <x-submenu.form-satpam />
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Menu Laporan dengan akses terbatas -->
        @if(in_array('Warehouse, Suhu, Maintenance', $accessibleMenus)||($user->status === 'Super Admin'))
        <div class="relative" x-data="{ open: false }">
            <button 
                @click="activeMenu = (activeMenu === 'laporan' ? null : 'laporan'); open = !open"
                class="p-2 hover:bg-gray-700 rounded-lg"
                :class="{'bg-gray-700': activeMenu === 'laporan'}"
            >
                <img src="{{ asset('laporan-icon.png') }}" alt="Laporan" class="w-8 h-8">
            </button>
            <div 
                x-show="activeMenu === 'laporan'"
                class="absolute left-16 top-0 bg-white shadow-lg rounded-lg p-2 w-[500px] z-50"
            >
                <div class="flex space-x-4">
                    <div class="w-1/2">
                        @if(in_array('Warehouse', $accessibleMenus))
                            {{-- Submenu laporan barang --}}
                            <x-submenu.laporan-barang />
                            {{-- Submenu laporan pallet --}}
                            <x-submenu.laporan-pallet />
                        @endif
                    </div>
                    <div class="w-1/2">
                        @if(in_array('Suhu', $accessibleMenus))
                            {{-- Submenu laporan suhu --}}
                            <x-submenu.laporan-suhu />
                        @endif
                        @if(in_array('Maintenance', $accessibleMenus))
                            {{-- Submenu laporan aset --}}
                            <x-submenu.laporan-aset />
                            {{-- Submenu laporan maintenance --}}
                            <x-submenu.laporan-maintenance />
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Menu Admin -->
        @if($user->status === 'Super Admin')
        <div class="relative">
            <a href="{{ route('admin.superadmin') }}" class="p-2 hover:bg-gray-700 rounded-lg block">
                <img src="{{ asset('admin-icon.png') }}" alt="Admin" class="w-8 h-8">
            </a>
        </div>
        @endif

    </nav>
</aside>
