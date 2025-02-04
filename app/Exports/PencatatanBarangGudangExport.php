<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\View\View;

class PencatatanBarangGudangExport implements FromCollection, WithStartRow, WithCustomStartCell, WithHeadings, WithMapping, WithStyles
{
    protected $data;
    protected $filters;
    protected $sorts;

    public function __construct($data, $filters = [], $sorts = [])
    {
        $this->data = $data;
        $this->filters = $filters;
        $this->sorts = $sorts;
    }

    public function collection()
    {
        return $this->data;
    }

    public function startRow(): int
    {
        return 7;
    }

    public function startCell(): string
    {
        return 'C7';
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Tempat',
            'Ruangan',
            'Kode Pallet',
            'Kode Barang',
            'Nama Barang',
            'Kualitas Barang',
            'Size Barang',
            'PIC'
        ];
    }

    public function map($row): array
    {
        static $no = 1;
        
        try {
            return [
                $no++,
                Carbon::parse($row->created_at)->format('d M Y'),
                $row->tempat->nama ?? '',
                $row->tempat->ruangan ?? '',
                $row->kode_pallet ?? '',
                $row->id_barang ?? '',
                $row->barang->nama ?? '',
                $row->barang->kualitas ?? '',
                $row->barang->size ?? '',
                $row->employee->nama ?? ''
            ];
        } catch (\Exception $e) {
            Log::error('Error in PencatatanBarangGudangExport mapping:', [
                'error' => $e->getMessage(),
                'row' => $row
            ]);
            
            return [
                $no++,
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ];
        }
    }

    public function styles(Worksheet $sheet)
    {
        $this->getHeader($sheet, []);
        
        $lastRow = $sheet->getHighestRow();
        $startRow = 7;
        
        // Style untuk headers kolom
        $sheet->getStyle('C'.$startRow.':L'.$startRow)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'B8CCE4']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ]
        ]);

        // Style untuk content/isi data
        $sheet->getStyle('C'.($startRow + 1).':L'.$lastRow)->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ]
        ]);

        // Style khusus per kolom untuk content
        $columnStyles = [
            'C' => Alignment::HORIZONTAL_CENTER, // No
            'D' => Alignment::HORIZONTAL_CENTER, // Tanggal
            'E' => Alignment::HORIZONTAL_LEFT,   // Tempat
            'F' => Alignment::HORIZONTAL_LEFT,   // Ruangan
            'G' => Alignment::HORIZONTAL_CENTER, // Kode Pallet
            'H' => Alignment::HORIZONTAL_CENTER, // Kode Barang
            'I' => Alignment::HORIZONTAL_LEFT,   // Nama Barang
            'J' => Alignment::HORIZONTAL_CENTER, // Kualitas Barang
            'K' => Alignment::HORIZONTAL_CENTER, // Size Barang
            'L' => Alignment::HORIZONTAL_LEFT    // PIC
        ];

        foreach ($columnStyles as $col => $alignment) {
            $sheet->getStyle($col.($startRow + 1).':'.$col.$lastRow)
                  ->getAlignment()
                  ->setHorizontal($alignment);
        }

        // Atur lebar kolom
        $columnWidths = [
            'C' => 8,     // No
            'D' => 15,    // Tanggal
            'E' => 20,    // Tempat
            'F' => 20,    // Ruangan
            'G' => 15,    // Kode Pallet
            'H' => 15,    // Kode Barang
            'I' => 30,    // Nama Barang
            'J' => 15,    // Kualitas Barang
            'K' => 15,    // Size Barang
            'L' => 20     // PIC
        ];

        foreach ($columnWidths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Border untuk semua sel
        $sheet->getStyle('C'.$startRow.':L'.$lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ]);

        $this->getFooter($sheet, $lastRow);
        
        return $sheet;
    }

    protected function getHeader($sheet, $styles)
    {
        // Hitung total kolom untuk posisi tengah
        $totalColumns = 10; // Total kolom tetap
        $headerWidth = 6;   // Lebar header
        $startCol = 'C';    // Mulai dari kolom C
        
        $startHeaderCol = $this->getColumnId($this->getColumnNumber($startCol) + 
                         floor(($totalColumns - $headerWidth) / 2));
        $endHeaderCol = $this->getColumnId($this->getColumnNumber($startHeaderCol) + 5);
        
        // Header baris 1
        $sheet->mergeCells("{$startHeaderCol}2:{$endHeaderCol}2");
        $sheet->setCellValue("{$startHeaderCol}2", "PT MONSTER LAUT INDONESIA");
        $sheet->getStyle("{$startHeaderCol}2")->applyFromArray([
            'font' => ['size' => 14, 'bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        
        // Header baris 2
        $sheet->mergeCells("{$startHeaderCol}3:{$endHeaderCol}3");
        $sheet->setCellValue("{$startHeaderCol}3", "LAPORAN PENCATATAN BARANG GUDANG");
        $sheet->getStyle("{$startHeaderCol}3")->applyFromArray([
            'font' => ['size' => 18, 'bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        
        // Header baris 3 (Tanggal)
        $startDateCol = $this->getColumnId($this->getColumnNumber($startHeaderCol) + 1);
        $endDateCol = $this->getColumnId($this->getColumnNumber($startDateCol) + 3);
        $sheet->mergeCells("{$startDateCol}4:{$endDateCol}4");
        
        // Format tanggal
        $tanggal = "";
        if (!empty($this->filters)) {
            $tanggalFilter = collect($this->filters)->firstWhere('type', 'tanggal');
            if ($tanggalFilter) {
                if (isset($tanggalFilter['value'])) {
                    $dateValue = is_string($tanggalFilter['value']) ? 
                                json_decode($tanggalFilter['value'], true) : 
                                $tanggalFilter['value'];
                    
                    if (isset($dateValue['start']) && isset($dateValue['end'])) {
                        $startDate = Carbon::parse($dateValue['start'])->format('d/m/Y');
                        $endDate = Carbon::parse($dateValue['end'])->format('d/m/Y');
                        $tanggal = $startDate === $endDate ? 
                                  "Tanggal : {$startDate}" : 
                                  "Tanggal : {$startDate} - {$endDate}";
                    }
                } else if (isset($tanggalFilter['start']) && isset($tanggalFilter['end'])) {
                    $startDate = Carbon::parse($tanggalFilter['start'])->format('d/m/Y');
                    $endDate = Carbon::parse($tanggalFilter['end'])->format('d/m/Y');
                    $tanggal = $startDate === $endDate ? 
                              "Tanggal : {$startDate}" : 
                              "Tanggal : {$startDate} - {$endDate}";
                }
            }
            else {
                // Gunakan rentang dari data jika tidak ada filter
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
        }
        else {
            // Gunakan rentang dari data jika tidak ada filter
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
        $endCol = 'L'; // Kolom terakhir (10 kolom dari C)
        
        // Left footer
        $sheet->setCellValue("{$startCol}" . ($lastRow + 2), 
            "Created At : " . Carbon::now()->format('d/m/Y H:i:s'));
        $sheet->getStyle("{$startCol}" . ($lastRow + 2))->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
        ]);
        
        // Right footer
        $employeeName = auth()->user()->employee->nama ?? 'Unknown';
        $rightFooterCell = "{$endCol}" . ($lastRow + 2);
        $sheet->setCellValue($rightFooterCell, "By : " . $employeeName);
        $sheet->getStyle($rightFooterCell)->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
        ]);
        
    }

    protected function getColumnNumber($column) {
        $columnIndex = 0;
        $length = strlen($column);
        for ($i = 0; $i < $length; $i++) {
            $columnIndex = $columnIndex * 26 + (ord(strtoupper($column[$i])) - ord('A') + 1);
        }
        return $columnIndex;
    }

    protected function getColumnId($number) {
        $column = '';
        while ($number > 0) {
            $modulo = ($number - 1) % 26;
            $column = chr(65 + $modulo) . $column;
            $number = floor(($number - $modulo) / 26);
        }
        return $column;
    }
} 