<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Surat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class FinanceController extends Controller
{
    public function index()
    {
        try {
            // Gunakan Eloquent dengan eager loading
            $surats = Surat::with('employees')
                          ->whereNotNull('dana')
                          ->where('status', 'terkirim')
                          ->orderBy('created_at', 'desc')
                          ->get();

            return view('finance.list-surat', [
                'surats' => $surats,
                'showDana' => true
            ]);

        } catch (\Exception $e) {
            Log::error('Error in FinanceController@index: ' . $e->getMessage());
            return view('finance.list-surat', ['surats' => collect()]);
        }
    }

    public function detail($id)
    {
        try {
            $surat = Surat::with('employees')->findOrFail($id);
            
            // Cek akses
            if (!auth()->user()->hasSubmenu('Finance')) {
                return back()->with('error', 'Tidak memiliki akses.');
            }

            // Cek apakah surat memiliki dana
            if (is_null($surat->dana)) {
                return back()->with('error', 'Surat tidak memiliki pengajuan dana.');
            }

            return view('finance.detailsurat', compact('surat'));

        } catch (\Exception $e) {
            Log::error('Error in Finance detail: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat detail surat.');
        }
    }

    public function download($id)
    {
        try {
            $surat = Surat::findOrFail($id);
            
            // Cek akses Finance
            if (!auth()->user()->hasSubmenu('Finance')) {
                return back()->with('error', 'Tidak memiliki akses untuk mengunduh dokumen.');
            }

            // Cek apakah surat memiliki dana
            if (is_null($surat->dana)) {
                return back()->with('error', 'Surat tidak memiliki pengajuan dana.');
            }

            // Cek file exists
            if (!$surat->dokumen_pelengkap || !Storage::exists('private/' . $surat->dokumen_pelengkap)) {
                return back()->with('error', 'Dokumen tidak ditemukan.');
            }

            return Storage::download('private/' . $surat->dokumen_pelengkap);

        } catch (\Exception $e) {
            Log::error('Error downloading document in Finance: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengunduh dokumen.');
        }
    }
} 