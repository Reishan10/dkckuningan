<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->orderBy('users.name', 'asc')
            ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*', 'golongan.name as golongan_name')
            ->where('status', 1)
            ->get();
        return view('frontend.pengumuman.index', compact('pendaftaran'));
    }
}
