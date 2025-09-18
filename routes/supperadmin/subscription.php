<?php

use App\Http\Controllers\Subscription\SubscriptionController;
use Illuminate\Support\Facades\Route;

// all these already have prefix "superadmin" and name "superadmin."
// because of the group in web.php

Route::get('/subscription', function () {
    return view('supperadmin.subscription.index');
})->name('subscription.index');
Route::post('/subscription/store', [SubscriptionController::class, 'Storesub'])
     ->name('subscription.store');

