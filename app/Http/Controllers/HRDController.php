<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Surat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class HRDController extends Controller
{
    public function showSurat()
    {
        try {
            // Gunakan Eloquent dengan eager loading
            $surats = Surat::with('employees')
                          ->where('status', 'terkirim')
                          ->orderBy('created_at', 'desc')
                          ->get();

            return view('hrd.list-surat', [
                'surats' => $surats,
                'showDana' => true // Untuk menampilkan kolom dana
            ]);

        } catch (\Exception $e) {
            Log::error('Error in HRDController@showSurat: ' . $e->getMessage());
            return view('hrd.list-surat', ['surats' => collect()]);
        }
    }

    public function detail($id)
    {
        try {
            $surat = Surat::with('employees')->findOrFail($id);
            
            // Cek akses
            if (!auth()->user()->hasSubmenu('HRD')) {
                return back()->with('error', 'Tidak memiliki akses.');
            }

            return view('hrd.detailsurat', compact('surat'));

        } catch (\Exception $e) {
            Log::error('Error in HRD detail: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat detail surat.');
        }
    }

    public function download($id)
    {
        try {
            $surat = Surat::findOrFail($id);
            
            // Cek akses HRD
            if (!auth()->user()->hasSubmenu('HRD')) {
                return back()->with('error', 'Tidak memiliki akses untuk mengunduh dokumen.');
            }

            // Cek file exists
            if (!$surat->dokumen_pelengkap || !Storage::exists('private/' . $surat->dokumen_pelengkap)) {
                return back()->with('error', 'Dokumen tidak ditemukan.');
            }

            return Storage::download('private/' . $surat->dokumen_pelengkap);

        } catch (\Exception $e) {
            Log::error('Error downloading document in HRD: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengunduh dokumen.');
        }
    }
} 