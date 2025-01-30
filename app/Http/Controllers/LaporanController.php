<?php

namespace App\Http\Controllers;

use App\Models\PencatatanBarangGudang;
use App\Models\Suhu;
use App\Exports\PencatatanBarangGudangExport;
use App\Exports\SuhuExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function pallet()
    {
        // Mengambil kode pallet unik dengan cara yang sama seperti di BarangPalletController
        $palletCodes = PencatatanBarangGudang::select('kode_pallet')
            ->whereNotNull('kode_pallet')
            ->distinct()
            ->get()
            ->map(function ($item) {
                return [
                    'value' => substr($item->kode_pallet, 0, 1),
                    'label' => substr($item->kode_pallet, 0, 1)
                ];
            })
            ->unique('value')
            ->values()
            ->all();

        // Mengirim data ke view dengan format yang sama seperti BarangPalletController
        return view('laporan.pallet', compact('palletCodes'));
    }

    public function suhu()
    {
        return view('laporan.suhu');
    }

    public function exportPallet(Request $request)
    {
        try {
            $filters = json_decode($request->filters, true) ?? [];
            $sorts = json_decode($request->sorts, true) ?? [];

            $query = PencatatanBarangGudang::with(['barang', 'tempat', 'employee']);

            // Kelompokkan filter berdasarkan tipe
            $groupedFilters = collect($filters)->groupBy('type');

            foreach ($groupedFilters as $type => $typeFilters) {
                switch ($type) {
                    case 'tanggal':
                        $dateRange = is_string($typeFilters->last()['value']) ? 
                            json_decode($typeFilters->last()['value'], true) : 
                            $typeFilters->last()['value'];
                            
                        $query->whereBetween('created_at', [
                            $dateRange['start'] . ' 00:00:00',
                            $dateRange['end'] . ' 23:59:59'
                        ]);
                        break;

                    case 'tempat-ruangan':
                        $query->where(function($q) use ($typeFilters) {
                            foreach ($typeFilters as $filter) {
                                // Skip jika ini adalah filter 'all'
                                if ($filter['tempat'] === 'all' && $filter['ruangan'] === 'all') {
                                    continue;
                                }
                                
                                $q->orWhere(function($subQ) use ($filter) {
                                    $subQ->whereHas('tempat', function($tempatQ) use ($filter) {
                                        $tempatQ->where('nama', strtoupper($filter['tempat']));
                                        if ($filter['ruangan'] !== 'all') {
                                            $tempatQ->where('ruangan', $filter['ruangan']);
                                        }
                                    });
                                });
                            }
                        });
                        break;

                    case 'pallet':
                        $query->where(function($q) use ($typeFilters) {
                            foreach ($typeFilters as $filter) {
                                if ($filter['value'] !== 'all') {
                                    $q->orWhere('kode_pallet', 'like', $filter['value'] . '%');
                                }
                            }
                        });
                        break;
                }
            }

            // Terapkan pengurutan berdasarkan prioritas
            foreach ($sorts as $sort) {
                switch ($sort['value']) {
                    case 'tempat':
                        $query->orderBy('tempat.nama');
                        break;
                    case 'ruangan':
                        $query->orderBy('tempat.ruangan');
                        break;
                    case 'pallet':
                        $query->orderBy('kode_pallet');
                        break;
                }
            }

            $data = $query->get();
            $fileName = date('Y-m-d') . '_Daftar_Pencatatan_Ikan_Dan_Kode_Pallet';

            if($request->format === 'excel') {
                return Excel::download(new PencatatanBarangGudangExport($data), $fileName . '.xlsx');
            }

        } catch (\Exception $e) {
            Log::error('Error in exportPallet:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function exportSuhu(Request $request)
    {
        try {
            $filters = json_decode($request->filters, true) ?? [];
            $sorts = json_decode($request->sorts, true) ?? [];

            // Definisi mapping ruangan - sesuaikan dengan case di database
            $allRoomsMapping = [
                'CS01' => [
                    'Room 1' => ['id' => 1],
                    'Room 2' => ['id' => 2],
                    'Room 3' => ['id' => 3]
                ],
                'CS02' => [
                    'Room 1' => ['id' => 4],
                    'Room 2' => ['id' => 5],
                    'Room 3' => ['id' => 6],
                    'Room 4' => ['id' => 7]
                ],
                'MASAL' => [
                    'Room 1' => ['id' => 8]
                ],
                'Kantor' => [
                    '' => ['id' => 9]
                ]
            ];

            // Inisialisasi query dasar
            $query = Suhu::with(['tempat', 'employee']);

            // Kelompokkan filter berdasarkan tipe
            $groupedFilters = collect($filters)->groupBy('type');

            // Menyiapkan konfigurasi tempat yang akan ditampilkan
            $selectedTempatRuangan = [];
            $validTempatIds = [];
            $tempatOrder = ['CS01', 'CS02', 'MASAL']; // Urutan default tempat

            if ($groupedFilters->has('tempat-ruangan')) {
                foreach ($groupedFilters['tempat-ruangan'] as $filter) {
                    $tempat = strtoupper($filter['tempat']);
                    
                    // Jika tempat spesifik dipilih (bukan 'all')
                    if ($tempat !== 'all') {
                        if (!isset($selectedTempatRuangan[$tempat])) {
                            $selectedTempatRuangan[$tempat] = [];
                        }

                        $ruangan = $filter['ruangan'];
                        
                        if ($ruangan === 'all') {
                            if (isset($allRoomsMapping[$tempat])) {
                                $selectedTempatRuangan[$tempat] = array_keys($allRoomsMapping[$tempat]);
                                foreach ($allRoomsMapping[$tempat] as $roomConfig) {
                                    $validTempatIds[] = $roomConfig['id'];
                                }
                            }
                        } else {
                            if (isset($allRoomsMapping[$tempat][$ruangan])) {
                                $selectedTempatRuangan[$tempat][] = $ruangan;
                                $validTempatIds[] = $allRoomsMapping[$tempat][$ruangan]['id'];
                            }
                        }
                    }
                }
            }

            // Filter query berdasarkan tempat yang dipilih
            if (!empty($validTempatIds)) {
                $query->whereIn('id_tempat', $validTempatIds);
            }

            // Ambil data sesuai filter
            $data = $query->get();

            // Hitung posisi kolom untuk setiap tempat
            $tempatConfig = [];
            $currentStartCol = 'A';
            $selectedTempat = array_keys($selectedTempatRuangan);

            foreach ($tempatOrder as $tempat) {
                if (in_array($tempat, $selectedTempat)) {
                    $ruangans = $selectedTempatRuangan[$tempat];
                    if (!empty($ruangans)) {
                        $roomConfig = [];
                        $colIndex = $this->getColumnNumber($currentStartCol);

                        foreach ($allRoomsMapping[$tempat] as $room => $config) {
                            if (in_array($room, $ruangans)) {
                                $startCol = $this->getColumnId($colIndex);
                                $endCol = $this->getColumnId($colIndex + 4);
                                
                                $roomConfig[$room] = [
                                    'id' => $config['id'],
                                    'cols' => [$startCol, $endCol]
                                ];
                                
                                $colIndex += 5;
                            }
                        }

                        if (!empty($roomConfig)) {
                            $tempatConfig[$tempat] = [
                                'startCol' => $currentStartCol,
                                'rooms' => $roomConfig
                            ];
                            $currentStartCol = $this->getColumnId($colIndex + 1); // Tambah 1 untuk jarak
                        }
                    }
                }
            }

            \Log::info('Export configuration:', [
                'selected_tempat' => $selectedTempatRuangan,
                'tempat_config' => $tempatConfig,
                'data_count' => $data->count()
            ]);

            if($request->format === 'excel') {
                return (new SuhuExport($data, $filters, $sorts, $tempatConfig))->export();
            }

        } catch (\Exception $e) {
            \Log::error('Error in exportSuhu:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'filters' => $filters
            ]);
            throw $e;
        }
    }

    private function getColumnNumber($column) {
        $result = 0;
        $length = strlen($column);
        
        for ($i = 0; $i < $length; $i++) {
            $result = $result * 26 + (ord($column[$i]) - ord('A') + 1);
        }
        
        return $result;
    }

    private function getColumnId($index) {
        if ($index <= 0) return 'A';
        
        $columnId = '';
        while ($index > 0) {
            $modulo = ($index - 1) % 26;
            $columnId = chr(65 + $modulo) . $columnId;
            $index = floor(($index - $modulo) / 26);
        }
        
        return $columnId;
    }
} 