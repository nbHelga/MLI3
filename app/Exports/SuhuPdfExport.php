<?php

namespace App\Exports;

use Mpdf\Mpdf;
use Carbon\Carbon;

class SuhuPdfExport
{
    protected $data;
    protected $filters;
    protected $mpdf;
    protected $styles;

    public function __construct($data, $filters = [])
    {
        $this->data = $data;
        $this->filters = collect($filters);
        $this->mpdf = new Mpdf([
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 40,
            'margin_bottom' => 20,
            'margin_header' => 10,
            'margin_footer' => 10
        ]);
        $this->styles = $this->getStyles();
    }

    public function export()
    {
        // Set header dan footer
        $this->mpdf->SetHTMLHeader($this->getHeader());
        $this->mpdf->SetHTMLFooter($this->getFooter());
        
        // Aktifkan margin otomatis
        $this->mpdf->setAutoTopMargin = 'stretch';
        $this->mpdf->setAutoBottomMargin = 'stretch';
        
        // Generate content
        $html = $this->generateContent();
        
        // Write HTML
        $this->mpdf->WriteHTML($this->styles, \Mpdf\HTMLParserMode::HEADER_CSS);
        $this->mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
        
        // Output PDF
        return $this->mpdf->Output('Laporan_Suhu_'.date('Y-m-d').'.pdf', 'D');
    }

    protected function getHeader()
    {
        $tanggal = $this->getFilterDate();
        
        return "
            <div style='text-align: right; color: #CCCCCC; margin-bottom: -30px; font-family: Times New Roman; font-size: 11pt;'>
                {PAGENO}
            </div>
            <div style='text-align: center;'>
                <div style='font-family: Times New Roman; font-size: 14pt;'>PT MONSTER LAUT INDONESIA</div>
                <div style='font-family: Times New Roman; font-size: 18pt; font-weight: bold;'>LAPORAN PENCATATAN SUHU</div>
                <div style='font-family: Times New Roman; font-size: 12pt;'>{$tanggal}</div>
            </div>
            <hr class='header-line'>
        ";
    }

    protected function getFooter()
    {
        $createdAt = Carbon::now()->format('d/m/Y H:i:s');
        $userName = auth()->user()->employee->nama ?? 'Unknown';
        
        return "
            <hr class='footer-line'>
            <table width='100%' style='font-family: Times New Roman; font-size: 11pt;'>
                <tr>
                    <td style='text-align: left;'>Created At : {$createdAt}</td>
                    <td style='text-align: right;'>By : {$userName}</td>
                </tr>
            </table>
        ";
    }

    protected function generateContent()
    {
        $html = '';
        $tempats = ['CS01', 'CS02', 'MASAL'];
        $ruangans = [
            'CS01' => ['Room 1', 'Room 2', 'Room 3'],
            'CS02' => ['Room 1', 'Room 2', 'Room 3', 'Room 4'],
            'MASAL' => ['Room 1']
        ];

        // Filter tempat yang dipilih
        $tempatFilter = $this->filters->where('type', 'tempat')->pluck('value')->toArray();
        $ruanganFilter = $this->filters->where('type', 'ruangan')->pluck('value')->toArray();
        
        foreach ($tempats as $tempat) {
            $showTempat = false;
            $tempatContent = '';
            
            // Cek apakah tempat ini dipilih dalam filter
            if (empty($tempatFilter) || in_array(strtolower($tempat), array_map('strtolower', $tempatFilter))) {
                foreach ($ruangans[$tempat] as $ruangan) {
                    // Cek apakah ruangan ini dipilih dalam filter
                    if (empty($ruanganFilter) || in_array($ruangan, $ruanganFilter)) {
                        $ruanganData = $this->data->filter(function($item) use ($tempat, $ruangan) {
                            return $item->tempat->nama === $tempat && $item->tempat->ruangan === $ruangan;
                        });

                        if ($ruanganData->isNotEmpty()) {
                            if (!$showTempat) {
                                $showTempat = true;
                                $tempatContent .= "<div class='tempat'>Tempat : {$tempat}</div>";
                            }
                            $tempatContent .= $this->generateTable($ruangan, $ruanganData);
                        }
                    }
                }
            }

            if ($tempatContent) {
                // Tambahkan page break sebelum content baru jika bukan tempat pertama
                if ($html !== '') {
                    $html .= "<pagebreak />";
                }
                $html .= $tempatContent;
            }
        }

        return $html;
    }

    protected function generateTable($ruangan, $data)
    {
        $html = "
            <table class='data-table'>
                <tr class='header-row'>
                    <th colspan='5' class='room-header'>{$ruangan}</th>
                </tr>
                <tr class='header-row'>
                    <th>Tanggal</th>
                    <th>Pukul</th>
                    <th>Suhu</th>
                    <th>Keterangan</th>
                    <th>PIC</th>
                </tr>
        ";

        foreach ($data as $row) {
            $html .= "
                <tr>
                    <td>" . Carbon::parse($row->created_at)->format('d/m/Y') . "</td>
                    <td>" . Carbon::parse($row->jam)->format('H:i') . "</td>
                    <td>{$row->suhu}</td>
                    <td>{$row->keterangan}</td>
                    <td>{$row->employee->nama}</td>
                </tr>
            ";
        }

        $html .= "</table>";
        
        // Tambahkan spacer hanya jika bukan tabel terakhir
        if ($ruangan !== 'Room 3') {
            $html .= "<div class='table-spacer'></div>";
        }
        return $html;
    }

    protected function getStyles()
    {
        return "
            <style>
                @page {
                    margin-top: 60mm;
                    margin-bottom: 30mm;
                }
                body { 
                    font-family: Times New Roman;
                }
                .header-line { 
                    border-top: 1px solid black;
                    margin: 10px 0;
                }
                .footer-line {
                    border-top: 1px solid black;
                    margin: 10px 0;
                }
                .tempat {
                    font-family: Times New Roman;
                    font-size: 12pt;
                    margin-top: 20mm;
                    margin-bottom: 10px;
                }
                .table-spacer {
                    height: 10px; /* Kurangi height spacer */
                }
                .data-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 15px; /* Kurangi margin bottom tabel */
                    page-break-inside: avoid;
                }
                .data-table th, .data-table td {
                    border: 1px solid black;
                    padding: 5px;
                }
                .room-header {
                    background-color: #4472C4;
                    font-weight: bold;
                }
                .header-row th {
                    background-color: #DCE6F1;
                }
                .data-table td {
                    vertical-align: middle;
                }
            </style>
        ";
    }

    protected function getFilterDate()
    {
        $tanggal = "";
        if (!empty($this->filters)) {
            $tanggalFilter = $this->filters->firstWhere('type', 'tanggal');
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
        
        return $tanggal;
    }
} 