<?php

namespace App\Models\Auth;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'user_models'; // <-- Replace with your table name if different

    protected $fillable = [
        'biller_code',
        'password',
        'username',
        'email',
        'password',
        'mobile',
        'role',
        'user_type',
        'store_id',
        'mobile_code',
        'plan',
        'created_by',
       
        'name', // add other columns as needed
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
