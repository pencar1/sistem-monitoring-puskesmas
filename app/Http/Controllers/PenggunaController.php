<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Session;

class PenggunaController extends Controller
{
    public function index()
    {
        $firebase = (new \Kreait\Firebase\Factory)
            ->withServiceAccount(config('firebase.credentials.file'))
            ->withDatabaseUri(config('firebase.database.url'));

        $database = $firebase->createDatabase();

        $data = $database->getReference('pengguna')->getValue();

        // Balik urutan array agar data terbaru di atas
        if ($data) {
            $data = array_reverse($data);
        }

        return view('admin.penggunaadmin', compact('data'));
    }

    public function create()
    {
        return view('admin.pengguna.tambahp');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'status' => 'required|in:Admin,Pengguna'
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 8 karakter.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status harus Admin atau Pengguna.',
        ]);

        $firebase = (new \Kreait\Firebase\Factory)
            ->withServiceAccount(config('firebase.credentials.file'))
            ->withDatabaseUri(config('firebase.database.url'));

        $auth = $firebase->createAuth();
        $database = $firebase->createDatabase();

        // Cek apakah email atau NIP sudah digunakan
        $existingUsers = $database->getReference('pengguna')->getValue();

        if ($existingUsers) {
            foreach ($existingUsers as $user) {
                if (isset($user['email']) && $user['email'] === $request->email) {
                    return redirect()->back()
                        ->withErrors(['email' => 'Email sudah digunakan.'])
                        ->withInput();
                }
            }
        }

        try {
            // Buat user di Firebase Auth
            $user = $auth->createUser([
                'email' => $request->email,
                'password' => $request->password,
                'displayName' => $request->nama,
            ]);

            // Simpan ke Realtime Database
            $database->getReference('pengguna/' . $user->uid)->set([
                'nama' => $request->nama,
                'email' => $request->email,
                'status' => $request->status
            ]);

            return redirect()->route('pengguna')->with('success', 'Data pengguna berhasil ditambahkan!');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['firebase' => $e->getMessage()]);
        }
    }

    public function edit($uid)
    {
        $firebase = (new Factory)
            ->withServiceAccount(config('firebase.credentials.file'))
            ->withDatabaseUri(config('firebase.database.url'));

        $database = $firebase->createDatabase();

        // Ambil data spesifik pengguna
        $userData = $database
            ->getReference('pengguna/' . $uid)
            ->getValue();

        if (! $userData) {
            return redirect()->route('pengguna')
                ->with('error', 'Data pengguna tidak ditemukan.');
        }

        return view('admin.pengguna.ubahp', [
            'uid'  => $uid,
            'user' => $userData,
        ]);
    }

    public function update(Request $request, $uid)
    {
        $request->validate([
            'nama'     => 'required|string',
            'email'    => 'required|email',
            'password' => 'nullable|min:8',
            'status'   => 'required|in:Admin,Pengguna',
        ], [
            'nama.required'     => 'Nama wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.min'      => 'Password minimal 8 karakter.',
            'status.required'   => 'Status wajib dipilih.',
            'status.in'         => 'Status harus Admin atau Pengguna.',
        ]);

        $firebase = (new Factory)
            ->withServiceAccount(config('firebase.credentials.file'))
            ->withDatabaseUri(config('firebase.database.url'));

        $auth     = $firebase->createAuth();
        $database = $firebase->createDatabase();

        try {
            // Jika email berubah, perbarui di Auth
            if ($request->email !== $database->getReference("pengguna/{$uid}/email")->getValue()) {
                $auth->updateUser($uid, [
                    'email' => $request->email,
                ]);
            }
            // Jika password diisi, perbarui juga
            if ($request->filled('password')) {
                $auth->updateUser($uid, [
                    'password' => $request->password,
                ]);
            }

            // Perbarui data di Realtime Database
            $database->getReference('pengguna/' . $uid)
                ->update([
                    'nama'   => $request->nama,
                    'email'  => $request->email,
                    'status' => $request->status,
                ]);

            return redirect()->route('pengguna')->with('success', 'Data pengguna berhasil diperbarui.');
        } catch (\Throwable $e) {
            return redirect()->back()
                ->withErrors(['firebase' => 'Gagal memperbarui: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy($uid)
    {
        $firebase = (new Factory)
            ->withServiceAccount(config('firebase.credentials.file'))
            ->withDatabaseUri(config('firebase.database.url'));

        $auth = $firebase->createAuth();
        $database = $firebase->createDatabase();

        try {
            // Ambil data pengguna yang akan dihapus
            $userToDelete = $database->getReference('pengguna/' . $uid)->getValue();

            if (!$userToDelete) {
                return redirect()->route('pengguna')->withErrors(['warning' => 'Pengguna tidak ditemukan.']);
            }

            // Cek jika user yang akan dihapus adalah admin
            if (isset($userToDelete['status']) && $userToDelete['status'] === 'Admin') {
                return redirect()->route('pengguna')->withErrors(['warning' => 'Pengguna dengan status Admin tidak boleh dihapus.']);
            }

            // Hapus dari Realtime Database
            $database->getReference('pengguna/' . $uid)->remove();

            // Hapus dari Firebase Auth
            $auth->deleteUser($uid);

            return redirect()->route('pengguna')->with('success', 'Data pengguna berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect()->route('pengguna')->withErrors(['warning' => 'Gagal menghapus pengguna: ' . $e->getMessage()]);
        }
    }

    public function editProfile()
    {
        $uid = Session::get('firebase_uid');

        if (!$uid) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $firebase = (new Factory)
            ->withServiceAccount(config('firebase.credentials.file'))
            ->withDatabaseUri(config('firebase.database.url'));

        $database = $firebase->createDatabase();

        $user = $database->getReference('pengguna/' . $uid)->getValue();

        if (!$user) {
            return redirect()->back()->with('error', 'Data pengguna tidak ditemukan.');
        }

        // Cek status user: Admin atau Pengguna
        if (isset($user['status']) && $user['status'] === 'Admin') {
            return view('admin.profileadmin', compact('user'));
        } else {
            return view('pengguna.profilepengguna', compact('user'));
        }
    }

    public function updateProfile(Request $request)
    {
        $uid = Session::get('firebase_uid');

        if (!$uid) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $request->validate([
            'nama'     => 'required|string',
            'email'    => 'required|email',
            'password' => 'nullable|min:8',
        ]);

        $firebase = (new Factory)
            ->withServiceAccount(config('firebase.credentials.file'))
            ->withDatabaseUri(config('firebase.database.url'));

        $auth     = $firebase->createAuth();
        $database = $firebase->createDatabase();

        try {
            $currentEmail = $database->getReference("pengguna/{$uid}/email")->getValue();
            $user = $database->getReference("pengguna/{$uid}")->getValue();

            if (!$user) {
                return redirect()->back()->withErrors(['error' => 'Data pengguna tidak ditemukan.']);
            }

            if ($request->email !== $currentEmail) {
                $auth->updateUser($uid, ['email' => $request->email]);
            }

            if ($request->filled('password')) {
                $auth->updateUser($uid, ['password' => $request->password]);
            }

            $database->getReference("pengguna/{$uid}")->update([
                'nama'  => $request->nama,
                'email' => $request->email,
            ]);

            Session::put('nama', $request->nama);

            // Redirect berdasarkan status
            if (isset($user['status']) && $user['status'] === 'Admin') {
                return redirect()->route('admin.editprofile')->with('success', 'Profil berhasil diperbarui.');
            } else {
                return redirect()->route('pengguna.editprofile')->with('success', 'Profil berhasil diperbarui.');
            }

        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
