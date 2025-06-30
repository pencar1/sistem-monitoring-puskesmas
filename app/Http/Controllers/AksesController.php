<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class AksesController extends Controller
{
    protected $database;

    public function __construct()
    {
        $firebase = (new Factory)
            ->withServiceAccount(config('firebase.credentials.file'))
            ->withDatabaseUri(config('firebase.database.url'));

        $this->database = $firebase->createDatabase();
    }

    public function index()
    {
        $ref = $this->database->getReference('data_rfid');
        $data = $ref->getValue();

        $result = [];
        $no = 1;

        if ($data) {
            foreach ($data as $uid => $entry) {
                $result[] = [
                    'no' => $no++,
                    'nama' => $entry['nama'] ?? 'N/A',
                    'uid' => $entry['uid'] ?? $uid,
                    'status' => $entry['status'] ?? 'N/A',
                    'waktu' => $entry['waktu'] ?? '-',
                ];
            }
        }

        return view('admin.aksespintuadmin', ['data' => $result]);
    }

    public function edit($uid)
    {
        $ref = $this->database->getReference("data_rfid/{$uid}");
        $data = $ref->getValue();

        if (!$data) return abort(404);

        $data['uid'] = $uid;

        return view('admin.aksespintu.ubahak', compact('data'));
    }

    public function update(Request $request, $uid)
    {
        $request->validate([
            'nama' => 'required|string',
            'status' => 'required|in:Pending,Aktif,Tidak Aktif'
        ]);

        $this->database->getReference("data_rfid/{$uid}")
            ->update([
                'nama' => $request->nama,
                'status' => $request->status,
            ]);

        return redirect()->route('aksespintu')->with('success', 'Data akses pintu berhasil diperbarui');
    }

    public function destroy($uid)
    {
        try {
            $this->database->getReference("data_rfid/{$uid}")->remove();

            return redirect()->route('aksespintu')->with('success', 'Data akses pintu berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect()->route('aksespintu')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
