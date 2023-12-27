<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\User;
use App\Notifications\NotificationsLolos;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Notification;

class HomeController extends Controller
{
    public function index()
    {
        $user = User::with('pendaftaran')->whereHas('pendaftaran', function ($query) {
            $query->where('status', 2)
                ->where('status_2', 2);
        })
            ->with(['pendaftaran' => function ($query) {
                $query->where('status', 2)
                    ->where('status_2', 2);
            }])
            ->get();

        $data = User::where('id', auth()->user()->id)->first();

        Notification::send($user, new NotificationsLolos($data));
        // $pdf = Pdf::loadView('backend.arsip.kelulusan.surat', compact('user'));
        // return $pdf->download('pendaftaran-semua-' . time() . '.pdf');
    }
}
