<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Models\Suhu;
use App\Models\Tempat;
use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Exports\SuhuExport;

class SuhuController extends Controller
{
    public function index(Request $request)
    {
        $query = Suhu::with(['tempat', 'employee']);
        
        // Filter berdasarkan tanggal
        $tanggal = $request->tanggal ?? Carbon::now()->format('Y-m-d');
        $query->whereDate('created_at', $tanggal);
        
        // Filter berdasarkan parent tab (CS01/CS02)
        if ($request->tempat && $request->tempat !== 'all') {
            $query->whereHas('tempat', function($q) use ($request) {
                $q->where('nama', strtoupper($request->tempat));
            });
        }

        // Filter berdasarkan child tab (Room)
        if ($request->ruangan && $request->ruangan !== 'all') {
            $query->whereHas('tempat', function($q) use ($request) {
                $q->where('ruangan', $request->ruangan);
            });
        }

        // Filter berdasarkan search
        if ($request->search && $request->category) {
            switch(strtolower($request->category)) {
                case 'ruangan':
                    $query->whereHas('tempat', function($q) use ($request) {
                        $q->where('ruangan', 'like', "%{$request->search}%");
                    });
                    break;
                case 'suhu':
                    $query->where('suhu', 'like', "%{$request->search}%");
                    break;
                case 'keterangan':
                    $query->where('keterangan', 'like', "%{$request->search}%");
                    break;
            }
        }

        // Filter berdasarkan keterangan
        if ($request->has('keterangan')) {
            $query->whereIn('keterangan', $request->keterangan);
        }

        // Filter berdasarkan PIC
        if ($request->has('pic')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->whereIn('nama', $request->pic);
            });
        }

        // Ambil nilai perPage dari request atau gunakan default 10
        $perPage = $request->input('perPage', 10);
        $suhuList = $query->latest()->paginate($perPage);

        return view('maintenance.suhu-list-all', [
            'suhuList' => $suhuList,
            'perPage' => $perPage,
            'categories' => [
                'PIC' => [
                    'selectAll' => false,
                    'items' => Employees::whereHas('suhu')
                        ->get()
                        ->map(fn($employee) => [
                            'name' => $employee->nama,
                            'count' => $employee->suhu()->count(),
                            'checked' => false
                        ])
                ],
                'Keterangan' => [
                    'selectAll' => false,
                    'items' => Suhu::select('keterangan')
                        ->distinct()
                        ->get()
                        ->map(fn($suhu) => [
                            'name' => $suhu->keterangan,
                            'count' => Suhu::where('keterangan', $suhu->keterangan)->count(),
                            'checked' => false
                        ])
                ]
            ]
        ]);
    }

    public function create()
    {
        return view('maintenance.suhu-form-all');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'tempat' => [
                'required',
                Rule::in(['cs01', 'cs02', 'masal'])
            ],  
            'ruangan' => [
                'required',
                Rule::exists('tempat', 'ruangan')
                    ->where(function ($query) use ($request) {
                        $query->where('nama', strtoupper($request->tempat));
                    })
            ],
            'suhu' => 'required|numeric',
            'keterangan' => 'required',
            'gambar' => 'nullable|image|max:10240'
        ], [
            'ruangan.exists' => 'Kombinasi tempat dan ruangan tidak valid.',
            'tempat.in' => 'Tempat hanya boleh CS01, CS02 atau MASAL.'
        ]);

        // Ambil id_tempat berdasarkan tempat dan ruangan yang dipilih
        $tempat = Tempat::where('nama', strtoupper($request->tempat))
                        ->where('ruangan', $request->ruangan)
                        ->first();

        if (!$tempat) {
            return back()->withErrors(['ruangan' => 'Kombinasi tempat dan ruangan tidak valid.']);
        }

        // Siapkan data untuk disimpan
        $data = [
            'id_tempat' => $tempat->id,
            'suhu' => $request->suhu,
            'keterangan' => $request->keterangan === 'custom' ? $request->custom_keterangan : $request->keterangan,
            'id_employees' => auth()->user()->id,
            'jam' => Carbon::now('Asia/Jakarta')->format('H:i') // Buat waktu Asia / Jakarta
        ];

        // Handle upload gambar jika ada
        if ($request->hasFile('gambar')) {
            try {
                $file = $request->file('gambar');
                $date = Carbon::now()->format('Y-m-d');
                $filename = time() . '_' . $file->getClientOriginalName();
                
                // Pastikan direktori ada
                if (!Storage::disk('public')->exists("suhu/$date")) {
                    Storage::disk('public')->makeDirectory("suhu/$date");
                }
                
                $path = $file->storeAs("suhu/$date", $filename, 'public');
                $data['gambar'] = $path;
            } catch (\Exception $e) {
                // Log::error('Error saat upload gambar: ' . $e->getMessage());
                return back()->withErrors(['gambar' => 'Gagal mengupload gambar. Silakan coba lagi.']);
            }
        }

        // Simpan data
        Suhu::create($data);

        return redirect()
            ->route('maintenance.suhu.list')
            ->with('add_success', true);
    }

    public function edit(Suhu $suhu)
    {
        return view('maintenance.suhu-form-all', compact('suhu'));
    }

    public function update(Request $request, Suhu $suhu)
    {
        // Validasi input
        $request->validate([
            'tempat' => [
                'required',
                Rule::in(['cs01', 'cs02'])
            ],
            'ruangan' => [
                'required',
                Rule::exists('tempat', 'ruangan')
                    ->where(function ($query) use ($request) {
                        $query->where('nama', strtoupper($request->tempat));
                    })
            ],
            'suhu' => 'required|numeric',
            'keterangan' => 'required',
            'gambar' => 'nullable|image|max:10240'
        ], [
            'ruangan.exists' => 'Kombinasi tempat dan ruangan tidak valid.',
            'tempat.in' => 'Tempat hanya boleh CS01 atau CS02.'
        ]);

        // Ambil id_tempat berdasarkan tempat dan ruangan yang dipilih
        $tempat = Tempat::where('nama', strtoupper($request->tempat))
                        ->where('ruangan', $request->ruangan)
                        ->first();

        if (!$tempat) {
            return back()->withErrors(['ruangan' => 'Kombinasi tempat dan ruangan tidak valid.']);
        }

        $data = [
            'id_tempat' => $tempat->id,
            'suhu' => $request->suhu,
            'keterangan' => $request->keterangan === 'custom' ? $request->custom_keterangan : $request->keterangan,
            'id_employees' => auth()->user()->id,
            'jam' => now()->format('H:i:s') // Update jam saat update data
        ];

        // Handle gambar jika ada
        if ($request->hasFile('gambar')) {
            try {
                // Hapus gambar lama jika ada
                if ($suhu->gambar) {
                    Storage::disk('public')->delete($suhu->gambar);
                }

                $file = $request->file('gambar');
                $date = now()->format('Y-m-d');
                $filename = time() . '_' . $file->getClientOriginalName();
                
                // Pastikan direktori ada
                if (!Storage::disk('public')->exists("suhu/$date")) {
                    Storage::disk('public')->makeDirectory("suhu/$date");
                }
                
                $path = $file->storeAs("suhu/$date", $filename, 'public');
                $data['gambar'] = $path;
            } catch (\Exception $e) {
                // Log::error('Error saat upload gambar: ' . $e->getMessage());
                return back()->withErrors(['gambar' => 'Gagal mengupload gambar. Silakan coba lagi.']);
            }
        }

        $suhu->update($data);

        return redirect()
            ->route('maintenance.suhu.list')
            ->with('update_success', true);
    }

    public function destroy(Suhu $suhu)
    {
        try {
            // Hapus gambar jika ada
            if ($suhu->gambar) {
                Storage::disk('public')->delete($suhu->gambar);
            }
            
            $suhu->delete();
            session()->flash('delete_success', true);
            return response ()->json(['delete_success' => true]);
        } catch (\Exception $e) {
            Log::error('Error saat menghapus data: ' . $e->getMessage());
            return response()->json(['delete_success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $results = [
            'Ruangan' => Tempat::where('ruangan', 'like', "%{$query}%")
                ->pluck('ruangan')
                ->unique()
                ->values()
                ->all(),
            'Suhu' => Suhu::where('suhu', 'like', "%{$query}%")
                ->pluck('suhu')
                ->unique()
                ->values()
                ->all(),
            'Keterangan' => Suhu::where('keterangan', 'like', "%{$query}%")
                ->pluck('keterangan')
                ->unique()
                ->values()
                ->all()
        ];

        return response()->json($results);
    }

    // public function export(Request $request)
    // {
    //     $filters = json_decode($request->filters, true) ?? [];
    //     $sorts = json_decode($request->sorts, true) ?? [];
        
    //     return (new SuhuExport($filters, $sorts))->export();
    // }
} 