<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class RiwayatPendaftarController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $query = Pendaftaran::with('user', 'golongan')
                ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                ->orderBy('users.name', 'asc')
                ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*');

            if ($request->has('start_date') && $request->has('end_date')) {
                $start_date = $request->input('start_date');
                $end_date = $request->input('end_date');

                $query->whereBetween('pendaftaran.created_at', [$start_date, $end_date]);
            }

            $pendaftaran = $query->get();

            return DataTables::of($pendaftaran)
                ->addIndexColumn()
                ->addColumn('nta', function ($user) {
                    $avatar = $user->avatar == ''
                        ? 'https://ui-avatars.com/api/?background=random&name=' . $user->name
                        : asset('storage/avatar/' . $user->avatar);

                    $avatarImage = '<img class="avatar-img rounded-circle" src="' . $avatar . '" alt="User Image">';

                    $nta = '<h2 class="table-avatar">
                                <a href="student-details.html" class="avatar avatar-sm me-2">
                                    ' . $avatarImage . '</a>
                                <a href="student-details.html">' . $user->nta . '</a>
                            </h2>';

                    return $nta;
                })
                ->addColumn('golongan', function ($user) {
                    $golongan = $user->golongan->name;

                    return $golongan;
                })
                ->addColumn('berkas', function ($user) {
                    $berkas = $user->berkas ? '<a href="' . Storage::url('public/berkas/' . $user->berkas) . '" target="_blank" class="btn btn-secondary btn-sm">Tautan ke Berkas</a>' : 'Berkas tidak tersedia';

                    return $berkas;
                })
                ->addColumn('status_1', function ($pendaftaran) {
                    $statusText = '';
                    $badgeClass = '';

                    if ($pendaftaran->status == 1) {
                        $badgeClass = 'badge-soft-info';
                        $statusText = 'Dalam Proses';
                    } elseif ($pendaftaran->status == 2) {
                        $badgeClass = 'badge-soft-success';
                        $statusText = 'Terima';
                    } elseif ($pendaftaran->status == 3) {
                        $badgeClass = 'badge-soft-danger';
                        $statusText = 'Tolak';
                    }

                    $status = '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';

                    return $status;
                })

                ->addColumn('status_2', function ($pendaftaran) {
                    $statusText = '';
                    $badgeClass = '';

                    if ($pendaftaran->status_2 == 1) {
                        $badgeClass = 'badge-soft-info';
                        $statusText = 'Dalam Proses';
                    } elseif ($pendaftaran->status_2 == 2) {
                        $badgeClass = 'badge-soft-success';
                        $statusText = 'Terima';
                    } elseif ($pendaftaran->status_2 == 3) {
                        $badgeClass = 'badge-soft-danger';
                        $statusText = 'Tolak';
                    }

                    $status = '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';

                    return $status;
                })


                ->rawColumns(['nta', 'golongan', 'berkas', 'status_1', 'status_2'])
                ->make(true);
        }
        return view('backend.riwayat.index');
    }
}
