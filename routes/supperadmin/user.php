<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/create/users',function(){
    return view('Supperadmin.users.index');
});