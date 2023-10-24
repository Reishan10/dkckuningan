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
            $soal = Soal::with('golongan')->orderBy('created_at','asc')->get();

            return DataTables::of($soal)
                ->addIndexColumn()
                ->addColumn('golongan', function ($soal) {
                    $golongan = $soal->golongan->name;
                    return $golongan;
                })
                ->addColumn('aksi', function ($soal) {
                    $btn = '<button type="button" class="btn btn-sm btn-warning me-2 text-light" data-id="' . $soal->id . '"  id="btnEdit"><i class="fas fa-pencil-alt"></i></button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $soal->id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';

                    return $btn;
                })
                ->rawColumns(['golongan', 'aksi'])
                ->make(true);
        }

        $golongan = Golongan::orderBy('name', 'asc')->get();
        return view('backend.soal.index', compact('golongan'));
    }

    public function store(Request $request)
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
            $soal = Soal::updateOrCreate([
                'id' => $id
            ], [
                'persyaratan' => $request->persyaratan,
                'keterangan' => $request->keterangan,
                'bobot_nilai' => $request->bobot_nilai,
                'golongan_id' => $request->golongan,
            ]);
            return response()->json($soal);
        }
    }

    public function edit($id)
    {
        $data = Soal::where('id', $id)->first();
        return response()->json($data);
    }

    public function destroy(Request $request)
    {
        $soal = Soal::where('id', $request->id)->delete();
        return Response()->json(['soal' => $soal, 'success' => 'Data berhasil dihapus']);
    }
}
