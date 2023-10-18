<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Golongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class GolonganController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $golongan = Golongan::orderBy('name', 'asc')->get();

            return DataTables::of($golongan)
                ->addIndexColumn()
                ->addColumn('aksi', function ($user) {
                    $btn = '<button type="button" class="btn btn-sm btn-warning me-2 text-light" data-id="' . $user->id . '"  id="btnEdit"><i class="fas fa-pencil-alt"></i></button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $user->id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';

                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return view('backend.golongan.index');
    }

    public function store(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:golongan,name,' . $id
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'name.unique' => 'Golongan sudah tersedia!',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $golongan = Golongan::updateOrCreate([
                'id' => $id
            ], [
                'name' => $request->name,
            ]);
            return response()->json($golongan);
        }
    }

    public function edit($id)
    {
        $data = Golongan::where('id', $id)->first();
        return response()->json($data);
    }

    public function destroy(Request $request)
    {
        $golongan = Golongan::where('id', $request->id)->delete();
        return Response()->json(['golongan' => $golongan, 'success' => 'Data berhasil dihapus']);
    }
}
