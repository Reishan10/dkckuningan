<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BerkasController extends Controller
{
    public function index()
    {
        $berkas = Arsip::orderBy('created_at', 'desc')->where('type', '1')->get();
        return view('frontend.berkas.index', compact('berkas'));
    }
}
