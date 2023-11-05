<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Penilaian;
use App\Models\Soal;
use Illuminate\Http\Request;
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
                ->where('tahap_1', 'Terima');

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

                ->addColumn('aksi', function ($pendaftaran) {
                    $btn = '<a href="' . route('penilaian.all.nilai', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-info text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';

                    return $btn;
                })

                ->rawColumns(['nta', 'aksi', 'berkas'])
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
        $status = 1;
        $success = true;

        foreach ($nilai as $soalId => $nilaiSoal) {
            $penilaian = Penilaian::updateOrCreate(
                ['pendaftaran_id' => $pendaftaranId, 'soal_id' => $soalId],
                ['nilai' => $nilaiSoal, 'status' => $status]
            );

            if (!$penilaian) {
                $success = false;
                break;
            }
        }

        if ($success) {
            return response()->json(['message' => 'Data berhasil disimpan'], 200);
        } else {
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data'], 500);
        }
    }



    public function indexSiaga()
    {
        if (request()->ajax()) {
            $pendaftaran = Pendaftaran::with('user', 'golongan')
                ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                ->orderBy('users.name', 'asc')
                ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*', 'golongan.name as golongan_name')
                ->where('status', 1)
                ->where('golongan.name', 'Siaga')
                ->get();

            return DataTables::of($pendaftaran)
                ->addIndexColumn()

                ->addColumn('aksi', function ($pendaftaran) {
                    $btn = '<a href="' . route('penilaian-siaga.nilai', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-info text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';

                    return $btn;
                })

                ->rawColumns(['aksi'])
                ->make(true);
        }
        return view('backend.penilaian.siaga.index');
    }

    public function nilaiSiaga($id)
    {
        $pendaftaran = Pendaftaran::find($id);
        $soal = Soal::where('golongan_id', $pendaftaran->golongan_id)->get();
        return view('backend.penilaian.siaga.nilai', compact('soal', 'pendaftaran'));
    }

    public function simpanPenilaianSiaga(Request $request)
    {
        $pendaftaranId = $request->input('pendaftaran_id');
        $nilai = $request->input('nilai');

        foreach ($nilai as $soalId => $nilaiSoal) {
            Penilaian::updateOrCreate(
                ['pendaftaran_id' => $pendaftaranId, 'soal_id' => $soalId],
                ['nilai' => $nilaiSoal]
            );
        }

        // Respon sukses
        return response()->json(['message' => 'Data berhasil disimpan'], 200);
    }

    public function indexPenggalang()
    {
        if (request()->ajax()) {
            $pendaftaran = Pendaftaran::with('user', 'golongan')
                ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                ->orderBy('users.name', 'asc')
                ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*', 'golongan.name as golongan_name')
                ->where('status', 1)
                ->where('golongan.name', 'Penggalang')
                ->get();

            return DataTables::of($pendaftaran)
                ->addIndexColumn()

                ->addColumn('aksi', function ($pendaftaran) {
                    $btn = '<a href="' . route('penilaian-penggalang.nilai', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-info text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';

                    return $btn;
                })

                ->rawColumns(['aksi'])
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

        foreach ($nilai as $soalId => $nilaiSoal) {
            Penilaian::updateOrCreate(
                ['pendaftaran_id' => $pendaftaranId, 'soal_id' => $soalId],
                ['nilai' => $nilaiSoal]
            );
        }

        // Respon sukses
        return response()->json(['message' => 'Data berhasil disimpan'], 200);
    }

    public function indexPenegak()
    {
        if (request()->ajax()) {
            $pendaftaran = Pendaftaran::with('user', 'golongan')
                ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                ->orderBy('users.name', 'asc')
                ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*', 'golongan.name as golongan_name')
                ->where('status', 1)
                ->where('golongan.name', 'Penegak')
                ->get();

            return DataTables::of($pendaftaran)
                ->addIndexColumn()

                ->addColumn('aksi', function ($pendaftaran) {
                    $btn = '<a href="' . route('penilaian-penegak.nilai', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-info text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';

                    return $btn;
                })

                ->rawColumns(['aksi'])
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

        foreach ($nilai as $soalId => $nilaiSoal) {
            Penilaian::updateOrCreate(
                ['pendaftaran_id' => $pendaftaranId, 'soal_id' => $soalId],
                ['nilai' => $nilaiSoal]
            );
        }

        // Respon sukses
        return response()->json(['message' => 'Data berhasil disimpan'], 200);
    }

    public function indexPandega()
    {
        if (request()->ajax()) {
            $pendaftaran = Pendaftaran::with('user', 'golongan')
                ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                ->join('golongan', 'pendaftaran.golongan_id', '=', 'golongan.id')
                ->orderBy('users.name', 'asc')
                ->select('pendaftaran.id as pendaftaran_id', 'users.id as user_id', 'users.*', 'pendaftaran.*', 'golongan.name as golongan_name')
                ->where('status', 1)
                ->where('golongan.name', 'Pandega')
                ->get();

            return DataTables::of($pendaftaran)
                ->addIndexColumn()

                ->addColumn('aksi', function ($pendaftaran) {
                    $btn = '<a href="' . route('penilaian-pandega.nilai', $pendaftaran->pendaftaran_id) . '" class="btn btn-sm btn-info text-light me-2"><i class="fas fa-pencil-alt"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->pendaftaran_id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';

                    return $btn;
                })

                ->rawColumns(['aksi'])
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

        foreach ($nilai as $soalId => $nilaiSoal) {
            Penilaian::updateOrCreate(
                ['pendaftaran_id' => $pendaftaranId, 'soal_id' => $soalId],
                ['nilai' => $nilaiSoal]
            );
        }

        // Respon sukses
        return response()->json(['message' => 'Data berhasil disimpan'], 200);
    }
}
