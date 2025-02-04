<?php

namespace App\Exports;

use App\Models\Suhu;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SuhuExport
{
    protected $data;
    protected $filters;
    protected $sorts;
    protected $tempatConfig;

    public function __construct($data, $filters = [], $sorts = [], $selectedConfig = [])
    {
        $this->data = $data;
        $this->filters = $filters;
        $this->sorts = $sorts;
        // Hanya gunakan konfigurasi tempat yang dipilih
        $this->tempatConfig = $selectedConfig;
    }

    protected function getQuery()
    {
        $query = Suhu::with(['tempat', 'employee']);
        
        // Terapkan filter
        foreach ($this->filters as $filter) {
            switch ($filter['type']) {
                case 'tanggal':
                    $query->whereDate('created_at', $filter['value']);
                    break;
                case 'tempat':
                    $query->whereHas('tempat', function($q) use ($filter) {
                        $q->where('nama', strtoupper($filter['value']));
                    });
                    break;
                case 'ruangan':
                    $query->whereHas('tempat', function($q) use ($filter) {
                        $q->where('ruangan', $filter['value']);
                    });
                    break;
            }
        }
        
        // Terapkan pengurutan
        foreach ($this->sorts as $sort) {
            switch ($sort['value']) {
                case 'tanggal':
                    $query->orderBy('created_at');
                    break;
                case 'tempat':
                    $query->orderBy('id_tempat');
                    break;
                case 'ruangan':
                    $query->orderByHas('tempat', function($q) {
                        $q->orderBy('ruangan');
                    });
                    break;
            }
        }
        
        return $query;
    }

    protected function getStyles()
    {
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ];

        return [
            'header' => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D3D3D3']
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ]
                ]
            ],
            'roomHeader' => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'B8CCE4']
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ]
                ]
            ],
            'columnHeader' => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'DCE6F1']
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ]
                ]
            ],
            'data' => [
                'alignment' => [
                    'wrapText' => true
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ]
                ]
            ],
            'columnWidths' => [
                'tanggal' => 15, // Lebar untuk kolom tanggal
                'pukul' => 12,   // Lebar untuk kolom pukul
                'suhu' => 8,     // Lebar untuk kolom suhu
                'keterangan' => 20, // Lebar untuk kolom keterangan
                'pic' => 15      // Lebar untuk kolom PIC
            ]
        ];
    }

    private function getColumnNumber($column) 
    {
        $result = 0;
        $length = strlen($column);
        
        for ($i = 0; $i < $length; $i++) {
            $result = $result * 26 + (ord($column[$i]) - ord('A') + 1);
        }
        
        return $result;
    }

    protected function getColumnId($index) 
    {
        if ($index <= 0) return 'A';
        
        $columnId = '';
        while ($index > 0) {
            $modulo = ($index - 1) % 26;
            $columnId = chr(65 + $modulo) . $columnId;
            $index = floor(($index - $modulo) / 26);
        }
        
        return $columnId;
    }

    protected function calculateLastColumn($startCol, $roomCount) 
    {
        // Konversi kolom awal ke angka
        $startColNum = 0;
        $len = strlen($startCol);
        for ($i = 0; $i < $len; $i++) {
            $startColNum = $startColNum * 26 + (ord($startCol[$i]) - ord('A') + 1);
        }
        
        // Hitung total kolom yang dibutuhkan (5 kolom per ruangan)
        $totalColumns = $startColNum + ($roomCount * 5) - 1;
        
        // Konversi kembali ke format kolom Excel
        return $this->getColumnId($totalColumns);
    }

    protected function getColumnRange($start, $end) 
    {
        $columns = [];
        $current = $start;
        
        while ($current !== $end) {
            $columns[] = $current;
            
            // Increment kolom
            if (strlen($current) === 1) {
                if ($current === 'Z') {
                    $current = 'AA';
                } else {
                    $current = chr(ord($current) + 1);
                }
            } else {
                $lastChar = substr($current, -1);
                if ($lastChar === 'Z') {
                    $firstChar = substr($current, 0, 1);
                    $current = chr(ord($firstChar) + 1) . 'A';
                } else {
                    $current = substr($current, 0, -1) . chr(ord($lastChar) + 1);
                }
            }
        }
        $columns[] = $end; // Tambahkan kolom terakhir
        
        return $columns;
    }

    protected function getMaxDataRows($data, $ruangans)
    {
        $maxRows = 0;
        foreach ($ruangans as $room => $config) {
            $roomData = $data->filter(function($item) use ($config) {
                return $item->id_tempat === $config['id'];
            });
            $maxRows = max($maxRows, $roomData->count());
        }
        return $maxRows;
    }

    protected function getTable($sheet, $tempat, $ruangans, $startCol, $data, $styles, $startRow)
    {
        $colIndex = $this->getColumnNumber($startCol);
        $totalColumns = 0;
        
        // Hitung total kolom hanya untuk ruangan yang dipilih
        foreach ($ruangans as $room => $config) {
            $totalColumns += 5; // 5 kolom per ruangan
        }
        
        $lastCol = $this->getColumnId($colIndex + $totalColumns - 1);
        $currentRow = $startRow;
        
        // Parent (Tempat header)
        $sheet->mergeCells("{$startCol}{$currentRow}:{$lastCol}{$currentRow}");
        $sheet->setCellValue("{$startCol}{$currentRow}", $tempat);
        $sheet->getStyle("{$startCol}{$currentRow}:{$lastCol}{$currentRow}")
              ->applyFromArray($styles['header']);
        
        $currentRow++;
        $maxDataRows = $this->getMaxDataRows($data, $ruangans);
        $initialRow = $currentRow;
        
        // Child (Room headers) - Semua header ruangan dalam satu baris
        foreach ($ruangans as $room => $config) {
            $roomStartCol = $this->getColumnId($colIndex);
            $roomEndCol = $this->getColumnId($colIndex + 4);
            
            // Room header
            $sheet->mergeCells("{$roomStartCol}{$currentRow}:{$roomEndCol}{$currentRow}");
            $sheet->setCellValue("{$roomStartCol}{$currentRow}", $room);
            $sheet->getStyle("{$roomStartCol}{$currentRow}:{$roomEndCol}{$currentRow}")
                  ->applyFromArray($styles['roomHeader']);
            
            $colIndex += 5;
        }
        
        $currentRow++;
        $colIndex = $this->getColumnNumber($startCol);
        
        // Sub Child (Column headers) - Semua header kolom dalam satu baris
        foreach ($ruangans as $room => $config) {
            $headers = ['Tanggal', 'Pukul', 'Suhu', 'Keterangan', 'PIC'];
            for ($i = 0; $i < 5; $i++) {
                $col = $this->getColumnId($colIndex + $i);
                $sheet->setCellValue("{$col}{$currentRow}", $headers[$i]);
                $sheet->getStyle("{$col}{$currentRow}")
                      ->applyFromArray($styles['columnHeader']);
            }
            $colIndex += 5;
        }
        
        $currentRow++;
        $dataStartRow = $currentRow;
        
        // Content (Data)
        foreach ($ruangans as $room => $config) {
            $roomStartCol = $this->getColumnId($this->getColumnNumber($startCol) + 
                           (array_search($room, array_keys($ruangans)) * 5));
            $roomEndCol = $this->getColumnId($this->getColumnNumber($roomStartCol) + 4);
            
            $roomData = $data->filter(function($item) use ($config) {
                return $item->id_tempat === $config['id'];
            });
            
            $currentRowForRoom = $dataStartRow;
            foreach ($roomData as $item) {
                $colOffset = 0;
                $sheet->setCellValue($this->getColumnId($this->getColumnNumber($roomStartCol) + $colOffset++) . 
                    $currentRowForRoom, date('d M Y', strtotime($item->created_at)));
                $sheet->setCellValue($this->getColumnId($this->getColumnNumber($roomStartCol) + $colOffset++) . 
                    $currentRowForRoom, $item->jam);
                $sheet->setCellValue($this->getColumnId($this->getColumnNumber($roomStartCol) + $colOffset++) . 
                    $currentRowForRoom, $item->suhu);
                $sheet->setCellValue($this->getColumnId($this->getColumnNumber($roomStartCol) + $colOffset++) . 
                    $currentRowForRoom, $item->keterangan);
                $sheet->setCellValue($this->getColumnId($this->getColumnNumber($roomStartCol) + $colOffset) . 
                    $currentRowForRoom, $item->employee->nama);
                
                $sheet->getStyle("{$roomStartCol}{$currentRowForRoom}:{$roomEndCol}{$currentRowForRoom}")
                      ->applyFromArray($styles['data']);
                
                $currentRowForRoom++;
            }
        }
        
        // Tambahkan border untuk seluruh tabel
        $lastDataRow = $dataStartRow + $maxDataRows - 1;
        $sheet->getStyle("{$startCol}{$initialRow}:{$lastCol}{$lastDataRow}")
              ->applyFromArray([
                  'borders' => [
                      'allBorders' => [
                          'borderStyle' => Border::BORDER_THIN,
                          'color' => ['rgb' => '000000'],
                      ],
                  ],
              ]);
        
        // Setelah menambahkan data, atur lebar kolom
        foreach ($ruangans as $room => $config) {
            $roomStartCol = $this->getColumnId($this->getColumnNumber($startCol) + 
                           (array_search($room, array_keys($ruangans)) * 5));
            
            // Atur lebar untuk setiap kolom dalam ruangan
            $sheet->getColumnDimension($this->getColumnId($this->getColumnNumber($roomStartCol)))
                  ->setWidth($styles['columnWidths']['tanggal']);
            $sheet->getColumnDimension($this->getColumnId($this->getColumnNumber($roomStartCol) + 1))
                  ->setWidth($styles['columnWidths']['pukul']);
            $sheet->getColumnDimension($this->getColumnId($this->getColumnNumber($roomStartCol) + 2))
                  ->setWidth($styles['columnWidths']['suhu']);
            $sheet->getColumnDimension($this->getColumnId($this->getColumnNumber($roomStartCol) + 3))
                  ->setWidth($styles['columnWidths']['keterangan']);
            $sheet->getColumnDimension($this->getColumnId($this->getColumnNumber($roomStartCol) + 4))
                  ->setWidth($styles['columnWidths']['pic']);
        }
        
        return [
            'nextStartCol' => $this->getColumnId($this->getColumnNumber($lastCol) + 2),
            'lastRow' => $lastDataRow
        ];
    }

    protected function getHeader($sheet, $styles)
    {
        $totalColumns = $this->getCountTab();
        $startCol = 'C';
        
        // Hitung posisi tengah untuk header (6 kolom)
        $headerWidth = 6;
        $startColNumber = $this->getColumnNumber($startCol);
        $centerPosition = floor(($totalColumns - $headerWidth) / 2);
        $startHeaderCol = $this->getColumnId($startColNumber + $centerPosition);
        $endHeaderCol = $this->getColumnId($this->getColumnNumber($startHeaderCol) + ($headerWidth - 1));
        
        // Header baris 1 - PT MONSTER LAUT INDONESIA
        $sheet->mergeCells("{$startHeaderCol}2:{$endHeaderCol}2");
        $sheet->setCellValue("{$startHeaderCol}2", "PT MONSTER LAUT INDONESIA");
        $sheet->getStyle("{$startHeaderCol}2")->applyFromArray([
            'font' => ['size' => 14, 'bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        
        // Header baris 2 - LAPORAN PENCATATAN SUHU
        $sheet->mergeCells("{$startHeaderCol}3:{$endHeaderCol}3");
        $sheet->setCellValue("{$startHeaderCol}3", "LAPORAN PENCATATAN SUHU");
        $sheet->getStyle("{$startHeaderCol}3")->applyFromArray([
            'font' => ['size' => 18, 'bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        
        // Header baris 3 - Tanggal (4 kolom)
        $dateCols = 4;
        $startDateCol = $this->getColumnId($this->getColumnNumber($startHeaderCol) + 1);
        $endDateCol = $this->getColumnId($this->getColumnNumber($startDateCol) + ($dateCols - 1));
        $sheet->mergeCells("{$startDateCol}4:{$endDateCol}4");
        
        // Format tanggal dengan penanganan yang lebih baik
        $tanggal = "";
        if (!empty($this->filters)) {
            $tanggalFilter = collect($this->filters)->firstWhere('type', 'tanggal');
            if ($tanggalFilter) {
                if (is_array($tanggalFilter) && isset($tanggalFilter['value'])) {
                    // Handle kasus dimana tanggal dalam format value
                    $dateValue = is_string($tanggalFilter['value']) ? 
                                json_decode($tanggalFilter['value'], true) : 
                                $tanggalFilter['value'];
                    
                    if (isset($dateValue['start']) && isset($dateValue['end'])) {
                        $startDate = Carbon::parse($dateValue['start'])->format('d/m/Y');
                        $endDate = Carbon::parse($dateValue['end'])->format('d/m/Y');
                        $tanggal = $startDate === $endDate ? 
                                  "Tanggal : {$startDate}" : 
                                  "Tanggal : {$startDate} - {$endDate}";
                    } else {
                        // Jika hanya satu tanggal
                        $singleDate = Carbon::parse($dateValue)->format('d/m/Y');
                        $tanggal = "Tanggal : {$singleDate}";
                    }
                } else if (isset($tanggalFilter['start']) && isset($tanggalFilter['end'])) {
                    // Handle kasus dimana tanggal dalam format start/end langsung
                    $startDate = Carbon::parse($tanggalFilter['start'])->format('d/m/Y');
                    $endDate = Carbon::parse($tanggalFilter['end'])->format('d/m/Y');
                    $tanggal = $startDate === $endDate ? 
                              "Tanggal : {$startDate}" : 
                              "Tanggal : {$startDate} - {$endDate}";
                } else {
                    // Jika format tanggal tidak sesuai ekspektasi, gunakan data
                    $firstDate = $this->data->min('created_at');
                    $lastDate = $this->data->max('created_at');
                    if ($firstDate && $lastDate) {
                        $startDate = Carbon::parse($firstDate)->format('d/m/Y');
                        $endDate = Carbon::parse($lastDate)->format('d/m/Y');
                        $tanggal = $startDate === $endDate ? 
                                  "Tanggal : {$startDate}" : 
                                  "Tanggal : {$startDate} - {$endDate}";
                    }
                }
            } else {
                // Jika tidak ada filter tanggal, gunakan rentang dari data
                $firstDate = $this->data->min('created_at');
                $lastDate = $this->data->max('created_at');
                if ($firstDate && $lastDate) {
                    $startDate = Carbon::parse($firstDate)->format('d/m/Y');
                    $endDate = Carbon::parse($lastDate)->format('d/m/Y');
                    $tanggal = $startDate === $endDate ? 
                              "Tanggal : {$startDate}" : 
                              "Tanggal : {$startDate} - {$endDate}";
                }
            }
        } else {
            // Jika tidak ada filter sama sekali, gunakan rentang dari data
            $firstDate = $this->data->min('created_at');
            $lastDate = $this->data->max('created_at');
            if ($firstDate && $lastDate) {
                $startDate = Carbon::parse($firstDate)->format('d/m/Y');
                $endDate = Carbon::parse($lastDate)->format('d/m/Y');
                $tanggal = $startDate === $endDate ? 
                          "Tanggal : {$startDate}" : 
                          "Tanggal : {$startDate} - {$endDate}";
            }
        }
        
        $sheet->setCellValue("{$startDateCol}4", $tanggal);
        $sheet->getStyle("{$startDateCol}4")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
    }

    protected function getFooter($sheet, $lastRow)
    {
        $startCol = 'C';
        $totalColumns = $this->getCountTab();
        
        // Left footer - selalu di kolom C
        $sheet->setCellValue("{$startCol}" . ($lastRow + 3), 
            "Created At : " . Carbon::now()->format('d/m/Y H:i:s'));
        $sheet->getStyle("{$startCol}" . ($lastRow + 3))->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
        ]);
        
        // Right footer dengan style align right
        $employeeName = auth()->user()->employee->nama ?? 'Unknown';
        if(!empty($this->filters)) {
            $displayedTables = collect($this->filters)
            ->where('type', 'tempat-ruangan')
            ->pluck('tempat')
            ->unique()
            ->count();
            $lastCol = $this->getColumnId($this->getColumnNumber($startCol) + $totalColumns - $displayedTables);
            $rightFooterCell = "{$lastCol}" . ($lastRow + 3);
        }
        else {
            $lastCol = $this->getColumnId($this->getColumnNumber($startCol) + $totalColumns - 2);
            $rightFooterCell = "{$lastCol}" . ($lastRow + 3);
        }
        
        $sheet->setCellValue($rightFooterCell, "By : " . $employeeName);
        $sheet->getStyle($rightFooterCell)->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
        ]);
    }

    protected function getCountTab()
    {
        $totalColumns = 0;
        if (empty($this->tempatConfig)) {
            // Jika tidak ada tempat yang dipilih, hitung semua tempat
            $allTempatOrder = ['CS01', 'CS02', 'MASAL'];
            foreach ($allTempatOrder as $tempat) {
                $rooms = $this->getAllRoomsForTempat($tempat);
                if (!empty($rooms)) {
                    $totalColumns += count($rooms) * 5; // 5 kolom per ruangan
                }
                $totalColumns++;
            }
        } else {
            // Hitung berdasarkan tempat yang dipilih
            foreach ($this->tempatConfig as $tempat => $config) {
                if (!empty($config['rooms'])) {
                    $totalColumns += count($config['rooms']) * 5;
                }
                $totalColumns++;
            }
        }
        return $totalColumns;
    }

    public function export()
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $styles = $this->getStyles();
            
            // Tambahkan header
            $this->getHeader($sheet, $styles);
            
            $currentStartCol = 'C'; // Mulai dari kolom C
            $currentRow = 7;  // Mulai dari baris 7
            $maxRow = $currentRow;
            
            // Jika tidak ada tempat yang dipilih, tampilkan semua tempat
            if (empty($this->tempatConfig)) {
                $allTempatOrder = ['CS01', 'CS02', 'MASAL'];
                foreach ($allTempatOrder as $tempat) {
                    $rooms = $this->getAllRoomsForTempat($tempat);
                    if (!empty($rooms)) {
                        $result = $this->getTable(
                            $sheet,
                            $tempat,
                            $rooms,
                            $currentStartCol,
                            $this->data,
                            $styles,
                            $currentRow
                        );
                        $currentStartCol = $result['nextStartCol'];
                        $maxRow = max($maxRow, $result['lastRow']);
                    }
                }
            } else {
                // Proses tempat yang dipilih
                foreach ($this->tempatConfig as $tempat => $config) {
                    if (!empty($config['rooms'])) {
                        $result = $this->getTable(
                            $sheet,
                            $tempat,
                            $config['rooms'],
                            $currentStartCol,
                            $this->data,
                            $styles,
                            $currentRow
                        );
                        $currentStartCol = $result['nextStartCol'];
                        $maxRow = max($maxRow, $result['lastRow']);
                    }
                }
            }
            
            // Tambahkan footer
            $this->getFooter($sheet, $maxRow);
            
            // Auto-size columns
            $usedRange = $sheet->calculateWorksheetDimension();
            $highestColumn = Coordinate::columnIndexFromString($sheet->getHighestColumn());
            
            for ($i = 3; $i <= $highestColumn; $i++) { // Mulai dari kolom C
                $columnLetter = Coordinate::stringFromColumnIndex($i);
                if ($sheet->cellExists($columnLetter . '1')) {
                    $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
                }
            }

            $tempFile = tempnam(sys_get_temp_dir(), 'suhu_export_');
            $writer = new Xlsx($spreadsheet);
            $writer->save($tempFile);

            return response()->download($tempFile, date('Y-m-d') . '_Laporan_Pencatatan_Suhu.xlsx')
                ->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            \Log::error('Error in SuhuExport:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function getUsedColumns($sheet)
    {
        $highestColumn = $sheet->getHighestColumn();
        $columns = [];
        $currentColumn = 'A';
        
        while ($currentColumn <= $highestColumn) {
            $columns[] = $currentColumn;
            $currentColumn++;
        }
        
        return $columns;
    }

    private function getColumnFromIndex($startCol, $offset)
    {
        // Konversi kolom awal ke angka
        $startNum = 0;
        $len = strlen($startCol);
        for ($i = 0; $i < $len; $i++) {
            $startNum = $startNum * 26 + (ord($startCol[$i]) - ord('A') + 1);
        }
        
        // Tambahkan offset
        $finalNum = $startNum + $offset;
        
        // Konversi kembali ke format kolom Excel
        return $this->getColumnId($finalNum);
    }

    // Tambahkan helper method untuk memastikan string comparison yang aman
    protected function normalizeString($str) 
    {
        return trim(strtoupper($str ?? ''));
    }

    // Update method filtering
    protected function filterByTempat($data, $tempat) 
    {
        $normalizedTempat = $this->normalizeString($tempat);
        return $data->filter(function($item) use ($normalizedTempat) {
            return $this->normalizeString($item->tempat->nama) === $normalizedTempat;
        });
    }

    protected function filterByRoom($data, $room) 
    {
        $normalizedRoom = $this->normalizeString($room);
        return $data->filter(function($item) use ($normalizedRoom) {
            return $this->normalizeString($item->tempat->ruangan) === $normalizedRoom;
        });
    }

    protected function getAllRoomsForTempat($tempat)
    {
        // Definisikan mapping ruangan untuk setiap tempat
        $roomMapping = [
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

        return $roomMapping[$tempat] ?? [];
    }
} 