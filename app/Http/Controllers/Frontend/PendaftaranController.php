<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FileCompressor;
use App\Models\Golongan;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PendaftaranController extends Controller
{
    public function index()
    {
        $golongan = Golongan::orderBy('name', 'asc')->get();
        $pendaftaran = Pendaftaran::with('user', 'golongan')->where('user_id', auth()->user()->id)->whereYear('created_at', now()->year)->get();
        return view('frontend.pendaftaran.index', compact('golongan', 'pendaftaran'));
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
                'gudep' => 'required|string',
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
                'gudep.required' => 'Silakan isi gugus depan terlebih dahulu!',
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
                    $pdfPath = $file->getPathname();
                    $originalSize = filesize($pdfPath);
                    $randomFileName = hash('sha256', time() . $file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();

                    $compressedData = [$this->compress(file_get_contents($file))];
                    $compressedString = implode(',', $compressedData);
                    $request->file('berkas')->move(public_path('berkas'), $randomFileName);
                    $path = public_path('berkas') . '/' . $randomFileName;
                    $compressedPdfSize = FileCompressor::compressFile($path);
                    $compressedPdfSize = filesize($path);
                    $this->decompress($compressedString);

                    $originalSizeKB = round($originalSize / 1024);
                    $compressedPdfSizeKB = round($compressedPdfSize / 1024);

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
                    $pendaftaran->gudep = $request->gudep;
                    $pendaftaran->berkas = $randomFileName;
                    $pendaftaran->original_size = $originalSizeKB . " KB";
                    $pendaftaran->compress_size = $compressedPdfSizeKB . " KB";
                    $pendaftaran->save();

                    return response()->json(['success' => 'Data berhasil disimpan']);
                }
            }
        }
    }

    public function compress($file)
    {
        $string = $file;

        $dictionary = [];
        $output = "";
        $p = "";

        for ($i = 0; $i < strlen($string); $i++) {
            $c = $string[$i];
            if (array_key_exists($p . $c, $dictionary)) {
                $p = $p . $c;
            } else {
                $output .= $p;
                $dictionary[$p . $c] = 1;
                $p = $c;
            }
        }
        $output .= $p;

        return $output;
    }

    public function decompress($fileCompressed)
    {
        $compressed = $fileCompressed;
        $dictionary = ['' => 0];
        $output = "";
        $pc = "";
        $p = "";

        for ($i = 0; $i < strlen($compressed); $i++) {
            $c = $compressed[$i];

            if (is_numeric($c)) {
                $output = $c;
                if (!array_key_exists($c, $dictionary)) {
                    $pc;
                }

                $output .= $dictionary[$pc];
                $output = $p . $c;
            } else {
                if (!array_key_exists($c, $dictionary)) {
                    $pc;
                }

                $output .= $dictionary[$pc];
                $output = $p . $c;
            }
        }

        return $output;
    }
}
