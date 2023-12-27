<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SKLulus;
use App\Models\User;
use App\Notifications\NotificationsLolos;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class SKController extends Controller
{
    public function send()
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
        return redirect()->back();
    }

    public function download($id)
    {
        $sk = SKLulus::find($id);
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
