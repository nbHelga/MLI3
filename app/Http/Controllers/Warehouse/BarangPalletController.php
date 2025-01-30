<?php

namespace App\Http\Controllers\Warehouse;

use App\Models\PencatatanBarangGudang;
use App\Models\Tempat;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
// use App\Models\Pallet;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Imports\PencatatanBarangGudangImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PencatatanBarangGudangExport;
use Illuminate\Support\Facades\DB;

class BarangPalletController extends Controller
{
    public function listBarangPallet(Request $request, $room = null)
    {
        try {
            // Determine tempat from route name
            $routeName = $request->route()->getName();
            $tempat = str_contains($routeName, 'cs01') ? 'CS01' : 
                     (str_contains($routeName, 'cs02') ? 'CS02' : 'MASAL');
            
            // Log::info('ListBarangPallet initial params:', [
            //     'tempat' => $tempat,
            //     'room' => $room,
            //     'request_all' => $request->all()
            // ]);

            // Base query with relationships
            $query = PencatatanBarangGudang::with(['barang', 'tempat', 'employee'])
                ->whereHas('tempat', function($q) use ($tempat) {
                    $q->where('nama', $tempat);
                });

            // Get rooms for tabs (hanya jika tempat bukan MASAL)
            $rooms = [];
            if ($tempat !== 'MASAL') {
                $rooms = Tempat::where('nama', $tempat)
                    ->whereNotNull('ruangan')
                    ->pluck('ruangan')
                    ->filter()
                    ->values()
                    ->toArray();
                
                // Add 'all' option at the beginning
                array_unshift($rooms, 'all');

                // Apply room filter if specified
                if ($room && strtolower($room) !== 'all') {
                    $query->whereHas('tempat', function($q) use ($room) {
                        $q->where('ruangan', str_replace('-', ' ', $room));
                    });
                }
            }

            // Apply search using searchBarangPallet logic
            if ($request->filled('search')) {
                $searchQuery = $request->search;
                $query->where(function($q) use ($searchQuery) {
                    $q->where('kode_pallet', 'like', '%' . strtoupper($searchQuery) . '%')
                      ->orWhereHas('barang', function($sq) use ($searchQuery) {
                          $sq->where('kode', 'like', '%' . strtoupper($searchQuery) . '%')
                            ->orWhere('nama', 'like', '%' . $searchQuery . '%')
                            ->orWhere('kualitas', 'like', '%' . $searchQuery . '%')
                            ->orWhere('size', 'like', '%' . $searchQuery . '%');
                      })
                      ->orWhereHas('employee', function($sq) use ($searchQuery) {
                          $sq->where('nama', 'like', '%' . $searchQuery . '%');
                      });
                });
            }

            // Apply filters from request
            if ($request->has('pallet')) {
                $palletPrefixes = (array)$request->pallet;
                $query->where(function($q) use ($palletPrefixes) {
                    foreach ($palletPrefixes as $prefix) {
                        $q->orWhere('kode_pallet', 'like', $prefix . '%');
                    }
                });
            }

            if ($request->has('kategori')) {
                $kategori = (array)$request->kategori;
                $query->whereHas('barang', function($q) use ($kategori) {
                    $q->whereIn('nama', $kategori);
                });
            }

            if ($request->has('kualitas')) {
                $kualitas = (array)$request->kualitas;
                $query->whereHas('barang', function($q) use ($kualitas) {
                    $q->whereIn('kualitas', $kualitas);
                });
            }

            if ($request->has('size')) {
                $size = (array)$request->size;
                $query->whereHas('barang', function($q) use ($size) {
                    $q->whereIn('size', $size);
                });
            }

            // Get paginated results
            $perPage = $request->get('per_page', 10);
            $barangPallets = $query->paginate($perPage)->withQueryString();
            
            // Log::info('Result count:', [
            //     'total' => $barangPallets->total(),
            //     'current_page' => $barangPallets->currentPage()
            // ]);

            // Get all data for categories (tanpa pagination)
            $allData = clone $query;
            $allDataResults = $allData->get();
            
            // Apply filters after getting categories data
            if ($request->filled('pallet')) {
                $palletPrefixes = (array)$request->pallet;
                $query->where(function($q) use ($palletPrefixes) {
                    foreach ($palletPrefixes as $prefix) {
                        $q->orWhere('kode_pallet', 'like', $prefix . '%');
                    }
                });
            }

            // Prepare categories data
            $categories = $this->getCategories($allDataResults);

            // Get unique pallet prefixes for dropdown
            $palletCodes = $allDataResults->pluck('kode_pallet')
                ->map(function($code) {
                    return substr($code, 0, 1);
                })
                ->unique()
                ->values()
                ->all();

            // Determine view based on tempat
            $view = 'warehouse.barangpallet-list-' . strtolower($tempat);

            return view($view, compact(
                'barangPallets',
                'categories',
                'rooms',
                'room',
                'perPage',
                'tempat',
                'palletCodes'
            ));

        } catch (\Exception $e) {
            // Log::error('Error in listBarangPallet:', [
            //     'message' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString()
            // ]);
            
            return back()->with('error', true);
        }
    }

    public function barangpalletCS01($id = null)
    {
        try {
            $tempat = 'cs01';
            $kodeBarang = Barang::pluck('kode');
            $barangPallet = null;

            if ($id) {
                $barangPallet = PencatatanBarangGudang::with(['barang', 'tempat'])
                    ->findOrFail($id);
            }

            // Log::info('Form CS01 data:', [
            //     'id' => $id,
            //     'barangPallet' => $barangPallet ? $barangPallet->toArray() : null,
            //     'kodeBarangCount' => $kodeBarang->count()
            // ]);

            return view('warehouse.barangpallet-cs01', [
                'tempat' => 'cs01',
                'kodeBarang' => $kodeBarang,
                'barangPallet' => $barangPallet
            ])->with('add_success', true);
        } catch (\Exception $e) {
            // Log::error('Error showing CS01 form:', [
            //     'message' => $e->getMessage()
            // ]);
            throw $e;
        }
    }

    public function barangpalletCS02($id = null)
    {
        try {
            $tempat = 'cs02';
            $kodeBarang = Barang::pluck('kode');
            $barangPallet = null;

            if ($id) {
                $barangPallet = PencatatanBarangGudang::with(['barang', 'tempat'])
                    ->findOrFail($id);
            }

            return view('warehouse.barangpallet-cs02', [
                'tempat' => 'cs02',
                'kodeBarang' => $kodeBarang,
                'barangPallet' => $barangPallet
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing CS02 form:', [
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function barangpalletMasal($id = null)
    {
        try {
            $tempat = 'masal';
            $kodeBarang = Barang::pluck('kode');
            $barangPallet = null;

            if ($id) {
                $barangPallet = PencatatanBarangGudang::with(['barang', 'tempat'])
                    ->findOrFail($id);
            }

            return view('warehouse.barangpallet-masal', [
                'tempat' => 'masal',
                'kodeBarang' => $kodeBarang,
                'barangPallet' => $barangPallet
            ]);
        } catch (\Exception $e) {
            return back()->with('error', true);
        }
    }

    private function getCategories($data)
    {
        // Group data by pallet prefix
        $palletCategories = $data->groupBy(function($item) {
            return substr($item->kode_pallet, 0, 1);
        })
        ->map(function($items, $prefix) {
            return [
                'name' => $prefix,
                'count' => $items->count(),
                'checked' => in_array($prefix, (array)request('pallet', []))
            ];
        })
        ->values();

        return [
            'Pallet' => [
                'selectAll' => false,
                'items' => $palletCategories
            ],
            'Kategori Ikan' => [
                'selectAll' => false,
                'items' => $this->getKategoriItems($data)
            ],
            'Kualitas Ikan' => [
                'selectAll' => false,
                'items' => $this->getKualitasItems($data)
            ],
            'Size Ikan' => [
                'selectAll' => false,
                'items' => $this->getSizeItems($data)
            ]
        ];
    }

    public function index(Request $request)
    {
        $query = PencatatanBarangGudang::with(['tempat', 'barang', 'employee']);
        
        // Filter berdasarkan tempat (CS01/CS02)
        $tempat = strpos($request->route()->getName(), 'cs01') !== false ? 'CS01' : 'CS02';
        $query->whereHas('tempat', function($q) use ($tempat) {
            $q->where('nama', $tempat);
        });

        // Filter berdasarkan ruangan
        if ($request->room && $request->room !== 'all') {
            $query->whereHas('tempat', function($q) use ($request) {
                $q->where('ruangan', $request->room);
            });
        }

        // Search yang lebih komprehensif
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_pallet', 'like', "%{$search}%")
                  ->orWhereHas('barang', function($sq) use ($search) {
                      $sq->where('nama', 'like', "%{$search}%")
                        ->orWhere('kode', 'like', "%{$search}%");
                  });
            });
        }

        $barangPallets = $query->paginate($request->input('perPage', 10));
        
        // Get categories for filter
        $categories = $tempat === 'CS02' ? [
            'pallet' => PencatatanBarangGudang::distinct('kode_pallet')->pluck('kode_pallet'),
            'kategori' => Barang::distinct('kategori')->pluck('kategori'),
            'kualitas' => Barang::distinct('kualitas')->pluck('kualitas'),
            'size' => Barang::distinct('size')->pluck('size'),
        ] : [];

        return view('warehouse.barangpallet-list', [
            'barangPallets' => $barangPallets,
            'rooms' => ['Room 1', 'Room 2', 'Room 3', 'Room 4'],
            'room' => $request->room ?? 'all',
            'tempat' => $tempat,
            'categories' => $categories,
            'perPage' => $request->input('perPage', 10),
        ]);
    }

    public function store(Request $request)
    {
        try {
            // Validate basic input
            $validator = Validator::make($request->all(), [
                'tempat' => 'required|in:cs01,cs02,masal', // validasi tempat untuk form, kalau tambah tempat harus tambah validasi disini
                'ruangan' => 'required|string',
                'kode_pallet' => ['required', 'string', 'size:5'],
                'id_barang' => 'required|exists:barang,kode'
            ], [
                'ruangan.required' => 'Ruangan harus dipilih',
                'kode_pallet.required' => 'Kode pallet harus diisi',
                'kode_pallet.size' => 'Kode pallet harus berukuran 5 karakter',
                'id_barang.required' => 'Barang harus dipilih'
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Get tempat_id based on ruangan and tempat (cs01/cs02)
            $tempat = Tempat::where('nama', strtoupper($request->tempat))
                           ->where('ruangan', $request->ruangan)
                           ->first();

            if (!$tempat) {
                throw new \Exception('Invalid room selection');
            }

            // Check existing combinations
            $existingPallet = PencatatanBarangGudang::where(function($query) use ($request, $tempat) {
                // Check kode_pallet and id_tempat combination
                $query->where('kode_pallet', $request->kode_pallet)
                      ->where('id_tempat', $tempat->id);
            })->first();

            if ($existingPallet) {
                $errors = [];
                
                // Hanya tambahkan error untuk field yang sudah diisi
                // if ($request->filled('ruangan')) {
                //     $errors['ruangan'] = "Ruangan {$tempat->ruangan} sudah memiliki kode pallet {$request->kode_pallet}";
                // }
                
                // if ($request->filled('kode_pallet')) {
                //     $errors['kode_pallet'] = "Kode pallet {$request->kode_pallet} sudah digunakan di {$tempat->ruangan}";
                // }
                
                // if ($request->filled('id_barang')) {
                //     $errors['id_barang'] = "Barang {$existingPallet->barang->id} sudah terdaftar dengan kode pallet {$request->kode_pallet} di {$tempat->ruangan}";
                // }

                return back()
                    ->withErrors($errors)
                    ->with('error_message', "Pencatatan Barang Gudang dengan kode {$request->kode_pallet} telah digunakan di {$tempat->ruangan} untuk barang {$existingPallet->barang->kode}")
                    ->withInput();
            }

            // Create new record
            PencatatanBarangGudang::create([
                'kode_pallet' => strtoupper($request->kode_pallet),
                'id_barang' => $request->id_barang,
                'id_employees' => auth()->user()->employee->id,
                'id_tempat' => $tempat->id
            ]);

            return redirect()
                ->back()
                ->with('showDialog', true);
                // ->with('add_success', true);

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', true);
        }
    }

    public function edit($id)
    {
        try {
            $barangPallet = PencatatanBarangGudang::findOrFail($id);
            
            // Tampilkan dialog konfirmasi
            return view('warehouse.barangpallet-edit', compact('barangPallet'));
        } catch (\Exception $e) {
            return back()->with('error', 'Data tidak ditemukan');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $pencatatan = PencatatanBarangGudang::findOrFail($id);
            
            // Validate basic input
            $request->validate([
                'tempat' => 'required|in:cs01,cs02',
                'ruangan' => 'required|string',
                'kode_pallet' => ['required', 'string', 'size:5'],
                'id_barang' => 'required|exists:barang,kode'
            ]);

            // Get tempat_id based on ruangan and tempat (cs01/cs02)
            $tempat = Tempat::where('nama', strtoupper($request->tempat))
                           ->where('ruangan', $request->ruangan)
                           ->first();

            if (!$tempat) {
                throw new \Exception('Invalid room selection');
            }

            // Check existing combinations (excluding current record)
            $existingPallet = PencatatanBarangGudang::where(function($query) use ($request, $tempat) {
                // Check kode_pallet and id_tempat combination
                $query->where('kode_pallet', $request->kode_pallet)
                      ->where('id_tempat', $tempat->id);
            })
            ->where('id', '!=', $id)
            ->first();

            if ($existingPallet) {
                // \Log::info('Update failed - Existing pallet found:', [
                //     'kode_pallet' => $request->kode_pallet,
                //     'tempat' => $tempat->nama,
                //     'ruangan' => $tempat->ruangan,
                //     'existing_barang' => $existingPallet->barang->id
                // ]);

                // Redirect ke form dengan data yang diperlukan
                return redirect()
                    ->route('warehouse.barangpallet-' . strtolower($request->tempat), ['id' => $id])
                    ->with('update_error', true)
                    ->with('error_message', "Pencatatan Barang Gudang dengan kode {$request->kode_pallet} telah digunakan di {$tempat->ruangan} untuk barang {$existingPallet->barang->kode}")
                    ->withInput();
            }

            // Update record
            $pencatatan->update([
                'kode_pallet' => strtoupper($request->kode_pallet),
                'id_barang' => $request->id_barang,
                'id_tempat' => $tempat->id
            ]);

            // \Log::info('Update success:', [
            //     'id' => $id,
            //     'kode_pallet' => $request->kode_pallet,
            //     'tempat' => $tempat->nama,
            //     'ruangan' => $tempat->ruangan
            // ]);

            // Redirect ke list dengan message success
            return redirect()
                ->route('warehouse.barangpallet-' . strtolower($request->tempat) . '-list')
                ->with('update_success', true);

        } catch (\Exception $e) {
            // \Log::error('Error updating data:', [
            //     'id' => $id,
            //     'message' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString()
            // ]);

            return redirect()
                ->route('warehouse.barangpallet-' . strtolower($request->tempat), ['id' => $id])
                ->with('error', 'Your actions did not complete successfully. Please try again.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $barangPallet = PencatatanBarangGudang::findOrFail($id);
            $tempat = strtolower($barangPallet->tempat->nama);
            
            $barangPallet->delete();
            
            return redirect()
                ->route('warehouse.barangpallet-' . $tempat . '-list')
                ->with('delete_success', true);
            
        } catch (\Exception $e) {
            // $tempat = isset($barangPallet) ? strtolower($barangPallet->tempat->nama) : 'default'; // Gunakan 'default' atau nilai lain yang sesuai jika $barangPallet tidak ada
            
            return redirect()
                ->back()
                ->with('error', true);
        }
    }

    // Metode helper untuk filter
    private function applyFilters($query, $request)
    {
        // Filter Kode Pallet
        if ($request->has('kode_pallet')) {
            $kodePallet = $request->kode_pallet;
            $query->where(function($q) use ($kodePallet) {
                foreach ($kodePallet as $kode) {
                    $q->orWhere('kode_pallet', 'like', $kode . '%');
                }
            });
        }

        // Filter Kategori
        if ($request->has('kategori')) {
            $kategori = $request->kategori;
            $query->whereHas('barang', function($q) use ($kategori) {
                $q->where(function($sq) use ($kategori) {
                    foreach ($kategori as $kat) {
                        $sq->orWhere('nama', 'like', $kat . '%');
                    }
                });
            });
        }

        // Filter Kualitas
        if ($request->has('kualitas')) {
            $query->whereHas('barang', function($q) use ($request) {
                $q->whereIn('kualitas', $request->kualitas);
            });
        }

        // Filter Size
        if ($request->has('size')) {
            $query->whereHas('barang', function($q) use ($request) {
                $q->whereIn('size', $request->size);
            });
        }
    }

    // Metode untuk search API
    public function searchBarangPallet(Request $request)
    {
        $query = $request->get('q');
        $tempat = $request->get('tempat');

        if (!$query) {
            return response()->json([]);
        }

        return PencatatanBarangGudang::with(['barang', 'tempat', 'employee'])
            ->whereHas('tempat', function($q) use ($tempat) {
                $q->where('nama', strtoupper($tempat));
            })
            ->where(function($q) use ($query) {
                $q->where('kode_pallet', 'like', '%' . strtoupper($query) . '%')
                  ->orWhereHas('barang', function($sq) use ($query) {
                      $sq->where('kode', 'like', '%' . strtoupper($query) . '%')
                        ->orWhere('nama', 'like', '%' . $query . '%')
                        ->orWhere('kualitas', 'like', '%' . $query . '%')
                        ->orWhere('size', 'like', '%' . $query . '%');
                  })
                  ->orWhereHas('employee', function($sq) use ($query) {
                      $sq->where('nama', 'like', '%' . $query . '%');
                  });
            })
            ->take(10)
            ->get();
    }

    // Search Kode Barang untuk Form Barang Gudang
    public function searchBarang(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {
            return response()->json([]);
        }

        $barang = Barang::where(function($q) use ($query) {
            $q->where('kode', 'like', '%' . strtoupper($query) . '%')
              ->orWhere('nama', 'like', '%' . $query . '%')
              ->orWhere('kualitas', 'like', '%' . $query . '%')
              ->orWhere('size', 'like', '%' . $query . '%');
        })
        ->orderBy('kode')
        ->get();

        // Log untuk debugging
        Log::info('Search Query:', [
            'input' => $query,
            'results_count' => $barang->count(),
            'results' => $barang->toArray()
        ]);

        return response()->json($barang);
    }

    protected function getKualitasItems($data)
    {
        return $data->map(function($item) {
            return [
                'kualitas' => $item->barang->kualitas,
                'id' => $item->barang->id
            ];
        })
        ->groupBy('kualitas')
        ->map(function($items, $kualitas) {
            return [
                'name' => $kualitas,
                'count' => $items->count(),
                'checked' => in_array($kualitas, request('kualitas', []))
            ];
        })
        ->values();
    }

    protected function getKategoriItems($data)
    {
        return $data->map(function($item) {
            return [
                'nama' => $item->barang->nama,
                'id' => $item->barang->id
            ];
        })
        ->groupBy('nama')
        ->map(function($items, $nama) {
            return [
                'name' => $nama,
                'count' => $items->count(),
                'checked' => in_array($nama, request('kategori', []))
            ];
        })
        ->values();
    }

    protected function getSizeItems($data)
    {
        return $data->map(function($item) {
            return [
                'size' => $item->barang->size,
                'id' => $item->barang->id
            ];
        })
        ->groupBy('size')
        ->map(function($items, $size) {
            return [
                'name' => $size,
                'count' => $items->count(),
                'checked' => in_array($size, request('size', []))
            ];
        })
        ->values();
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            $import = new PencatatanBarangGudangImport();
            Excel::import($import, $request->file('file'));
            
            return redirect()
                ->back()
                ->with('import_success', true)
                ->with('import_count', $import->getSuccessCount());
                
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return redirect()
                ->back()
                ->with('import_error', true);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('import_error', true);
        }
    }

    // public function export(Request $request)
    // {
    //     $filters = json_decode($request->filters, true) ?? [];
    //     $sorts = json_decode($request->sorts, true) ?? [];
        
    //     $query = PencatatanBarangGudang::query()
    //         ->with(['tempat', 'employee']);
        
    //     // Group filters by type
    //     $groupedFilters = collect($filters)->groupBy('type');
        
    //     // Apply filters
    //     foreach ($groupedFilters as $type => $typeFilters) {
    //         switch ($type) {
    //             case 'tanggal':
    //                 $dates = $typeFilters->pluck('value')->toArray();
    //                 $query->where(function($q) use ($dates) {
    //                     foreach ($dates as $date) {
    //                         $q->orWhereDate('created_at', $date);
    //                     }
    //                 });
    //                 break;
    //             case 'ruangan':
    //                 $rooms = $typeFilters->pluck('value')->toArray();
    //                 $query->whereHas('tempat', function ($q) use ($rooms) {
    //                     $q->whereIn('ruangan', $rooms);
    //                 });
    //                 break;
    //             case 'pallet':
    //                 $palletPrefixes = $typeFilters->pluck('value')->toArray();
    //                 $query->where(function ($q) use ($palletPrefixes) {
    //                     foreach ($palletPrefixes as $prefix) {
    //                         $q->orWhere('kode_pallet', 'like', $prefix . '%');
    //                     }
    //                 });
    //                 break;
    //         }
    //     }
        
    //     // Apply sorts
    //     if (!empty($sorts)) {
    //         foreach ($sorts as $sort) {
    //             switch ($sort['value']) {
    //                 case 'tanggal':
    //                     $query->orderBy(DB::raw('DATE(created_at)'));
    //                     break;
    //                 case 'ruangan':
    //                     $query->orderBy('id_tempat');
    //                     break;
    //                 case 'pallet':
    //                     $query->orderBy('kode_pallet');
    //                     break;
    //             }
    //         }
    //     } else {
    //         $query->orderBy('id'); // Default sorting
    //     }
        
    //     $data = $query->get();
        
    //     $fileName = date('Y-m-d') . '_Daftar_Pencatatan_Ikan_Dan_Kode_Pallet_' . strtoupper($request->tempat);
        
    //     if($request->format === 'excel') {
    //         return Excel::download(new PencatatanBarangGudangExport($data), $fileName . '.xlsx');
    //     } 

    // }
}
