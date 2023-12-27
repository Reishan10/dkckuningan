<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SKLulus;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications;
        auth()->user()->unreadNotifications->markAsRead();
        return view('frontend.notifikasi.index', compact('notifications'));
    }

    public function download()
    {
        $sk = SKLulus::latest('created_at')
            ->first();
        $user = User::with('pendaftaran')->whereHas('pendaftaran', function ($query) {
            $query->where('status', 2)
                ->where('status_2', 2);
        })
            ->with(['pendaftaran' => function ($query) {
                $query->where('status', 2)
                    ->where('status_2', 2);
            }])
            ->get();
        $pdf = Pdf::loadView('backend.arsip.kelulusan.surat', compact('user', 'sk'));
        return $pdf->download($sk->name . '-' . time() . '.pdf');
    }
}
