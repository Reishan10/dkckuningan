<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Timeline;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    public function index()
    {
        $timeline = Timeline::orderBy('created_at', 'asc')->get();
        return view('frontend.timeline.index', compact('timeline'));
    }
}
