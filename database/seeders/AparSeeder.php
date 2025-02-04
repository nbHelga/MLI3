<?php

namespace Database\Seeders;

use App\Models\Apar;
use App\Models\AssetApar;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AparSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //apar no.1
        AssetApar::create(attributes:[
            'kode'=>'P1',
            'lokasi'=>'POS SATPAM CS 02',
            'nama'=>'EUROTECT',
            'ukuran'=>'9KG',
            'tgl_exp'=>null,
            'tgl_produksi'=>'2025-01-25',
            'keterangan'=>'ISI PENUH SEGEL UTUH',
            'keterangan_refill'=>'DIISI ULANG PADA 25/01/2025',
            'biaya_refill'=> 5854000,

        ]);
        //appar no.2
        AssetApar::create(attributes:[
            'kode'=>'P2',
            'lokasi'=>'KANTOR SSI',
            'nama'=>'EUROTECT',
            'ukuran'=>'4,5KG',
            'tgl_exp'=>null,
            'tgl_produksi'=>'2022-10-03',
            'keterangan'=>'ISI PENUH SEGEL UTUH',
            'keterangan_refill'=>null,
            'biaya_refill'=>null

        ]);
        //apar no.3
        //3	P3		EUROTECT		Tulisan Exp. Hilang 		"ISI PENUH SEGEL TERBUKA"	
        AssetApar::create(attributes:[
            'kode'=>'P3',
            'lokasi'=>'R. PANEL CS 02',
            'nama'=>'EUROTECT',
            'ukuran'=>'6KG',
            'tgl_exp'=>null,
            'tgl_produksi'=>'2022-10-03',
            'keterangan'=>'ISI PENUH SEGEL TERBUKA',
            'keterangan_refill'=>null,
            'biaya_refill'=>null,
        ]);

        AssetApar::create(attributes:[
            'kode'=>'P4',
            'lokasi'=>'R. GENSET CS 02',
            'nama'=>'EUROTECT',
            'ukuran'=>'6KG',
            'tgl_exp'=>null,
            'tgl_produksi'=>'2022-01-05',
            'keterangan'=>'ISI PENUH SEGEL UTUH',
            'keterangan_refill'=>null,
            'biaya_refill'=>null,

        ]);
        // 5			EUROTECT	KG	Tulisan Exp. Hilang 		"ISI PENUH
// SEGEL UTUH"	

        AssetApar::create(attributes:[
            'kode'=>'P5',
            'lokasi'=>'R. CS 02 MESIN 1',
            'nama'=>'EUROTECT',
            'ukuran'=>'6KG',
            'tgl_exp'=>null,
            'tgl_produksi'=>'2022-01-02',
            'keterangan'=>'ISI PENUH SEGEL UTUH',
            'keterangan_refill'=>null,
            'biaya_refill'=>null

        ]);
        // 6			EUROTECT		Tulisan Exp. Hilang 	2022	"ISI PENUH
// SEGEL UTUH"	

        AssetApar::create(attributes:[
            'kode'=>'P6',
            'lokasi'=>'R. CS 02 MESIN 2',
            'nama'=>'EUROTECT',
            'ukuran'=>'6KG',
            'tgl_exp'=>null,
            'tgl_produksi'=>'2022-01-01',
            'keterangan'=>'ISI PENUH SEGEL UTUH',
            'keterangan_refill'=>null,
            'biaya_refill'=>null,

        ]);
        // 7	P7		EUROTECT	KG	Tulisan Exp. Hilang 	2022	"ISI PENUH
// SEGEL UTUH"	

        AssetApar::create(attributes:[
            'kode'=>'P7',
            'lokasi'=>'R. CS 02 MESIN 3',
            'nama'=>'EUROTECT',
            'ukuran'=>'6KG',
            'tgl_exp'=>null,
            'tgl_produksi'=>'2022-01-01',
            'keterangan'=>'ISI PENUH SEGEL UTUH',
            'keterangan_refill'=>null,
            'biaya_refill'=>null

        ]);
        // 8	
        AssetApar::create(attributes:[
                'kode'=>'P8',
                'lokasi'=>'CS 01',
                'nama'=>'EUROTECT',
                'ukuran'=>'6KG',
                'tgl_exp'=>null,
                'tgl_produksi'=>'2023-01-02',
                'keterangan'=>'ISI PENUH SEGEL TERBUKA',
                'keterangan_refill'=>null,
                'biaya_refill'=>null,
    
        ]);
        // 9	P9	R. PANEL CS 01 	EUROTECT	6KG	Tulisan Exp. Hilang 		"ISI PENUH
// SEGEL UTUH"	
        AssetApar::create(attributes:[
            'kode'=>'P9',
            'lokasi'=>'R. PANEL CS 01',
            'nama'=>'EUROTECT',
            'ukuran'=>'6KG',
            'tgl_exp'=>null,
            'tgl_produksi'=>'2023-01-02',
            'keterangan'=>'ISI PENUH SEGEL UTUH',
            'keterangan_refill'=>null,
            'biaya_refill'=>null,

        ]);
        //10

        AssetApar::create(attributes:[
            'kode'=>'P10',
            'lokasi'=>'R. CS 02 MESIN 3',
            'nama'=>'EUROTECT',
            'ukuran'=>'6KG',
            'tgl_exp'=>null,
            'tgl_produksi'=>'2022-01-01',
            'keterangan'=>'ISI PENUH SEGEL UTUH',
            'keterangan_refill'=>'DIISI ULANG PADA 25/01/2025',
            'biaya_refill'=> 3837000,

        ]);
        // 11	P11	R. 	EUROTECT	6KG	Tulisan Exp. Hilang 		""	
        AssetApar::create(attributes:[
            'kode'=>'P11',
            'lokasi'=>'R. POS SATPAM DEPAN',
            'nama'=>'EUROTECT',
            'ukuran'=>'6KG',
            'tgl_exp'=>null,
            'tgl_produksi'=>'2023-01-02',
            'keterangan'=>'ISI PENUH SEGEL HILANG',
            'keterangan_refill'=>null,
            'biaya_refill'=>null,

        ]);// 12	P12	K	EUROTECT	6KG	Tulisan Exp. Hilang 		"ISI PENUH
// SEGEL "	
        AssetApar::create(attributes:[
            'kode'=>'P12',
            'lokasi'=>'PABRIK PLASTIK',
            'nama'=>'EUROTECT',
            'ukuran'=>'6KG',
            'tgl_exp'=>null,
            'tgl_produksi'=>'2024-09-17',
            'keterangan'=>'ISI PENUH SEGEL UTUH',
            'keterangan_refill'=>'DIISI ULANG PADA 17/09/2024',
            'biaya_refill'=> 3837000,

        ]);// 13
        AssetApar::create(attributes:[
            'kode'=>'P13',
            'lokasi'=>'R. KANTOR MLINDO CS 01',
            'nama'=>'EUROTECT',
            'ukuran'=>'4,5KG',
            'tgl_exp'=>null,
            'tgl_produksi'=>'2024-06-10',
            'keterangan'=>'ISI PENUH SEGEL TERBUKA',
            'keterangan_refill'=>null,
            'biaya_refill'=>null,

        ]);// 14
        AssetApar::create(attributes:[
            'kode'=>'P14',
            'lokasi'=>'DAPUR',
            'nama'=>'EUROTECT',
            'ukuran'=>'6KG',
            'tgl_exp'=>null,
            'tgl_produksi'=>'2025-01-25',
            'keterangan'=>'ISI PENUH SEGEL UTUH',
            'keterangan_refill'=>'DIISI ULANG PADA 25/01/2025',
            'biaya_refill'=> 3837000,

        ]);// 15
        AssetApar::create(attributes:[
            'kode'=>'P15',
            'lokasi'=>'R. GENSET CS 01',
            'nama'=>'EUROTECT',
            'ukuran'=>'6KG',
            'tgl_exp'=>null,
            'tgl_produksi'=>'2025-01-25',
            'keterangan'=>'ISI PENUH SEGEL UTUH',
            'keterangan_refill'=>'DITUKAR PADA 25/01/2025',

        ]);// 16Tulisan Exp. Hilang 		"ISI PENUH
// SEGEL "	
        AssetApar::create(attributes:[
            'kode'=>'P16',
            'lokasi'=>'R. KANTOR MLINTER CS 02',
            'nama'=>'EUROTECT',
            'ukuran'=>'6KG', 
            'tgl_exp'=>null,
            'tgl_produksi'=>'2025-01-25',
            'keterangan'=>'ISI PENUH SEGEL UTUH',
            'keterangan_refill'=>'DIISI ULANG PADA 25/01/2025',
            'biaya_refill'=>3837000,

        ]);
        
    
        


    }
}