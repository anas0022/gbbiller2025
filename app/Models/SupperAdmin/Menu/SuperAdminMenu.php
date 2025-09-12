<?php

namespace App\Models\SupperAdmin\Menu;

use Illuminate\Database\Eloquent\Model;

class SuperAdminMenu extends Model
{
    protected $table = 'super_admin_menus'; // adjust to your actual table name

    protected $fillable = [
        'modulename',
        'icon',
        'route',
        'menuname',
        'submenuname',
        'module_id',
        'menu_id',
        'status',

    ];
}
