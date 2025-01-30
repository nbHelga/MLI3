<?php

namespace App\Http\Controllers\Warehouse;

use App\Models\Barang;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Imports\BarangImport;
use Maatwebsite\Excel\Facades\Excel;

class BarangController extends Controller
{
    private $sizeOrder = [
        'JUMBO', 'BOM', 'BESAR', 'TANGGUNG', 'KECIL', 'KOIN', 'LEMBUT', 
        'RICEK', 'MERCON', 'CUSTOM', '20 UP', '50 UP', 'UYER', 'RUT', 
        'KASAR', 'UKURAN'
    ];

    private $kualitasOrder = ['SP', 'KW', 'COP', 'CC', 'MT', 'PP', 'BS', 'DF', 'RUT'];

    public function getCategories()
    {
        $barang = Barang::all();
        
        // Opsi kualitas yang tetap/fix
        $kualitasOptions = [
            'SP', 'KW', 'COP', 'CC', 'MT', 'PP', 'BS', 'DF', 'RUT'
        ];
        
        // Opsi size awal (tanpa CUSTOM)
        $sizeOptions = [
            'JUMBO', 'BOM', 'BESAR', 'TANGGUNG', 'KECIL', 'KOIN', 
            'LEMBUT', 'RICEK', 'MERCON', '20 UP', '50 UP', 
            'UYER', 'RUT', 'KASAR'
        ];
        
        $categories = [
            'Kategori Ikan' => [
                'selectAll' => false,
                'items' => $barang->map(function($item) use ($barang) {
                        $kategori = explode(' ', $item->nama)[0];
                        $count = $barang->filter(function($b) use ($kategori) {
                            return str_starts_with($b->nama, $kategori);
                        })->count();
                        
                        // Hanya return jika count > 0
                        if ($count > 0) {
                            return [
                                'name' => $kategori,
                                'count' => $count,
                                'checked' => false
                            ];
                        }
                    })
                    ->filter() // Hapus nilai null
                    ->unique('name')
                    ->values()
            ],
            'Kualitas Ikan' => [
                'selectAll' => false,
                'items' => collect($kualitasOptions)
                    ->map(function($kualitas) use ($barang) {
                        return [
                            'name' => $kualitas,
                            'count' => $barang->where('kualitas', $kualitas)->count(),
                            'checked' => false
                        ];
                    })
                    ->push([
                        'name' => 'null',
                        'count' => $barang->whereNull('kualitas')->count(),
                        'checked' => false
                    ])
            ],
            'Size Ikan' => [
                'selectAll' => false,
                'items' => collect($sizeOptions)
                    ->map(function($size) use ($barang) {
                        return [
                            'name' => $size,
                            'count' => $barang->where('size', $size)->count(),
                            'checked' => false
                        ];
                    })
                    ->concat(
                        $barang->whereNotIn('size', $sizeOptions)
                            ->whereNotNull('size')
                            ->pluck('size')
                            ->unique()
                            ->map(function($size) use ($barang) {
                                return [
                                    'name' => $size,
                                    'count' => $barang->where('size', $size)->count(),
                                    'checked' => false
                                ];
                            })
                    )
                    ->push([
                        'name' => 'null',
                        'count' => $barang->whereNull('size')->count(),
                        'checked' => false
                    ])
            ]
        ];
        
        if (request()->ajax()) {
            return response()->json($categories);
        }
        
        return $categories;
    }

    public function index(Request $request)
    {
        $categories = $this->getCategories();
        $perPage = request()->get('per_page', 10); // Default 10 items per page
        $query = Barang::query();
        
        // Filter berdasarkan kategori yang dipilih
        if ($request->has('kategori')) {
            $kategori = $request->kategori;
            $query->where(function($q) use ($kategori) {
                foreach ($kategori as $kat) {
                    $q->orWhere('nama', 'like', $kat . '%');
                }
            });
        }
        
        // Filter berdasarkan kualitas yang dipilih
        if ($request->has('kualitas')) {
            $query->whereIn('kualitas', $request->kualitas);
        }
        
        // Filter berdasarkan size yang dipilih
        if ($request->has('size')) {
            $query->whereIn('size', $request->size);
        }
        
        // Filter berdasarkan pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }
        
        $barang = $query->paginate($perPage)->withQueryString();
        
        // Tentukan view berdasarkan route name
        $viewName = request()->route()->getName() === 'warehouse.product-list2' 
            ? 'warehouse.product-list2' 
            : 'warehouse.product-list';
        
        return view($viewName, compact('barang', 'categories', 'perPage'));
    }

    // API endpoint untuk search recommendations
    public function searchRecommendations(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $recommendations = Barang::where('nama', 'like', "%{$query}%")
            ->orWhere('kode', 'like', "%{$query}%")
            ->select([
                'kode',
                'nama',
                'kualitas',
                'size',
                DB::raw("CONCAT(kode, ' - ', nama) as text")
            ])
            ->orderBy('nama')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'kode' => $item->kode,
                    'nama' => $item->nama,
                    'text' => $item->text,
                    'kualitas' => $item->kualitas ?? '-',
                    'size' => $item->size ?? '-'
                ];
            });

        return response()->json($recommendations);
    }

    public function showForm()
    {
        return view('warehouse.product-form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:barang,kode',
            'nama' => 'required',
            'kualitas' => 'nullable',
            'size' => 'nullable',
            'jumlah' => 'nullable|integer|min:0'
        ], [
            'kode.unique' => 'Kode sudah digunakan',
            'kode.required' => 'Kode barang harus diisi',
            'nama.required' => 'Nama barang harus diisi'
            // 'jumlah.integer' => 'Jumlah harus berupa angka',
            // 'jumlah.min' => 'Jumlah minimal 0'
        ]);

        try {
            // Menggunakan timestamp dengan timezone Asia/Jakarta
            $data = $request->all();
            Barang::create($data);
            
            return back()->with('showDialog', true);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Your actions did not complete successfully. Please try again.');
        }
    }

    public function create()
    {
        return view('warehouse.product-form');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('warehouse.product-form', compact('barang'));
    }

    public function update(Request $request, $kode)
    {
        try {
            $barang = Barang::where('kode', $kode)->firstOrFail();
            
            // Validasi kode unik kecuali untuk kode yang sedang diupdate
            $existingBarang = Barang::where('kode', $request->kode)
                ->where('kode', '!=', $kode)
                ->first();
                
            if ($existingBarang) {
                // Redirect ke product list dengan pesan error
                session()->flash('error', 'Oops');
                return redirect()->route('warehouse.product-list');
            }

            // Update data
            $barang->update([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'kualitas' => $request->kualitas,
                'size' => $request->size,
                'jumlah' => $request->jumlah
            ]);
            
            // Flash message untuk update sukses
            session()->flash('update_success', true);
            
            // Redirect ke product list
            return redirect()->route('warehouse.product-list');
            
        } catch (\Exception $e) {
            // Redirect ke product list dengan pesan error
            session()->flash('error', 'Oops');
            return redirect()->route('warehouse.product-list');
        }
    }

    public function destroy($kode)
    {
        try {
            \Log::info('Attempting to delete barang with kode: ' . $kode);
            
            $barang = Barang::findOrFail($kode);
            $barang->delete();
            
            \Log::info('Successfully deleted barang with kode: ' . $kode);
            
            // Menentukan route redirect berdasarkan halaman asal
            return redirect()
                ->back()
                // ->route(request()->routeIs('warehouse.product-list2') ? 'warehouse.product-list2' : 'warehouse.product-list')
                ->with('delete_success', true);
            
        } catch (\Exception $e) {
            \Log::error('Error deleting barang: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', true);
        }
    }

    public function destroyMultiple(Request $request)
    {
        try {
            Log::info('Attempting to delete multiple barang: ', $request->kodes);
            
            Barang::whereIn('kode', $request->kodes)->delete();
            
            Log::info('Successfully deleted multiple barang');
            
            return redirect()
                ->back()
                ->with('delete_success', true);
            
        } catch (\Exception $e) {
            Log::error('Error deleting multiple barang: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', true);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            $import = new BarangImport();
            Excel::import($import, $request->file('file'));
            
            return redirect()
                ->back()
                ->with('import_success', true)
                ->with('import_count', $import->getSuccessCount());
            
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return redirect()
                ->back()
                ->with('import_error', true);
        }
    }
}
