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

                    $compressedData = [$this->lzw_compress(file_get_contents($file))];
                    $compressedString = implode(',', $compressedData);
                    $this->lzw_decompress($compressedString);
                    $request->file('berkas')->move(public_path('berkas'), $randomFileName);
                    $path = public_path('berkas') . '/' . $randomFileName;
                    $compressedPdfSize = FileCompressor::compressFile($path);
                    $compressedPdfSize = filesize($path);

                   
                    $originalSizeKB = round($originalSize / 1024);
                    $compressedPdfSizeKB = round($compressedPdfSize / 1024);

                    $persentaseKompresi = (1 - ($compressedPdfSize / $originalSize)) * 100;
                    $persentase = number_format($persentaseKompresi, 2) . '%';

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

                    return response()->json(['success' => 'Data berhasil disimpan', 'persentase' => $persentase]);
                }
            }
        }
    }

    function lzw_compress($uncompressed)
    {
        $MAX_BITS = 12;
        $dictionary = [];

        for ($i = 0; $i < 256; $i++) {
            $dictionary[chr($i)] = $i;
        }

        $dict_size = 256;
        $bits = 9;
        $result = "";
        $current_code = "";
        $compressed_data = "";

        for ($i = 0; $i < strlen($uncompressed); $i++) {
            $char = $uncompressed[$i];
            $current_code .= $char;
            if (!isset($dictionary[$current_code])) {
                $dictionary[$current_code] = $dict_size++;
                $result .= pack('n', $dictionary[substr($current_code, 0, -1)]);
                if ($dict_size >= (1 << $bits)) {
                    if ($bits < $MAX_BITS) {
                        $bits++;
                    }
                }
                $current_code = $char;
            }
        }
        $result .= pack('n', $dictionary[$current_code]);
        $compressed_data = pack('n', $dict_size) . $result;
        return $compressed_data;
    }

    function lzw_decompress($compressed_data)
    {
        $MAX_BITS = 12;
        $dictionary = [];

        for ($i = 0; $i < 256; $i++) {
            $dictionary[$i] = chr($i);
        }

        $dict_size = 256;
        $bits = 9;
        $current_code = null;
        $uncompressed = "";
        $compressed_data = unpack('n*', $compressed_data);
        $compressed_data = array_values($compressed_data);

        if (count($compressed_data) > 0) {
            $current_code = $compressed_data[0];
            $uncompressed .= isset($dictionary[$current_code]) ? $dictionary[$current_code] : '';

            for ($i = 1; $i < count($compressed_data); $i++) {
                $code = $compressed_data[$i];

                if (!isset($dictionary[$code])) {
                    $entry = isset($dictionary[$current_code]) ? $dictionary[$current_code] . $dictionary[substr($dictionary[$current_code], 0, 1)] : '';
                } else {
                    $entry = $dictionary[$code];
                }

                $uncompressed .= $entry;

                $dictionary[$dict_size++] = isset($dictionary[$current_code]) ? $dictionary[$current_code] . substr($entry, 0, 1) : '';

                if ($dict_size >= (1 << $bits) && $bits < $MAX_BITS) {
                    $bits++;
                }

                $current_code = $code;
            }
        }

        return $uncompressed;
    }
}
