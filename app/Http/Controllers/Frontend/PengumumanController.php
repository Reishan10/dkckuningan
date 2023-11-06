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
            ->where('user_id', auth()->user()->id)
            ->first();

        return view('frontend.pengumuman.index', compact('pendaftaran'));
    }
}
