<?php
use App\Http\Controllers\Routes\RoutesListController;
use App\Models\SupperAdmin\Menu\SuperAdminMenu;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SupperAdmin\SupperAdminModuleController;
use App\Http\Controllers\SupperAdmin\SupperAdminMenuController;
use App\Http\Controllers\SupperAdmin\SupperAdminSubMenuController;
use App\Models\SupperAdmin\Menu\SuperAdminModules;
use App\Models\SupperAdmin\Menu\SuperAdminSubmenu;
$allowedIp = '127.0.0.1';

Route::middleware(['auth'])->group(function () use ($allowedIp) {
    Route::get('/superadmin/Create Menu/superadmin', function () use ($allowedIp) {
        if (request()->ip() !== $allowedIp) {
            return redirect('/'); // redirect to home if IP is not allowed
        }
        return view('Supperadmin.Menus.superadmin.index');
    })->name('superadmin.menus');

    Route::post('/superadmin/Create Menu/superadmin', [App\Http\Controllers\SupperAdmin\SupperAdminModuleController::class, 'StoreModule'])->name('superadmin.menus.store');
    Route::get('/superadmin/Create Menu/menu-table', [App\Http\Controllers\SupperAdmin\SupperAdminModuleController::class, 'superadminmenu'])->name('superadmin.menus.table');
    Route::post('/superadmin/Create Menu/update-status', [App\Http\Controllers\SupperAdmin\SupperAdminModuleController::class, 'updateStatus'])
        ->name('module.updateStatus');

    Route::delete('/superadmin/CreateMenu/delete-module/{id}', [SupperAdminModuleController::class, 'deleteModule'])->name('superadmin.menus.delete');

    /* supper admin menu */

    Route::post('/superadmin/menu/create', [SupperAdminMenuController::class, 'create_menu'])->name('create.menu');
    Route::get('/get-modules', function () {
        $module = SuperAdminModules::where('Status', 1)->get();
        ;
        return ($module);
    });


    Route::get('/get-menu/superadmin', function () {
        $menu = SuperAdminMenu::with('module')->get();
        return ($menu);
    });


    Route::post('/superadmin/menu/update-status', [SupperAdminMenuController::class, 'updateStatus'])
        ->name('menu.updateStatus');

    Route::delete('/superadmin/menu/delete-menu/{id}', [SupperAdminMenuController::class, 'deleteModule'])->name('superadmin.menus.delete');

    Route::get('/get-menu', function () {
        $menu = SuperAdminMenu::where('Status', 1)
        ->where('route','')
        ->get();
        ;
        return ($menu);
    });
    Route::post('/superadmin/submenu/create', [SupperAdminSubMenuController::class, 'create_menu'])->name('create.submenu');

    Route::get('/get-submenu/superadmin', function () {
        $menu = SuperAdminSubmenu::with('module')
            ->with('submenu')
            ->get();
        return ($menu);
    });
    Route::post('/superadmin/submenu/update-status', [SupperAdminSubMenuController::class, 'updateStatus'])
        ->name('submenu.updateStatus');
    Route::delete('/superadmin/menu/delete-submenu/{id}', [SupperAdminSubMenuController::class, 'deleteMenu'])->name('superadmin.submenus.delete');
Route::get('/available-routes', [RoutesListController::class, 'getAvailableRoutes'])->name('available.routes');

});



