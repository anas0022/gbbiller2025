<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

$allowedIp = '127.0.0.1';

Route::middleware(['auth'])->group(function () use ($allowedIp) {
    Route::get('/superadmin/Create Menu/superadmin', function () use ($allowedIp) {
        if (request()->ip() !== $allowedIp) {
            return redirect('/'); // redirect to home if IP is not allowed
        }
        return view('Supperadmin.Menus.superadmin.index');
    })->name('superadmin.menus');
});
