<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMenu extends Model
{     protected $table = 'usermenu';

    public $incrementing = false;

    protected $primaryKey = ['id_employees', 'id_menu'];

    protected $fillable = [
        'id_employees',
        'id_menu',
    ];
    
} 