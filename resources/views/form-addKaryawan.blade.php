@extends('layouts.app')

@section('content')
<section class="bg-white dark:bg-gray-900">
    <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Add Employee</h2>
        <form action="#">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="sm:col-span-2">
                    <label for="nama_ktp" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama KTP</label>
                    <input type="string" name="nama_ktp" id="nama_ktp" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Ketik nama lengkap (sesuai KTP)" required="">
                </div>
                <div class="sm:col-span-2">
                    <label for="nama_panggil" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Panggilan</label>
                    <input type="string" name="nama_panggil" id="nama_panggil" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Ketik nama panggilan" required="">
                </div>
                <div>
                    <label for="gender" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gender</label>
                    <select id="gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected="">--Pilih--</option>
                        <option value="gender_1">Pria</option>
                        <option value="gender_2">Wanita</option>
                        
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label for="nik" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NIK</label>
                    <input type="string" name="nik" id="nik" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="isi sesuai dengan KTP (16 digit)" required="">
                </div>
                <div class="sm:col-span-2">
                    <label for="kk" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor KK</label>
                    <input type="string" name="kk" id="kk" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="isi sesuai dengan KK (16 digit)" required="">
                </div>
                <div class="w-full">
                    <label for="kode_perusahaan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode Perusahaan</label>
                    <input type="string" name="kode_perusahaan" id="kode_perusahaan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Isi inisial Perusahaan" required="">
                </div>
                <div class="w-full">
                    <label for="departemen" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Departemen</label>
                    <input type="string" name="departemen" id="departemen" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Isi nama departemen" required="">
                </div>
                <div class="w-full">
                    <label for="jabatan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Departemen</label>
                    <input type="string" name="jabatan" id="jabatan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Isi nama jabatan karyawan" required="">
                </div>
                <div class="w-full">
                    <label for="tempat_lahir" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tempat Lahir</label>
                    <input type="string" name="tempat_lahir" id="tempat_lahir" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Isi tempat lahir (sesuai KTP)" required="">
                </div>
                <div class="w-full">
                    <label for="tgl_lahir" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" id="tgl_lahir" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Isi tanggal lahir" required="">
                </div>
                <div class="w-full">
                    <label for="usia" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Usia</label>
                    <input type="number" name="usia" id="usia" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Isi dengan angka (tanpa koma)" required="">
                </div>
                <div class="sm:col-span-2">
                    <label for="alamat_ktp" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat KTP</label>
                    <input type="text" name="alamat_ktp" id="alamat_ktp" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="isi dengan alamat di KTP" required="">
                </div>
                <div class="sm:col-span-2">
                    <label for="alamat_skrg" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat Domisili</label>
                    <input type="text" name="alamat_skrg" id="alamat_skrg" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="isi dengan alamat tinggal saat ini" required="">
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
                    <label for="nama_sekolah" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Sekolah</label>
                    <input type="string" name="nama_sekolah" id="nama_sekolah" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="" required="">
                </div>
                <div class="w-full">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat email</label>
                    <input type="string" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="isi alamat email aktif" required="">
                </div>
                
                <div class="w-full">
                    <label for="email_company" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat email Perusahaan</label>
                    <input type="string" name="email_company" id="email_company" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="isi alamat email perusahaan(jika ada)" required="">
                </div>
                <div class="w-full">
                    <label for="no_hp" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor HP</label>
                    <input type="string" name="no_hp" id="no_hp" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="isi no.hp aktif" required="">
                </div>
                <div class="w-full">
                    <label for="no_hp_rekan_1" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor HP Darurat 1</label>
                    <input type="string" name="no_hp_rekan_1" id="no_hp_rekan_1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="isi no.hp darurat 1 aktif" required="">
                </div>
                <div class="sm:col-span-2">
                    <label for="no_hp_rekan_1_ket" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keterangan Kontak Darurat 1</label>
                    <input type="text" name="no_hp_rekan_1_ket" id="no_hp_rekan_1_ket" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="isi keterangan kontak 1" required="">
                </div>
                
                <div class="w-full">
                    <label for="no_hp_rekan_2" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor HP Darurat 2</label>
                    <input type="string" name="no_hp_rekan_2" id="no_hp_rekan_2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="isi no.hp darurat 2 aktif" required="">
                </div>
                <div class="sm:col-span-2">
                    <label for="no_hp_rekan_2_ket" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keterangan Kontak Darurat 2</label>
                    <input type="text" name="no_hp_rekan_2_ket" id="no_hp_rekan_2_ket" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="isi keterangan kontak 2" required="">
                </div>
                <div class="w-full">
                    <label for="tgl_masuk" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Masuk Kerja</label>
                    <input type="date" name="tgl_masuk" id="tgl_masuk" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Isi tanggal pertama masuk kerja" required="">
                </div>
            </div>
            <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                KOnfirmasi
            </button>
        </form>
    </div>
  </section>

@endsection