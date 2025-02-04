<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Suhu;
use App\Models\Tempat;

class SuhuSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan data tempat sudah ada
        // $cs01Rooms = Tempat::where('nama', 'CS01')->pluck('id');
        // $cs02Rooms = Tempat::where('nama', 'CS02')->pluck('id');
        // $masalRooms = Tempat::where('nama', 'Masal')->pluck('id');

        // Copy gambar dari folder resources ke storage
        // $this->copyDemoImages();

        Suhu::create([
            'id_tempat' => 2,
            'jam' => '08:00',
            'suhu' => -6,
            'keterangan' => 'suhu ruang aman',
            // 'gambar' => 'suhu/ruang1.png',
            'id_employees' => 'MLIIOF0001' // Pastikan employee ID valid
        ]);

        Suhu::create([
            'id_tempat' => 3,
            'jam' => '09:00',
            'suhu' => -6,
            'keterangan' => 'suhu ruang aman',
            // 'gambar' => 'suhu/ruang2.jpg',
            'id_employees' => 'MLIIOF0001' // Pastikan employee ID valid
        ]);
        Suhu::create([
            'id_tempat' => 4,
            'jam' => '10:00',
            'suhu' => -6,
            'keterangan' => 'suhu ruang aman',
            // 'gambar' => 'suhu/ruang3.png',
            'id_employees' => 'MLIIOF0001' // Pastikan employee ID valid
        ]);
        Suhu::create([
            'id_tempat' => 5,
            'jam' => '11:00',
            'suhu' => -6,
            'keterangan' => 'suhu ruang aman',
            // 'gambar' => 'suhu/ruang3.png',
            'id_employees' => 'MLIIOF0001' // Pastikan employee ID valid
        ]);
        Suhu::create([
            'id_tempat' => 6,
            'jam' => '12:00',
            'suhu' => -6,
            'keterangan' => 'suhu ruang aman',
            // 'gambar' => 'suhu/ruang3.png',
            'id_employees' => 'MLIIOF0001' // Pastikan employee ID valid
        ]);
        Suhu::create([
            'id_tempat' => 7,
            'jam' => '13:00',
            'suhu' => -6,
            'keterangan' => 'suhu ruang aman',
            // 'gambar' => 'suhu/ruang3.png',
            'id_employees' => 'MLIIOF0001' // Pastikan employee ID valid
        ]);
        Suhu::create([
            'id_tempat' => 8,
            'jam' => '14:00',
            'suhu' => -6,
            'keterangan' => 'suhu ruang aman',
            // 'gambar' => 'suhu/ruang3.png',
            'id_employees' => 'MLIIOF0001' // Pastikan employee ID valid
        ]);
    }

    // private function copyDemoImages()
    // {
    //     // Buat direktori jika belum ada
    //     if (!file_exists(storage_path('app/public/suhu'))) {
    //         mkdir(storage_path('app/public/suhu'), 0755, true);
    //     }

    //     // Copy demo images
    //     $demoImages = ['ruang1.png', 'ruang2.png', 'ruang3.png'];
    //     foreach ($demoImages as $image) {
    //         if (file_exists(resource_path('demo-images/' . $image))) {
    //             copy(
    //                 resource_path('demo-images/' . $image),
    //                 storage_path('app/public/suhu/' . $image)
    //             );
    //         }
    //     }
    // }
}
