<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Konten;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index()
    {
        $konten = Konten::with('user')->where('status', 0)->orderBy('created_at', 'asc')->paginate(10);
        return view('frontend.berita.index', compact('konten'));
    }

    public function detail($slug)
    {
        $konten = Konten::where('slug', $slug)->first();
        $recentPosts = Konten::where('slug', '!=', $slug)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        return view('frontend.berita.detail', compact('konten', 'recentPosts'));
    }
}
