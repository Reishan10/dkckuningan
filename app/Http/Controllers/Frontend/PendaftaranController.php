<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Golongan;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ilovepdf\Ilovepdf;

class PendaftaranController extends Controller
{
    public function index()
    {
        $golongan = Golongan::orderBy('name', 'asc')->get();
        $user = Pendaftaran::where('user_id', auth()->user()->id)->whereYear('created_at', now()->year)->get();
        return view('frontend.pendaftaran.index', compact('golongan', 'user'));
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
                    file_get_contents($file);
                    $pdfPath = $file->getPathname();
                    $originalSize = filesize($pdfPath);
                    $randomFileName = hash('sha256', time() . $file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
                    $compressedData = $this->compressLZW(file_get_contents($file));
                    $compressedString = implode(',', $compressedData);
                    Storage::put($randomFileName, $compressedString);
                    $request->file('berkas')->move(public_path('berkas'), $randomFileName);
                    $path = public_path('berkas') . '/' . $randomFileName;
                    $ilovepdf = new Ilovepdf('project_public_d09d93d43d6c50b22a77448f6b3c1a96_CHJ-x909810da60baf4deb64175a136f3bd6a', 'secret_key_fa6f4b36e077abb9a359ac0e5315cc27_F6fu7fb53e2975d4a141aa2521f0e1ad331a5');
                    $myTask = $ilovepdf->newTask('compress');
                    $myTask->addFile($path);
                    $myTask->execute();
                    $myTask->download(public_path('berkas'));
                    $compressedPdfSize = filesize($path);
                    $this->decompressLZW($compressedString);

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

    public function compressLZW($data)
    {
        $dictionary = [];
        $output = [];
        $current = '';

        for ($i = 0; $i < 256; $i++) {
            $dictionary[chr($i)] = $i;
        }

        for ($i = 0; $i < strlen($data); $i++) {
            $char = $data[$i];
            $combined = $current . $char;

            if (isset($dictionary[$combined])) {
                $current = $combined;
            } else {
                $output[] = $dictionary[$current];
                $dictionary[$combined] = count($dictionary);
                $current = $char;
            }
        }

        if ($current !== '') {
            $output[] = $dictionary[$current];
        }

        return $output;
    }

    public function decompressLZW($data)
    {
        $dictionary = [];
        $output = '';
        $current = '';
        $data = explode(',', $data);

        for ($i = 0; $i < 256; $i++) {
            $dictionary[$i] = chr($i);
        }

        foreach ($data as $code) {
            if (isset($dictionary[$code])) {
                $entry = $dictionary[$code];
                $output .= $entry;
                if ($current !== '') {
                    $dictionary[] = $current . $entry[0];
                }
                $current = $entry;
            } else {
                $entry = $current . $current[0];
                $output .= $entry;
                $dictionary[] = $entry;
                $current = $entry;
            }
        }

        return $output;
    }
}
