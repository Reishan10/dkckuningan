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
            $query = Pendaftaran::with('user', 'golongan')
                ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                ->orderBy('users.name', 'asc')
                ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                ->where('tahap_1', 'Selesai')
                ->where('status', 2);

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
                    $berkasLink = $user->berkas ? asset('berkas/' . $user->berkas) : null;

                    $berkas = $berkasLink
                        ? '<div class="d-flex flex-column"><a href="' . $berkasLink . '" target="_blank" class="btn btn-secondary btn-sm mr-2">Tautan ke Berkas</a><small class="text-muted text-center mt-2">' . ($user->original_size ?? 0) . ' KB - ' . ($user->compress_size ?? 0) . ' KB</small></div>'
                        : '<span class="text-muted">Berkas tidak tersedia</span>';

                    return $berkas;
                })

                ->addColumn('persentase', function ($user) {
                    $persentaseKompresi = (1 - ($user->compress_size / $user->original_size)) * 100;
                    $persentase = number_format($persentaseKompresi, 2) . '%';

                    return $persentase;
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
                    } else {
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
                    } elseif ($pendaftaran->status_2 == 4) {
                        $badgeClass = 'badge-soft-warning';
                        $statusText = 'Pertimbangkan';
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
                        if (auth()->user()->type == 'Juri') {
                            $btn .= '<a href="' . route('penilaian.all.nilai', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-warning text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                        }
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    } elseif ($pendaftaran->status_2 == 1) {
                        $btn .= '<a href="' . route('penilaian.all.nilai.edit', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-warning text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-secondary text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnPertimbangkan"><i class="fas fa-users"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTolak" title="Tolak"><i class="fas fa-times"></i></button>';
                    } elseif ($pendaftaran->status_2 == 4) {
                        $btn .= '<a href="' . route('penilaian.all.nilai.edit', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-warning text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTolak" title="Tolak"><i class="fas fa-times"></i></button>';
                    } else {
                        $btn = '<a href="' . route('penilaian.all.detail', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-info text-light me-2"><i class="fas fa-eye"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    }

                    return $btn;
                })

                ->rawColumns(['nta', 'aksi', 'berkas', 'status_1', 'status_2'])
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

    public function nilaiEditAll($id)
    {
        $pendaftar = Pendaftaran::with('user', 'golongan')->findOrFail($id);
        $penilaian = Penilaian::with('soal')->where('pendaftaran_id', $pendaftar->id)->get();
        return view('backend.penilaian.all.nilai_edit', compact('pendaftar', 'penilaian'));
    }

    public function updatePenilaianAll(Request $request)
    {
        $pendaftaranId = $request->input('pendaftaran_id');
        $nilai = $request->input('nilai');

        // Update nilai di tabel Penilaian
        foreach ($nilai as $soalId => $nilaiSoal) {
            $penilaian = Penilaian::updateOrCreate(
                ['pendaftaran_id' => $pendaftaranId, 'soal_id' => $soalId],
                ['nilai' => $nilaiSoal]
            );
        }

        // Menghitung rata-rata nilai
        $totalNilai = array_sum($nilai);
        $jumlahNilai = count($nilai);
        $rataRata = $jumlahNilai > 0 ? $totalNilai / $jumlahNilai : 0;

        // Update nilai rata-rata di tabel Pendaftaran
        $pendaftaran = Pendaftaran::find($pendaftaranId);
        if ($pendaftaran) {
            $pendaftaran->nilai = $rataRata;
            $pendaftaran->save();
            return response()->json(['message' => 'Data dan rata-rata berhasil disimpan'], 200);
        } else {
            return response()->json(['message' => 'Pendaftaran tidak ditemukan'], 404);
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

    public function pertimbangkanAll(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_2 = "Selesai";
        $pendaftaran->status_2 = 4;
        $pendaftaran->save();
        return response()->json(['success' => 'Status berhasil diubah menjadi "Pertimbangkan"']);
    }

    public function printAll()
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->orderBy('users.name', 'asc')
            ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.name', 'golongan.name as golongan_name', 'pangkalan')
            ->where('tahap_1', 'Selesai')
            ->where('status', 2)
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
            ->where('tahap_1', 'Selesai')
            ->where('status', 2)
            ->get();

        $pdf = Pdf::loadView('backend.penilaian.all.printPDF', compact('pendaftaran'));
        return $pdf->download('interview-semua-' . time() . '.pdf');
    }

    public function detailAll($id)
    {
        $detail_user = Pendaftaran::with('user', 'golongan')->findOrFail($id);
        $penilaian = Penilaian::with('soal')->where('pendaftaran_id', $detail_user->id)->get();
        return view('backend.penilaian.all.detail', compact('detail_user', 'penilaian'));
    }

    public function indexSiaga(Request $request)
    {
        if (request()->ajax()) {
            $query = Pendaftaran::with('user', 'golongan')
                ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                ->orderBy('users.name', 'asc')
                ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                ->where('tahap_1', 'Selesai')
                ->where('status', 2)
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
                    $berkasLink = $user->berkas ? asset('berkas/' . $user->berkas) : null;

                    $berkas = $berkasLink
                        ? '<div class="d-flex flex-column"><a href="' . $berkasLink . '" target="_blank" class="btn btn-secondary btn-sm mr-2">Tautan ke Berkas</a><small class="text-muted text-center mt-2">' . ($user->original_size ?? 0) . ' KB - ' . ($user->compress_size ?? 0) . ' KB</small></div>'
                        : '<span class="text-muted">Berkas tidak tersedia</span>';

                    return $berkas;
                })

                ->addColumn('persentase', function ($user) {
                    $persentaseKompresi = (1 - ($user->compress_size / $user->original_size)) * 100;
                    $persentase = number_format($persentaseKompresi, 2) . '%';

                    return $persentase;
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
                    } else {
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
                    } elseif ($pendaftaran->status_2 == 4) {
                        $badgeClass = 'badge-soft-warning';
                        $statusText = 'Pertimbangkan';
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
                        if (auth()->user()->type == 'Juri') {
                            $btn .= '<a href="' . route('penilaian.all.nilai', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-warning text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                        }
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    } elseif ($pendaftaran->status_2 == 1) {
                        $btn .= '<a href="' . route('penilaian.siaga.nilai.edit', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-warning text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-secondary text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnPertimbangkan"><i class="fas fa-users"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTolak" title="Tolak"><i class="fas fa-times"></i></button>';
                    } elseif ($pendaftaran->status_2 == 4) {
                        $btn .= '<a href="' . route('penilaian.siaga.nilai.edit', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-warning text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTolak" title="Tolak"><i class="fas fa-times"></i></button>';
                    } else {
                        $btn = '<a href="' . route('penilaian.siaga.detail', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-info text-light me-2"><i class="fas fa-eye"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    }

                    return $btn;
                })

                ->rawColumns(['nta', 'aksi', 'berkas', 'status_1', 'status_2'])
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

    public function nilaiEditSiaga($id)
    {
        $pendaftar = Pendaftaran::with('user', 'golongan')->findOrFail($id);
        $penilaian = Penilaian::with('soal')->where('pendaftaran_id', $pendaftar->id)->get();
        return view('backend.penilaian.siaga.nilai_edit', compact('pendaftar', 'penilaian'));
    }

    public function updatePenilaianSiaga(Request $request)
    {
        $pendaftaranId = $request->input('pendaftaran_id');
        $nilai = $request->input('nilai');

        // Update nilai di tabel Penilaian
        foreach ($nilai as $soalId => $nilaiSoal) {
            $penilaian = Penilaian::updateOrCreate(
                ['pendaftaran_id' => $pendaftaranId, 'soal_id' => $soalId],
                ['nilai' => $nilaiSoal]
            );
        }

        // Menghitung rata-rata nilai
        $totalNilai = array_sum($nilai);
        $jumlahNilai = count($nilai);
        $rataRata = $jumlahNilai > 0 ? $totalNilai / $jumlahNilai : 0;

        // Update nilai rata-rata di tabel Pendaftaran
        $pendaftaran = Pendaftaran::find($pendaftaranId);
        if ($pendaftaran) {
            $pendaftaran->nilai = $rataRata;
            $pendaftaran->save();
            return response()->json(['message' => 'Data dan rata-rata berhasil disimpan'], 200);
        } else {
            return response()->json(['message' => 'Pendaftaran tidak ditemukan'], 404);
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

    public function pertimbangkanSiaga(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_2 = "Selesai";
        $pendaftaran->status_2 = 4;
        $pendaftaran->save();
        return response()->json(['success' => 'Status berhasil diubah menjadi "Pertimbangkan"']);
    }

    public function printSiaga()
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->orderBy('users.name', 'asc')
            ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.name', 'golongan.name as golongan_name', 'pangkalan')
            ->where('tahap_1', 'Selesai')
            ->where('status', 2)
            ->where('golongan.name', 'Siaga')
            ->get();
        return view('backend.penilaian.siaga.print', compact('pendaftaran'));
    }

    public function printPDFSiaga()
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->orderBy('users.name', 'asc')
            ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.name', 'golongan.name as golongan_name', 'pangkalan')
            ->where('tahap_1', 'Selesai')
            ->where('status', 2)
            ->where('golongan.name', 'Siaga')
            ->get();

        $pdf = Pdf::loadView('backend.penilaian.siaga.printPDF', compact('pendaftaran'));
        return $pdf->download('interview-siaga-' . time() . '.pdf');
    }

    public function detailSiaga($id)
    {
        $detail_user = Pendaftaran::with('user', 'golongan')->findOrFail($id);
        $penilaian = Penilaian::with('soal')->where('pendaftaran_id', $detail_user->id)->get();
        return view('backend.penilaian.siaga.detail', compact('detail_user', 'penilaian'));
    }

    public function indexPenggalang(Request $request)
    {
        if (request()->ajax()) {
            $query = Pendaftaran::with('user', 'golongan')
                ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                ->orderBy('users.name', 'asc')
                ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                ->where('tahap_1', 'Selesai')
                ->where('status', 2)
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
                    $berkasLink = $user->berkas ? asset('berkas/' . $user->berkas) : null;

                    $berkas = $berkasLink
                        ? '<div class="d-flex flex-column"><a href="' . $berkasLink . '" target="_blank" class="btn btn-secondary btn-sm mr-2">Tautan ke Berkas</a><small class="text-muted text-center mt-2">' . ($user->original_size ?? 0) . ' KB - ' . ($user->compress_size ?? 0) . ' KB</small></div>'
                        : '<span class="text-muted">Berkas tidak tersedia</span>';

                    return $berkas;
                })

                ->addColumn('persentase', function ($user) {
                    $persentaseKompresi = (1 - ($user->compress_size / $user->original_size)) * 100;
                    $persentase = number_format($persentaseKompresi, 2) . '%';

                    return $persentase;
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
                    } else {
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
                    } elseif ($pendaftaran->status_2 == 4) {
                        $badgeClass = 'badge-soft-warning';
                        $statusText = 'Pertimbangkan';
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
                        if (auth()->user()->type == 'Juri') {
                            $btn .= '<a href="' . route('penilaian.all.nilai', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-warning text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                        }
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    } elseif ($pendaftaran->status_2 == 1) {
                        $btn .= '<a href="' . route('penilaian.penggalang.nilai.edit', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-warning text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-secondary text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnPertimbangkan"><i class="fas fa-users"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTolak" title="Tolak"><i class="fas fa-times"></i></button>';
                    } elseif ($pendaftaran->status_2 == 4) {
                        $btn .= '<a href="' . route('penilaian.penggalang.nilai.edit', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-warning text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTolak" title="Tolak"><i class="fas fa-times"></i></button>';
                    } else {
                        $btn = '<a href="' . route('penilaian.penggalang.detail', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-info text-light me-2"><i class="fas fa-eye"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    }

                    return $btn;
                })

                ->rawColumns(['nta', 'aksi', 'berkas', 'status_1', 'status_2'])
                ->make(true);
        }
        return view('backend.penilaian.penggalang.index');
    }

    public function nilaiPenggalang($id)
    {
        $pendaftaran = Pendaftaran::find($id);
        $soal = Soal::where('golongan_id', $pendaftaran->golongan_id)->get();
        return view('backend.penilaian.penggalang.nilai', compact('soal', 'pendaftaran'));
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

    public function nilaiEditPenggalang($id)
    {
        $pendaftar = Pendaftaran::with('user', 'golongan')->findOrFail($id);
        $penilaian = Penilaian::with('soal')->where('pendaftaran_id', $pendaftar->id)->get();
        return view('backend.penilaian.penggalang.nilai_edit', compact('pendaftar', 'penilaian'));
    }

    public function updatePenilaianPenggalang(Request $request)
    {
        $pendaftaranId = $request->input('pendaftaran_id');
        $nilai = $request->input('nilai');

        // Update nilai di tabel Penilaian
        foreach ($nilai as $soalId => $nilaiSoal) {
            $penilaian = Penilaian::updateOrCreate(
                ['pendaftaran_id' => $pendaftaranId, 'soal_id' => $soalId],
                ['nilai' => $nilaiSoal]
            );
        }

        // Menghitung rata-rata nilai
        $totalNilai = array_sum($nilai);
        $jumlahNilai = count($nilai);
        $rataRata = $jumlahNilai > 0 ? $totalNilai / $jumlahNilai : 0;

        // Update nilai rata-rata di tabel Pendaftaran
        $pendaftaran = Pendaftaran::find($pendaftaranId);
        if ($pendaftaran) {
            $pendaftaran->nilai = $rataRata;
            $pendaftaran->save();
            return response()->json(['message' => 'Data dan rata-rata berhasil disimpan'], 200);
        } else {
            return response()->json(['message' => 'Pendaftaran tidak ditemukan'], 404);
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

    public function pertimbangkanPenggalang(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_2 = "Selesai";
        $pendaftaran->status_2 = 4;
        $pendaftaran->save();
        return response()->json(['success' => 'Status berhasil diubah menjadi "Pertimbangkan"']);
    }

    public function printPenggalang()
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->orderBy('users.name', 'asc')
            ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.name', 'golongan.name as golongan_name', 'pangkalan')
            ->where('tahap_1', 'Selesai')
            ->where('status', 2)
            ->where('golongan.name', 'Penggalang')
            ->get();
        return view('backend.penilaian.penggalang.print', compact('pendaftaran'));
    }

    public function printPDFPenggalang()
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->orderBy('users.name', 'asc')
            ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.name', 'golongan.name as golongan_name', 'pangkalan')
            ->where('tahap_1', 'Selesai')
            ->where('status', 2)
            ->where('golongan.name', 'Penggalang')
            ->get();

        $pdf = Pdf::loadView('backend.penilaian.penggalang.printPDF', compact('pendaftaran'));
        return $pdf->download('invertiew-penggalang-' . time() . '.pdf');
    }

    public function detailPenggalang($id)
    {
        $detail_user = Pendaftaran::with('user', 'golongan')->findOrFail($id);
        $penilaian = Penilaian::with('soal')->where('pendaftaran_id', $detail_user->id)->get();
        return view('backend.penilaian.penggalang.detail', compact('detail_user', 'penilaian'));
    }

    public function indexPenegak(Request $request)
    {
        if (request()->ajax()) {
            $query = Pendaftaran::with('user', 'golongan')
                ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                ->orderBy('users.name', 'asc')
                ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                ->where('tahap_1', 'Selesai')
                ->where('status', 2)
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
                    $berkasLink = $user->berkas ? asset('berkas/' . $user->berkas) : null;

                    $berkas = $berkasLink
                        ? '<div class="d-flex flex-column"><a href="' . $berkasLink . '" target="_blank" class="btn btn-secondary btn-sm mr-2">Tautan ke Berkas</a><small class="text-muted text-center mt-2">' . ($user->original_size ?? 0) . ' KB - ' . ($user->compress_size ?? 0) . ' KB</small></div>'
                        : '<span class="text-muted">Berkas tidak tersedia</span>';

                    return $berkas;
                })

                ->addColumn('persentase', function ($user) {
                    $persentaseKompresi = (1 - ($user->compress_size / $user->original_size)) * 100;
                    $persentase = number_format($persentaseKompresi, 2) . '%';

                    return $persentase;
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
                    } else {
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
                    } elseif ($pendaftaran->status_2 == 4) {
                        $badgeClass = 'badge-soft-warning';
                        $statusText = 'Pertimbangkan';
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
                        if (auth()->user()->type == 'Juri') {
                            $btn .= '<a href="' . route('penilaian.all.nilai', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-warning text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                        }
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    } elseif ($pendaftaran->status_2 == 1) {
                        $btn .= '<a href="' . route('penilaian.penegak.nilai.edit', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-warning text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-secondary text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnPertimbangkan"><i class="fas fa-users"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTolak" title="Tolak"><i class="fas fa-times"></i></button>';
                    } elseif ($pendaftaran->status_2 == 4) {
                        $btn .= '<a href="' . route('penilaian.penegak.nilai.edit', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-warning text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTolak" title="Tolak"><i class="fas fa-times"></i></button>';
                    } else {
                        $btn = '<a href="' . route('penilaian.penegak.detail', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-info text-light me-2"><i class="fas fa-eye"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    }

                    return $btn;
                })

                ->rawColumns(['nta', 'aksi', 'berkas', 'status_1', 'status_2'])
                ->make(true);
        }
        return view('backend.penilaian.penegak.index');
    }

    public function nilaiPenegak($id)
    {
        $pendaftaran = Pendaftaran::find($id);
        $soal = Soal::where('golongan_id', $pendaftaran->golongan_id)->get();
        return view('backend.penilaian.penegak.nilai', compact('soal', 'pendaftaran'));
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

    public function nilaiEditPenegak($id)
    {
        $pendaftar = Pendaftaran::with('user', 'golongan')->findOrFail($id);
        $penilaian = Penilaian::with('soal')->where('pendaftaran_id', $pendaftar->id)->get();
        return view('backend.penilaian.penegak.nilai_edit', compact('pendaftar', 'penilaian'));
    }

    public function updatePenilaianPenegak(Request $request)
    {
        $pendaftaranId = $request->input('pendaftaran_id');
        $nilai = $request->input('nilai');

        // Update nilai di tabel Penilaian
        foreach ($nilai as $soalId => $nilaiSoal) {
            $penilaian = Penilaian::updateOrCreate(
                ['pendaftaran_id' => $pendaftaranId, 'soal_id' => $soalId],
                ['nilai' => $nilaiSoal]
            );
        }

        // Menghitung rata-rata nilai
        $totalNilai = array_sum($nilai);
        $jumlahNilai = count($nilai);
        $rataRata = $jumlahNilai > 0 ? $totalNilai / $jumlahNilai : 0;

        // Update nilai rata-rata di tabel Pendaftaran
        $pendaftaran = Pendaftaran::find($pendaftaranId);
        if ($pendaftaran) {
            $pendaftaran->nilai = $rataRata;
            $pendaftaran->save();
            return response()->json(['message' => 'Data dan rata-rata berhasil disimpan'], 200);
        } else {
            return response()->json(['message' => 'Pendaftaran tidak ditemukan'], 404);
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

    public function pertimbangkanPenegak(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_2 = "Selesai";
        $pendaftaran->status_2 = 4;
        $pendaftaran->save();
        return response()->json(['success' => 'Status berhasil diubah menjadi "Pertimbangkan"']);
    }

    public function printPenegak()
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->orderBy('users.name', 'asc')
            ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.name', 'golongan.name as golongan_name', 'pangkalan')
            ->where('tahap_1', 'Selesai')
            ->where('status', 2)
            ->where('golongan.name', 'Penegak')
            ->get();
        return view('backend.penilaian.penegak.print', compact('pendaftaran'));
    }

    public function printPDFPenegak()
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->orderBy('users.name', 'asc')
            ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.name', 'golongan.name as golongan_name', 'pangkalan')
            ->where('tahap_1', 'Selesai')
            ->where('status', 2)
            ->where('golongan.name', 'Penegak')
            ->get();

        $pdf = Pdf::loadView('backend.penilaian.penegak.printPDF', compact('pendaftaran'));
        return $pdf->download('interview-penegak-' . time() . '.pdf');
    }

    public function detailPenegak($id)
    {
        $detail_user = Pendaftaran::with('user', 'golongan')->findOrFail($id);
        $penilaian = Penilaian::with('soal')->where('pendaftaran_id', $detail_user->id)->get();
        return view('backend.penilaian.penegak.detail', compact('detail_user', 'penilaian'));
    }

    public function indexPandega(Request $request)
    {
        if (request()->ajax()) {
            $query = Pendaftaran::with('user', 'golongan')
                ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                ->orderBy('users.name', 'asc')
                ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*')
                ->where('tahap_1', 'Selesai')
                ->where('status', 2)
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
                    $berkasLink = $user->berkas ? asset('berkas/' . $user->berkas) : null;

                    $berkas = $berkasLink
                        ? '<div class="d-flex flex-column"><a href="' . $berkasLink . '" target="_blank" class="btn btn-secondary btn-sm mr-2">Tautan ke Berkas</a><small class="text-muted text-center mt-2">' . ($user->original_size ?? 0) . ' KB - ' . ($user->compress_size ?? 0) . ' KB</small></div>'
                        : '<span class="text-muted">Berkas tidak tersedia</span>';

                    return $berkas;
                })

                ->addColumn('persentase', function ($user) {
                    $persentaseKompresi = (1 - ($user->compress_size / $user->original_size)) * 100;
                    $persentase = number_format($persentaseKompresi, 2) . '%';

                    return $persentase;
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
                    } else {
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
                    } elseif ($pendaftaran->status_2 == 4) {
                        $badgeClass = 'badge-soft-warning';
                        $statusText = 'Pertimbangkan';
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
                        if (auth()->user()->type == 'Juri') {
                            $btn .= '<a href="' . route('penilaian.all.nilai', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-warning text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                        }
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    } elseif ($pendaftaran->status_2 == 1) {
                        $btn .= '<a href="' . route('penilaian.pandega.nilai.edit', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-warning text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-secondary text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnPertimbangkan"><i class="fas fa-users"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTolak" title="Tolak"><i class="fas fa-times"></i></button>';
                    } elseif ($pendaftaran->status_2 == 4) {
                        $btn .= '<a href="' . route('penilaian.pandega.nilai.edit', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-warning text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-success text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTerima"><i class="fas fa-check"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnTolak" title="Tolak"><i class="fas fa-times"></i></button>';
                    } else {
                        $btn = '<a href="' . route('penilaian.pandega.detail', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-info text-light me-2"><i class="fas fa-eye"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    }

                    return $btn;
                })

                ->rawColumns(['nta', 'aksi', 'berkas', 'status_1', 'status_2'])
                ->make(true);
        }
        return view('backend.penilaian.pandega.index');
    }

    public function nilaiPandega($id)
    {
        $pendaftaran = Pendaftaran::find($id);
        $soal = Soal::where('golongan_id', $pendaftaran->golongan_id)->get();
        return view('backend.penilaian.pandega.nilai', compact('soal', 'pendaftaran'));
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

    public function nilaiEditPandega($id)
    {
        $pendaftar = Pendaftaran::with('user', 'golongan')->findOrFail($id);
        $penilaian = Penilaian::with('soal')->where('pendaftaran_id', $pendaftar->id)->get();
        return view('backend.penilaian.pandega.nilai_edit', compact('pendaftar', 'penilaian'));
    }

    public function updatePenilaianPandega(Request $request)
    {
        $pendaftaranId = $request->input('pendaftaran_id');
        $nilai = $request->input('nilai');

        // Update nilai di tabel Penilaian
        foreach ($nilai as $soalId => $nilaiSoal) {
            $penilaian = Penilaian::updateOrCreate(
                ['pendaftaran_id' => $pendaftaranId, 'soal_id' => $soalId],
                ['nilai' => $nilaiSoal]
            );
        }

        // Menghitung rata-rata nilai
        $totalNilai = array_sum($nilai);
        $jumlahNilai = count($nilai);
        $rataRata = $jumlahNilai > 0 ? $totalNilai / $jumlahNilai : 0;

        // Update nilai rata-rata di tabel Pendaftaran
        $pendaftaran = Pendaftaran::find($pendaftaranId);
        if ($pendaftaran) {
            $pendaftaran->nilai = $rataRata;
            $pendaftaran->save();
            return response()->json(['message' => 'Data dan rata-rata berhasil disimpan'], 200);
        } else {
            return response()->json(['message' => 'Pendaftaran tidak ditemukan'], 404);
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

    public function pertimbangkanPandega(Request $request)
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')->findOrFail($request->id);
        $pendaftaran->tahap_2 = "Selesai";
        $pendaftaran->status_2 = 4;
        $pendaftaran->save();
        return response()->json(['success' => 'Status berhasil diubah menjadi "Pertimbangkan"']);
    }

    public function printPandega()
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->orderBy('users.name', 'asc')
            ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.name', 'golongan.name as golongan_name', 'pangkalan')
            ->where('tahap_1', 'Selesai')
            ->where('status', 2)
            ->where('golongan.name', 'Pandega')
            ->get();
        return view('backend.penilaian.pandega.print', compact('pendaftaran'));
    }

    public function printPDFPandega()
    {
        $pendaftaran = Pendaftaran::with('user', 'golongan')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
            ->orderBy('users.name', 'asc')
            ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.name', 'golongan.name as golongan_name', 'pangkalan')
            ->where('tahap_1', 'Selesai')
            ->where('status', 2)
            ->where('golongan.name', 'Pandega')
            ->get();

        $pdf = Pdf::loadView('backend.penilaian.pandega.printPDF', compact('pendaftaran'));
        return $pdf->download('interview-pandega-' . time() . '.pdf');
    }

    public function detailPandega($id)
    {
        $detail_user = Pendaftaran::with('user', 'golongan')->findOrFail($id);
        $penilaian = Penilaian::with('soal')->where('pendaftaran_id', $detail_user->id)->get();
        return view('backend.penilaian.pandega.detail', compact('detail_user', 'penilaian'));
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
