<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Warehouse\BarangController;
use App\Http\Controllers\Warehouse\PalletController;
use App\Http\Controllers\Warehouse\BarangPalletController;
use App\Http\Middleware\LogHomeAccess;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Maintenance\SuhuController;
use App\Http\Controllers\Maintenance\MaintenanceController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HRISController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\HRDController;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Karyawan;
use App\Models\NonKaryawan;
use Illuminate\Support\Arr;


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
// Route::get('/login', function () {
//     return redirect()->route('home');
// })->name('login');

Route::middleware(['auth'])->group(function () {
    // Redirect dari root ke home
    Route::get('/', function () {
        return redirect()->route('home');
    });

    // Jadikan hris/list sebagai home yang bisa diakses dengan /home atau /hris
    Route::get('/home', [HRISController::class, 'index'])->name('home');

    // Route untuk profile karyawan
    Route::get('/profile', function () {
        return view('profile.karyawan');
    })->name('profile.karyawan')->middleware('auth');

    // HRIS Menu Routes
    Route::prefix('hris')->name('hris.')->middleware(['auth'])->group(function () {
        Route::get('/form', [HRISController::class, 'create'])->name('form');
        Route::post('/store', [HRISController::class, 'store'])->name('store');
        Route::get('/detail/{id}', [HRISController::class, 'show'])->name('detail');
        Route::get('/edit/{id}', [HRISController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [HRISController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [HRISController::class, 'destroy'])->name('destroy');
        Route::get('/download/{id}', [HRISController::class, 'download'])->name('download');
        Route::put('/send/{id}', [HRISController::class, 'send'])->name('send');
    });

    Route::prefix('hrd')->name('hrd.')->middleware(['auth'])->group(function () {
        Route::middleware(['check.menu.access:HRD'])->group(function () {
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
        
        Route::get('/surat', [HRDController::class, 'showSurat'])->name('list-surat');
        Route::get('/detail/{id}', [HRDController::class, 'detail'])->name('detail');
        Route::get('/download/{id}', [HRDController::class, 'download'])->name('download');
        });
    }); 

    // Finance Routes
    Route::prefix('finance')->name('finance.')->middleware(['check.menu.access:Finance'])->group(function () {
        Route::get('/surat', [FinanceController::class, 'index'])->name('list-surat');
        Route::get('/detail/{id}', [FinanceController::class, 'detail'])->name('detail');
        Route::get('/download/{id}', [FinanceController::class, 'download'])->name('download');
    });
    // }); 

    // Warehouse Menu Routes
    Route::prefix('warehouse')->name('warehouse.')->middleware(['auth'])->group(function () {
        Route::middleware(['check.menu.access:Warehouse'])->group(function () { 
            // CS01 routes
            Route::middleware(['check.submenu.access:CS01'])->group(function () {
                Route::get('/product-cs01', [BarangController::class, 'index'])->name('product-list2'); //sidebar
                Route::get('/barangpallet/cs01/list', [BarangPalletController::class, 'listBarangPallet'])
                    ->name('barangpallet-cs01-list');
                
                Route::get('/barangpallet/cs01/list/{room}', [BarangPalletController::class, 'listBarangPallet'])
                    ->name('barangpallet-cs01-list.room');

                Route::middleware(['check.admin.status'])->group(function () {
                    Route::get('/barangpallet/cs01/{id?}', [BarangPalletController::class, 'barangpalletCS01'])
                        ->name('barangpallet-cs01');
                });
            });

            // CS02 routes  
            Route::middleware(['check.submenu.access:CS02'])->group(function () {
                Route::get('/product-cs02', [BarangController::class, 'index'])->name('product-list');
                Route::get('/barangpallet/cs02/list', [BarangPalletController::class, 'listBarangPallet'])
                    ->name('barangpallet-cs02-list');
                
                Route::get('/barangpallet/cs02/list/{room}', [BarangPalletController::class, 'listBarangPallet'])
                    ->name('barangpallet-cs02-list.room');

                Route::middleware(['check.admin.status'])->group(function () {
                    Route::get('/barangpallet/cs02/{id?}', [BarangPalletController::class, 'barangpalletCS02'])
                        ->name('barangpallet-cs02');
                });
            });

            // Form routes Barang dapat diakses semua user dengan akses Warehouse dengan status Administrator atau Super Admin
            Route::middleware(['check.admin.status'])->group(function () {
                Route::post('/product', [BarangController::class, 'store'])->name('product.store');
                Route::get('/product/{id}/edit', [BarangController::class, 'edit'])->name('product.edit');
                Route::put('/product/{id}', [BarangController::class, 'update'])->name('product.update');
                Route::delete('/product/{barang}', [BarangController::class, 'destroy'])->name('product.destroy');
                Route::post('/product/import', [BarangController::class, 'import'])->name('product.import');
                Route::get('/product/create', [BarangController::class, 'create'])->name('product-form');
            });

            // Import routes - hanya untuk Administrator dan Super Admin dengan beberapa persyaratan khusus yang memberikan perbedaan pada dropdown tempat
            Route::middleware(['check.admin.status'])->group(function () {
                Route::post('/barangpallet/import', [BarangPalletController::class, 'import'])->name('barangpallet.import');
            });

            // Fitur-fitur yang dapat diakses semua users dengan akses Warehouse
            Route::get('/api/search', [BarangController::class, 'searchRecommendations'])
            ->name('api.search');

            // Barang Pallet Search API
            Route::get('/barangpallet/search', [BarangPalletController::class, 'searchBarangPallet'])
                ->name('barangpallet.search');

            // Dalam group route warehouse
            Route::get('/barangpallet/search-barang', [BarangPalletController::class, 'searchBarang'])
                ->name('barangpallet.search-barang');

            // Fitur-fitur yang hanya dapat diakses oleh Administrator atau Super Admin
            Route::middleware(['check.admin.status'])->group(function () {
                Route::post('/barangpallet', [BarangPalletController::class, 'store'])
                    ->name('barangpallet.store');

                Route::get('/barangpallet/{id}/edit', [BarangPalletController::class, 'edit'])
                    ->name('barangpallet.edit');

                Route::put('/barangpallet/{id}', [BarangPalletController::class, 'update'])
                    ->name('barangpallet.update');
                    
                Route::delete('/barangpallet/{id}', [BarangPalletController::class, 'destroy'])
                    ->name('barangpallet.destroy');

                // Tambahkan route untuk delete multiple
                Route::delete('barangpallet/destroy-multiple', [BarangPalletController::class, 'destroyMultiple'])
                    ->name('barangpallet.destroy-multiple');

                // API route untuk mendapatkan daftar ruangan berdasarkan tempat, digunakan di Barang Pallet Form
                Route::get('/api/ruangan/{tempat}', function ($tempat) {
                    return App\Models\Tempat::where('nama', strtoupper($tempat))
                        ->whereNotNull('ruangan')
                            ->pluck('ruangan');
                })->name('api.ruangan');
            });
        });
        
    }); 

    Route::prefix('suhu')->name('suhu.')->middleware(['auth'])->group(function () {
        Route::middleware(['check.menu.access:Suhu'])->group(function () {
            Route::get('/list', [SuhuController::class, 'index'])->name('list'); //sidebar
            // Form routes - hanya untuk Administrator
            Route::middleware(['check.admin.status'])->group(function () {
                Route::get('/create', [SuhuController::class, 'create'])->name('create'); //sidebar
                Route::post('/list', [SuhuController::class, 'store'])->name('store');
                Route::get('/{suhu}/edit', [SuhuController::class, 'edit'])->name('edit');
                Route::put('/{suhu}', [SuhuController::class, 'update'])->name('update'); //form
                Route::delete('/{suhu}', [SuhuController::class, 'destroy'])->name('destroy');
            });
        });
    });

    // Maintenance Routes
    Route::prefix('maintenance')->name('maintenance.')->middleware(['auth'])->group(function () {
        Route::middleware(['check.menu.access:Maintenance'])->group(function () {
            Route::get('/form', function () {
                return view('maintenance.facility-form');
            })->name('form');
        
            Route::get('/list', function () {
                return view('maintenance.facilityroom');
            })->name('list');
        });
    });

    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        
        Route::get('/superadmin', [AdminController::class, 'index'])->name('superadmin');
        Route::get('/editform/{id}', [AdminController::class, 'editForm'])->name('editform');
        Route::post('/update-status', [AdminController::class, 'updateStatus'])->name('users.status');
        Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('destroy');
        Route::get('/add-users', [AdminController::class, 'showAddUserForm'])->name('add-users');
        Route::post('/add-users', [AdminController::class, 'storeUser'])->name('store-user');
        Route::put('/users/{id}/status', [AdminController::class, 'updateStatus'])->name('update-status');
        Route::post('/update-menu-access', [AdminController::class, 'updateMenuAccess'])->name('update-menu-access');
    }); 

    // Laporan Routes (dapat diakses semua user)
    Route::prefix('laporan')->name('laporan.')->middleware('auth')->group(function () {
        Route::middleware(['check.menu.access:CS01','check.menu.access:CS02'])->group(function () {
            Route::get('/barang', function () {
                return view('laporan.barang');
            })->name('barang');
            Route::post('/barang/export', [LaporanController::class, 'exportBarang'])->name('barang.export');
            Route::get('/barang', [LaporanController::class, 'barang'])->name('barang');
        });

        Route::middleware(['check.menu.access:CS01','check.menu.access:CS02'])->group(function () {
            Route::get('/pencatatan-pallet', function () {
                return view('laporan.pallet');
            })->name('pallet');
            Route::post('/pallet/export', [LaporanController::class, 'exportPallet'])->name('pallet.export');
            Route::get('/pallet', [LaporanController::class, 'pallet'])->name('pallet');
        });
        
        Route::middleware(['check.menu.access:Suhu'])->group(function () {
            Route::get('/suhu', function () {
                return view('laporan.suhu');
            })->name('suhu');
            Route::post('/suhu/export', [LaporanController::class, 'exportSuhu'])->name('suhu.export');
        });

        Route::middleware(['check.menu.access:Maintenance'])->group(function () {
            Route::get('/aset-fasilitas', function () {
                return view('laporan.aset-fasilitas');
            })->name('aset');
        });

        Route::middleware(['check.menu.access:Maintenance'])->group(function () {
            Route::get('/maintenance', function () {
                return view('laporan.maintenance');
            })->name('maintenance');
        });
    }); 

    // Menu Security
    Route::prefix('security')->name('security.')->middleware(['auth'])->group(function () { 
        Route::middleware(['check.menu.access:Security'])->group(function () {
            Route::get('/list', function(){
                return view('loglists',['title'=>'Security']);
            })->name('list-satpam');

        Route::get('/form-satpam', function(){
            return view('security',['title'=>'Form Satpam']);
            })->name('form-satpam');
        }); 
    }); 

    // Logout Route
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
}); 
