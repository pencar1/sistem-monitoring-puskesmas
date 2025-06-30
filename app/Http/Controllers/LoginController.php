<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    protected $auth;
    protected $database;

    public function __construct()
    {
        $firebase = (new Factory)
            ->withServiceAccount(config('firebase.credentials.file'))
            ->withDatabaseUri(config('firebase.database.url'));

        $this->auth = $firebase->createAuth();
        $this->database = $firebase->createDatabase();
    }

    public function LoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:8',
        ], [
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password minimal harus 8 karakter.',
        ]);

        try {
            $signInResult = $this->auth->signInWithEmailAndPassword($request->email, $request->password);
            $uid = $signInResult->firebaseUserId();

            // Cek data pengguna di database
            $user = $this->database->getReference('pengguna/' . $uid)->getValue();

            if (!$user) {
                return back()->with('error', 'Data pengguna tidak ditemukan.');
            }

            // Simpan data ke session
            Session::put('firebase_uid', $uid);
            Session::put('nama', $user['nama']);
            Session::put('status', $user['status']);

            // Redirect berdasarkan status
            return $user['status'] === 'Admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('pengguna.dashboard');

        } catch (\Throwable $e) {
            return back()->with('error', 'Login gagal: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login')->with('success', 'Logout berhasil.');
    }
}
