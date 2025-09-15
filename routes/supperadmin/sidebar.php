<?php
use App\Models\SupperAdmin\Menu\SuperAdminModules;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
Route::get('/sidebar/menu',function(){
    $sidemenu = SuperAdminModules::where('status','1')
    ->with(['menu','submenu'])->get();
    return $sidemenu;
});