<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Konten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KontenController extends Controller
{
    public function index()
    {
        $konten = Konten::with('user')->orderBy('created_at', 'asc');

        $status = request()->query('status');

        if ($status === '1') {
            $konten->where('status', '1');
        } elseif ($status === '0') {
            $konten->where('status', '0');
        }

        $konten = $konten->paginate(9);
        return view('backend.konten.index', compact('konten'));
    }

    public function create()
    {
        return view('backend.konten.add');
    }

    public function store(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'title' => 'required|unique:konten,title',
                'content' => 'required|string',
                'image' => 'required|image|mimes:jpg,png,jpeg,webp,svgfile|max:5120',
            ],
            [
                'title.required' => 'Silakan isi judul terlebih dahulu!',
                'title.unique' => 'Judul sudah tersedia!',
                'content.required' => 'Silakan isi konten terlebih dahulu!',
                'image.required' => 'Silakan isi foto terlebih dahulu!',
                'image.image' => 'File harus berupa gambar!',
                'image.mimes' => 'Gambar yang diunggah harus dalam format JPG, PNG, JPEG, WEBP, atau SVG.',
                'image.max' => 'Maksimal ukuran foto 5 MB',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file->isValid()) {
                    $randomFileName = uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('konten/', $randomFileName, 'public');

                    $konten = new Konten();
                    $konten->title = $request->title;
                    $konten->content = $request->content;
                    $konten->image = $randomFileName;
                    $slug = strtolower($request->title);
                    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
                    $konten->slug = $slug;
                    $konten->status = 0;
                    $konten->user_id = auth()->user()->id;
                    $konten->save();

                    return response()->json($konten);
                }
            } else {
                return response()->json(['errors' => ['image' => 'File image tidak ditemukan']]);
            }
        }
    }

    public function edit($id)
    {
        $konten = Konten::where('id', $id)->first();
        return view('backend.konten.edit', compact('konten'));
    }

    public function update(Request $request)
    {
        $id = $request->id;

        $validated = Validator::make(
            $request->all(),
            [
                'title' => 'required|unique:konten,title,' . $id,
                'content' => 'required|string',
                'image' => 'image|mimes:jpg,png,jpeg,webp,svgfile|max:5120',
            ],
            [
                'title.required' => 'Silakan isi judul terlebih dahulu!',
                'title.unique' => 'Judul sudah tersedia!',
                'content.required' => 'Silakan isi konten terlebih dahulu!',
                'image.image' => 'File harus berupa gambar!',
                'image.mimes' => 'Gambar yang diunggah harus dalam format JPG, PNG, JPEG, WEBP, atau SVG.',
                'image.max' => 'Maksimal ukuran foto 5 MB',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $konten = Konten::find($id);

            if (!$konten) {
                return response()->json(['errors' => ['message' => 'Konten tidak ditemukan']]);
            }

            if ($request->hasFile('image')) {
                $previousImage = $konten->image;
                if ($previousImage) {
                    Storage::disk('public')->delete('konten/' . $previousImage);
                }

                $file = $request->file('image');
                if ($file->isValid()) {
                    $randomFileName = uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('konten/', $randomFileName, 'public');
                    $konten->image = $randomFileName;
                }
            }

            $konten->title = $request->title;
            $konten->content = $request->content;
            $slug = strtolower($request->title);
            $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
            $konten->slug = $slug;
            $konten->status = 0;
            $konten->user_id = auth()->user()->id;
            $konten->save();

            return response()->json($konten);
        }
    }

    public function destroy(Request $request)
    {
        $konten = Konten::find($request->id);

        if (!$konten) {
            return response()->json(['error' => 'Konten not found']);
        }

        if (!empty($konten->image)) {
            if (Storage::exists('public/konten/' . $konten->image)) {
                Storage::delete('public/konten/' . $konten->image);
            }
        }

        $konten->delete();

        return response()->json(['success' => 'Data berhasil dihapus']);
    }

    public function publish(Request $request)
    {
        $konten = Konten::find($request->id);
        $konten->status = 0;
        $konten->save();
        return response()->json(['success' => 'Data berhasil di publish']);
    }

    public function pending(Request $request)
    {
        $konten = Konten::find($request->id);
        $konten->status = 1;
        $konten->save();
        return response()->json(['success' => 'Data berhasil di pending']);
    }
}
