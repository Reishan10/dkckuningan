<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Konten;
use App\Models\Timeline;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        $timeline = Timeline::orderBy('created_at', 'asc')->get();
        $konten = Konten::with('user')->where('status', 0)->orderBy('created_at', 'asc')->take(6)->get();
        return view('frontend.beranda.index', compact('timeline', 'konten'));
    }
}
