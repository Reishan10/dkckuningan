<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Golongan;
use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SoalController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $soal = Soal::with('golongan')->orderBy('created_at', 'asc')->get();

            return DataTables::of($soal)
                ->addIndexColumn()
                ->addColumn('golongan', function ($soal) {
                    $golongan = $soal->golongan->name;
                    return $golongan;
                })

                ->editColumn('persyaratan', function ($pendaftaran) {
                    return strip_tags($pendaftaran->persyaratan);
                })

                ->editColumn('keterangan', function ($pendaftaran) {
                    return strip_tags($pendaftaran->keterangan);
                })
                ->addColumn('aksi', function ($soal) {
                    $btn = '';
                    if (auth()->user()->type != "Juri") {
                        $btn .= '<a href="' . route('soal.edit', ['id' => $soal->id]) . '" class="btn btn-sm btn-warning text-light me-2" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $soal->id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    }

                    return $btn;
                })
                ->rawColumns(['golongan', 'aksi'])
                ->make(true);
        }


        return view('backend.soal.semua.index');
    }

    public function create()
    {
        $golongan = Golongan::orderBy('name', 'asc')->get();
        return view('backend.soal.semua.add', compact('golongan'));
    }

    public function store(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'persyaratan' => 'required|unique:soal,persyaratan',
                'keterangan' => 'required',
                'bobot_nilai' => 'required|string',
                'golongan' => 'required|string',
            ],
            [
                'persyaratan.required' => 'Silakan isi persyaratan terlebih dahulu!',
                'persyaratan.unique' => 'Persyaratan sudah tersedia!',
                'keterangan.required' => 'Silakan isi keterangan terlebih dahulu!',
                'bobot_nilai.required' => 'Silakan isi bobot nilai terlebih dahulu!',
                'golongan.required' => 'Silakan pilih golongan terlebih dahulu!',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $soal = new Soal();
            $soal->persyaratan = $request->persyaratan;
            $soal->keterangan = $request->keterangan;
            $soal->bobot_nilai = $request->bobot_nilai;
            $soal->golongan_id = $request->golongan;
            $soal->save();
            return response()->json($soal);
        }
    }

    public function edit($id)
    {
        $soal = Soal::where('id', $id)->first();
        $golongan = Golongan::orderBy('name', 'asc')->get();
        return view('backend.soal.semua.edit', compact('soal', 'golongan'));
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'persyaratan' => 'required|unique:soal,persyaratan,' . $id,
                'keterangan' => 'required',
                'bobot_nilai' => 'required|string',
                'golongan' => 'required|string',
            ],
            [
                'persyaratan.required' => 'Silakan isi persyaratan terlebih dahulu!',
                'persyaratan.unique' => 'Persyaratan sudah tersedia!',
                'keterangan.required' => 'Silakan isi keterangan terlebih dahulu!',
                'bobot_nilai.required' => 'Silakan isi bobot nilai terlebih dahulu!',
                'golongan.required' => 'Silakan pilih golongan terlebih dahulu!',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $soal = Soal::find($id);
            $soal->persyaratan = $request->persyaratan;
            $soal->keterangan = $request->keterangan;
            $soal->bobot_nilai = $request->bobot_nilai;
            $soal->golongan_id = $request->golongan;
            $soal->save();
            return response()->json($soal);
        }
    }

    public function indexSiaga()
    {
        if (request()->ajax()) {
            $soal = Soal::with('golongan')
                ->whereHas('golongan', function ($query) {
                    $query->where('name', 'Siaga');
                })
                ->orderBy('created_at', 'asc')
                ->get();

            return DataTables::of($soal)
                ->addIndexColumn()
                ->addColumn('golongan', function ($soal) {
                    $golongan = $soal->golongan->name;
                    return $golongan;
                })

                ->editColumn('persyaratan', function ($pendaftaran) {
                    return strip_tags($pendaftaran->persyaratan);
                })

                ->editColumn('keterangan', function ($pendaftaran) {
                    return strip_tags($pendaftaran->keterangan);
                })
                ->addColumn('aksi', function ($soal) {
                    $btn = '';
                    if (auth()->user()->type != "Juri") {
                        $btn .= '<a href="' . route('soal.editSiaga', ['id' => $soal->id]) . '" class="btn btn-sm btn-warning text-light me-2" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $soal->id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    }

                    return $btn;
                })
                ->rawColumns(['golongan', 'aksi'])
                ->make(true);
        }


        return view('backend.soal.siaga.index');
    }

    public function createSiaga()
    {
        $golongan = Golongan::orderBy('name', 'asc')->get();
        return view('backend.soal.siaga.add', compact('golongan'));
    }

    public function storeSiaga(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'persyaratan' => 'required|unique:soal,persyaratan',
                'keterangan' => 'required',
                'bobot_nilai' => 'required|string',
                'golongan' => 'required|string',
            ],
            [
                'persyaratan.required' => 'Silakan isi persyaratan terlebih dahulu!',
                'persyaratan.unique' => 'Persyaratan sudah tersedia!',
                'keterangan.required' => 'Silakan isi keterangan terlebih dahulu!',
                'bobot_nilai.required' => 'Silakan isi bobot nilai terlebih dahulu!',
                'golongan.required' => 'Silakan pilih golongan terlebih dahulu!',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $soal = new Soal();
            $soal->persyaratan = $request->persyaratan;
            $soal->keterangan = $request->keterangan;
            $soal->bobot_nilai = $request->bobot_nilai;
            $soal->golongan_id = $request->golongan;
            $soal->save();
            return response()->json($soal);
        }
    }

    public function editSiaga($id)
    {
        $soal = Soal::where('id', $id)->first();
        $golongan = Golongan::orderBy('name', 'asc')->get();
        return view('backend.soal.siaga.edit', compact('soal', 'golongan'));
    }

    public function updateSiaga(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'persyaratan' => 'required|unique:soal,persyaratan,' . $id,
                'keterangan' => 'required',
                'bobot_nilai' => 'required|string',
                'golongan' => 'required|string',
            ],
            [
                'persyaratan.required' => 'Silakan isi persyaratan terlebih dahulu!',
                'persyaratan.unique' => 'Persyaratan sudah tersedia!',
                'keterangan.required' => 'Silakan isi keterangan terlebih dahulu!',
                'bobot_nilai.required' => 'Silakan isi bobot nilai terlebih dahulu!',
                'golongan.required' => 'Silakan pilih golongan terlebih dahulu!',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $soal = Soal::find($id);
            $soal->persyaratan = $request->persyaratan;
            $soal->keterangan = $request->keterangan;
            $soal->bobot_nilai = $request->bobot_nilai;
            $soal->golongan_id = $request->golongan;
            $soal->save();
            return response()->json($soal);
        }
    }

    public function indexPenggalang()
    {
        if (request()->ajax()) {
            $soal = Soal::with('golongan')
                ->whereHas('golongan', function ($query) {
                    $query->where('name', 'Penggalang');
                })
                ->orderBy('created_at', 'asc')
                ->get();

            return DataTables::of($soal)
                ->addIndexColumn()
                ->addColumn('golongan', function ($soal) {
                    $golongan = $soal->golongan->name;
                    return $golongan;
                })

                ->editColumn('persyaratan', function ($pendaftaran) {
                    return strip_tags($pendaftaran->persyaratan);
                })

                ->editColumn('keterangan', function ($pendaftaran) {
                    return strip_tags($pendaftaran->keterangan);
                })
                ->addColumn('aksi', function ($soal) {
                    $btn = '';
                    if (auth()->user()->type != "Juri") {
                        $btn .= '<a href="' . route('soal.editPenggalang', ['id' => $soal->id]) . '" class="btn btn-sm btn-warning text-light me-2" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $soal->id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    }

                    return $btn;
                })
                ->rawColumns(['golongan', 'aksi'])
                ->make(true);
        }


        return view('backend.soal.penggalang.index');
    }

    public function createPenggalang()
    {
        $golongan = Golongan::orderBy('name', 'asc')->get();
        return view('backend.soal.penggalang.add', compact('golongan'));
    }

    public function storePenggalang(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'persyaratan' => 'required|unique:soal,persyaratan',
                'keterangan' => 'required',
                'bobot_nilai' => 'required|string',
                'golongan' => 'required|string',
            ],
            [
                'persyaratan.required' => 'Silakan isi persyaratan terlebih dahulu!',
                'persyaratan.unique' => 'Persyaratan sudah tersedia!',
                'keterangan.required' => 'Silakan isi keterangan terlebih dahulu!',
                'bobot_nilai.required' => 'Silakan isi bobot nilai terlebih dahulu!',
                'golongan.required' => 'Silakan pilih golongan terlebih dahulu!',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $soal = new Soal();
            $soal->persyaratan = $request->persyaratan;
            $soal->keterangan = $request->keterangan;
            $soal->bobot_nilai = $request->bobot_nilai;
            $soal->golongan_id = $request->golongan;
            $soal->save();
            return response()->json($soal);
        }
    }

    public function editPenggalang($id)
    {
        $soal = Soal::where('id', $id)->first();
        $golongan = Golongan::orderBy('name', 'asc')->get();
        return view('backend.soal.penggalang.edit', compact('soal', 'golongan'));
    }

    public function updatePenggalang(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'persyaratan' => 'required|unique:soal,persyaratan,' . $id,
                'keterangan' => 'required',
                'bobot_nilai' => 'required|string',
                'golongan' => 'required|string',
            ],
            [
                'persyaratan.required' => 'Silakan isi persyaratan terlebih dahulu!',
                'persyaratan.unique' => 'Persyaratan sudah tersedia!',
                'keterangan.required' => 'Silakan isi keterangan terlebih dahulu!',
                'bobot_nilai.required' => 'Silakan isi bobot nilai terlebih dahulu!',
                'golongan.required' => 'Silakan pilih golongan terlebih dahulu!',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $soal = Soal::find($id);
            $soal->persyaratan = $request->persyaratan;
            $soal->keterangan = $request->keterangan;
            $soal->bobot_nilai = $request->bobot_nilai;
            $soal->golongan_id = $request->golongan;
            $soal->save();
            return response()->json($soal);
        }
    }

    public function indexPenegak()
    {
        if (request()->ajax()) {
            $soal = Soal::with('golongan')
                ->whereHas('golongan', function ($query) {
                    $query->where('name', 'Penegak');
                })
                ->orderBy('created_at', 'asc')
                ->get();

            return DataTables::of($soal)
                ->addIndexColumn()
                ->addColumn('golongan', function ($soal) {
                    $golongan = $soal->golongan->name;
                    return $golongan;
                })

                ->editColumn('persyaratan', function ($pendaftaran) {
                    return strip_tags($pendaftaran->persyaratan);
                })

                ->editColumn('keterangan', function ($pendaftaran) {
                    return strip_tags($pendaftaran->keterangan);
                })
                ->addColumn('aksi', function ($soal) {
                    $btn = '';
                    if (auth()->user()->type != "Juri") {
                        $btn .= '<a href="' . route('soal.editPenegak', ['id' => $soal->id]) . '" class="btn btn-sm btn-warning text-light me-2" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $soal->id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    }

                    return $btn;
                })
                ->rawColumns(['golongan', 'aksi'])
                ->make(true);
        }


        return view('backend.soal.penegak.index');
    }

    public function createPenegak()
    {
        $golongan = Golongan::orderBy('name', 'asc')->get();
        return view('backend.soal.penegak.add', compact('golongan'));
    }

    public function storePenegak(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'persyaratan' => 'required|unique:soal,persyaratan',
                'keterangan' => 'required',
                'bobot_nilai' => 'required|string',
                'golongan' => 'required|string',
            ],
            [
                'persyaratan.required' => 'Silakan isi persyaratan terlebih dahulu!',
                'persyaratan.unique' => 'Persyaratan sudah tersedia!',
                'keterangan.required' => 'Silakan isi keterangan terlebih dahulu!',
                'bobot_nilai.required' => 'Silakan isi bobot nilai terlebih dahulu!',
                'golongan.required' => 'Silakan pilih golongan terlebih dahulu!',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $soal = new Soal();
            $soal->persyaratan = $request->persyaratan;
            $soal->keterangan = $request->keterangan;
            $soal->bobot_nilai = $request->bobot_nilai;
            $soal->golongan_id = $request->golongan;
            $soal->save();
            return response()->json($soal);
        }
    }

    public function editPenegak($id)
    {
        $soal = Soal::where('id', $id)->first();
        $golongan = Golongan::orderBy('name', 'asc')->get();
        return view('backend.soal.penegak.edit', compact('soal', 'golongan'));
    }

    public function updatePenegak(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'persyaratan' => 'required|unique:soal,persyaratan,' . $id,
                'keterangan' => 'required',
                'bobot_nilai' => 'required|string',
                'golongan' => 'required|string',
            ],
            [
                'persyaratan.required' => 'Silakan isi persyaratan terlebih dahulu!',
                'persyaratan.unique' => 'Persyaratan sudah tersedia!',
                'keterangan.required' => 'Silakan isi keterangan terlebih dahulu!',
                'bobot_nilai.required' => 'Silakan isi bobot nilai terlebih dahulu!',
                'golongan.required' => 'Silakan pilih golongan terlebih dahulu!',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $soal = Soal::find($id);
            $soal->persyaratan = $request->persyaratan;
            $soal->keterangan = $request->keterangan;
            $soal->bobot_nilai = $request->bobot_nilai;
            $soal->golongan_id = $request->golongan;
            $soal->save();
            return response()->json($soal);
        }
    }

    public function indexPandega()
    {
        if (request()->ajax()) {
            $soal = Soal::with('golongan')
                ->whereHas('golongan', function ($query) {
                    $query->where('name', 'Pandega');
                })
                ->orderBy('created_at', 'asc')
                ->get();

            return DataTables::of($soal)
                ->addIndexColumn()
                ->addColumn('golongan', function ($soal) {
                    $golongan = $soal->golongan->name;
                    return $golongan;
                })

                ->editColumn('persyaratan', function ($pendaftaran) {
                    return strip_tags($pendaftaran->persyaratan);
                })

                ->editColumn('keterangan', function ($pendaftaran) {
                    return strip_tags($pendaftaran->keterangan);
                })
                ->addColumn('aksi', function ($soal) {
                    $btn = '';
                    if (auth()->user()->type != "Juri") {
                        $btn .= '<a href="' . route('soal.editPandega', ['id' => $soal->id]) . '" class="btn btn-sm btn-warning text-light me-2" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger text-light me-2" data-id="' . $soal->id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';
                    }

                    return $btn;
                })
                ->rawColumns(['golongan', 'aksi'])
                ->make(true);
        }


        return view('backend.soal.pandega.index');
    }

    public function createPandega()
    {
        $golongan = Golongan::orderBy('name', 'asc')->get();
        return view('backend.soal.pandega.add', compact('golongan'));
    }

    public function storePandega(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'persyaratan' => 'required|unique:soal,persyaratan',
                'keterangan' => 'required',
                'bobot_nilai' => 'required|string',
                'golongan' => 'required|string',
            ],
            [
                'persyaratan.required' => 'Silakan isi persyaratan terlebih dahulu!',
                'persyaratan.unique' => 'Persyaratan sudah tersedia!',
                'keterangan.required' => 'Silakan isi keterangan terlebih dahulu!',
                'bobot_nilai.required' => 'Silakan isi bobot nilai terlebih dahulu!',
                'golongan.required' => 'Silakan pilih golongan terlebih dahulu!',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $soal = new Soal();
            $soal->persyaratan = $request->persyaratan;
            $soal->keterangan = $request->keterangan;
            $soal->bobot_nilai = $request->bobot_nilai;
            $soal->golongan_id = $request->golongan;
            $soal->save();
            return response()->json($soal);
        }
    }

    public function editPandega($id)
    {
        $soal = Soal::where('id', $id)->first();
        $golongan = Golongan::orderBy('name', 'asc')->get();
        return view('backend.soal.pandega.edit', compact('soal', 'golongan'));
    }

    public function updatePandega(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'persyaratan' => 'required|unique:soal,persyaratan,' . $id,
                'keterangan' => 'required',
                'bobot_nilai' => 'required|string',
                'golongan' => 'required|string',
            ],
            [
                'persyaratan.required' => 'Silakan isi persyaratan terlebih dahulu!',
                'persyaratan.unique' => 'Persyaratan sudah tersedia!',
                'keterangan.required' => 'Silakan isi keterangan terlebih dahulu!',
                'bobot_nilai.required' => 'Silakan isi bobot nilai terlebih dahulu!',
                'golongan.required' => 'Silakan pilih golongan terlebih dahulu!',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $soal = Soal::find($id);
            $soal->persyaratan = $request->persyaratan;
            $soal->keterangan = $request->keterangan;
            $soal->bobot_nilai = $request->bobot_nilai;
            $soal->golongan_id = $request->golongan;
            $soal->save();
            return response()->json($soal);
        }
    }

    public function destroy(Request $request)
    {
        $soal = Soal::where('id', $request->id)->delete();
        return Response()->json(['soal' => $soal, 'success' => 'Data berhasil dihapus']);
    }
}
