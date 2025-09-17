<?php

use Illuminate\Support\Facades\Route;

// all these already have prefix "superadmin" and name "superadmin."
// because of the group in web.php

Route::get('/subscription', function () {
    return view('supperadmin.subscription.index');
})->name('subscription.index');
