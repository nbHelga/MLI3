<?php

namespace App\Imports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class BarangImport implements ToModel, WithStartRow, WithValidation
{
    private $successCount = 0;

    public function model(array $row)
    {
        $this->successCount++;
        
        return new Barang([
            'kode' => $row[0],
            'nama' => $row[1],
            'kualitas' => $row[2] ?: null,
            'size' => $row[3] ?: null,
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
                Rule::unique('barang', 'kode'),
            ],
            '1' => [
                'required',
                Rule::unique('barang', 'nama'),
            ],
            '2' => 'nullable',
            '3' => 'nullable',
        ];
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }
} 