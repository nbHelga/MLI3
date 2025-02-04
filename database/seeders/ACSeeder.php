<?php

namespace Database\Seeders;

use App\Models\AssetAc;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ACSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //ac no.1
        AssetAc::create(attributes:[
            'nama'=>'AC',
            'status'=>'AKTIF',
            'kode'=>'AC-KANTOR-01',
            'lokasi'=>'KANTOR CS 01 - PT MLINDO',
            'divisi'=>'LIS',//LISTRIK / TERKNISI
            'pic'=>'GUN',
            'kategori'=>'ELEKTRONIK',
            'tipe'=>'PANASONIC',
            'kondisi'=>'NORMAL',
        ]);
        //ac no.2
        AssetAc::create(attributes:[
            'nama'=>'AC',
            'status'=>'AKTIF',
            'kode'=>'AC-KANTOR-02'	,
            'lokasi'=>'KANTOR CS 01 - PT MLINDO',
            'divisi'=>'LIS',
            'pic'=>'GUN',
            'kategori'=>'ELEKTRONIK',
            'tipe'=>'PANASONIC',
            'kondisi'=>'NORMAL', //normal/rusak
        ]);
        //ac no.3
        AssetAc::create(attributes:[

            'nama'=>'AC',
            'status'=>'AKTIF',
            'kode'=>'AC-KANTOR-03'	,
            'lokasi'=>'KANTOR CS 02 - PT MLINTER',
            'divisi'=>'LIS',
            'pic'=>'GUN',
            'kategori'=>'ELEKTRONIK',
            'tipe'=>'DAIKIN',
            'kondisi'=>'NORMAL',
        ]);
        //ac no.4
        AssetAc::create(attributes:[
            'nama'=>'AC',
            'status'=>'AKTIF',
            'kode'=>'AC-KANTOR-04'	,
            'lokasi'=>'LANTAI 2 CS 01 - RUANG MEETING TENGAH',
            'divisi'=>'LIS',
            'pic'=>'GUN',
            'kategori'=>'ELEKTRONIK',
            'tipe'=>'DAIKIN',
            'kondisi'=>'NORMAL',
        ]);
        //ac no.5
        AssetAc::create(attributes:[
            'nama'=>'AC',
            'status'=>'AKTIF',
            'kode'=>'AC-KANTOR-05'	,
            'lokasi'=>'LANTAI 2 CS 01 - RUANG MEETING TENGAH',
            'divisi'=>'LIS',
            'pic'=>'GUN',
            'kategori'=>'ELEKTRONIK',
            'tipe'=>'PANASONIC',
            'kondisi'=>'NORMAL',
        ]);
        //ac no.6
        AssetAc::create(attributes:[
            'nama'=>'AC',
            'status'=>'AKTIF',
            'kode'=>'AC-KANTOR-06'	,
            'lokasi'=>'LANTAI 2 CS 01 - RUANG MEETING DALAM',
            'divisi'=>'LIS',
            'pic'=>'GUN',
            'kategori'=>'ELEKTRONIK',
            'tipe'=>'GREE',
            'kondisi'=>'NORMAL',
        ]);
        //ac no.7
        AssetAc::create(attributes:[
            'nama'=>'AC',
            'status'=>'AKTIF',
            'kode'=>'AC-KANTOR-07'	,
            'lokasi'=>'LANTAI 2 CS 01 - RUANG KANTOR',
            'divisi'=>'LIS',
            'pic'=>'GUN',
            'kategori'=>'ELEKTRONIK',
            'tipe'=>'GREE',
            'kondisi'=>'NORMAL',

        ]);
        //ac no.8
        AssetAc::create(attributes:[
            'nama'=>'AC',
            'status'=>'AKTIF',
            'kode'=>'AC-KANTOR-08'	,
            'lokasi'=>'LANTAI 2 CS 01 - RUANG MESS',
            'divisi'=>'LIS',
            'pic'=>'GUN',
            'kategori'=>'ELEKTRONIK',
            'tipe'=>'GREE',
            'kondisi'=>'NORMAL',
        ]);
        //ac no.9
        AssetAc::create(attributes:[
            'nama'=>'AC',
            'status'=>'AKTIF',
            'kode'=>'AC-KANTOR-09', //bisa diganti, jgn jadi primary key
            'lokasi'=>'KANTOR BEKAS MASAL',
            'divisi'=>'LIS',
            'pic'=>'GUN',
            'kategori'=>'ELEKTRONIK',
            'tipe'=>'PANASONIC',
            'kondisi'=>'NORMAL',
        ]);
    }
}
