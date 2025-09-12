<?php

namespace App\Models\SupperAdmin\Menu;

use Illuminate\Database\Eloquent\Model;

class SuperAdminModules extends Model
{
    protected $table = 'super_admin_modules'; // adjust to your actual table name

    protected $fillable = [
        'modulename',
        'icon'
        

    ];
}
