<?php

return [
    'credentials' => [
        'file' => storage_path('app/firebase/firebase_credentials.json'),
    ],
    'database' => [
        'url' => env('FIREBASE_DATABASE_URL'),
    ],
];
