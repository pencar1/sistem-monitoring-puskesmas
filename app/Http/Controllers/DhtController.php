<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class DhtController extends Controller
{
    public function index()
    {
        $firebase = (new \Kreait\Firebase\Factory)
            ->withServiceAccount(config('firebase.credentials.file'))
            ->withDatabaseUri(config('firebase.database.url'));

        $database = $firebase->createDatabase();
        $data = $database->getReference('sensor_dht')->getValue();

        // Balik urutan array agar data terbaru di atas
        if ($data) {
            $data = array_reverse($data);
        }

        // Ambil status user dari session
        $status = session('status');

        // Pilih view berdasarkan status
        if ($status === 'Admin') {
            return view('admin.suhudankelembabanadmin', compact('data'));
        } elseif ($status === 'Pengguna') {
            return view('pengguna.suhudankelembabanpengguna', compact('data'));
        } else {
            return redirect()->route('login')->with('error', 'Status tidak valid atau belum login');
        }
    }
}
