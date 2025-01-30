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
                    'vertical' => Alignment::VERTICAL_CENTER
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
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ]
                ]
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

    protected function getTable($sheet, $tempat, $ruangans, $startCol, $data, $styles)
    {
        $colIndex = $this->getColumnNumber($startCol);
        $totalColumns = count($ruangans) * 5;
        $lastCol = $this->getColumnId($colIndex + $totalColumns - 1);
        
        // Parent (Tempat header)
        $sheet->mergeCells("{$startCol}1:{$lastCol}1");
        $sheet->setCellValue("{$startCol}1", $tempat);
        $sheet->getStyle("{$startCol}1:{$lastCol}1")->applyFromArray($styles['header']);
        
        // Child (Room headers)
        foreach ($ruangans as $room => $config) {
            $roomStartCol = $this->getColumnId($colIndex);
            $roomEndCol = $this->getColumnId($colIndex + 4);
            
            // Room header
            $sheet->mergeCells("{$roomStartCol}2:{$roomEndCol}2");
            $sheet->setCellValue("{$roomStartCol}2", $room);
            $sheet->getStyle("{$roomStartCol}2:{$roomEndCol}2")->applyFromArray($styles['roomHeader']);
            
            // Sub Child (Column headers)
            $headers = ['Tanggal', 'Pukul', 'Suhu', 'Keterangan', 'PIC'];
            for ($i = 0; $i < 5; $i++) {
                $col = $this->getColumnId($colIndex + $i);
                $sheet->setCellValue("{$col}3", $headers[$i]);
                $sheet->getStyle("{$col}3")->applyFromArray($styles['columnHeader']);
            }
            
            // Content (Data)
            $roomData = $data->filter(function($item) use ($config) {
                return $item->id_tempat === $config['id'];
            });
            
            $currentRow = 4;
            foreach ($roomData as $item) {
                $sheet->setCellValue($roomStartCol . $currentRow, date('d M Y', strtotime($item->created_at)));
                $sheet->setCellValue($this->getColumnId($colIndex + 1) . $currentRow, $item->jam);
                $sheet->setCellValue($this->getColumnId($colIndex + 2) . $currentRow, $item->suhu);
                $sheet->setCellValue($this->getColumnId($colIndex + 3) . $currentRow, $item->keterangan);
                $sheet->setCellValue($this->getColumnId($colIndex + 4) . $currentRow, $item->employee->nama);
                
                $sheet->getStyle("{$roomStartCol}{$currentRow}:{$roomEndCol}{$currentRow}")
                      ->applyFromArray($styles['data']);
                
                $currentRow++;
            }
            
            $colIndex += 5;
        }
        
        // Tambahkan satu kolom kosong setelah tabel
        return $this->getColumnId($colIndex + 1);
    }

    public function export()
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $styles = $this->getStyles();
            
            $currentStartCol = 'A';
            
            // Hanya proses tempat yang ada di konfigurasi
            foreach ($this->tempatConfig as $tempat => $config) {
                if (!empty($config['rooms'])) {
                    // Gunakan startCol dari konfigurasi yang sudah dihitung di controller
                    $currentStartCol = $config['startCol'];

                    $nextStartCol = $this->getTable(
                        $sheet,
                        $tempat,
                        $config['rooms'],
                        $currentStartCol,
                        $this->data,
                        $styles
                    );
                    $currentStartCol = $nextStartCol;
                }
            }

            // Auto-size hanya untuk kolom yang digunakan
            $usedRange = $sheet->calculateWorksheetDimension();
            $highestColumn = Coordinate::columnIndexFromString($sheet->getHighestColumn());
            
            for ($i = 1; $i <= $highestColumn; $i++) {
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
} 