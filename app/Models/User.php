<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;


//User -> Singular form of Users table
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'users';

    protected $primaryKey = 'id';
 
     public $incrementing = false; // Karena id adalah char(10)
 
    protected $fillable = [
        'id',
        'username',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }
            

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employees::class, 'id', 'id');
        
    }

    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'usermenu', 'id_users', 'id_menu')
                    ->withTimestamps();
    }

    // public function hasMenu($menuName)
    // {
    //     // HRIS dan Laporan dapat diakses semua user
    //     if ($menuName === 'HRIS' || $menuName === 'Laporan') {
    //         return true;
    //     }

    //     // Super Admin memiliki akses ke semua menu
    //     if ($this->status === 'Super Admin') {
    //         return true;
    //     }

    //     // Cek akses berdasarkan usermenu
    //     return $this->menus()
    //                ->where('nama', $menuName)
    //                ->exists();
    // }

    public function hasSubmenu($menuName, $submenuName = null)
    {
        // HRIS dapat diakses semua user (tidak perlu dicek karena sudah dipindah ke home)
        if ($menuName === 'HRIS') {
            return true;
        }

        // Super Admin memiliki akses ke semua menu dan submenu
        if ($this->status === 'Super Admin') {
            return true;
        }

        // Cek akses menu dasar
        $menuAccess = $this->menus()
            ->where('nama', $menuName);

        // Jika menu tidak memiliki submenu (HRD, Finance, Suhu, Maintenance, Security)
        // maka cukup cek akses ke menu utamanya
        if (!$submenuName) {
            return $menuAccess->exists();
        }

        // Khusus untuk Warehouse, cek submenu CS01/CS02
        if ($menuName === 'Warehouse') {
            $hasAccess = $menuAccess->where('submenu', $submenuName)->exists();
            
            // Khusus untuk form-barang, cek status user
            if ($submenuName === 'form-barang') {
                return $hasAccess && ($this->status === 'Administrator' || $this->status === 'Super Admin');
            }
            
            return $hasAccess;
        }

        // Default return false jika tidak memenuhi kondisi di atas
        return false;
    }

    public function getAccessibleMenus()
    {
        $menus = [];
        // Jika Super Admin, berikan akses ke semua menu
        if ($this->status === 'Super Admin') {
            return array_merge($menus, ['HRD', 'Finance', 'Warehouse', 'Maintenance', 'Suhu', 'Security', 'Laporan']);
        }

        // Tambahkan menu yang diberikan akses melalui usermenu
        $userMenus = $this->menus()
                         ->pluck('nama')
                         ->unique()
                         ->toArray();

        return array_merge($menus, $userMenus);
    }

    public function userMenus()
    {
        return $this->hasMany(UserMenu::class, 'id_users', 'id');
    }

    public function hasMenuAccess($menuName)
    {
        return DB::table('usermenu')
            ->join('menu', 'usermenu.id_menu', '=', 'menu.id')
            ->where('usermenu.id_users', $this->id)
            ->where('menu.nama', $menuName)
            ->exists();
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu');
    }

}
