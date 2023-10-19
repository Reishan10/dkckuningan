<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Timeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TimelineController extends Controller
{
    public function index()
    {
        $timeline = Timeline::orderBy('created_at', 'asc')->paginate(10);
        return view('backend.timeline.index', compact('timeline'));
    }

    public function create()
    {
        return view('backend.timeline.add');
    }

    public function store(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:timeline,name',
                'foto' => 'required|image|mimes:jpg,png,jpeg,webp,svgfile|max:5120',
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'name.unique' => 'Timeline sudah tersedia!',
                'foto.required' => 'Silakan isi foto terlebih dahulu!',
                'foto.image' => 'File harus berupa gambar!',
                'foto.mimes' => 'Gambar yang diunggah harus dalam format JPG, PNG, JPEG, WEBP, atau SVG.',
                'foto.max' => 'Maksimal ukuran foto 5 MB',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                if ($file->isValid()) {
                    $randomFileName = uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('timeline/', $randomFileName, 'public');

                    $timeline = new Timeline();
                    $timeline->name = $request->name;
                    $timeline->foto = $randomFileName;
                    $timeline->save();

                    return response()->json($timeline);
                }
            } else {
                return response()->json(['errors' => ['foto' => 'File foto tidak ditemukan']]);
            }
        }
    }

    public function edit($id)
    {
        $timeline = Timeline::where('id', $id)->first();
        return view('backend.timeline.edit', compact('timeline'));
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:timeline,name,' . $id,
                'foto' => 'image|mimes:jpg,png,jpeg,webp,svgfile|max:5120',
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'name.unique' => 'Timeline sudah tersedia!',
                'foto.required' => 'Silakan isi foto terlebih dahulu!',
                'foto.image' => 'File harus berupa gambar!',
                'foto.mimes' => 'Gambar yang diunggah harus dalam format JPG, PNG, JPEG, WEBP, atau SVG.',
                'foto.max' => 'Maksimal ukuran foto 5 MB',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $timeline = Timeline::find($id);
            if ($request->hasFile('foto')) {
                Storage::disk('public')->delete('timeline/' . $timeline->foto);
                $file = $request->file('foto');
                if ($file->isValid()) {
                    $randomFileName = uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('timeline/', $randomFileName, 'public');
                    $timeline->foto = $randomFileName;
                }
            }

            $timeline->name = $request->name;
            $timeline->save();

            return response()->json($timeline);
        }
    }

    public function destroy(Request $request)
    {
        $timeline = Timeline::find($request->id);

        if (!$timeline) {
            return response()->json(['error' => 'Timeline not found']);
        }

        if (!empty($timeline->foto)) {
            if (Storage::exists('public/timeline/' . $timeline->foto)) {
                Storage::delete('public/timeline/' . $timeline->foto);
            }
        }

        $timeline->delete();

        return response()->json(['success' => 'Data berhasil dihapus', 'name' => $timeline->name]);
    }
}
