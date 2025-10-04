<?php
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/create/users',function(){
    return view('Supperadmin.users.index');
});
Route::post("/create/user",[UserController::class,'userCreate'])->name('user.creating');