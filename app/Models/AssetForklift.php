<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetForklift extends Model
{
    /** @use HasFactory<\Database\Factories\AssetForkliftFactory> */
    use HasFactory;
    protected $fillable=[
        'nama',
        'merk',
        'qty',
        'lokasi',
    ];
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
