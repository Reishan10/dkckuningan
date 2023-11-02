<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $currentYear = date('Y');

        $laki_laki = Pendaftaran::where('jenis_kelamin', 'Laki-laki')
            ->whereYear('created_at', $currentYear)
            ->count();

        $perempuan = Pendaftaran::where('jenis_kelamin', 'Perempuan')
            ->whereYear('created_at', $currentYear)
            ->count();

        $siaga = Pendaftaran::join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->where('golongan.name', 'Siaga')
            ->count();

        $penggalang = Pendaftaran::join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->where('golongan.name', 'Penggalang')
            ->count();

        $penegak = Pendaftaran::join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->where('golongan.name', 'Penegak')
            ->count();

        $pandega = Pendaftaran::join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->where('golongan.name', 'Pandega')
            ->count();

        $currentYear = Carbon::now()->year;
        $fiveYearsAgo = $currentYear - 5;

        $data = Pendaftaran::join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->select(DB::raw('YEAR(pendaftaran.created_at) as year'), 'golongan.name as golongan_name', DB::raw('COUNT(*) as total'))
            ->whereBetween(DB::raw('YEAR(pendaftaran.created_at)'), [$fiveYearsAgo, $currentYear])
            ->groupBy('year', 'golongan_name')
            ->get();

        $data_pendaftar = Pendaftaran::select(DB::raw('YEAR(created_at) as year'), DB::raw('COUNT(*) as total'))
            ->whereBetween(DB::raw('YEAR(created_at)'), [$fiveYearsAgo, $currentYear])
            ->groupBy('year')
            ->get();

        $data_gender = Pendaftaran::join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->select(DB::raw('YEAR(pendaftaran.created_at) as year'), 'golongan.name as golongan_name', 'jenis_kelamin', DB::raw('COUNT(*) as total'))
            ->whereYear('pendaftaran.created_at', $currentYear)
            ->groupBy('year', 'golongan_name', 'jenis_kelamin')
            ->get();


        return view('backend.dashboard.index', compact('laki_laki', 'perempuan', 'siaga', 'penggalang', 'penegak', 'pandega', 'data', 'data_pendaftar', 'data_gender'));
    }
}
