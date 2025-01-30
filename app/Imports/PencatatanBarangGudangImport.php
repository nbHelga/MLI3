<?php

namespace App\Imports;

use App\Models\PencatatanBarangGudang;
use App\Models\Barang;
use App\Models\Tempat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class PencatatanBarangGudangImport implements ToModel, WithStartRow, WithValidation
{
    private $successCount = 0;

    public function model(array $row)
    {
        // Validasi kombinasi unik kode_pallet dan id_tempat
        $existingRecord = PencatatanBarangGudang::where('kode_pallet', $row[1])
            ->where('id_tempat', $row[0])
            ->first();

        if ($existingRecord) {
            throw new \Exception('Duplicate combination of kode_pallet and id_tempat');
        }

        // Validasi bahwa id_barang ada di tabel barang
        $barang = Barang::where('kode', $row[2])->first();
        if (!$barang) {
            throw new \Exception('Invalid id_barang');
        }

        // Validasi bahwa id_tempat ada di tabel tempat
        $tempat = Tempat::find($row[0]);
        if (!$tempat) {
            throw new \Exception('Invalid id_tempat');
        }

        $this->successCount++;
        
        return new PencatatanBarangGudang([
            'id_tempat' => $row[0],
            'kode_pallet' => strtoupper($row[1]), // Konversi ke uppercase untuk konsistensi
            'id_barang' => strtoupper($row[2]),
            'id_employees' => auth()->user()->id
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            '0' => [
                'required',
                Rule::exists('tempat', 'id'),
            ],
            '1' => [
                'required',
                'string',
                'size:5',
            ],
            '2' => [
                'required',
                Rule::exists('barang', 'kode'),
            ],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '0.exists' => 'The selected id_tempat is invalid.',
            '2.exists' => 'The selected id_barang is invalid.',
            '1.size' => 'The kode_pallet must be exactly 5 characters.',
        ];
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }
} 