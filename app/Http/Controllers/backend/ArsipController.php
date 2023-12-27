<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\SKLulus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ArsipController extends Controller
{
    public function indexKelulusan()
    {
        if (request()->ajax()) {
            $kelulusan = SKLulus::orderBy('created_at', 'desc')->get();

            return DataTables::of($kelulusan)
                ->addIndexColumn()
                ->addColumn('file', function ($kelulusan) {
                    $file = '<a href="' . route('sk.download', ['id' => $kelulusan->id]) . '" class="btn btn-secondary btn-sm">Download</a>';
                    return $file;
                })
                ->addColumn('aksi', function ($kelulusan) {
                    $btn = '';
                    if ($kelulusan->created_at == SKLulus::latest()->first()->created_at) {
                        $btn .= '<a href="' . route('sk.send') . '" class="btn btn-sm btn-info me-2 text-light"><i class="fas fa-share"></i></a>';
                    }
                    $btn .= '<a href="' . route('surat-kelulusan.edit', ['id' => $kelulusan->id]) . '" class="btn btn-sm btn-warning me-2 text-light"><i class="fas fa-pencil-alt"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $kelulusan->id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';

                    return $btn;
                })
                ->rawColumns(['file', 'aksi'])
                ->make(true);
        }

        return view('backend.arsip.kelulusan.index');
    }


    public function createKelulusan()
    {
        return view('backend.arsip.kelulusan.add');
    }

    public function storeKelulusan(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:arsip,name',
                'tahun' => 'required|string',
                'lokasi' => 'required|string',
                'tanggal_penetapan' => 'required|date',
                'nomor_lampiran' => 'required|string',
                'tanggal_lampiran' => 'required|date',
                'tentang_lampiran' => 'required|string',
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'name.unique' => 'Nama arsip sudah tersedia!',
                'tahun.required' => 'Silakan isi tahun terlebih dahulu!',
                'lokasi.required' => 'Silakan isi lokasi terlebih dahulu!',
                'tanggal_penetapan.required' => 'Silakan isi tanggal penetapan terlebih dahulu!',
                'tanggal_penetapan.date' => 'Format tanggal penetapan tidak valid.',
                'nomor_lampiran.required' => 'Silakan isi nomor lampiran terlebih dahulu!',
                'tanggal_lampiran.required' => 'Silakan isi tanggal lampiran terlebih dahulu!',
                'tanggal_lampiran.date' => 'Format tanggal lampiran tidak valid.',
                'tentang_lampiran.required' => 'Silakan isi tentang lampiran terlebih dahulu!',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $skKelulus = new SKLulus();
            $skKelulus->name = $request->name;
            $skKelulus->tahun = $request->tahun;
            $skKelulus->lokasi = $request->lokasi;
            $skKelulus->tanggal_penetapan = $request->tanggal_penetapan;
            $skKelulus->nomor_lampiran = $request->nomor_lampiran;
            $skKelulus->tanggal_lampiran = $request->tanggal_lampiran;
            $skKelulus->tentang_lampiran = $request->tentang_lampiran;
            $skKelulus->save();
            return response()->json($skKelulus);
        }
    }

    public function editKelulusan($id)
    {
        $arsip = SKLulus::where('id', $id)->first();
        return view('backend.arsip.kelulusan.edit', compact('arsip'));
    }

    public function updateKelulusan(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:arsip,name',
                'tahun' => 'required|string',
                'lokasi' => 'required|string',
                'tanggal_penetapan' => 'required|date',
                'nomor_lampiran' => 'required|string',
                'tanggal_lampiran' => 'required|date',
                'tentang_lampiran' => 'required|string',
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'name.unique' => 'Nama arsip sudah tersedia!',
                'tahun.required' => 'Silakan isi tahun terlebih dahulu!',
                'lokasi.required' => 'Silakan isi lokasi terlebih dahulu!',
                'tanggal_penetapan.required' => 'Silakan isi tanggal penetapan terlebih dahulu!',
                'tanggal_penetapan.date' => 'Format tanggal penetapan tidak valid.',
                'nomor_lampiran.required' => 'Silakan isi nomor lampiran terlebih dahulu!',
                'tanggal_lampiran.required' => 'Silakan isi tanggal lampiran terlebih dahulu!',
                'tanggal_lampiran.date' => 'Format tanggal lampiran tidak valid.',
                'tentang_lampiran.required' => 'Silakan isi tentang lampiran terlebih dahulu!',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $skKelulus = SKLulus::find($id);
            $skKelulus->name = $request->name;
            $skKelulus->tahun = $request->tahun;
            $skKelulus->lokasi = $request->lokasi;
            $skKelulus->tanggal_penetapan = $request->tanggal_penetapan;
            $skKelulus->nomor_lampiran = $request->nomor_lampiran;
            $skKelulus->tanggal_lampiran = $request->tanggal_lampiran;
            $skKelulus->tentang_lampiran = $request->tentang_lampiran;
            $skKelulus->save();
            return response()->json($skKelulus);
        }
    }

    public function destroyKelulusan(Request $request)
    {
        $arsip = SKLulus::find($request->id);
        $arsip->delete();

        return response()->json(['success' => 'Data berhasil dihapus']);
    }

    public function indexPendaftaran()
    {
        if (request()->ajax()) {
            $pendaftaran = Arsip::orderBy('created_at', 'desc')->where('type', '1')->get();

            return DataTables::of($pendaftaran)
                ->addIndexColumn()
                ->addColumn('file', function ($pendaftaran) {
                    $file = $pendaftaran->file ? '<a href="' . Storage::url('public/arsip/pendaftaran/' . $pendaftaran->file) . '" target="_blank" class="btn btn-secondary btn-sm">Tautan ke Berkas</a>' : 'Berkas tidak tersedia';
                    return $file;
                })
                ->addColumn('aksi', function ($pendaftaran) {
                    $btn = '<a href="' . route('berkas-pendaftaran.edit', ['id' => $pendaftaran->id]) . '" class="btn btn-sm btn-warning me-2 text-light"><i class="fas fa-pencil-alt"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $pendaftaran->id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';

                    return $btn;
                })
                ->rawColumns(['file', 'aksi'])
                ->make(true);
        }

        return view('backend.arsip.pendaftaran.index');
    }

    public function createPendaftaran()
    {
        return view('backend.arsip.pendaftaran.add');
    }

    public function storePendaftaran(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:arsip,name',
                'tanggal_terbit' => 'required|string',
                'file' => 'required|mimes:pdf,jpg,jpeg,png'
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'name.unique' => 'Golongan sudah tersedia!',
                'tanggal_terbit.required' => 'Silakan isi tanggal terbit terlebih dahulu!',
                'file.required' => 'Silakan unggah file!',
                'file.mimes' => 'Jenis file yang diizinkan adalah PDF, JPG, JPEG, atau PNG.'
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                if ($file->isValid()) {
                    $randomFileName = uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('arsip/pendaftaran', $randomFileName, 'public');

                    $arsip = new Arsip();
                    $arsip->name = $request->name;
                    $arsip->tanggal_terbit = $request->tanggal_terbit;
                    $arsip->file = $randomFileName;
                    $arsip->type = 1;
                    $arsip->save();

                    return response()->json($arsip);
                }
            } else {
                return response()->json(['errors' => ['file' => 'File tidak ditemukan']]);
            }
        }
    }

    public function editPendaftaran($id)
    {
        $arsip = Arsip::where('id', $id)->first();
        return view('backend.arsip.pendaftaran.edit', compact('arsip'));
    }

    public function updatePendaftaran(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:arsip,name,' . $id,
                'tanggal_terbit' => 'required|string',
                'file' => 'mimes:pdf,jpg,jpeg,png'
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'name.unique' => 'Nama Arsip sudah digunakan!',
                'tanggal_terbit.required' => 'Silakan isi tanggal terbit terlebih dahulu!',
                'file.mimes' => 'Jenis file yang diizinkan adalah PDF, JPG, JPEG, atau PNG.'
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $arsip = Arsip::find($id);
            if (!$arsip) {
                return response()->json(['errors' => ['id' => 'Arsip tidak ditemukan']]);
            }

            $arsip->name = $request->name;
            $arsip->tanggal_terbit = $request->tanggal_terbit;

            if ($request->hasFile('file')) {
                $previousFile = $arsip->file;
                if ($previousFile) {
                    Storage::disk('public')->delete('arsip/pendaftaran/' . $previousFile);
                }

                $file = $request->file('file');
                if ($file->isValid()) {
                    $randomFileName = uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('arsip/pendaftaran', $randomFileName, 'public');
                    $arsip->file = $randomFileName;
                } else {
                    return response()->json(['errors' => ['file' => 'File tidak valid']]);
                }
            }

            $arsip->save();
            return response()->json($arsip);
        }
    }

    public function destroyPendaftaran(Request $request)
    {
        $arsip = Arsip::find($request->id);

        if (!$arsip) {
            return response()->json(['error' => 'Arsip not found']);
        }

        if (!empty($arsip->file)) {
            if (Storage::exists('public/arsip/pendaftaran/' . $arsip->file)) {
                Storage::delete('public/arsip/pendaftaran/' . $arsip->file);
            }
        }

        $arsip->delete();

        return response()->json(['success' => 'Data berhasil dihapus']);
    }

    public function indexLain()
    {
        if (request()->ajax()) {
            $berkas_lain = Arsip::orderBy('created_at', 'desc')->where('type', '2')->get();

            return DataTables::of($berkas_lain)
                ->addIndexColumn()
                ->addColumn('file', function ($berkas_lain) {
                    $file = $berkas_lain->file ? '<a href="' . Storage::url('public/arsip/berkas_lain/' . $berkas_lain->file) . '" target="_blank" class="btn btn-secondary btn-sm">Tautan ke Berkas</a>' : 'Berkas tidak tersedia';
                    return $file;
                })
                ->addColumn('aksi', function ($berkas_lain) {
                    $btn = '<a href="' . route('berkas-lain.edit', ['id' => $berkas_lain->id]) . '" class="btn btn-sm btn-warning me-2 text-light"><i class="fas fa-pencil-alt"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $berkas_lain->id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';

                    return $btn;
                })
                ->rawColumns(['file', 'aksi'])
                ->make(true);
        }

        return view('backend.arsip.lain_lain.index');
    }

    public function createLain()
    {
        return view('backend.arsip.lain_lain.add');
    }

    public function storeLain(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:arsip,name',
                'tanggal_terbit' => 'required|string',
                'file' => 'required|mimes:pdf,jpg,jpeg,png'
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'name.unique' => 'Golongan sudah tersedia!',
                'tanggal_terbit.required' => 'Silakan isi tanggal terbit terlebih dahulu!',
                'file.required' => 'Silakan unggah file!',
                'file.mimes' => 'Jenis file yang diizinkan adalah PDF, JPG, JPEG, atau PNG.'
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                if ($file->isValid()) {
                    $randomFileName = uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('arsip/berkas_lain', $randomFileName, 'public');

                    $arsip = new Arsip();
                    $arsip->name = $request->name;
                    $arsip->tanggal_terbit = $request->tanggal_terbit;
                    $arsip->file = $randomFileName;
                    $arsip->type = 2;
                    $arsip->save();

                    return response()->json($arsip);
                }
            } else {
                return response()->json(['errors' => ['file' => 'File tidak ditemukan']]);
            }
        }
    }

    public function editLain($id)
    {
        $arsip = Arsip::where('id', $id)->first();
        return view('backend.arsip.lain_lain.edit', compact('arsip'));
    }

    public function updateLain(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:arsip,name,' . $id,
                'tanggal_terbit' => 'required|string',
                'file' => 'mimes:pdf,jpg,jpeg,png'
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'name.unique' => 'Nama Arsip sudah digunakan!',
                'tanggal_terbit.required' => 'Silakan isi tanggal terbit terlebih dahulu!',
                'file.mimes' => 'Jenis file yang diizinkan adalah PDF, JPG, JPEG, atau PNG.'
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $arsip = Arsip::find($id);
            if (!$arsip) {
                return response()->json(['errors' => ['id' => 'Arsip tidak ditemukan']]);
            }

            $arsip->name = $request->name;
            $arsip->tanggal_terbit = $request->tanggal_terbit;

            if ($request->hasFile('file')) {
                $previousFile = $arsip->file;
                if ($previousFile) {
                    Storage::disk('public')->delete('arsip/berkas_lain/' . $previousFile);
                }

                $file = $request->file('file');
                if ($file->isValid()) {
                    $randomFileName = uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('arsip/berkas_lain', $randomFileName, 'public');
                    $arsip->file = $randomFileName;
                } else {
                    return response()->json(['errors' => ['file' => 'File tidak valid']]);
                }
            }

            $arsip->save();
            return response()->json($arsip);
        }
    }

    public function destroyLain(Request $request)
    {
        $arsip = Arsip::find($request->id);

        if (!$arsip) {
            return response()->json(['error' => 'Arsip not found']);
        }

        if (!empty($arsip->file)) {
            if (Storage::exists('public/arsip/berkas_lain/' . $arsip->file)) {
                Storage::delete('public/arsip/berkas_lain/' . $arsip->file);
            }
        }

        $arsip->delete();

        return response()->json(['success' => 'Data berhasil dihapus']);
    }
}
