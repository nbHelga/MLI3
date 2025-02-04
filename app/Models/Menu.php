<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Menu extends Model
{
    protected $table = 'menu';
    protected $fillable = [
        'nama',
        'submenu'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'usermenu', 'id_menu', 'id_users');
    }
} 