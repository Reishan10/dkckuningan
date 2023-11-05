<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::BERANDA;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'no_telepon' => ['required', 'string', 'min:11', 'max:13', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Silakan isi nama terlebih dahulu.',
            'no_telepon.required' => 'Silakan isi nomor telepon terlebih dahulu.',
            'no_telepon.min' => 'No Telepon harus terdiri dari :min karakter.',
            'no_telepon.max' => 'No Telepon harus terdiri dari :max karakter.',
            'email.unique' => 'No Telepon ini sudah digunakan oleh pengguna lain.',
            'email.required' => 'Silakan isi alamat email terlebih dahulu.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.max' => 'Alamat email tidak boleh lebih dari :max karakter.',
            'email.unique' => 'Alamat email ini sudah digunakan oleh pengguna lain.',
            'password.required' => 'Silakan isi password terlebih dahulu.',
            'password.min' => 'Password harus terdiri dari :min karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password.',
        ]);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'no_telepon' => $data['no_telepon'],
            'type' => 3,
            'password' => Hash::make($data['password']),
        ]);
    }
}
