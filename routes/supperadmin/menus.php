<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SupperAdmin\SupperAdminModuleController;

$allowedIp = '127.0.0.1';

Route::middleware(['auth'])->group(function () use ($allowedIp) {
    Route::get('/superadmin/Create Menu/superadmin', function () use ($allowedIp) {
        if (request()->ip() !== $allowedIp) {
            return redirect('/'); // redirect to home if IP is not allowed
        }
        return view('Supperadmin.Menus.superadmin.index');
    })->name('superadmin.menus');

    Route::post('/superadmin/Create Menu/superadmin', [App\Http\Controllers\SupperAdmin\SupperAdminModuleController::class, 'StoreModule'])->name('superadmin.menus.store');
    Route::get('/superadmin/Create Menu/menu-table',[App\Http\Controllers\SupperAdmin\SupperAdminModuleController::class ,'superadminmenu'])->name('superadmin.menus.table');
    Route::post('/superadmin/Create Menu/update-status', [App\Http\Controllers\SupperAdmin\SupperAdminModuleController::class, 'updateStatus'])
    ->name('module.updateStatus');
    
    Route::delete('/superadmin/CreateMenu/delete-module/{id}', [SupperAdminModuleController::class, 'deleteModule'])->name('superadmin.menus.delete');


});
