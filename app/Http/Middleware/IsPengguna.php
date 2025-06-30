<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Symfony\Component\HttpFoundation\Response;

class IsPengguna
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $uid = $request->session()->get('firebase_uid');

        $firebase = (new Factory)
            ->withServiceAccount(config('firebase.credentials.file'))
            ->withDatabaseUri(config('firebase.database.url'));

        $database = $firebase->createDatabase();
        $userRef = $database->getReference('pengguna/' . $uid)->getValue();

        if (!$userRef || ($userRef['status'] ?? '') !== 'Pengguna') {
            return abort(403, 'Akses ditolak. Anda bukan pengguna.');
        }

        return $next($request);
    }
}
