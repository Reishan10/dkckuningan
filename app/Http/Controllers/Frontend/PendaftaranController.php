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
                    $pendaftaran->original_size = $originalSizeKB;
                    $pendaftaran->compress_size = $compressedPdfSizeKB;
                    $pendaftaran->save();

                    return response()->json(['success' => 'Data berhasil disimpan']);
                }
            }
        }
    }

    function lzw_compress($uncompressed)
    {
        // Inisialisasi Dictionary
        $dictionary = [];

        // Mengisi dictionary dengan karakter ASCII sebagai kunci dan indeks sebagai nilai
        for ($i = 0; $i < 256; $i++) {
            $dictionary[chr($i)] = $i;
        }

        // Inisialisasi Variabel
        $dict_size = 256;        // Jumlah awal entri dalam dictionary
        $bits = 9;               // Jumlah awal bit untuk merepresentasikan setiap indeks dalam dictionary
        $result = "";            // String untuk menyimpan hasil kompresi
        $current_code = "";      // String untuk membangun kode saat memproses data tidak terkompres
        $compressed_data = "";   // String untuk menyimpan data yang sudah terkompres

        // Loop melalui Data Tidak Terkompres
        for ($i = 0; $i < strlen($uncompressed); $i++) {
            $char = $uncompressed[$i];
            $current_code .= $char;

            // Proses Kompresi
            if (!isset($dictionary[$current_code])) {
                // Jika kode saat ini tidak ada dalam dictionary, tambahkan ke dictionary
                $dictionary[$current_code] = $dict_size++;
                // Tambahkan indeks dari kode sebelumnya ke hasil kompresi
                $result .= pack('n', $dictionary[substr($current_code, 0, -1)]);

                // Periksa apakah dictionary sudah penuh, jika ya, tingkatkan jumlah bit
                if ($dict_size >= (1 << $bits)) {
                    $bits++;
                }
                // Set ulang kode saat ini
                $current_code = $char;
            }
        }
        // Tambahkan indeks dari kode terakhir ke hasil kompresi
        $result .= pack('n', $dictionary[$current_code]);
        // Bangun string data terkompresi
        $compressed_data = pack('n', $dict_size) . $result;

        // Kembalikan nilai compressed_data
        return $compressed_data;
    }

    function lzw_decompress($compressed_data)
    {
        // Inisialisasi Dictionary
        $dictionary = [];

        // Mengisi dictionary dengan karakter ASCII sebagai kunci dan karakter tersebut sebagai nilai
        for ($i = 0; $i < 256; $i++) {
            $dictionary[$i] = chr($i);
        }

        // Inisialisasi Variabel
        $dict_size = 256;          // Jumlah awal entri dalam dictionary
        $bits = 9;                 // Jumlah awal bit untuk merepresentasikan setiap indeks dalam dictionary
        $current_code = null;      // Variabel untuk menyimpan kode saat memproses data terkompres
        $uncompressed = "";        // String untuk menyimpan hasil dekompresi

        // Unpack Data Terkompres
        $compressed_data = unpack('n*', $compressed_data);
        $compressed_data = array_values($compressed_data);

        // Periksa apakah ada data yang terkompres
        if (count($compressed_data) > 0) {
            $current_code = $compressed_data[0];
            $uncompressed .= isset($dictionary[$current_code]) ? $dictionary[$current_code] : '';

            // Loop melalui Data Terkompres
            for ($i = 1; $i < count($compressed_data); $i++) {
                $code = $compressed_data[$i];

                // Proses Dekompresi
                if (!isset($dictionary[$code])) {
                    // Jika kode tidak ada dalam dictionary, buat entri baru
                    $entry = isset($dictionary[$current_code]) ? $dictionary[$current_code] . $dictionary[substr($dictionary[$current_code], 0, 1)] : '';
                } else {
                    // Jika kode ada dalam dictionary, gunakan entri yang ada
                    $entry = $dictionary[$code];
                }

                // Tambahkan entri ke hasil dekompresi
                $uncompressed .= $entry;

                // Tambahkan entri baru ke dictionary
                $dictionary[$dict_size++] = isset($dictionary[$current_code]) ? $dictionary[$current_code] . substr($entry, 0, 1) : '';

                // Periksa apakah dictionary sudah penuh, jika ya, tingkatkan jumlah bit
                if ($dict_size >= (1 << $bits)) {
                    $bits++;
                }

                // Set ulang kode saat ini
                $current_code = $code;
            }
        }

        // Kembalikan nilai uncompressed
        return $uncompressed;
    }
}
