<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\MailLolosAkhir;
use App\Mail\MailTolakAkhir;
use App\Models\Pendaftaran;
use App\Models\Penilaian;
use App\Models\Soal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class PenilaianController extends Controller
{
    public function indexAll(Request $request)
    {
        if (request()->ajax()) {
            $query = '';
            if (auth()->user()->type == "Juri") {
                $query = Pendaftaran::with('user', 'golongan')
                    ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'asc')
                    ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                    ->where('tahap_1', 'Selesai')
                    ->where('status', 2);
            } else {
                $query = Pendaftaran::with('user', 'golongan')
                    ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'asc')
                    ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*');
            }

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
                ->addColumn('status_2', function ($pendaftaran) {
                    $statusText = '';
                    $badgeClass = '';

                    if ($pendaftaran->status_2 == 1) {
                        $badgeClass = 'badge-soft-info';
                        $statusText = 'Dalam Proses';
                    } elseif ($pendaftaran->status_2 == 2) {
                        $badgeClass = 'badge-soft-success';
                        $statusText = 'Terima';
                    } else {
                        $badgeClass = 'badge-soft-danger';
                        $statusText = 'Tolak';
                    }
                    $status_2 = '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';

                    return $status_2;
                })
                ->addColumn('aksi', function ($pendaftaran) {
                    $btn = '';
                    if ($pendaftaran->tahap_2 == null) {
                        $btn = '<a href="' . route('penilaian.all.nilai', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-warning text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    } elseif ($pendaftaran->status_2 == 1) {
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTolak" title="Tolak"><i class="fas fa-times"></i></button>';
                    } else {
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    }

                    return $btn;
                })

                ->rawColumns(['nta', 'aksi', 'berkas', 'status_2'])
                ->make(true);
        }
        return view('backend.penilaian.all.index');
    }

    public function nilaiAll($id)
    {
        $pendaftaran = Pendaftaran::find($id);
        $soal = Soal::where('golongan_id', $pendaftaran->golongan_id)->get();
        return view('backend.penilaian.all.nilai', compact('soal', 'pendaftaran'));
    }

    public function simpanPenilaianAll(Request $request)
    {
        $pendaftaranId = $request->input('pendaftaran_id');
        $nilai = $request->input('nilai');
        $success = true;

        foreach ($nilai as $soalId => $nilaiSoal) {
            $penilaian = Penilaian::updateOrCreate(
                ['pendaftaran_id' => $pendaftaranId, 'soal_id' => $soalId],
                ['nilai' => $nilaiSoal]
            );

            if (!$penilaian) {
                $success = false;
                break;
            }
        }

        if ($success) {
            // Menghitung rata-rata nilai
            $totalNilai = array_sum($nilai);
            $jumlahNilai = count($nilai);
            $rataRata = $jumlahNilai > 0 ? $totalNilai / $jumlahNilai : 0;

            // Update nilai rata-rata di tabel pendaftaran
            $pendaftaran = Pendaftaran::find($pendaftaranId);
            if ($pendaftaran) {
                $pendaftaran->tahap_2 = "Selesai";
                $pendaftaran->nilai = $rataRata;
                $pendaftaran->save();
            }

            if ($pendaftaran) {
                return response()->json(['message' => 'Data dan rata-rata berhasil disimpan'], 200);
            } else {
                return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data'], 500);
            }
        } else {
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data'], 500);
        }
    }

    public function terimaAll(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_2 = "Selesai";
        $pendaftaran->status_2 = 2;
        $pendaftaran->save();

        Mail::to($pendaftaran->user->email)->send(new MailLolosAkhir($pendaftaran));
        return response()->json(['success' => 'Status berhasil diubah menjadi "Terima"']);
    }

    public function tolakAll(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_2 = "Selesai";
        $pendaftaran->status_2 = 3;
        $pendaftaran->save();

        Mail::to($pendaftaran->user->email)->send(new MailTolakAkhir($pendaftaran));
        return response()->json(['success' => 'Status berhasil diubah menjadi "Tolak"']);
    }

    public function printAll()
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->orderBy('users.name', 'asc')
            ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.name', 'golongan.name as golongan_name', 'pangkalan')
            ->get();
        return view('backend.penilaian.all.print', compact('pendaftaran'));
    }

    public function printPDFAll()
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->orderBy('users.name', 'asc')
            ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.name', 'golongan.name as golongan_name', 'pangkalan')
            ->get();

        $pdf = Pdf::loadView('backend.penilaian.all.printPDF', compact('pendaftaran'));
        return $pdf->download('pendaftaran-semua-' . time() . '.pdf');
    }

    public function indexSiaga(Request $request)
    {
        if (request()->ajax()) {
            $query = '';
            if (auth()->user()->type == 'Juri') {
                $query = Pendaftaran::with('user', 'golongan')
                    ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                    ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                    ->orderBy('users.name', 'asc')
                    ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                    ->where('tahap_1', 'Selesai')
                    ->where('golongan.name', 'Siaga')
                    ->where('status', 2);
            } else {
                $query = Pendaftaran::with('user', 'golongan')
                    ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                    ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                    ->orderBy('users.name', 'asc')
                    ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                    ->where('golongan.name', 'Siaga');
            }

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
                ->addColumn('status_2', function ($pendaftaran) {
                    $statusText = '';
                    $badgeClass = '';

                    if ($pendaftaran->status_2 == 1) {
                        $badgeClass = 'badge-soft-info';
                        $statusText = 'Dalam Proses';
                    } elseif ($pendaftaran->status_2 == 2) {
                        $badgeClass = 'badge-soft-success';
                        $statusText = 'Terima';
                    } else {
                        $badgeClass = 'badge-soft-danger';
                        $statusText = 'Tolak';
                    }
                    $status_2 = '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';

                    return $status_2;
                })
                ->addColumn('aksi', function ($pendaftaran) {
                    $btn = '';
                    if ($pendaftaran->tahap_2 == null) {
                        $btn = '<a href="' . route('penilaian.siaga.nilai', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-warning text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    } elseif ($pendaftaran->status_2 == 1) {
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTolak" title="Tolak"><i class="fas fa-times"></i></button>';
                    } else {
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    }

                    return $btn;
                })

                ->rawColumns(['nta', 'aksi', 'berkas', 'status_2'])
                ->make(true);
        }
        return view('backend.penilaian.siaga.index');
    }

    public function nilaiSiaga($id)
    {
        $pendaftaran = Pendaftaran::find($id);
        $soal = Soal::where('golongan_id', $pendaftaran->golongan_id)->get();
        return view('backend.penilaian.all.nilai', compact('soal', 'pendaftaran'));
    }

    public function simpanPenilaianSiaga(Request $request)
    {
        $pendaftaranId = $request->input('pendaftaran_id');
        $nilai = $request->input('nilai');
        $success = true;

        foreach ($nilai as $soalId => $nilaiSoal) {
            $penilaian = Penilaian::updateOrCreate(
                ['pendaftaran_id' => $pendaftaranId, 'soal_id' => $soalId],
                ['nilai' => $nilaiSoal]
            );

            if (!$penilaian) {
                $success = false;
                break;
            }
        }

        if ($success) {
            // Menghitung rata-rata nilai
            $totalNilai = array_sum($nilai);
            $jumlahNilai = count($nilai);
            $rataRata = $jumlahNilai > 0 ? $totalNilai / $jumlahNilai : 0;

            // Update nilai rata-rata di tabel pendaftaran
            $pendaftaran = Pendaftaran::find($pendaftaranId);
            if ($pendaftaran) {
                $pendaftaran->tahap_2 = "Selesai";
                $pendaftaran->nilai = $rataRata;
                $pendaftaran->save();
            }

            if ($pendaftaran) {
                return response()->json(['message' => 'Data dan rata-rata berhasil disimpan'], 200);
            } else {
                return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data'], 500);
            }
        } else {
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data'], 500);
        }
    }

    public function terimaSiaga(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_2 = "Selesai";
        $pendaftaran->status_2 = 2;
        $pendaftaran->save();

        Mail::to($pendaftaran->user->email)->send(new MailLolosAkhir($pendaftaran));
        return response()->json(['success' => 'Status berhasil diubah menjadi "Terima"']);
    }

    public function tolakSiaga(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_2 = "Selesai";
        $pendaftaran->status_2 = 3;
        $pendaftaran->save();

        Mail::to($pendaftaran->user->email)->send(new MailTolakAkhir($pendaftaran));
        return response()->json(['success' => 'Status berhasil diubah menjadi "Tolak"']);
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
        return view('backend.penilaian.all.print', compact('pendaftaran'));
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

        $pdf = Pdf::loadView('backend.penilaian.all.printPDF', compact('pendaftaran'));
        return $pdf->download('pendaftaran-siaga-' . time() . '.pdf');
    }

    public function indexPenggalang(Request $request)
    {
        if (request()->ajax()) {
            $query = '';

            if (auth()->user()->type == 'Juri') {
                $query = Pendaftaran::with('user', 'golongan')
                    ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                    ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                    ->orderBy('users.name', 'asc')
                    ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                    ->where('tahap_1', 'Selesai')
                    ->where('golongan.name', 'Penggalang')
                    ->where('status', 2);
            } else {
                $query = Pendaftaran::with('user', 'golongan')
                    ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                    ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                    ->orderBy('users.name', 'asc')
                    ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                    ->where('golongan.name', 'Penggalang');
            }

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
                ->addColumn('status_2', function ($pendaftaran) {
                    $statusText = '';
                    $badgeClass = '';

                    if ($pendaftaran->status_2 == 1) {
                        $badgeClass = 'badge-soft-info';
                        $statusText = 'Dalam Proses';
                    } elseif ($pendaftaran->status_2 == 2) {
                        $badgeClass = 'badge-soft-success';
                        $statusText = 'Terima';
                    } else {
                        $badgeClass = 'badge-soft-danger';
                        $statusText = 'Tolak';
                    }
                    $status_2 = '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';

                    return $status_2;
                })
                ->addColumn('aksi', function ($pendaftaran) {
                    $btn = '';
                    if ($pendaftaran->tahap_2 == null) {
                        $btn = '<a href="' . route('penilaian.penggalang.nilai', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-warning text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    } elseif ($pendaftaran->status_2 == 1) {
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTolak" title="Tolak"><i class="fas fa-times"></i></button>';
                    } else {
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    }

                    return $btn;
                })

                ->rawColumns(['nta', 'aksi', 'berkas', 'status_2'])
                ->make(true);
        }
        return view('backend.penilaian.penggalang.index');
    }

    public function nilaiPenggalang($id)
    {
        $pendaftaran = Pendaftaran::find($id);
        $soal = Soal::where('golongan_id', $pendaftaran->golongan_id)->get();
        return view('backend.penilaian.all.nilai', compact('soal', 'pendaftaran'));
    }

    public function simpanPenilaianPenggalang(Request $request)
    {
        $pendaftaranId = $request->input('pendaftaran_id');
        $nilai = $request->input('nilai');
        $success = true;

        foreach ($nilai as $soalId => $nilaiSoal) {
            $penilaian = Penilaian::updateOrCreate(
                ['pendaftaran_id' => $pendaftaranId, 'soal_id' => $soalId],
                ['nilai' => $nilaiSoal]
            );

            if (!$penilaian) {
                $success = false;
                break;
            }
        }

        if ($success) {
            // Menghitung rata-rata nilai
            $totalNilai = array_sum($nilai);
            $jumlahNilai = count($nilai);
            $rataRata = $jumlahNilai > 0 ? $totalNilai / $jumlahNilai : 0;

            // Update nilai rata-rata di tabel pendaftaran
            $pendaftaran = Pendaftaran::find($pendaftaranId);
            if ($pendaftaran) {
                $pendaftaran->tahap_2 = "Selesai";
                $pendaftaran->nilai = $rataRata;
                $pendaftaran->save();
            }

            if ($pendaftaran) {
                return response()->json(['message' => 'Data dan rata-rata berhasil disimpan'], 200);
            } else {
                return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data'], 500);
            }
        } else {
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data'], 500);
        }
    }

    public function terimaPenggalang(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_2 = "Selesai";
        $pendaftaran->status_2 = 2;
        $pendaftaran->save();

        Mail::to($pendaftaran->user->email)->send(new MailLolosAkhir($pendaftaran));
        return response()->json(['success' => 'Status berhasil diubah menjadi "Terima"']);
    }

    public function tolakPenggalang(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_2 = "Selesai";
        $pendaftaran->status_2 = 3;
        $pendaftaran->save();

        Mail::to($pendaftaran->user->email)->send(new MailTolakAkhir($pendaftaran));
        return response()->json(['success' => 'Status berhasil diubah menjadi "Tolak"']);
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
        return view('backend.penilaian.all.print', compact('pendaftaran'));
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

        $pdf = Pdf::loadView('backend.penilaian.all.printPDF', compact('pendaftaran'));
        return $pdf->download('pendaftaran-penggalang-' . time() . '.pdf');
    }

    public function indexPenegak(Request $request)
    {
        if (request()->ajax()) {
            $query = '';

            if (auth()->user()->type == 'Juri') {
                $query = Pendaftaran::with('user', 'golongan')
                    ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                    ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                    ->orderBy('users.name', 'asc')
                    ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                    ->where('tahap_1', 'Selesai')
                    ->where('golongan.name', 'Penegak')
                    ->where('status', 2);
            } else {
                $query = Pendaftaran::with('user', 'golongan')
                    ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                    ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                    ->orderBy('users.name', 'asc')
                    ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                    ->where('golongan.name', 'Penegak');
            }

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
                ->addColumn('status_2', function ($pendaftaran) {
                    $statusText = '';
                    $badgeClass = '';

                    if ($pendaftaran->status_2 == 1) {
                        $badgeClass = 'badge-soft-info';
                        $statusText = 'Dalam Proses';
                    } elseif ($pendaftaran->status_2 == 2) {
                        $badgeClass = 'badge-soft-success';
                        $statusText = 'Terima';
                    } else {
                        $badgeClass = 'badge-soft-danger';
                        $statusText = 'Tolak';
                    }
                    $status_2 = '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';

                    return $status_2;
                })
                ->addColumn('aksi', function ($pendaftaran) {
                    $btn = '';
                    if ($pendaftaran->tahap_2 == null) {
                        $btn = '<a href="' . route('penilaian.penegak.nilai', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-warning text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    } elseif ($pendaftaran->status_2 == 1) {
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTolak" title="Tolak"><i class="fas fa-times"></i></button>';
                    } else {
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    }

                    return $btn;
                })

                ->rawColumns(['nta', 'aksi', 'berkas', 'status_2'])
                ->make(true);
        }
        return view('backend.penilaian.penegak.index');
    }

    public function nilaiPenegak($id)
    {
        $pendaftaran = Pendaftaran::find($id);
        $soal = Soal::where('golongan_id', $pendaftaran->golongan_id)->get();
        return view('backend.penilaian.all.nilai', compact('soal', 'pendaftaran'));
    }

    public function simpanPenilaianPenegak(Request $request)
    {
        $pendaftaranId = $request->input('pendaftaran_id');
        $nilai = $request->input('nilai');
        $success = true;

        foreach ($nilai as $soalId => $nilaiSoal) {
            $penilaian = Penilaian::updateOrCreate(
                ['pendaftaran_id' => $pendaftaranId, 'soal_id' => $soalId],
                ['nilai' => $nilaiSoal]
            );

            if (!$penilaian) {
                $success = false;
                break;
            }
        }

        if ($success) {
            // Menghitung rata-rata nilai
            $totalNilai = array_sum($nilai);
            $jumlahNilai = count($nilai);
            $rataRata = $jumlahNilai > 0 ? $totalNilai / $jumlahNilai : 0;

            // Update nilai rata-rata di tabel pendaftaran
            $pendaftaran = Pendaftaran::find($pendaftaranId);
            if ($pendaftaran) {
                $pendaftaran->tahap_2 = "Selesai";
                $pendaftaran->nilai = $rataRata;
                $pendaftaran->save();
            }

            if ($pendaftaran) {
                return response()->json(['message' => 'Data dan rata-rata berhasil disimpan'], 200);
            } else {
                return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data'], 500);
            }
        } else {
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data'], 500);
        }
    }

    public function terimaPenegak(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_2 = "Selesai";
        $pendaftaran->status_2 = 2;
        $pendaftaran->save();

        Mail::to($pendaftaran->user->email)->send(new MailLolosAkhir($pendaftaran));
        return response()->json(['success' => 'Status berhasil diubah menjadi "Terima"']);
    }

    public function tolakPenegak(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_2 = "Selesai";
        $pendaftaran->status_2 = 3;
        $pendaftaran->save();

        Mail::to($pendaftaran->user->email)->send(new MailTolakAkhir($pendaftaran));
        return response()->json(['success' => 'Status berhasil diubah menjadi "Tolak"']);
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
        return view('backend.penilaian.all.print', compact('pendaftaran'));
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

        $pdf = Pdf::loadView('backend.penilaian.all.printPDF', compact('pendaftaran'));
        return $pdf->download('pendaftaran-penegak-' . time() . '.pdf');
    }

    public function indexPandega(Request $request)
    {
        if (request()->ajax()) {
            $query = '';

            if (auth()->user()->type == 'Juri') {
                $query = Pendaftaran::with('user', 'golongan')
                    ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                    ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                    ->orderBy('users.name', 'asc')
                    ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                    ->where('tahap_1', 'Selesai')
                    ->where('golongan.name', 'Pandega')
                    ->where('status', 2);
            } else {
                $query = Pendaftaran::with('user', 'golongan')
                    ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                    ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                    ->orderBy('users.name', 'asc')
                    ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                    ->where('golongan.name', 'Pandega');
            }

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
                ->addColumn('status_2', function ($pendaftaran) {
                    $statusText = '';
                    $badgeClass = '';

                    if ($pendaftaran->status_2 == 1) {
                        $badgeClass = 'badge-soft-info';
                        $statusText = 'Dalam Proses';
                    } elseif ($pendaftaran->status_2 == 2) {
                        $badgeClass = 'badge-soft-success';
                        $statusText = 'Terima';
                    } else {
                        $badgeClass = 'badge-soft-danger';
                        $statusText = 'Tolak';
                    }
                    $status_2 = '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';

                    return $status_2;
                })
                ->addColumn('aksi', function ($pendaftaran) {
                    $btn = '';
                    if ($pendaftaran->tahap_2 == null) {
                        $btn = '<a href="' . route('penilaian.pandega.nilai', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-warning text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    } elseif ($pendaftaran->status_2 == 1) {
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTolak" title="Tolak"><i class="fas fa-times"></i></button>';
                    } else {
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    }

                    return $btn;
                })

                ->rawColumns(['nta', 'aksi', 'berkas', 'status_2'])
                ->make(true);
        }
        return view('backend.penilaian.pandega.index');
    }

    public function nilaiPandega($id)
    {
        $pendaftaran = Pendaftaran::find($id);
        $soal = Soal::where('golongan_id', $pendaftaran->golongan_id)->get();
        return view('backend.penilaian.all.nilai', compact('soal', 'pendaftaran'));
    }

    public function simpanPenilaianPandega(Request $request)
    {
        $pendaftaranId = $request->input('pendaftaran_id');
        $nilai = $request->input('nilai');
        $success = true;

        foreach ($nilai as $soalId => $nilaiSoal) {
            $penilaian = Penilaian::updateOrCreate(
                ['pendaftaran_id' => $pendaftaranId, 'soal_id' => $soalId],
                ['nilai' => $nilaiSoal]
            );

            if (!$penilaian) {
                $success = false;
                break;
            }
        }

        if ($success) {
            // Menghitung rata-rata nilai
            $totalNilai = array_sum($nilai);
            $jumlahNilai = count($nilai);
            $rataRata = $jumlahNilai > 0 ? $totalNilai / $jumlahNilai : 0;

            // Update nilai rata-rata di tabel pendaftaran
            $pendaftaran = Pendaftaran::find($pendaftaranId);
            if ($pendaftaran) {
                $pendaftaran->tahap_2 = "Selesai";
                $pendaftaran->nilai = $rataRata;
                $pendaftaran->save();
            }

            if ($pendaftaran) {
                return response()->json(['message' => 'Data dan rata-rata berhasil disimpan'], 200);
            } else {
                return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data'], 500);
            }
        } else {
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data'], 500);
        }
    }

    public function terimaPandega(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_2 = "Selesai";
        $pendaftaran->status_2 = 2;
        $pendaftaran->save();

        Mail::to($pendaftaran->user->email)->send(new MailLolosAkhir($pendaftaran));
        return response()->json(['success' => 'Status berhasil diubah menjadi "Terima"']);
    }

    public function tolakPandega(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_2 = "Selesai";
        $pendaftaran->status_2 = 3;
        $pendaftaran->save();

        Mail::to($pendaftaran->user->email)->send(new MailTolakAkhir($pendaftaran));
        return response()->json(['success' => 'Status berhasil diubah menjadi "Tolak"']);
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
        return view('backend.penilaian.all.print', compact('pendaftaran'));
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

        $pdf = Pdf::loadView('backend.penilaian.all.printPDF', compact('pendaftaran'));
        return $pdf->download('pendaftaran-pandega-' . time() . '.pdf');
    }


    public function destroy(Request $request)
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
