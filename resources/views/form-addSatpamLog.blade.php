@extends('layouts.app')

@section('content')
<section class="bg-white dark:bg-gray-900">
    <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Add Guest Log</h2>
        <form action="#">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="w-full">
                    <label for="tgl_masuk" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Tamu Masuk</label>
                    <input type="date" name="tgl_masuk" id="tgl_masuk" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Isi tanggal" required="">
                </div>
                <div class="sm:col-span-2">
                    <label for="nama_tamu" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Tamu</label>
                    <input type="string" name="nama_tamu" id="nama_tamu" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Ketik nama lengkap / panggilan" required="">
                </div>
                <div class="sm:col-span-2">
                    <label for="nama_panggil" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Panggilan</label>
                    <input type="string" name="nama_panggil" id="nama_panggil" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Ketik nama panggilan" required="">
                </div>
                
                <div class="sm:col-span-2">
                    <label for="bertemu_dgn" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">bertemu dengan:</label>
                    <input type="text" name="bertemu_dgn" id="bertemu_dgn" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Yang ingin ditemui tamu" required="">
                </div>
                <div class="sm:col-span-2">
                    <label for="keperluan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keperluan</label>
                    <input type="text" name="keperluan" id="keperluan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Alasan menemui" required="">
                </div>
                
                <div>
                    <label for="agama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Agama</label>
                    <select id="agama" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected="">--Pilih Agama--</option>
                        <option value="islam">Islam</option>
                        <option value="kristen">Kristen</option>
                        <option value="katolik">Katolik</option>
                        <option value="hindu">Hindu</option>
                        <option value="buddha">Buddha</option>
                    </select>
                </div>
                <div>
                    <label for="status_nikah" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status Pernikahan (sesuai KTP)</label>
                    <select id="status_nikah" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected="">--Pilih--</option>
                        <option value="blm_menikah">Belum Menikah</option>
                        <option value="menikah">Menikah</option>
                        <option value="cerai_hidup">Cerai Hidup</option>
                        <option value="cerai_mati">Cerai Mati</option>
                    </select>
                </div>
                <div>
                    <label for="kewarganegaraan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status Kewarganegaraan</label>
                    <select id="kewarganegaraan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected="">--Pilih--</option>
                        <option value="wni">WNI</option>
                        <option value="wna">WNA</option>
                    </select>
                </div>
                <div class="w-full">
                    <label for="pendidikan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">pendidikan terakhir</label>
                    <input type="string" name="pendidikan" id="pendidikan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="" required="">
                </div>
                
                <div class="w-full">
                    <label for="no_hp_rekan_1" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor HP Darurat 1</label>
                    <input type="string" name="no_hp_rekan_1" id="no_hp_rekan_1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="isi no.hp darurat 1 aktif" required="">
                </div>
                <div class="sm:col-span-2">
                    <label for="no_hp" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keterangan Kontak Darurat 1</label>
                    <input type="string" name="no_hp" id="no_hp" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="isi keterangan kontak 1" required="">
                </div>
                <div class="w-full">
                    <label for="jam_keluar" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam tamu keluar</label>
                    <input type="time" name="tgl_masuk" id="tgl_masuk" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Isi tanggal pertama masuk kerja" required="">
                </div>
            </div>
            <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                KOnfirmasi
            </button>
        </form>
    </div>
  </section>

=======
@extends('layouts.app')

@section('content')
<section class="bg-white dark:bg-gray-900">
    <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Add Guest Log</h2>
        <form action="#">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="w-full">
                    <label for="tgl_masuk" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Tamu Masuk</label>
                    <input type="date" name="tgl_masuk" id="tgl_masuk" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Isi tanggal" required="">
                </div>
                <div class="sm:col-span-2">
                    <label for="nama_tamu" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Tamu</label>
                    <input type="string" name="nama_tamu" id="nama_tamu" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Ketik nama lengkap / panggilan" required="">
                </div>
                <div class="sm:col-span-2">
                    <label for="nama_panggil" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Panggilan</label>
                    <input type="string" name="nama_panggil" id="nama_panggil" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Ketik nama panggilan" required="">
                </div>
                
                <div class="sm:col-span-2">
                    <label for="bertemu_dgn" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">bertemu dengan:</label>
                    <input type="text" name="bertemu_dgn" id="bertemu_dgn" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Yang ingin ditemui tamu" required="">
                </div>
                <div class="sm:col-span-2">
                    <label for="keperluan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keperluan</label>
                    <input type="text" name="keperluan" id="keperluan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Alasan menemui" required="">
                </div>
                
                <div>
                    <label for="agama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Agama</label>
                    <select id="agama" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected="">--Pilih Agama--</option>
                        <option value="islam">Islam</option>
                        <option value="kristen">Kristen</option>
                        <option value="katolik">Katolik</option>
                        <option value="hindu">Hindu</option>
                        <option value="buddha">Buddha</option>
                    </select>
                </div>
                <div>
                    <label for="status_nikah" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status Pernikahan (sesuai KTP)</label>
                    <select id="status_nikah" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected="">--Pilih--</option>
                        <option value="blm_menikah">Belum Menikah</option>
                        <option value="menikah">Menikah</option>
                        <option value="cerai_hidup">Cerai Hidup</option>
                        <option value="cerai_mati">Cerai Mati</option>
                    </select>
                </div>
                <div>
                    <label for="kewarganegaraan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status Kewarganegaraan</label>
                    <select id="kewarganegaraan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected="">--Pilih--</option>
                        <option value="wni">WNI</option>
                        <option value="wna">WNA</option>
                    </select>
                </div>
                <div class="w-full">
                    <label for="pendidikan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">pendidikan terakhir</label>
                    <input type="string" name="pendidikan" id="pendidikan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="" required="">
                </div>
                
                <div class="w-full">
                    <label for="no_hp_rekan_1" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor HP Darurat 1</label>
                    <input type="string" name="no_hp_rekan_1" id="no_hp_rekan_1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="isi no.hp darurat 1 aktif" required="">
                </div>
                <div class="sm:col-span-2">
                    <label for="no_hp" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keterangan Kontak Darurat 1</label>
                    <input type="string" name="no_hp" id="no_hp" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="isi keterangan kontak 1" required="">
                </div>
                <div class="w-full">
                    <label for="jam_keluar" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam tamu keluar</label>
                    <input type="time" name="tgl_masuk" id="tgl_masuk" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Isi tanggal pertama masuk kerja" required="">
                </div>
            </div>
            <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                KOnfirmasi
            </button>
        </form>
    </div>
  </section>
@endsection