<?php

namespace App\Models;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetAc extends Model
{
    /** @use HasFactory<\Database\Factories\AssetAcFactory> */
    use HasFactory;
    protected $fillable=[
        'nama', //default AC
        'status', // aktif/nonaktif
        'id',
        'lokasi',
        'divisi',
        // 'pic', default yg edit 
        'kategori',
        'tipe',
        'kondisi',
    ];
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

}
