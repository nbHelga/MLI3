<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMenu extends Model
{    
    protected $table = 'usermenu';

    public $incrementing = false;

    protected $primaryKey = ['id_users', 'id_menu'];

    protected $fillable = [
        'id_users',
        'id_menu'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users', 'id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id');
    }
    
} 