<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan; // ✅ add this


require __DIR__.'/auth.php';
$allowedIp = '127.0.0.1';
Route::middleware(['auth'])->group(function () use ($allowedIp) {
    Route::group([
        'middleware' => function ($request, $next) use ($allowedIp) {
            if ($request->ip() !== $allowedIp) {
                abort(403, 'Unauthorized access from this IP.');
            }
            return $next($request);
        }
    ], function () {
require __DIR__.'/database.php';
require __DIR__.'/supperadmin/dasboard.php';
require __DIR__.'/supperadmin/menus.php';
require __DIR__.'/supperadmin/sidebar.php';


    });});
