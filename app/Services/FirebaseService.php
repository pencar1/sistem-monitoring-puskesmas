<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Illuminate\Support\Carbon;


class FirebaseService
{
    protected Database $database;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'));

        $this->database = $factory->createDatabase();
    }

    public function pushSensorDht($suhu, $kelembaban)
    {
        $this->database
            ->getReference('sensor_dht')
            ->push([
                'suhu' => $suhu,
                'kelembaban' => $kelembaban,
                'timestamp' => Carbon::now()->toDateTimeString(),
            ]);
    }

    public function getLatestData()
    {
        return $this->database
            ->getReference('sensor_dht')
            ->orderByKey()
            ->limitToLast(1)
            ->getValue();
    }
}
