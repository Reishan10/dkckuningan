<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\MailLolosAdministrasi;
use App\Mail\MailTolakAdministrasi;
use App\Models\Notifikasi;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class PendaftaranController extends Controller
{
    public function indexAll(Request $request)
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
                    $berkas = $user->berkas ? '<a href="' . asset('berkas/' . $user->berkas) . '" target="_blank" class="btn btn-secondary btn-sm">Tautan ke Berkas</a>' : 'Berkas tidak tersedia';

                    $size = $user->compress_size ?? 0;
                    $berkas .= ' <small>' . $size . '</small>';

                    return $berkas;
                })

                ->addColumn('status', function ($pendaftaran) {
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

                ->addColumn('aksi', function ($pendaftaran) {
                    $btn = '';
                    if ($pendaftaran->status == 1) {
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTolak" title="Tolak"><i class="fas fa-times"></i></button>';
                    }
                    $btn .= '<button type="button" class="btn btn-sm btn-info text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnDetail" data-bs-toggle="modal" data-bs-target="#detailModal"><i class="fas fa-eye"></i></button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-warning text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    return $btn;
                })

                ->rawColumns(['nta', 'aksi', 'active_status', 'comboBox', 'status', 'berkas'])
                ->make(true);
        }
        return view('backend.pendaftaran.all.index');
    }

    public function detailAll(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        return response()->json(['pendaftaran' => $pendaftaran]);
    }

    public function terimaAll(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_1 = "Selesai";
        $pendaftaran->status = 2;
        $pendaftaran->save();

        $notifikasi = new Notifikasi();
        $notifikasi->receiver_id = $pendaftaran->user->id;
        $notifikasi->sender_id = auth()->user()->id;
        $notifikasi->message = "Selamat anda lolos seleksi tahap administrasi";
        $notifikasi->save();

        Mail::to($pendaftaran->user->email)->send(new MailLolosAdministrasi($pendaftaran));
        return response()->json(['success' => 'Status berhasil diubah menjadi "Terima"']);
    }

    public function tolakAll(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_1 = "Selesai";
        $pendaftaran->status = 3;
        $pendaftaran->save();

        $notifikasi = new Notifikasi();
        $notifikasi->receiver_id = $pendaftaran->user->id;
        $notifikasi->sender_id = auth()->user()->id;
        $notifikasi->message = "Mohon maaf anda belum lolos seleksi tahap administrasi";
        $notifikasi->save();

        Mail::to($pendaftaran->user->email)->send(new MailTolakAdministrasi($pendaftaran));
        return response()->json(['success' => 'Status berhasil diubah menjadi "Tolak"']);
    }

    public function destroyAll(Request $request)
    {
        $pendaftaran = Pendaftaran::find($request->id);

        if (!empty($pendaftaran->berkas)) {
            if (Storage::exists('public/berkas/' . $pendaftaran->berkas)) {
                Storage::delete('public/berkas/' . $pendaftaran->berkas);
            }
        }

        $pendaftaran->delete();

        return response()->json(['success' => 'Data berhasil dihapus', 'berkas' => $pendaftaran->berkas]);
    }

    public function printAll()
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->orderBy('users.name', 'asc')
            ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.name', 'golongan.name as golongan_name', 'pangkalan')
            ->get();

        return view('backend.pendaftaran.all.print', compact('pendaftaran'));
    }

    public function printPDFAll()
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->orderBy('users.name', 'asc')
            ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.name', 'golongan.name as golongan_name', 'pangkalan')
            ->get();

        $pdf = Pdf::loadView('backend.pendaftaran.all.printPDF', compact('pendaftaran'));
        return $pdf->download('pendaftaran-semua-' . time() . '.pdf');
    }

    public function indexSiaga(Request $request)
    {
        if (request()->ajax()) {
            $query = Pendaftaran::with('user', 'golongan')
                ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                ->orderBy('users.name', 'asc')
                ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                ->where('golongan.name', 'Siaga');

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
                    $berkas = $user->berkas ? '<a href="' . asset('berkas/' . $user->berkas) . '" target="_blank" class="btn btn-secondary btn-sm">Tautan ke Berkas</a>' : 'Berkas tidak tersedia';

                    $size = $user->compress_size ?? 0;
                    $berkas .= ' <small>' . $size . '</small>';

                    return $berkas;
                })
                ->addColumn('status', function ($pendaftaran) {
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

                ->addColumn('aksi', function ($pendaftaran) {
                    $btn = '';
                    if ($pendaftaran->status == 1) {
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTolak" title="Tolak"><i class="fas fa-times"></i></button>';
                    }
                    $btn .= '<button type="button" class="btn btn-sm btn-info text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnDetail" data-bs-toggle="modal" data-bs-target="#detailModal"><i class="fas fa-eye"></i></button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-warning text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    return $btn;
                })

                ->rawColumns(['nta', 'aksi', 'active_status', 'comboBox', 'status', 'berkas'])
                ->make(true);
        }
        return view('backend.pendaftaran.siaga.index');
    }

    public function detailSiaga(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        return response()->json(['pendaftaran' => $pendaftaran]);
    }

    public function terimaSiaga(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_1 = "Selesai";
        $pendaftaran->status = 2;
        $pendaftaran->save();

        $notifikasi = new Notifikasi();
        $notifikasi->receiver_id = $pendaftaran->user->id;
        $notifikasi->sender_id = auth()->user()->id;
        $notifikasi->message = "Selamat anda lolos seleksi tahap administrasi";
        $notifikasi->save();

        Mail::to($pendaftaran->user->email)->send(new MailLolosAdministrasi($pendaftaran));
        return response()->json(['success' => 'Status berhasil diubah menjadi "Terima"']);
    }

    public function tolakSiaga(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_1 = "Selesai";
        $pendaftaran->status = 3;
        $pendaftaran->save();

        $notifikasi = new Notifikasi();
        $notifikasi->receiver_id = $pendaftaran->user->id;
        $notifikasi->sender_id = auth()->user()->id;
        $notifikasi->message = "Mohon maaf anda belum lolos seleksi tahap administrasi";
        $notifikasi->save();

        Mail::to($pendaftaran->user->email)->send(new MailTolakAdministrasi($pendaftaran));
        return response()->json(['success' => 'Status berhasil diubah menjadi "Tolak"']);
    }

    public function destroySiaga(Request $request)
    {
        $pendaftaran = Pendaftaran::find($request->id);

        if (!empty($pendaftaran->berkas)) {
            if (Storage::exists('public/berkas/' . $pendaftaran->berkas)) {
                Storage::delete('public/berkas/' . $pendaftaran->berkas);
            }
        }

        $pendaftaran->delete();

        return response()->json(['success' => 'Data berhasil dihapus', 'berkas' => $pendaftaran->berkas]);
    }

    public function printSiaga()
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->orderBy('users.name', 'asc')
            ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.name', 'golongan.name as golongan_name', 'pangkalan')
            ->where('golongan.name', 'Siaga')
            ->get();

        return view('backend.pendaftaran.siaga.print', compact('pendaftaran'));
    }

    public function printPDFSiaga()
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->orderBy('users.name', 'asc')
            ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.name', 'golongan.name as golongan_name', 'pangkalan')
            ->where('golongan.name', 'Siaga')
            ->get();

        $pdf = Pdf::loadView('backend.pendaftaran.siaga.printPDF', compact('pendaftaran'));
        return $pdf->download('pendaftaran-siaga-' . time() . '.pdf');
    }

    public function indexPenggalang(Request $request)
    {
        if (request()->ajax()) {
            $query = Pendaftaran::with('user', 'golongan')
                ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                ->orderBy('users.name', 'asc')
                ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                ->where('golongan.name', 'Penggalang');

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
                    $berkas = $user->berkas ? '<a href="' . asset('berkas/' . $user->berkas) . '" target="_blank" class="btn btn-secondary btn-sm">Tautan ke Berkas</a>' : 'Berkas tidak tersedia';

                    $size = $user->compress_size ?? 0;
                    $berkas .= ' <small>' . $size . '</small>';

                    return $berkas;
                })
                ->addColumn('status', function ($pendaftaran) {
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

                ->addColumn('aksi', function ($pendaftaran) {
                    $btn = '';
                    if ($pendaftaran->status == 1) {
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTolak" title="Tolak"><i class="fas fa-times"></i></button>';
                    }
                    $btn .= '<button type="button" class="btn btn-sm btn-info text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnDetail" data-bs-toggle="modal" data-bs-target="#detailModal"><i class="fas fa-eye"></i></button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-warning text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    return $btn;
                })

                ->rawColumns(['nta', 'aksi', 'active_status', 'comboBox', 'status', 'berkas'])
                ->make(true);
        }
        return view('backend.pendaftaran.penggalang.index');
    }

    public function detailPenggalang(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        return response()->json(['pendaftaran' => $pendaftaran]);
    }

    public function terimaPenggalang(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_1 = "Selesai";
        $pendaftaran->status = 2;
        $pendaftaran->save();

        $notifikasi = new Notifikasi();
        $notifikasi->receiver_id = $pendaftaran->user->id;
        $notifikasi->sender_id = auth()->user()->id;
        $notifikasi->message = "Selamat anda lolos seleksi tahap administrasi";
        $notifikasi->save();

        Mail::to($pendaftaran->user->email)->send(new MailLolosAdministrasi($pendaftaran));
        return response()->json(['success' => 'Status berhasil diubah menjadi "Terima"']);
    }

    public function tolakPenggalang(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_1 = "Selesai";
        $pendaftaran->status = 3;
        $pendaftaran->save();

        $notifikasi = new Notifikasi();
        $notifikasi->receiver_id = $pendaftaran->user->id;
        $notifikasi->sender_id = auth()->user()->id;
        $notifikasi->message = "Mohon maaf anda belum lolos seleksi tahap administrasi";
        $notifikasi->save();

        Mail::to($pendaftaran->user->email)->send(new MailTolakAdministrasi($pendaftaran));
        return response()->json(['success' => 'Status berhasil diubah menjadi "Tolak"']);
    }

    public function destroyPenggalang(Request $request)
    {
        $pendaftaran = Pendaftaran::find($request->id);

        if (!empty($pendaftaran->berkas)) {
            if (Storage::exists('public/berkas/' . $pendaftaran->berkas)) {
                Storage::delete('public/berkas/' . $pendaftaran->berkas);
            }
        }

        $pendaftaran->delete();

        return response()->json(['success' => 'Data berhasil dihapus', 'berkas' => $pendaftaran->berkas]);
    }

    public function printPenggalang()
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->orderBy('users.name', 'asc')
            ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.name', 'golongan.name as golongan_name', 'pangkalan')
            ->where('golongan.name', 'Penggalang')
            ->get();

        return view('backend.pendaftaran.penggalang.print', compact('pendaftaran'));
    }

    public function printPDFPenggalang()
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->orderBy('users.name', 'asc')
            ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.name', 'golongan.name as golongan_name', 'pangkalan')
            ->where('golongan.name', 'Penggalang')
            ->get();

        $pdf = Pdf::loadView('backend.pendaftaran.penggalang.printPDF', compact('pendaftaran'));
        return $pdf->download('pendaftaran-penggalang-' . time() . '.pdf');
    }

    public function indexPenegak(Request $request)
    {
        if (request()->ajax()) {
            $query = Pendaftaran::with('user', 'golongan')
                ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                ->orderBy('users.name', 'asc')
                ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                ->where('golongan.name', 'Penegak');

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
                    $berkas = $user->berkas ? '<a href="' . asset('berkas/' . $user->berkas) . '" target="_blank" class="btn btn-secondary btn-sm">Tautan ke Berkas</a>' : 'Berkas tidak tersedia';

                    $size = $user->compress_size ?? 0;
                    $berkas .= ' <small>' . $size . '</small>';

                    return $berkas;
                })
                ->addColumn('status', function ($pendaftaran) {
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

                ->addColumn('aksi', function ($pendaftaran) {
                    $btn = '';
                    if ($pendaftaran->status == 1) {
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTolak" title="Tolak"><i class="fas fa-times"></i></button>';
                    }
                    $btn .= '<button type="button" class="btn btn-sm btn-info text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnDetail" data-bs-toggle="modal" data-bs-target="#detailModal"><i class="fas fa-eye"></i></button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-warning text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    return $btn;
                })

                ->rawColumns(['nta', 'aksi', 'active_status', 'comboBox', 'status', 'berkas'])
                ->make(true);
        }
        return view('backend.pendaftaran.penegak.index');
    }

    public function detailPenegak(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        return response()->json(['pendaftaran' => $pendaftaran]);
    }

    public function terimaPenegak(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_1 = "Selesai";
        $pendaftaran->status = 2;
        $pendaftaran->save();

        $notifikasi = new Notifikasi();
        $notifikasi->receiver_id = $pendaftaran->user->id;
        $notifikasi->sender_id = auth()->user()->id;
        $notifikasi->message = "Selamat anda lolos seleksi tahap administrasi";
        $notifikasi->save();

        Mail::to($pendaftaran->user->email)->send(new MailLolosAdministrasi($pendaftaran));
        return response()->json(['success' => 'Status berhasil diubah menjadi "Terima"']);
    }

    public function tolakPenegak(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_1 = "Selesai";
        $pendaftaran->status = 3;
        $pendaftaran->save();

        $notifikasi = new Notifikasi();
        $notifikasi->receiver_id = $pendaftaran->user->id;
        $notifikasi->sender_id = auth()->user()->id;
        $notifikasi->message = "Mohon maaf anda belum lolos seleksi tahap administrasi";
        $notifikasi->save();

        Mail::to($pendaftaran->user->email)->send(new MailTolakAdministrasi($pendaftaran));
        return response()->json(['success' => 'Status berhasil diubah menjadi "Tolak"']);
    }

    public function destroyPenegak(Request $request)
    {
        $pendaftaran = Pendaftaran::find($request->id);

        if (!empty($pendaftaran->berkas)) {
            if (Storage::exists('public/berkas/' . $pendaftaran->berkas)) {
                Storage::delete('public/berkas/' . $pendaftaran->berkas);
            }
        }

        $pendaftaran->delete();

        return response()->json(['success' => 'Data berhasil dihapus', 'berkas' => $pendaftaran->berkas]);
    }

    public function printPenegak()
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->orderBy('users.name', 'asc')
            ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.name', 'golongan.name as golongan_name', 'pangkalan')
            ->where('golongan.name', 'Penegak')
            ->get();

        return view('backend.pendaftaran.penegak.print', compact('pendaftaran'));
    }

    public function printPDFPenegak()
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->orderBy('users.name', 'asc')
            ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.name', 'golongan.name as golongan_name', 'pangkalan')
            ->where('golongan.name', 'Penegak')
            ->get();

        $pdf = Pdf::loadView('backend.pendaftaran.penegak.printPDF', compact('pendaftaran'));
        return $pdf->download('pendaftaran-penegak-' . time() . '.pdf');
    }

    public function indexPandega(Request $request)
    {
        if (request()->ajax()) {
            $query = Pendaftaran::with('user', 'golongan')
                ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                ->orderBy('users.name', 'asc')
                ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                ->where('golongan.name', 'Pandega');

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
                    $berkas = $user->berkas ? '<a href="' . asset('berkas/' . $user->berkas) . '" target="_blank" class="btn btn-secondary btn-sm">Tautan ke Berkas</a>' : 'Berkas tidak tersedia';

                    $size = $user->compress_size ?? 0;
                    $berkas .= ' <small>' . $size . '</small>';

                    return $berkas;
                })
                ->addColumn('status', function ($pendaftaran) {
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

                ->addColumn('aksi', function ($pendaftaran) {
                    $btn = '';
                    if ($pendaftaran->status == 1) {
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTolak" title="Tolak"><i class="fas fa-times"></i></button>';
                    }
                    $btn .= '<button type="button" class="btn btn-sm btn-info text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnDetail" data-bs-toggle="modal" data-bs-target="#detailModal"><i class="fas fa-eye"></i></button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-warning text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    return $btn;
                })

                ->rawColumns(['nta', 'aksi', 'active_status', 'comboBox', 'status', 'berkas'])
                ->make(true);
        }
        return view('backend.pendaftaran.pandega.index');
    }

    public function detailPandega(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        return response()->json(['pendaftaran' => $pendaftaran]);
    }

    public function terimaPandega(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_1 = "Selesai";
        $pendaftaran->status = 2;

        $notifikasi = new Notifikasi();
        $notifikasi->receiver_id = $pendaftaran->user->id;
        $notifikasi->sender_id = auth()->user()->id;
        $notifikasi->message = "Selamat anda lolos seleksi tahap administrasi";
        $notifikasi->save();

        Mail::to($pendaftaran->user->email)->send(new MailLolosAdministrasi($pendaftaran));
        return response()->json(['success' => 'Status berhasil diubah menjadi "Terima"']);
    }

    public function tolakPandega(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_1 = "Selesai";
        $pendaftaran->status = 3;
        $pendaftaran->save();

        $notifikasi = new Notifikasi();
        $notifikasi->receiver_id = $pendaftaran->user->id;
        $notifikasi->sender_id = auth()->user()->id;
        $notifikasi->message = "Mohon maaf anda belum lolos seleksi tahap administrasi";
        $notifikasi->save();

        Mail::to($pendaftaran->user->email)->send(new MailTolakAdministrasi($pendaftaran));
        return response()->json(['success' => 'Status berhasil diubah menjadi "Tolak"']);
    }

    public function destroyPandega(Request $request)
    {
        $pendaftaran = Pendaftaran::find($request->id);

        if (!empty($pendaftaran->berkas)) {
            if (Storage::exists('public/berkas/' . $pendaftaran->berkas)) {
                Storage::delete('public/berkas/' . $pendaftaran->berkas);
            }
        }

        $pendaftaran->delete();

        return response()->json(['success' => 'Data berhasil dihapus', 'berkas' => $pendaftaran->berkas]);
    }

    public function printPandega()
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->orderBy('users.name', 'asc')
            ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.name', 'golongan.name as golongan_name', 'pangkalan')
            ->where('golongan.name', 'Pandega')
            ->get();

        return view('backend.pendaftaran.pandega.print', compact('pendaftaran'));
    }

    public function printPDFPandega()
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->orderBy('users.name', 'asc')
            ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.name', 'golongan.name as golongan_name', 'pangkalan')
            ->where('golongan.name', 'Pandega')
            ->get();

        $pdf = Pdf::loadView('backend.pendaftaran.pandega.printPDF', compact('pendaftaran'));
        return $pdf->download('pendaftaran-pandega-' . time() . '.pdf');
    }
}
