<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::DASHBOARD;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'username' => 'required',
                'password' => 'required',
            ],
            [
                'username.required' => 'Silakan isi username terlebih dahulu',
                'password.required' => 'Silakan isi password terlebih dahulu'
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $credentials = $request->only('username', 'password');
            $user = User::where(function ($query) use ($credentials) {
                $query->where('email', $credentials['username'])
                    ->orWhere('no_telepon', $credentials['username']);
            })
                ->first();

            if (!$user) {
                return response()->json(['NoUsername' => ['message' => 'Username tidak tersedia.']]);
            }

            if ($user->active_status == 1) {
                return response()->json(['NonActiveUsername' => ['message' => 'Akun anda telah di nonaktifkan.']]);
            }

            if ($user && Hash::check($credentials['password'], $user->password)) {
                Auth::loginUsingId($user->id);
                if ($user->type == 'Panitia') {
                    return response()->json(['redirect' => route('dashboard.index')]);
                } elseif ($user->type == 'Juri') {
                    return response()->json(['redirect' => route('dashboard.index')]);
                } elseif ($user->type == 'Kwarcab') {
                    return response()->json(['redirect' => route('dashboard.index')]);
                } else {
                    return response()->json(['redirect' => route('beranda.index')]);
                }
            } else {
                return response()->json(['WrongPassword' => ['message' => 'Password salah.']]);
            }
        }
    }


    public function username()
    {
        $fieldType = filter_var(request()->input('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'no_telepon';
        request()->merge([$fieldType => request('username')]);
        return $fieldType;
    }
}
