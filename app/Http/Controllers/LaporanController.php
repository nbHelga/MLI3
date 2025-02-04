<?php

namespace App\Http\Controllers;

use App\Models\PencatatanBarangGudang;
use App\Models\Suhu;
use App\Exports\PencatatanBarangGudangExport;
use App\Exports\PencatatanBarangGudangPdfExport;
use App\Exports\SuhuExport;
use App\Exports\SuhuPdfExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Tempat;
use App\Models\Barang;
use App\Exports\BarangPdfExport;
use App\Exports\BarangExport;

class LaporanController extends Controller
{
    protected $allRoomsMapping = [
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
        ]
    ];

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

    public function barang()
    {
        $filters = [
            'kualitas' => ['SP', 'KW', 'COP', 'CC', 'MT', 'PP', 'BS', 'DF', 'RUT'],
            'size' => \App\Models\Barang::select('size')->distinct()->pluck('size')->filter()
        ];

        return view('laporan.barang', compact('filters'));
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

            // Flag untuk mengecek apakah sudah join dengan tabel tempat
            $hasTempatJoin = false;

            // Terapkan pengurutan berdasarkan prioritas
            foreach ($sorts as $sort) {
                switch ($sort['value']) {
                    case 'tempat':
                    case 'ruangan':
                        if (!$hasTempatJoin) {
                            $query->leftJoin('tempat', 'pencatatan_barang_gudang.id_tempat', '=', 'tempat.id');
                            $hasTempatJoin = true;
                        }
                        if ($sort['value'] === 'tempat') {
                            $query->orderBy('tempat.nama');
                        } else {
                            $query->orderBy('tempat.ruangan');
                        }
                        break;
                    case 'pallet':
                        $query->orderBy('kode_pallet');
                        break;
                }
            }

            // Pastikan select hanya mengambil kolom dari tabel utama untuk menghindari duplikasi
            if ($hasTempatJoin) {
                $query->select('pencatatan_barang_gudang.*');
            }

            $data = $query->get();
            $fileName = date('Y-m-d') . '_Daftar_Pencatatan_Ikan_Dan_Kode_Pallet';

            if($request->format === 'excel') {
                return Excel::download(
                    new PencatatanBarangGudangExport($data, $filters, $sorts), 
                    $fileName . '.xlsx'
                );
            }
            else if ($request->format === 'pdf') {
                $export = new PencatatanBarangGudangPdfExport($data, $filters, $sorts);
                return $export->export();
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

            // Inisialisasi query dasar
            $query = Suhu::with(['tempat', 'employee']);

            // Kelompokkan filter berdasarkan tipe
            $groupedFilters = collect($filters)->groupBy('type');

            // Menyiapkan konfigurasi tempat yang akan ditampilkan
            $selectedTempatRuangan = [];
            $validTempatIds = [];
            $tempatOrder = ['CS01', 'CS02', 'MASAL'];

            // Filter berdasarkan tempat dan ruangan
            if ($groupedFilters->has('tempat-ruangan')) {
                foreach ($groupedFilters['tempat-ruangan'] as $filter) {
                    $tempat = strtoupper($filter['tempat']);
                    
                    if ($tempat !== 'all') {
                        if (!isset($selectedTempatRuangan[$tempat])) {
                            $selectedTempatRuangan[$tempat] = [];
                        }

                        $ruangan = $filter['ruangan'];
                        
                        if ($ruangan === 'all') {
                            if (isset($this->allRoomsMapping[$tempat])) {
                                $selectedTempatRuangan[$tempat] = array_keys($this->allRoomsMapping[$tempat]);
                                foreach ($this->allRoomsMapping[$tempat] as $roomConfig) {
                                    $validTempatIds[] = $roomConfig['id'];
                                }
                            }
                        } else {
                            if (isset($this->allRoomsMapping[$tempat][$ruangan])) {
                                $selectedTempatRuangan[$tempat][] = $ruangan;
                                $validTempatIds[] = $this->allRoomsMapping[$tempat][$ruangan]['id'];
                            }
                        }
                    }
                }
            }

            // Filter query berdasarkan tempat yang dipilih
            if (!empty($validTempatIds)) {
                $query->whereIn('id_tempat', $validTempatIds);
            }

            // Filter tanggal jika ada
            if ($groupedFilters->has('tanggal')) {
                $tanggalFilter = $groupedFilters['tanggal']->first();
                if ($tanggalFilter) {
                    // Periksa apakah tanggal dalam format value atau start/end
                    if (isset($tanggalFilter['value'])) {
                        $dateValue = is_string($tanggalFilter['value']) ? 
                                   json_decode($tanggalFilter['value'], true) : 
                                   $tanggalFilter['value'];
                        
                        if (isset($dateValue['start']) && isset($dateValue['end'])) {
                            $query->whereBetween('created_at', [
                                Carbon::parse($dateValue['start'])->startOfDay(),
                                Carbon::parse($dateValue['end'])->endOfDay()
                            ]);
                        } else {
                            // Jika hanya satu tanggal
                            $singleDate = Carbon::parse($dateValue);
                            $query->whereDate('created_at', $singleDate);
                        }
                    } else if (isset($tanggalFilter['start']) && isset($tanggalFilter['end'])) {
                        $query->whereBetween('created_at', [
                            Carbon::parse($tanggalFilter['start'])->startOfDay(),
                            Carbon::parse($tanggalFilter['end'])->endOfDay()
                        ]);
                    } else {
                        // Log untuk debugging
                        \Log::warning('Unexpected date filter format:', ['filter' => $tanggalFilter]);
                    }
                }
            }

            // Ambil data sesuai filter
            $data = $query->get();

            // Hitung posisi kolom untuk setiap tempat yang dipilih
            $tempatConfig = [];
            $currentStartCol = 'C';

            foreach ($tempatOrder as $tempat) {
                if (isset($selectedTempatRuangan[$tempat])) {
                    $ruangans = $selectedTempatRuangan[$tempat];
                    if (!empty($ruangans)) {
                        $roomConfig = [];
                        $colIndex = $this->getColumnNumber($currentStartCol);

                        foreach ($this->allRoomsMapping[$tempat] as $room => $config) {
                            if (in_array($room, $ruangans)) {
                                $roomConfig[$room] = [
                                    'id' => $config['id'],
                                    'cols' => [
                                        $this->getColumnId($colIndex),
                                        $this->getColumnId($colIndex + 4)
                                    ]
                                ];
                                $colIndex += 5;
                            }
                        }

                        if (!empty($roomConfig)) {
                            $tempatConfig[$tempat] = [
                                'startCol' => $currentStartCol,
                                'rooms' => $roomConfig
                            ];
                            $currentStartCol = $this->getColumnId($colIndex + 1);
                        }
                    }
                }
            }

            if($request->format === 'excel') {
                return (new SuhuExport($data, $filters, $sorts, $tempatConfig))->export();
            }
            else if ($request->format === 'pdf') {
                $export = new SuhuPdfExport($data, $filters, $sorts, $tempatConfig);
                return $export->export();
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

    public function exportBarang(Request $request)
    {
        try {
            $query = Barang::query();
            
            // Decode filters dari request
            $filters = json_decode($request->filters, true) ?? [];
            
            // Log filters yang diterima
            Log::info('Received filters:', ['filters' => $filters]);
            
            // Kelompokkan filter berdasarkan tipe
            $groupedFilters = collect($filters)->groupBy('type');
            
            Log::info('Grouped filters:', ['groupedFilters' => $groupedFilters->toArray()]);

            // Filter untuk kualitas dan size menggunakan OR condition
            $query = $query->where(function($q) use ($groupedFilters) {
                // Filter untuk kualitas
                if ($groupedFilters->has('kualitas')) {
                    $kualitasValues = $groupedFilters['kualitas']
                        ->pluck('value')
                        ->filter(fn($value) => $value !== 'all')
                        ->values()
                        ->all();

                    Log::info('Kualitas values:', ['values' => $kualitasValues]);

                    if (!empty($kualitasValues)) {
                        $q->orWhereIn('kualitas', $kualitasValues);
                    }
                }

                // Filter untuk size
                if ($groupedFilters->has('size')) {
                    $sizeValues = $groupedFilters['size']
                        ->pluck('value')
                        ->filter(fn($value) => $value !== 'all')
                        ->values()
                        ->all();

                    Log::info('Size values:', ['values' => $sizeValues]);

                    if (!empty($sizeValues)) {
                        $q->orWhereIn('size', $sizeValues);
                    }
                }
            });

            // Filter untuk tanggal (tetap menggunakan AND karena ini adalah range)
            if ($groupedFilters->has('tanggal')) {
                $tanggalFilter = $groupedFilters['tanggal']->first();
                if ($tanggalFilter && isset($tanggalFilter['value'])) {
                    $dateRange = $tanggalFilter['value'];
                    $query = $query->whereBetween('created_at', [
                        Carbon::parse($dateRange['start'])->startOfDay(),
                        Carbon::parse($dateRange['end'])->endOfDay()
                    ]);
                    
                    Log::info('Date range:', ['range' => $dateRange]);
                }
            }
            
            // Log the final SQL query
            Log::info('Final SQL:', [
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);
            
            $data = $query->get();
            
            // Log the result count
            Log::info('Query result count:', ['count' => $data->count()]);
            
            if ($request->format === 'excel') {
                // Pass data langsung ke BarangExport tanpa query lagi
                return Excel::download(
                    new BarangExport($data, $filters),
                    date('Y-m-d') . '_Laporan_Barang.xlsx'
                );
            }
            else if ($request->format === 'pdf') {
                try {
                    $export = new BarangPdfExport($data, $filters);
                    return $export->export();
                } catch (\Exception $e) {
                    Log::error('PDF Export Error:', [
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }
            }

        } catch (\Exception $e) {
            Log::error('Error in exportBarang:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengekspor data: ' . $e->getMessage()
            ], 500);
        }
    }
} 