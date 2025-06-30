<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class HistoryController extends Controller
{
    public function index()
    {
        $firebase = (new \Kreait\Firebase\Factory)
            ->withServiceAccount(config('firebase.credentials.file'))
            ->withDatabaseUri(config('firebase.database.url'));

        $database = $firebase->createDatabase();
        $rawData = $database->getReference('log_akses')->getValue();

        $data = [];

        if ($rawData) {
            foreach ($rawData as $timestamp => $value) {
                $value['waktu'] = str_replace('_', ' ', $timestamp); // Tambahkan field 'waktu'
                $data[] = $value;
            }

            // Balik data agar terbaru muncul di atas
            $data = array_reverse($data);
        }

        $status = session('status');

        if ($status === 'Admin') {
            return view('admin.historyaksespintuadmin', compact('data'));
        } elseif ($status === 'Pengguna') {
            return view('pengguna.historyaksespintupengguna', compact('data'));
        } else {
            return redirect()->route('login')->with('error', 'Status tidak valid atau belum login');
        }
    }
}
