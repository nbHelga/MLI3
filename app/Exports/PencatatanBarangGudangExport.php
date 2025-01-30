<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
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

class PencatatanBarangGudangExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
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
            // 'Keterangan'
        ];
    }

    public function map($row): array
    {
        static $no = 1;
        
        try {
            // Konversi tanggal dengan aman
            $tanggal = '';
            if (!empty($row['created_at'])) {
                $tanggal = Carbon::parse($row['created_at'])->format('d M Y');
            }

            return [
                $no++,
                $tanggal,
                $row['tempat']['nama'] ?? '',
                $row['tempat']['ruangan'] ?? '',
                $row['kode_pallet'] ?? '',
                $row['id_barang'] ?? '',
                $row['barang']['nama'] ?? '',
                $row['barang']['kualitas'] ?? '',
                $row['barang']['size'] ?? '',
                $row['employee']['nama'] ?? '',
                $row['keterangan'] ?? ''
            ];
        } catch (\Exception $e) {
            Log::error('Error in PencatatanBarangGudangExport mapping:', [
                'error' => $e->getMessage(),
                'row' => $row
            ]);
            
            // Return baris dengan nilai default jika terjadi error
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
                '',
                ''
            ];
        }
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        
        // Style for headers
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'B8CCE4' // Light blue color
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Style for all cells including headers
        $sheet->getStyle('A1:J'.$lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ]);

        // Make headers row taller
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Center align 'No' column
        $sheet->getStyle('A2:A'.$lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Left align other columns
        $sheet->getStyle('B2:J'.$lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Auto-size columns
        foreach(range('A','J') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Add extra width to header cells
        foreach(range('A','J') as $column) {
            $currentWidth = $sheet->getColumnDimension($column)->getWidth();
            $sheet->getColumnDimension($column)->setWidth($currentWidth * 1.2);
        }
    }
} 