<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetApar extends Model
{
    /** @use HasFactory<\Database\Factories\AparFactory> */
    use HasFactory;
    protected $fillable=[
        'id',
        'lokasi',
        'nama',
        'ukuran',
        'tgl_exp',
        'tgl_produksi',
        'keterangan',
        'biaya_refill',
        'keterangan_refill',
    ];
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
