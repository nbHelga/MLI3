<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class HRISController extends Controller
{
    public function index()
    {
        try {
            $employeeId = auth()->id();
            
            // Query untuk user sendiri
            $surats = DB::table('surat')
                        ->where('id_employees', $employeeId)
                        ->orderBy('created_at', 'desc')
                        ->get();

            return view('hris.list', ['surats' => $surats]);

        } catch (\Exception $e) {
            Log::error('Error in HRISController@index: ' . $e->getMessage());
            return view('hris.list', ['surats' => collect()]);
        }
    }

    public function create()
    {
        return view('hris.form');
    }

    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'perihal' => 'required|string',
                'tgl' => 'nullable|date',
                'dana' => 'nullable|numeric',
                'durasi' => 'nullable|integer',
                'keterangan' => 'nullable|string',
                'dokumen_pelengkap' => 'nullable|file|mimes:pdf,doc,docx|max:2048'
            ]);

            $data = [
                'id_employees' => auth()->id(),
                'perihal' => $request->perihal,
                'tgl' => $request->tgl,
                'dana' => $request->dana,
                'durasi' => $request->durasi,
                'keterangan' => $request->keterangan,
                'status' => 'pending'
            ];

            // Handle file upload
            if ($request->hasFile('dokumen_pelengkap')) {
                $file = $request->file('dokumen_pelengkap');
                $fileName = time() . '_' . $file->getClientOriginalName();
                
                // Simpan file ke storage
                $path = $file->storeAs(
                    'private/dokumen_pelengkap', 
                    $fileName
                );
                
                // Simpan path ke database
                $data['dokumen_pelengkap'] = 'dokumen_pelengkap/' . $fileName;
            }

            // Create surat
            $surat = Surat::create($data);

            Log::info('Surat created successfully', [
                'surat_id' => $surat->id,
                'employee_id' => auth()->id()
            ]);

            // Redirect ke home setelah store
            return redirect()->route('home')
                           ->with('success', 'Pengajuan surat berhasil ditambahkan.');

        } catch (\Exception $e) {
            Log::error('Error in store: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan pengajuan surat.')
                        ->withInput();
        }
    }

    public function show($id)
    {
        try {
            // Gunakan with() untuk eager loading
            $surat = Surat::with('employees')
                         ->findOrFail($id);

            // Debug info
            Log::info('Detail Surat:', [
                'surat_id' => $id,
                'employee_id' => $surat->id_employees,
                'employee_data' => $surat->employees
            ]);

            if (!$surat->employees) {
                Log::error('Employee not found for surat:', [
                    'surat_id' => $id,
                    'employee_id' => $surat->id_employees
                ]);
                return back()->with('error', 'Data pegawai tidak ditemukan.');
            }

            return view('hris.detailsurat', compact('surat'));

        } catch (\Exception $e) {
            Log::error('Error in HRISController@show: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat detail surat.');
        }
    }

    public function edit($id)
    {
        try {
            // Eager load employees relation
            $surat = Surat::with('employees')->findOrFail($id);
            
            // Cek akses - hanya pemilik surat yang bisa edit
            if ($surat->id_employees !== auth()->id()) {
                return back()->with('error', 'Tidak memiliki akses untuk mengedit surat ini.');
            }

            // Cek status - hanya surat pending yang bisa diedit
            if ($surat->status !== 'pending') {
                return back()->with('error', 'Surat yang sudah terkirim tidak dapat diedit.');
            }

            // Format tanggal untuk input date
            if ($surat->tgl) {
                $surat->tgl = \Carbon\Carbon::parse($surat->tgl)->format('Y-m-d');
            }

            // Debug untuk memastikan data terkirim
            Log::info('Edit Surat Data:', [
                'surat_id' => $surat->id,
                'perihal' => $surat->perihal,
                'tgl' => $surat->tgl,
                'durasi' => $surat->durasi,
                'dana' => $surat->dana,
                'keterangan' => $surat->keterangan
            ]);

            return view('hris.form', compact('surat'));

        } catch (\Exception $e) {
            Log::error('Error in edit: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat form edit.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $surat = Surat::findOrFail($id);
            
            // Validasi akses
            if ($surat->id_employees !== auth()->id()) {
                return back()->with('error', 'Tidak memiliki akses.');
            }

            // Validasi input
            $validated = $request->validate([
                'perihal' => 'required|string|in:Lembur,Izin,Cuti,Reimbursement,Perjalanan Dinas,Survey Pelanggan,Penagihan Piutang,Lainnya',
                'tgl' => [
                    'required',
                    'date',
                    'after_or_equal:' . now()->format('Y-m-d')
                ],
                'dana' => 'nullable|integer|min:0',
                'durasi' => 'nullable|integer|min:1',
                'keterangan' => 'nullable|string',
                'dokumen_pelengkap' => [
                    'nullable',
                    'file',
                    'max:2048', // 2MB
                    'mimes:pdf,doc,docx,png,jpg,jpeg'
                ]
            ], [
                'perihal.required' => 'Perihal harus dipilih',
                'tgl.after_or_equal' => 'Tanggal harus hari ini atau setelahnya',
                'dana.integer' => 'Dana harus berupa angka tanpa titik atau koma',
                'durasi.integer' => 'Durasi harus berupa angka',
                'dokumen_pelengkap.mimes' => 'Format file tidak sesuai'
            ]);

            // Handle file upload
            if ($request->hasFile('dokumen_pelengkap')) {
                // Tentukan folder berdasarkan perihal atau dana
                $folder = $request->dana ? 'Dana' : $request->perihal;
                
                // Hapus file lama jika ada
                if ($surat->dokumen_pelengkap) {
                    Storage::delete('private/' . $surat->dokumen_pelengkap);
                }

                // Upload file baru
                $file = $request->file('dokumen_pelengkap');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs("private/{$folder}", $fileName);
                
                $validated['dokumen_pelengkap'] = "{$folder}/{$fileName}";
            }

            $surat->update($validated);
            return redirect()->route('hris.detail', $surat->id)
                            ->with('success', 'Surat berhasil diupdate.');

        } catch (\Exception $e) {
            Log::error('Error in update: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengupdate surat.')
                        ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $surat = Surat::findOrFail($id);
            
            // Cek kepemilikan surat
            if ($surat->id_employees !== auth()->user()->employee->id) {
                return redirect()
                    ->route('home')
                    ->with('error', 'Anda tidak memiliki akses ke data ini');
            }

            // Hapus file jika ada
            if ($surat->dokumen_pelengkap) {
                Storage::disk('private')->delete($surat->dokumen_pelengkap);
            }

            $surat->delete();

            return redirect()
                ->route('home')
                ->with('success', 'Data berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function download($id)
    {
        try {
            $surat = Surat::findOrFail($id);
            $user = auth()->user();
            
            // Cek akses menggunakan fungsi yang sudah ada
            $isOwner = $surat->id_employees === $user->id;
            $hasHRDAccess = $user->hasMenuAccess('HRD');
            $hasFinanceAccess = $user->hasMenuAccess('Finance') && !is_null($surat->dana);
            $isSuperAdmin = $user->status === 'Super Admin';
            
            if (!$isOwner && !$hasHRDAccess && !$hasFinanceAccess && !$isSuperAdmin) {
                return back()->with('error', 'Tidak memiliki akses untuk mengunduh dokumen.');
            }

            // Cek file exists
            if (!$surat->dokumen_pelengkap || !Storage::exists('private/' . $surat->dokumen_pelengkap)) {
                return back()->with('error', 'Dokumen tidak ditemukan.');
            }

            return Storage::download('private/' . $surat->dokumen_pelengkap);

        } catch (\Exception $e) {
            \Log::error('Error downloading document: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengunduh dokumen.');
        }
    }

    public function send($id)
    {
        try {
            $surat = Surat::findOrFail($id);
            
            if ($surat->id_employees !== auth()->user()->employee->id) {
                return redirect()
                    ->route('hris.list')
                    ->with('error', 'Anda tidak memiliki akses ke data ini');
            }

            $surat->update(['status' => 'terkirim']);

            return redirect()
                ->route('hris.detail', $id)
                ->with('success', 'Surat berhasil dikirim');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    // Method untuk HRD - menampilkan semua surat yang status terkirim
    // public function hrdList()
    // {
    //     try {
    //         if (!auth()->user()->menus()->where('nama', 'HRD')->exists()) {
    //             return redirect()->route('home')->with('error', 'Akses ditolak');
    //         }

    //         // Ambil semua surat dengan status terkirim
    //         $surats = Surat::with('employee')
    //                       ->where('status', 'terkirim')
    //                       ->orderBy('created_at', 'desc')
    //                       ->get();

    //         return view('hrd.list-surat', compact('surats'));
    //     } catch (\Exception $e) {
    //         Log::error('Error in HRISController@hrdList: ' . $e->getMessage());
    //         return view('hrd.list-surat', ['surats' => collect()])
    //             ->with('error', 'Terjadi kesalahan saat memuat data.');
    //     }
    // }

    // // Method untuk Finance - menampilkan surat terkirim dengan dana
    // public function financeList()
    // {
    //     try {
    //         if (!auth()->user()->menus()->where('nama', 'Finance')->exists()) {
    //             return redirect()->route('home')->with('error', 'Akses ditolak');
    //         }

    //         // Ambil surat dengan status terkirim dan dana tidak null
    //         $surats = Surat::with('employee')
    //                       ->where('status', 'terkirim')
    //                       ->whereNotNull('dana')
    //                       ->orderBy('created_at', 'desc')
    //                       ->get();

    //         return view('finance.list-surat', compact('surats'));
    //     } catch (\Exception $e) {
    //         Log::error('Error in HRISController@financeList: ' . $e->getMessage());
    //         return view('finance.list-surat', ['surats' => collect()])
    //             ->with('error', 'Terjadi kesalahan saat memuat data.');
    //     }
    // }
} 