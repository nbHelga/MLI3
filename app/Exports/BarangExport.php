<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;


class BarangExport implements FromCollection, WithStartRow, WithCustomStartCell, WithHeadings, WithMapping, WithStyles
{
    protected $data;
    protected $filters;

    public function __construct($data, $filters = [])
    {
        $this->data = $data;
        $this->filters = collect($filters);
    }

    public function collection()
    {
        // Data sudah difilter di controller, jadi langsung return saja
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
            'Kode Barang',
            'Nama Barang',
            'Kualitas Barang',
            'Size Barang'
        ];
    }

    public function map($row): array
    {
        static $no = 1;
        return [
            $no++,
            $row->kode,
            $row->nama,
            $row->kualitas,
            $row->size
        ];
    }

    protected function getHeader($sheet, $styles)
    {
        $startCol = 'C';
        $endCol = 'G';
        
        // Header baris 1
        $sheet->mergeCells("{$startCol}2:{$endCol}2");
        $sheet->setCellValue("{$startCol}2", "PT MONSTER LAUT INDONESIA");
        $sheet->getStyle("{$startCol}2")->applyFromArray([
            'font' => ['size' => 14, 'bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        
        // Header baris 2
        $sheet->mergeCells("{$startCol}3:{$endCol}3");
        $sheet->setCellValue("{$startCol}3", "LAPORAN BARANG");
        $sheet->getStyle("{$startCol}3")->applyFromArray([
            'font' => ['size' => 18, 'bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        
        // Header tanggal
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
        
        $sheet->mergeCells("{$startCol}4:{$endCol}4");
        $sheet->setCellValue("{$startCol}4", $tanggal);
        $sheet->getStyle("{$startCol}4")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
    }

    protected function getFooter($sheet, $lastRow)
    {
        $startCol = 'C';
        $endCol = 'G';
        
        // Left footer
        $sheet->setCellValue("{$startCol}" . ($lastRow + 2), 
            "Created At : " . Carbon::now()->format('d/m/Y H:i:s'));
        
        // Right footer
        $employeeName = auth()->user()->employee->nama ?? 'Unknown';
        $sheet->setCellValue("{$endCol}" . ($lastRow + 2), 
            "By : " . $employeeName);
        $sheet->getStyle("{$endCol}" . ($lastRow + 2))->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $this->getHeader($sheet, []);
        
        $lastRow = $sheet->getHighestRow();
        $startRow = 7;
        
        // Style untuk headers kolom
        $sheet->getStyle('C'.$startRow.':G'.$startRow)->applyFromArray([
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
        $sheet->getStyle('C'.($startRow + 1).':G'.$lastRow)->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ]
        ]);

        // Style khusus per kolom untuk content
        $columnStyles = [
            'C' => Alignment::HORIZONTAL_CENTER, // No
            'D' => Alignment::HORIZONTAL_CENTER, // Kode Barang
            'E' => Alignment::HORIZONTAL_LEFT,   // Nama Barang
            'F' => Alignment::HORIZONTAL_LEFT,   // Kualitas
            'G' => Alignment::HORIZONTAL_CENTER, // Size
        ];

        foreach ($columnStyles as $col => $alignment) {
            $sheet->getStyle($col.($startRow + 1).':'.$col.$lastRow)
                  ->getAlignment()
                  ->setHorizontal($alignment);
        }

        // Atur lebar kolom
        $columnWidths = [
            'C' => 8,     // No
            'D' => 30,    // Kode Barang
            'E' => 40,    // Nama Barang
            'F' => 10,    // Kualitas
            'G' => 20,    // Size
        ];

        foreach ($columnWidths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Border untuk semua sel
        $sheet->getStyle('C'.$startRow.':G'.$lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ]);

        $this->getFooter($sheet, $lastRow);
        
        return $sheet;
    }
} 