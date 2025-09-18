<?php

namespace App\Models\SupperAdmin\Subscription;

use Illuminate\Database\Eloquent\Model;

class SubscriptionModules extends Model
{
    protected $table = 'subscription_modules';

    protected $fillable = ['Sub_type','icon'];
}
