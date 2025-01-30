<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
// use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\Warehouse\BarangController;
use App\Http\Controllers\Warehouse\PalletController;
use App\Http\Controllers\Warehouse\BarangPalletController;
use App\Http\Middleware\LogHomeAccess;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Maintenance\SuhuController;
use App\Http\Controllers\Maintenance\MaintenanceController;
use App\Http\Controllers\LaporanController;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Karyawan;
use App\Models\NonKaryawan;
use Illuminate\Support\Arr;
// use Illuminate\Support\Facades\Route;

// // Route::get('/', function () {
// //     return redirect()->route('login');
// // });
// // Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
// // Route::post('/login', [LoginController::class, 'authenticate']);

Route::get('/home', function () {
    return view('home', ['title'=> 'Home Page']);
});


Route::get('/about', function () {
    return view('about',['nama' => 'Raven', 'title' => 'Contact']);
});

Route::get('/posts', function () {
    //$posts= Post::with(['author', 'category'])->latest()->get();
    $posts=Post::latest()->get();
    return view('posts', ['title'=> 'Blog Page','posts'=> $posts]);
});

Route::get('/posts/{post:slug}', function(Post $post){ //memanggil berdasarkan ID post, dan bisa memakai slug utk nama link post
    
    return view('post', ['title' => 'Single Post', 'post'=>$post]);
});

Route::get('/authors/{user:username}', function(User $user){ //memanggil berdasarkan ID post, dan bisa memakai slug utk nama link post
    //$posts=$user->posts->load('category','author');

    return view('posts', ['title' =>count($user->posts). ' Articles by '.$user->name, 'posts'=>$user->posts]);
});

Route::get('/categories/{category:slug}', function(Category $category){ //memanggil berdasarkan ID post, dan bisa memakai slug utk nama link post
    //$posts=$category->posts->load('category','author');

    return view('posts', ['title' =>' Articles in: '.$category->name, 'posts'=>$category->posts]);
});

Route::get('/contact', function () {
    return view('contact', ['title'=> 'Contact']);
});

Route::get('/assets', function () {
    return view('assets', ['title'=> 'Assets']);
});

Route::get('/karyawan', function () {
    return view('karyawan', ['title'=> 'Employees']);
});

Route::get('/karyawan/list', function () {
    $karyawans=Karyawan::get();
    return view('karyawan', ['title'=> 'List of Employees','posts'=>$karyawans]);
});

Route::get('/karyawan/form', function () {
    return view('karyawan', ['title'=> 'Add/Edit Employees']);
});

    //ROUTE UTK NON KARYAWAN : FORM(ADD, EDIT), LIST}} 
Route::get('/nonkaryawan', function(){
    $nonkaryawans=NonKaryawan::get();
    return view('non_karyawan',['title'=>'Non-Karyawan']);
});

Route::get('/nonkaryawan/list', function(){
    return view('non_karyawan',['title'=>'List of Non-Karyawan']);
});

Route::get('/nonkaryawan/form', function(){
    return view('non_karyawan',['title'=>'Add/Edit Non-Karyawan']);
});
Route::get('/nonkaryawan/form/add', function(){
    return view('non_karyawan',['title'=>'Add Non-Karyawan']);
});
Route::get('/nonkaryawan/form/edit', function(){
    return view('non_karyawan',['title'=>'Edit Non-Karyawan']);
});

Route::get('/security', function(){
    return view('loglists',['title'=>'Security']);
})->name('security.list-satpam');

Route::get('security/form-satpam', function(){
    return view('security',['title'=>'Form Satpam']);
})->name('security.form-satpam');
// //     //MAINTENANCE : FORM, LIST
// // Route::get('/maintenance', function(){
// //     return view('maintenance',['title'=>'Maintenance']);
// // });
// // Route::get('/maintenance/list', function(){
// //     return view('maintenance',['title'=>'Maintenance List']);
// // });

// // Route::get('/maintenance/list', function(){
// //     return view('maintenance',['title'=>'Maintenance List']);
// // });
// Route::get('/submenu', function(){
//     return view('submenu',['title'=>'Submenu']);
// });

// Route::get('/employees-add', function(){
//     return view('form-addKaryawan',['title'=>'Tambah Data Karyawan Baru']);
// });

// Route::get('/nonemployees-add', function(){
//     return view('form-addNonKaryawan',['title'=>'Tambah Data Non-Karyawan Baru']);
// });

// Route::get('/satpamlog-add', function(){
//     return view('form-addSatpamLog',['title'=>'Tambah Data Karyawan Baru']);
// });
// Route::get('/asset-add', function(){
//     return view('form-addAsset',['title'=>'Tambah Aset']);
// });



// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Home Route
Route::get('/', function () {
    return view('auth.login');
})->name('welcome');

Route::middleware('auth')->group(function () { 
    Route::get('/home', function (Request $request) {
        // Tambahkan log detail untuk debugging
        Log::info('Accessing home route', [
            'session_all' => $request->session()->all(),
            'auth_check' => Auth::check(),
            'auth_id' => Auth::id(),
            'session_user_id' => $request->session()->get('user_id'),
            'request_path' => $request->path(),
            'request_url' => $request->url()
        ]);
        
        // Pastikan view home.blade.php ada
        if (!View::exists('home')) {
            Log::error('View home.blade.php tidak ditemukan');
            abort(404, 'View home.blade.php tidak ditemukan');
        }
        return view('home');
    })->name('home'); 

    // Logout Route
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');

    // Route untuk profile karyawan
    Route::get('/profile', function () {
        return view('profile.karyawan');
    })->name('profile.karyawan')->middleware('auth');

    // HRIS Routes
    Route::prefix('hris')->name('hris.')->group(function () {
        Route::get('/form', function () {
            return view('hris.form');
        })->name('form');
        
        Route::get('/list', function () {
            return view('hris.list');
        })->name('list');
    }); 

    // HRD Routes
    Route::prefix('hrd')->name('hrd.')->group(function () {
        Route::get('/form-karyawan', function () {
            return view('hrd.form-karyawan');
        })->name('form-karyawan');
        
        Route::get('/list-karyawan', function () {
            return view('hrd.list-karyawan');
        })->name('list-karyawan');
        
        Route::get('/form-nonkaryawan', function () {
            return view('hrd.form-nonkaryawan');
        })->name('form-nonkaryawan');
        
        Route::get('/list-nonkaryawan', function () {
            return view('hrd.list-nonkaryawan');
        })->name('list-nonkaryawan');
        
        Route::get('/list-surat', function () {
            return view('hrd.list-surat');
        })->name('list-surat');
    }); 

    // Finance Routes
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/list-surat', function () {
            return view('finance.list-surat');
        })->name('list-surat');
    }); 

    // Warehouse Routes
    Route::prefix('warehouse')->name('warehouse.')->middleware(['auth'])->group(function () {
        // Product routes
        Route::get('/product', [BarangController::class, 'index'])->name('product-list');
        Route::get('/product2', [BarangController::class, 'index'])->name('product-list2');
        Route::get('/product/create', [BarangController::class, 'create'])->name('product-form');
        Route::post('/product', [BarangController::class, 'store'])->name('product.store');
        Route::get('/product/{id}/edit', [BarangController::class, 'edit'])->name('product.edit');
        Route::put('/product/{id}', [BarangController::class, 'update'])->name('product.update');
        Route::delete('/product/{barang}', [BarangController::class, 'destroy'])->name('product.destroy');
        Route::post('/product/destroy-multiple', [BarangController::class, 'destroyMultiple'])
            ->name('product.destroy-multiple');

        // Import route
        Route::post('/product/import', [BarangController::class, 'import'])
            ->name('product.import');

        // Barang Pallet List Routes
        Route::prefix('barangpallet')->group(function () {
            // CS01 routes
            Route::get('/cs01/list', [BarangPalletController::class, 'listBarangPallet'])
                ->name('barangpallet-cs01-list');
            
            Route::get('/cs01/list/{room}', [BarangPalletController::class, 'listBarangPallet'])
                ->name('barangpallet-cs01-list.room');

            // CS02 routes  
            Route::get('/cs02/list', [BarangPalletController::class, 'listBarangPallet'])
                ->name('barangpallet-cs02-list');
            
            Route::get('/cs02/list/{room}', [BarangPalletController::class, 'listBarangPallet'])
                ->name('barangpallet-cs02-list.room');

            // Masal routes
            Route::get('/masal/list', [BarangPalletController::class, 'listBarangPallet'])
                ->name('barangpallet-masal-list');
            
            Route::get('/masal/list/{room}', [BarangPalletController::class, 'listBarangPallet'])
                ->name('barangpallet-masal-list.room');
        });

        // Barang Pallet Form Routes
        Route::get('/barangpallet/cs01/{id?}', [BarangPalletController::class, 'barangpalletCS01'])
            ->name('barangpallet-cs01');
        Route::get('/barangpallet/cs02/{id?}', [BarangPalletController::class, 'barangpalletCS02'])
            ->name('barangpallet-cs02');
        Route::get('/barangpallet/masal/{id?}', [BarangPalletController::class, 'barangpalletMasal'])
            ->name('barangpallet-masal');
        
        // Barang Pallet Actions
        Route::post('/barangpallet', [BarangPalletController::class, 'store'])
            ->name('barangpallet.store');
        Route::get('/barangpallet/{id}/edit', [BarangPalletController::class, 'edit'])
            ->name('barangpallet.edit');
        Route::put('/barangpallet/{id}', [BarangPalletController::class, 'update'])
            ->name('barangpallet.update');
        Route::delete('/barangpallet/{id}', [BarangPalletController::class, 'destroy'])
            ->name('barangpallet.destroy');
        
        // Barang Pallet Search API
        Route::get('/barangpallet/search', [BarangPalletController::class, 'searchBarangPallet'])
            ->name('barangpallet.search');

        // Import route
        Route::post('/barangpallet/import', [BarangPalletController::class, 'import'])
            ->name('barangpallet.import');

        // Export routes
        Route::post('/barangpallet/export', [BarangPalletController::class, 'export'])
            ->name('barangpallet.export');

        // Tambahkan route untuk delete multiple
        Route::delete('barangpallet/destroy-multiple', [BarangPalletController::class, 'destroyMultiple'])
            ->name('barangpallet.destroy-multiple');

        // Dalam group route warehouse
        Route::get('/barangpallet/search-barang', [BarangPalletController::class, 'searchBarang'])
            ->name('barangpallet.search-barang');

        
    }); 

    Route::prefix('maintenance')->name('maintenance.')->group(function () {
        // Route::prefix('suhu')->name('suhu.')->group(function () {
            
        //     // CS01 specific routes
        //     Route::get('/list-cs01', [SuhuController::class, 'listCS01'])->name('list-cs01');
        //     Route::get('/form-cs01', [SuhuController::class, 'formCS01'])->name('form-cs01');
            
        //     // CS02 specific routes
        //     Route::get('/list-cs02', [SuhuController::class, 'listCS02'])->name('list-cs02');
        //     Route::get('/form-cs02', [SuhuController::class, 'formCS02'])->name('form-cs02');
            
        //     // Actions
        //     Route::post('/store', [SuhuController::class, 'store'])->name('store');
        //     Route::put('/{suhu}', [SuhuController::class, 'update'])->name('edit');
        //     Route::delete('/{suhu}', [SuhuController::class, 'destroy'])->name('destroy');

            
        // });
        // Suhu routes
        Route::get('/suhu', [SuhuController::class, 'index'])->name('suhu.list');
        Route::get('/suhu/create', [SuhuController::class, 'create'])->name('suhu.create');
        Route::post('/suhu', [SuhuController::class, 'store'])->name('suhu.store');
        Route::get('/suhu/{suhu}/edit', [SuhuController::class, 'edit'])->name('suhu.edit');
        Route::put('/suhu/{suhu}', [SuhuController::class, 'update'])->name('suhu.update');
        Route::delete('/suhu/{suhu}', [SuhuController::class, 'destroy'])->name('suhu.destroy');
        
        // API route untuk search
        Route::get('/api/suhu/search', [SuhuController::class, 'search'])->name('suhu.search');

        Route::prefix('facility')->name('facility.')->group(function () {
            Route::get('/form', function () {
                return view('maintenance.facility-form');
            })->name('form');
            
            Route::get('/list', function () {
                return view('list-maintenance');
            })->name('list');
        });

        // Route::get('/suhu/export', [SuhuController::class, 'export'])
        //     ->name('suhu.export');
    });

    // Laporan Routes
    Route::prefix('laporan')->name('laporan.')->middleware('auth')->group(function () {
        Route::get('/barang', function () {
            return view('laporan.barang');
        })->name('barang');
        
        Route::get('/perpindahan-barang-gudang', function () {
            return view('laporan.pallet');
        })->name('pallet');
        
        Route::get('/suhu', function () {
            return view('laporan.suhu');
        })->name('suhu');
        
        Route::get('/aset-fasilitas', function () {
            return view('laporan.aset-fasilitas');
        })->name('aset');
        
        Route::get('/maintenance', function () {
            return view('laporan.maintenance');
        })->name('maintenance');
        
        // Export routes
        Route::post('/pallet/export', [LaporanController::class, 'exportPallet'])->name('pallet.export');
        Route::get('/suhu/export', [LaporanController::class, 'exportSuhu'])->name('suhu.export');
    }); 

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/superadmin', function () {
            return view('admin.superadmin');
        })->name('superadmin');
    }); 

    // Search API route (ok)
    Route::get('/api/search', [BarangController::class, 'searchRecommendations'])
        ->name('api.search')
        ->middleware('auth');

    // API route untuk mendapatkan daftar ruangan berdasarkan tempat (ok)
    Route::get('/api/ruangan/{tempat}', function ($tempat) {
        return App\Models\Tempat::where('nama', strtoupper($tempat))
            ->whereNotNull('ruangan')
            ->pluck('ruangan');
    })->name('api.ruangan');

    Route::get('/laporan/pallet', [LaporanController::class, 'pallet'])->name('laporan.pallet');



    // Route::get('/submenu/', [LaporanController::class, 'pallet'])->name('laporan.pallet');

}); 
