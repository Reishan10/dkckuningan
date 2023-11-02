<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Golongan;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PendaftaranController extends Controller
{
    public function index()
    {
        $golongan = Golongan::orderBy('name', 'asc')->get();
        return view('frontend.pendaftaran.index', compact('golongan'));
    }

    public function store(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'nta' => 'required|string|unique:pendaftaran,nta',
                'tempat_lahir' => 'required|string',
                'tanggal_lahir' => 'required|string',
                'alamat' => 'required|string',
                'jenis_kelamin' => 'required|string',
                'pangkalan' => 'required|string',
                'kwaran' => 'required|string',
                'golongan' => 'required|string',
                'berkas' => 'required|file|mimes:pdf'
            ],
            [
                'nta.required' => 'Silakan isi Nomor Tanda Anggota terlebih dahulu!',
                'nta.unique' => 'Nomor Tanda Anggota sudah tersedia!',
                'tempat_lahir.required' => 'Silakan isi tempat lahir terlebih dahulu!',
                'tanggal_lahir.required' => 'Silakan isi tanggal lahir terlebih dahulu!',
                'alamat.required' => 'Silakan isi alamat terlebih dahulu!',
                'jenis_kelamin.required' => 'Silakan isi jenis kelamin terlebih dahulu!',
                'kwaran.required' => 'Silakan isi kwartir ranting terlebih dahulu!',
                'pangkalan.required' => 'Silakan isi pangkalan terlebih dahulu!',
                'golongan.required' => 'Silakan isi golongan terlebih dahulu!',
                'berkas.required' => 'Silakan isi berkas terlebih dahulu!',
                'berkas.mimes' => 'Berkas harus dalam format PDF.'
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            if ($request->hasFile('berkas')) {
                $file = $request->file('berkas');
                if ($file->isValid()) {
                    $randomFileName = uniqid() . '.' . $file->getClientOriginalExtension();
                    $request->file('berkas')->storeAs('berkas/', $randomFileName, 'public');

                    $pendaftaran = new Pendaftaran();
                    $pendaftaran->user_id = $request->id_user;
                    $pendaftaran->nta = $request->nta;
                    $pendaftaran->tempat_lahir = $request->tempat_lahir;
                    $pendaftaran->tanggal_lahir = $request->tanggal_lahir;
                    $pendaftaran->alamat = $request->alamat;
                    $pendaftaran->jenis_kelamin = $request->jenis_kelamin;
                    $pendaftaran->kwaran = $request->kwaran;
                    $pendaftaran->pangkalan = $request->pangkalan;
                    $pendaftaran->golongan_id = $request->golongan;
                    $pendaftaran->berkas = $randomFileName;
                    $pendaftaran->save();

                    return response()->json(['success' => 'Data berhasil disimpan']);
                }
            }
        }
    }
}
