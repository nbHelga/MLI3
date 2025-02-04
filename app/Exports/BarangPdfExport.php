<?php

namespace App\Exports;

use Mpdf\Mpdf;
use Carbon\Carbon;

class BarangPdfExport
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
            'margin_footer' => 10,
            'default_font' => 'times'
        ]);
        $this->styles = $this->getStyles();
    }

    public function export()
    {
        // Tingkatkan limit PCRE jika memungkinkan
        if (ini_get('pcre.backtrack_limit') < 3000000) {
            ini_set('pcre.backtrack_limit', 3000000);
        }
        
        $this->mpdf->SetHTMLHeader($this->getHeader());
        $this->mpdf->SetHTMLFooter($this->getFooter());
        
        $this->mpdf->setAutoTopMargin = 'stretch';
        $this->mpdf->setAutoBottomMargin = 'stretch';
        
        $html = $this->generateContent();
        
        $this->mpdf->WriteHTML($this->styles, \Mpdf\HTMLParserMode::HEADER_CSS);
        $this->mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
        
        return $this->mpdf->Output('Laporan_Barang_'.date('Y-m-d').'.pdf', 'D');
    }

    protected function getHeader()
    {
        $tanggal = $this->getFilterDate();
        
        return "
            <div style='text-align: right; color: #CCCCCC; margin-bottom: -30px; font-family: Times New Roman; font-size: 11pt;'>
                {PAGENO} / {nbpg}
            </div>
            <div style='text-align: center;'>
                <div style='font-family: Times New Roman; font-size: 14pt;'>PT MONSTER LAUT INDONESIA</div>
                <div style='font-family: Times New Roman; font-size: 18pt; font-weight: bold;'>LAPORAN BARANG</div>
                <div style='font-family: Times New Roman; font-size: 12pt;'>{$tanggal}</div>
            </div>
            <hr class='header-line'>
        ";
    }

    protected function generateContent()
    {
        try {
            $html = "
                <table class='data-table'>
                    <tr class='header-row'>
                        <th style='width: 8%;'>No</th>
                        <th style='width: 20%;'>Kode Barang</th>
                        <th style='width: 32%;'>Nama Barang</th>
                        <th style='width: 20%;'>Kualitas Barang</th>
                        <th style='width: 20%;'>Size Barang</th>
                    </tr>
            ";

            // Konversi Collection ke array dan proses dalam chunk
            $chunks = array_chunk($this->data->toArray(), 100); // Proses per 100 data
            $counter = 1;

            foreach ($chunks as $chunk) {
                foreach ($chunk as $row) {
                    $html .= "
                        <tr>
                            <td>" . $counter++ . "</td>
                            <td>{$row['kode']}</td>
                            <td>{$row['nama']}</td>
                            <td>{$row['kualitas']}</td>
                            <td>{$row['size']}</td>
                        </tr>
                    ";
                }
                
                // Flush memory setelah setiap chunk
                unset($chunk);
            }

            $html .= "</table>";
            return $html;
        } catch (\Exception $e) {
            return "Error generating content: " . $e->getMessage();
        }
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

    protected function getStyles()
    {
        return "
            <style>
                @page {
                    margin-top: 60mm;
                    margin-bottom: 30mm;
                }
                body { 
                    font-family: times;
                }
                .header-line { 
                    border-top: 1px solid black;
                    margin: 10px 0;
                }
                .footer-line {
                    border-top: 1px solid black;
                    margin: 10px 0;
                }
                .data-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 10px;    /* Tambahkan margin atas tabel */
                    font-size: 11pt;     /* Tambahkan ukuran font */
                    page-break-inside: avoid;
                }
                .data-table th, .data-table td {
                    border: 1px solid black;
                    padding: 5px;
                    text-align: left;    /* Tambahkan alignment */
                }
                .header-row th {
                    background-color: #DCE6F1;
                    font-weight: bold;
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