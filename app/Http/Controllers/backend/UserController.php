<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function indexAll()
    {
        if (request()->ajax()) {
            $users = User::where('id', '!=', auth()->user()->id)->orderBy('name', 'asc')->get();

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('name', function ($user) {
                    $avatar = $user->avatar == ''
                        ? 'https://ui-avatars.com/api/?background=random&name=' . $user->name
                        : asset('storage/avatar/' . $user->avatar);

                    $name = '<h2 class="table-avatar">
                            <a href="student-details.html" class="avatar avatar-sm me-2">
                                <img class="avatar-img rounded-circle" src="' . $avatar . '" alt="User Image"></a>
                            <a href="student-details.html">' . $user->name . '</a>
                        </h2>';

                    return $name;
                })
                ->addColumn('active_status', function ($user) {
                    $badgeClass = $user->active_status == 0 ? 'badge-soft-info' : 'badge-soft-danger';
                    $active_status = '<span class="badge ' . $badgeClass . '">';
                    $active_status .= $user->active_status == 0 ? 'Aktif' : 'Tidak aktif';
                    $active_status .= '</span>';

                    return $active_status;
                })
                ->addColumn('aksi', function ($user) {
                    $editLink = route('pengguna.semua.edit', $user->id);
                    $btn = '<a href="' . $editLink . '" class="btn btn-sm btn-warning me-2 text-light" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $user->id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';

                    return $btn;
                })
                ->rawColumns(['name', 'aksi', 'active_status', 'comboBox'])
                ->make(true);
        }

        return view('backend.user.all.index');
    }

    public function createAll()
    {
        return view('backend.user.all.add');
    }

    public function storeAll(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'no_telepon' => 'required|string|min:11|max:13|unique:users,no_telepon',
                'type' => 'required|string',
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'email.required' => 'Silakan isi alamat email terlebih dahulu!',
                'email.unique' => 'Alamat email sudah digunakan!',
                'no_telepon.required' => 'Silakan isi nomor telepon terlebih dahulu!',
                'no_telepon.min' => 'Nomor telepon minimal harus terdiri dari 11 karakter!',
                'no_telepon.max' => 'Nomor telepon maksimal harus terdiri dari 13 karakter!',
                'no_telepon.unique' => 'Nomor telepon sudah digunakan!',
                'type.required' => 'Silakan pilih tipe terlebih dahulu!',
            ]

        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->no_telepon = $request->no_telepon;
            $user->password = Hash::make('123456789');
            $user->type = $request->type;
            $user->save();

            return response()->json(['success' => 'Data barhasil ditambahkan']);
        }
    }

    public function editAll($id)
    {
        $user = User::find($id);
        return view('backend.user.all.edit', compact('user'));
    }

    public function updateAll(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email,' . $id,
                'no_telepon' => 'required|string|min:11|max:13|unique:users,no_telepon,' . $id,
                'type' => 'required|string',
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'email.required' => 'Silakan isi alamat email terlebih dahulu!',
                'email.unique' => 'Alamat email sudah digunakan!',
                'no_telepon.required' => 'Silakan isi nomor telepon terlebih dahulu!',
                'no_telepon.min' => 'Nomor telepon minimal harus terdiri dari 11 karakter!',
                'no_telepon.max' => 'Nomor telepon maksimal harus terdiri dari 13 karakter!',
                'no_telepon.unique' => 'Nomor telepon sudah digunakan!',
                'type.required' => 'Silakan pilih tipe terlebih dahulu!',
            ]

        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->no_telepon = $request->no_telepon;
            $user->type = $request->type;
            $user->active_status = $request->status;
            $user->save();

            return response()->json(['success' => 'Data barhasil diubah']);
        }
    }

    public function destroyAll(Request $request)
    {
        $user = User::find($request->id);

        if (!$user) {
            return response()->json(['error' => 'User not found']);
        }

        if (!empty($user->avatar)) {
            if (Storage::exists('public/avatar/' . $user->avatar)) {
                Storage::delete('public/avatar/' . $user->avatar);
            }
        }

        $user->delete();

        return response()->json(['success' => 'Data berhasil dihapus', 'name' => $user->name]);
    }

    public function indexJuri()
    {
        if (request()->ajax()) {
            $users = User::where('id', '!=', auth()->user()->id)
                ->where('type', 1)
                ->orderBy('name', 'asc')
                ->get();

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('name', function ($user) {
                    $avatar = $user->avatar == ''
                        ? 'https://ui-avatars.com/api/?background=random&name=' . $user->name
                        : asset('storage/avatar/' . $user->avatar);

                    $name = '<h2 class="table-avatar">
                            <a href="student-details.html" class="avatar avatar-sm me-2">
                                <img class="avatar-img rounded-circle" src="' . $avatar . '" alt="User Image"></a>
                            <a href="student-details.html">' . $user->name . '</a>
                        </h2>';

                    return $name;
                })
                ->addColumn('comboBox', function ($user) {
                    $comboBox = "<label class='custom_check'>
                                <input type='checkbox' id='checkbox' data-id='" . $user->id . "'>
                                <span class='checkmark'></span>
                            </label>";

                    return $comboBox;
                })
                ->addColumn('active_status', function ($user) {
                    $badgeClass = $user->active_status == 0 ? 'badge-soft-info' : 'badge-soft-danger';
                    $active_status = '<span class="badge ' . $badgeClass . '">';
                    $active_status .= $user->active_status == 0 ? 'Aktif' : 'Tidak aktif';
                    $active_status .= '</span>';

                    return $active_status;
                })
                ->addColumn('aksi', function ($user) {
                    $editLink = route('pengguna.juri.edit', $user->id);
                    $btn = '<a href="' . $editLink . '" class="btn btn-sm btn-warning me-2 text-light" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $user->id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';

                    return $btn;
                })
                ->rawColumns(['name', 'aksi', 'active_status', 'comboBox'])
                ->make(true);
        }

        return view('backend.user.juri.index');
    }

    public function createJuri()
    {
        return view('backend.user.juri.add');
    }

    public function storeJuri(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'no_telepon' => 'required|string|min:11|max:13|unique:users,no_telepon',
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'email.required' => 'Silakan isi alamat email terlebih dahulu!',
                'email.unique' => 'Alamat email sudah digunakan!',
                'no_telepon.required' => 'Silakan isi nomor telepon terlebih dahulu!',
                'no_telepon.min' => 'Nomor telepon minimal harus terdiri dari 11 karakter!',
                'no_telepon.max' => 'Nomor telepon maksimal harus terdiri dari 13 karakter!',
                'no_telepon.unique' => 'Nomor telepon sudah digunakan!',
            ]

        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->no_telepon = $request->no_telepon;
            $user->password = Hash::make('123456789');
            $user->type = "1";
            $user->save();

            return response()->json(['success' => 'Data barhasil ditambahkan']);
        }
    }

    public function editJuri($id)
    {
        $user = User::find($id);
        return view('backend.user.juri.edit', compact('user'));
    }

    public function updateJuri(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email,' . $id,
                'no_telepon' => 'required|string|min:11|max:13|unique:users,no_telepon,' . $id,
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'email.required' => 'Silakan isi alamat email terlebih dahulu!',
                'email.unique' => 'Alamat email sudah digunakan!',
                'no_telepon.required' => 'Silakan isi nomor telepon terlebih dahulu!',
                'no_telepon.min' => 'Nomor telepon minimal harus terdiri dari 11 karakter!',
                'no_telepon.max' => 'Nomor telepon maksimal harus terdiri dari 13 karakter!',
                'no_telepon.unique' => 'Nomor telepon sudah digunakan!',
            ]

        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->no_telepon = $request->no_telepon;
            $user->active_status = $request->status;
            $user->save();

            return response()->json(['success' => 'Data barhasil diubah']);
        }
    }

    public function destroyJuri(Request $request)
    {
        $user = User::find($request->id);

        if (!$user) {
            return response()->json(['error' => 'User not found']);
        }

        if (!empty($user->avatar)) {
            if (Storage::exists('public/avatar/' . $user->avatar)) {
                Storage::delete('public/avatar/' . $user->avatar);
            }
        }

        $user->delete();

        return response()->json(['success' => 'Data berhasil dihapus', 'name' => $user->name]);
    }

    public function indexKwarcab()
    {
        if (request()->ajax()) {
            $users = User::where('id', '!=', auth()->user()->id)
                ->where('type', 2)
                ->orderBy('name', 'asc')
                ->get();

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('name', function ($user) {
                    $avatar = $user->avatar == ''
                        ? 'https://ui-avatars.com/api/?background=random&name=' . $user->name
                        : asset('storage/avatar/' . $user->avatar);

                    $name = '<h2 class="table-avatar">
                            <a href="student-details.html" class="avatar avatar-sm me-2">
                                <img class="avatar-img rounded-circle" src="' . $avatar . '" alt="User Image"></a>
                            <a href="student-details.html">' . $user->name . '</a>
                        </h2>';

                    return $name;
                })
                ->addColumn('comboBox', function ($user) {
                    $comboBox = "<label class='custom_check'>
                                <input type='checkbox' id='checkbox' data-id='" . $user->id . "'>
                                <span class='checkmark'></span>
                            </label>";

                    return $comboBox;
                })
                ->addColumn('active_status', function ($user) {
                    $badgeClass = $user->active_status == 0 ? 'badge-soft-info' : 'badge-soft-danger';
                    $active_status = '<span class="badge ' . $badgeClass . '">';
                    $active_status .= $user->active_status == 0 ? 'Aktif' : 'Tidak aktif';
                    $active_status .= '</span>';

                    return $active_status;
                })
                ->addColumn('aksi', function ($user) {
                    $editLink = route('pengguna.kwarcab.edit', $user->id);
                    $btn = '<a href="' . $editLink . '" class="btn btn-sm btn-warning me-2 text-light" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $user->id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';

                    return $btn;
                })
                ->rawColumns(['name', 'aksi', 'active_status', 'comboBox'])
                ->make(true);
        }

        return view('backend.user.kwarcab.index');
    }

    public function createKwarcab()
    {
        return view('backend.user.kwarcab.add');
    }

    public function storeKwarcab(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'no_telepon' => 'required|string|min:11|max:13|unique:users,no_telepon',
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'email.required' => 'Silakan isi alamat email terlebih dahulu!',
                'email.unique' => 'Alamat email sudah digunakan!',
                'no_telepon.required' => 'Silakan isi nomor telepon terlebih dahulu!',
                'no_telepon.min' => 'Nomor telepon minimal harus terdiri dari 11 karakter!',
                'no_telepon.max' => 'Nomor telepon maksimal harus terdiri dari 13 karakter!',
                'no_telepon.unique' => 'Nomor telepon sudah digunakan!',
            ]

        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->no_telepon = $request->no_telepon;
            $user->password = Hash::make('123456789');
            $user->type = "2";
            $user->save();

            return response()->json(['success' => 'Data barhasil ditambahkan']);
        }
    }

    public function editKwarcab($id)
    {
        $user = User::find($id);
        return view('backend.user.kwarcab.edit', compact('user'));
    }

    public function updateKwarcab(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email,' . $id,
                'no_telepon' => 'required|string|min:11|max:13|unique:users,no_telepon,' . $id,
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'email.required' => 'Silakan isi alamat email terlebih dahulu!',
                'email.unique' => 'Alamat email sudah digunakan!',
                'no_telepon.required' => 'Silakan isi nomor telepon terlebih dahulu!',
                'no_telepon.min' => 'Nomor telepon minimal harus terdiri dari 11 karakter!',
                'no_telepon.max' => 'Nomor telepon maksimal harus terdiri dari 13 karakter!',
                'no_telepon.unique' => 'Nomor telepon sudah digunakan!',
            ]

        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->no_telepon = $request->no_telepon;
            $user->active_status = $request->status;
            $user->save();

            return response()->json(['success' => 'Data barhasil diubah']);
        }
    }

    public function destroyKwarcab(Request $request)
    {
        $user = User::find($request->id);

        if (!$user) {
            return response()->json(['error' => 'User not found']);
        }

        if (!empty($user->avatar)) {
            if (Storage::exists('public/avatar/' . $user->avatar)) {
                Storage::delete('public/avatar/' . $user->avatar);
            }
        }

        $user->delete();

        return response()->json(['success' => 'Data berhasil dihapus', 'name' => $user->name]);
    }

    public function indexPeserta()
    {
        if (request()->ajax()) {
            $users = User::where('id', '!=', auth()->user()->id)
                ->where('type', 3)
                ->orderBy('name', 'asc')
                ->get();

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('name', function ($user) {
                    $avatar = $user->avatar == ''
                        ? 'https://ui-avatars.com/api/?background=random&name=' . $user->name
                        : asset('storage/avatar/' . $user->avatar);

                    $name = '<h2 class="table-avatar">
                            <a href="student-details.html" class="avatar avatar-sm me-2">
                                <img class="avatar-img rounded-circle" src="' . $avatar . '" alt="User Image"></a>
                            <a href="student-details.html">' . $user->name . '</a>
                        </h2>';

                    return $name;
                })
                ->addColumn('comboBox', function ($user) {
                    $comboBox = "<label class='custom_check'>
                                <input type='checkbox' id='checkbox' data-id='" . $user->id . "'>
                                <span class='checkmark'></span>
                            </label>";

                    return $comboBox;
                })
                ->addColumn('active_status', function ($user) {
                    $badgeClass = $user->active_status == 0 ? 'badge-soft-info' : 'badge-soft-danger';
                    $active_status = '<span class="badge ' . $badgeClass . '">';
                    $active_status .= $user->active_status == 0 ? 'Aktif' : 'Tidak aktif';
                    $active_status .= '</span>';

                    return $active_status;
                })
                ->addColumn('aksi', function ($user) {
                    $editLink = route('pengguna.peserta.edit', $user->id);
                    $btn = '<a href="' . $editLink . '" class="btn btn-sm btn-warning me-2 text-light" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger text-light" data-id="' . $user->id . '" id="btnHapus" title="Hapus"><i class="fas fa-trash"></i></button>';

                    return $btn;
                })
                ->rawColumns(['name', 'aksi', 'active_status', 'comboBox'])
                ->make(true);
        }

        return view('backend.user.peserta.index');
    }

    public function createPeserta()
    {
        return view('backend.user.peserta.add');
    }

    public function storePeserta(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'no_telepon' => 'required|string|min:11|max:13|unique:users,no_telepon',
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'email.required' => 'Silakan isi alamat email terlebih dahulu!',
                'email.unique' => 'Alamat email sudah digunakan!',
                'no_telepon.required' => 'Silakan isi nomor telepon terlebih dahulu!',
                'no_telepon.min' => 'Nomor telepon minimal harus terdiri dari 11 karakter!',
                'no_telepon.max' => 'Nomor telepon maksimal harus terdiri dari 13 karakter!',
                'no_telepon.unique' => 'Nomor telepon sudah digunakan!',
            ]

        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->no_telepon = $request->no_telepon;
            $user->password = Hash::make('123456789');
            $user->type = "3";
            $user->save();

            return response()->json(['success' => 'Data barhasil ditambahkan']);
        }
    }

    public function editPeserta($id)
    {
        $user = User::find($id);
        return view('backend.user.peserta.edit', compact('user'));
    }

    public function updatePeserta(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email,' . $id,
                'no_telepon' => 'required|string|min:11|max:13|unique:users,no_telepon,' . $id,
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'email.required' => 'Silakan isi alamat email terlebih dahulu!',
                'email.unique' => 'Alamat email sudah digunakan!',
                'no_telepon.required' => 'Silakan isi nomor telepon terlebih dahulu!',
                'no_telepon.min' => 'Nomor telepon minimal harus terdiri dari 11 karakter!',
                'no_telepon.max' => 'Nomor telepon maksimal harus terdiri dari 13 karakter!',
                'no_telepon.unique' => 'Nomor telepon sudah digunakan!',
            ]

        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->no_telepon = $request->no_telepon;
            $user->active_status = $request->status;
            $user->save();

            return response()->json(['success' => 'Data barhasil diubah']);
        }
    }

    public function destroyPeserta(Request $request)
    {
        $user = User::find($request->id);

        if (!$user) {
            return response()->json(['error' => 'User not found']);
        }

        if (!empty($user->avatar)) {
            if (Storage::exists('public/avatar/' . $user->avatar)) {
                Storage::delete('public/avatar/' . $user->avatar);
            }
        }

        $user->delete();

        return response()->json(['success' => 'Data berhasil dihapus', 'name' => $user->name]);
    }
}
