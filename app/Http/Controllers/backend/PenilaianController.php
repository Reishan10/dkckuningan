<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Penilaian;
use App\Models\Soal;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PenilaianController extends Controller
{
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
