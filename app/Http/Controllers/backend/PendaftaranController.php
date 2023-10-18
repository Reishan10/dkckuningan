<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class PendaftaranController extends Controller
{
    public function indexAll()
    {
        if (request()->ajax()) {
            $pendaftaran = Pendaftaran::with('user')
                ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                ->orderBy('users.name', 'asc')
                ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                ->get();


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
                ->addColumn('berkas', function ($user) {
                    $berkas = $user->berkas ? '<a href="' . Storage::url('public/berkas/' . $user->berkas) . '" target="_blank" class="btn btn-secondary btn-sm">Tautan ke Berkas</a>' : 'Berkas tidak tersedia';

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

                    if ($pendaftaran->status != 2) {
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                    } elseif ($pendaftaran->status != 3) {
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
        $pendaftaran = Pendaftaran::find($request->id);
        $pendaftaran->status = 2;
        $pendaftaran->save();

        return response()->json(['success' => 'Status berhasil diubah menjadi "Terima"']);
    }

    public function tolakAll(Request $request)
    {
        $pendaftaran = Pendaftaran::find($request->id);
        $pendaftaran->status = 3;
        $pendaftaran->save();

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

    public function indexSiaga()
    {
        if (request()->ajax()) {
            $pendaftaran = Pendaftaran::with('user')
                ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                ->orderBy('users.name', 'asc')
                ->where('golongan.name', 'Siaga')
                ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                ->get();

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
                ->addColumn('berkas', function ($user) {
                    $berkas = $user->berkas ? '<a href="' . Storage::url('public/berkas/' . $user->berkas) . '" target="_blank" class="btn btn-secondary btn-sm">Tautan ke Berkas</a>' : 'Berkas tidak tersedia';

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

                    if ($pendaftaran->status != 2) {
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                    } elseif ($pendaftaran->status != 3) {
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
        $pendaftaran = Pendaftaran::find($request->id);
        $pendaftaran->status = 2;
        $pendaftaran->save();

        return response()->json(['success' => 'Status berhasil diubah menjadi "Terima"']);
    }

    public function tolakSiaga(Request $request)
    {
        $pendaftaran = Pendaftaran::find($request->id);
        $pendaftaran->status = 3;
        $pendaftaran->save();

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

    public function indexPenggalang()
    {
        if (request()->ajax()) {
            $pendaftaran = Pendaftaran::with('user')
                ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                ->orderBy('users.name', 'asc')
                ->where('golongan.name', 'Penggalang')
                ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                ->get();

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
                ->addColumn('berkas', function ($user) {
                    $berkas = $user->berkas ? '<a href="' . Storage::url('public/berkas/' . $user->berkas) . '" target="_blank" class="btn btn-secondary btn-sm">Tautan ke Berkas</a>' : 'Berkas tidak tersedia';

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

                    if ($pendaftaran->status != 2) {
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                    } elseif ($pendaftaran->status != 3) {
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
        $pendaftaran = Pendaftaran::find($request->id);
        $pendaftaran->status = 2;
        $pendaftaran->save();

        return response()->json(['success' => 'Status berhasil diubah menjadi "Terima"']);
    }

    public function tolakPenggalang(Request $request)
    {
        $pendaftaran = Pendaftaran::find($request->id);
        $pendaftaran->status = 3;
        $pendaftaran->save();

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

    public function indexPenegak()
    {
        if (request()->ajax()) {
            $pendaftaran = Pendaftaran::with('user')
                ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                ->orderBy('users.name', 'asc')
                ->where('golongan.name', 'Penegak')
                ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                ->get();

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
                ->addColumn('berkas', function ($user) {
                    $berkas = $user->berkas ? '<a href="' . Storage::url('public/berkas/' . $user->berkas) . '" target="_blank" class="btn btn-secondary btn-sm">Tautan ke Berkas</a>' : 'Berkas tidak tersedia';

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

                    if ($pendaftaran->status != 2) {
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                    } elseif ($pendaftaran->status != 3) {
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
        $pendaftaran = Pendaftaran::find($request->id);
        $pendaftaran->status = 2;
        $pendaftaran->save();

        return response()->json(['success' => 'Status berhasil diubah menjadi "Terima"']);
    }

    public function tolakPenegak(Request $request)
    {
        $pendaftaran = Pendaftaran::find($request->id);
        $pendaftaran->status = 3;
        $pendaftaran->save();

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

    public function indexPandega()
    {
        if (request()->ajax()) {
            $pendaftaran = Pendaftaran::with('user')
                ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                ->orderBy('users.name', 'asc')
                ->where('golongan.name', 'Pandega')
                ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                ->get();

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
                ->addColumn('berkas', function ($user) {
                    $berkas = $user->berkas ? '<a href="' . Storage::url('public/berkas/' . $user->berkas) . '" target="_blank" class="btn btn-secondary btn-sm">Tautan ke Berkas</a>' : 'Berkas tidak tersedia';

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

                    if ($pendaftaran->status != 2) {
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                    } elseif ($pendaftaran->status != 3) {
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
        $pendaftaran = Pendaftaran::find($request->id);
        $pendaftaran->status = 2;
        $pendaftaran->save();

        return response()->json(['success' => 'Status berhasil diubah menjadi "Terima"']);
    }

    public function tolakPandega(Request $request)
    {
        $pendaftaran = Pendaftaran::find($request->id);
        $pendaftaran->status = 3;
        $pendaftaran->save();

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
}
