<aside class="bg-white text-white w-16 min-h-screen fixed left-0 top-0 z-50">
    <nav class="flex flex-col items-center py-20 space-y-4" x-data="{ activeMenu: null }">
        <!-- HRIS -->
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
        </div>

        <!-- HRD -->
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

        <!-- Finance -->
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

        <!-- Warehouse -->
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
                class="absolute left-16 top-0 bg-white shadow-lg rounded-lg p-2 w-[600px] z-50"
            >
                <div class="flex space-x-4">
                    <!-- Barang -->
                    <div class="w-1/4">
                        <h3 class="font-bold text-gray-700 mb-2 text-center">Barang</h3>
                        <x-submenu.list-barang2 />
                        <x-submenu.list-barang />
                        <x-submenu.form-barang />
                    </div>
                    <!-- Garis Pemisah -->
                    <div class="w-px bg-gray-300"></div>
                    <!-- Perpindahan Barang Gudang -->
                    <div class="w-3/4">
                        <h3 class="font-bold text-gray-700 mb-2 text-center">Penempatan Ikan dan Kode Pallet</h3>
                        <div class="flex space-x-4">
                            <div class="w-1/3">
                                <h4 class="font-semibold text-gray-600 mb-1 text-center">CS01</h4>
                                <x-submenu.list-barang-gudang-cs01 />
                                <x-submenu.form-barang-gudang-cs01 />
                            </div>
                            <!-- CS02 -->
                            <div class="w-1/3">
                                <h4 class="font-semibold text-gray-600 mb-1 text-center">CS02</h4>
                                <x-submenu.list-barang-gudang-cs02 />
                                <x-submenu.form-barang-gudang-cs02 />
                            </div>
                            <!-- Masal -->
                            <div class="w-1/3">
                                <h4 class="font-semibold text-gray-600 mb-1 text-center">Masal</h4>
                                <x-submenu.list-barang-gudang-masal />
                                <x-submenu.form-barang-gudang-masal />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Maintenance -->
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
                class="absolute left-16 top-0 bg-white shadow-lg rounded-lg p-2 w-[500px] z-50"
            >
                <div class="flex space-x-4">
                    <!-- Suhu -->
                    <div class="w-1/2">
                        <h3 class="font-bold text-gray-700 mb-2 text-center">Suhu</h3>
                        {{-- <div class="flex space-x-4"> --}}
                            {{-- <div class="w-1/2">
                                <h4 class="font-semibold text-gray-600 mb-1">CS01</h4>
                                <x-submenu.list-suhu-cs01 />
                                <x-submenu.form-suhu-cs01 />
                            </div>
                            <div class="w-1/2">
                                <h4 class="font-semibold text-gray-600 mb-1">CS02</h4>
                                <x-submenu.list-suhu-cs02 />
                                <x-submenu.form-suhu-cs02 />
                            </div> --}}
                            <div>
                                <x-submenu.list-suhu-all />
                                <x-submenu.form-suhu-all />
                            </div>
                        {{-- </div> --}}
                    </div>
                    <!-- Garis Pemisah -->
                    <div class="w-px bg-gray-300"></div>
                    <!-- Maintenance -->
                    <div class="w-1/2">
                        <h3 class="font-bold text-gray-700 mb-2 text-center">Maintenance</h3>
                        <x-submenu.list-maintenance />
                        <x-submenu.form-maintenance />
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin -->
        <div class="relative">
            <a href="{{ route('admin.superadmin') }}" class="p-2 hover:bg-gray-700 rounded-lg block">
                <img src="{{ asset('admin-icon.png') }}" alt="Admin" class="w-8 h-8">
            </a>
        </div>

        <!-- Laporan -->
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
                    <div class = "w-1/2">
                        <x-submenu.laporan-barang />
                        <x-submenu.laporan-pallet />
                    </div>
                    <div class = "w-1/2">
                        <x-submenu.laporan-suhu />
                        <x-submenu.laporan-aset />
                        <x-submenu.laporan-maintenance />
                    </div>
                </div>
            </div>
        </div>

        <!-- Security -->
        <div class="relative" x-data="{ open: false }">
            <button 
                @click="activeMenu = (activeMenu === 'security' ? null : 'security'); open = !open"
                class="p-2 hover:bg-gray-700 rounded-lg"
                :class="{'bg-gray-700': activeMenu === 'security'}"
            >
                <img src="{{ asset('security-icon.png') }}" alt="Security" class="w-8 h-8">
            </button>
            <div 
                x-show="activeMenu === 'security'"
                class="absolute left-16 top-0 bg-white shadow-lg rounded-lg p-2 w-48 z-50"
            >
                <x-submenu.list-satpam />
                <x-submenu.form-satpam />
            </div>
        </div>
    </nav>
</aside>
