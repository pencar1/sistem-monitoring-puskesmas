<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class DashboardController extends Controller
{
    public function index()
    {
        $firebase = (new Factory)
            ->withServiceAccount(config('firebase.credentials.file'))
            ->withDatabaseUri(config('firebase.database.url'));

        $database = $firebase->createDatabase();

        // Ambil data pengguna
        $refPengguna = $database->getReference('pengguna');
        $dataPengguna = $refPengguna->getValue();
        $jumlahPengguna = $dataPengguna ? count($dataPengguna) : 0;

        // Ambil data akses pintu
        $refAkses = $database->getReference('data_rfid');
        $dataAkses = $refAkses->getValue();
        $jumlahAksesPintu = $dataAkses ? count($dataAkses) : 0;

        // Ambil data suhu & kelembaban
        $refSensor = $database->getReference('sensor_dht');
        $dataSensor = $refSensor->getValue();

        $suhu = '-';
        $kelembaban = '-';
        $suhuKelembabanChart = [];

        if ($dataSensor) {
            // Urutkan data agar terbaru di atas
            $dataSensor = array_reverse($dataSensor);

            // Ambil maksimal 10 data untuk chart
            $chartData = array_slice($dataSensor, 0, 10);

            foreach ($chartData as $timestamp => $entry) {
                $label = str_replace('_', ' ', $timestamp); // ubah key jadi label
                $suhuKelembabanChart[] = [
                    'label' => $label,
                    'suhu' => floatval($entry['suhu'] ?? 0),
                    'kelembaban' => floatval($entry['kelembaban'] ?? 0),
                ];
            }

            // Ambil data terbaru (paling pertama setelah dibalik)
            $latest = reset($dataSensor);
            $suhu = $latest['suhu'] ?? '-';
            $kelembaban = $latest['kelembaban'] ?? '-';
        }

        // Ambil data log akses RFID
        $refLogAkses = $database->getReference('log_akses');
        $rawLogData = $refLogAkses->getValue();
        $logAkses = [];

        if ($rawLogData) {
            foreach ($rawLogData as $timestamp => $value) {
                $logAkses[] = [
                    'nama' => $value['nama'] ?? 'Tidak diketahui',
                    'uid' => $value['uid'] ?? '-',
                    'waktu' => str_replace('_', ' ', $timestamp)
                ];
            }

            // Urutkan agar terbaru di atas
            $logAkses = array_reverse($logAkses);

            // Ambil maksimal 5 data saja
            $logAkses = array_slice($logAkses, 0, 5);
        }

        $uidTerakhir = $logAkses[0]['uid'] ?? '-';

        // Ambil nama
        $namaPengguna = session('nama') ?? 'Tidak diketahui';

        // Ambil status dari session
        $status = session('status');

        if ($status === 'Admin') {
            return view('admin.dashboardadmin', compact('suhu', 'kelembaban', 'jumlahPengguna', 'jumlahAksesPintu', 'logAkses', 'suhuKelembabanChart'));
        } elseif ($status === 'Pengguna') {
            return view('pengguna.dashboardpengguna', compact('suhu', 'kelembaban', 'namaPengguna', 'uidTerakhir', 'logAkses', 'suhuKelembabanChart'));
        } else {
            return redirect()->route('login')->with('error', 'Status tidak dikenali');
        }
    }
}
