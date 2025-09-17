<?php

namespace App\Models\SupperAdmin\Menu;

use Illuminate\Database\Eloquent\Model;

class SuperAdminModules extends Model
{
    protected $table = 'super_admin_modules';

    protected $fillable = [
        'modulename',
        'icon',
        'status'
    ];

    // A module has many menus
    public function menu()
    {
        return $this->hasMany(SuperAdminMenu::class, 'Module_id', 'id')
            ->select('id', 'Menuname', 'route', 'Module_id')
            ->where('status','1');
            ;
    }

    // A module has many submenus through menus (optional)
    public function submenu()
    {
        return $this->hasMany(SuperAdminSubmenu::class, 'menu_module', 'id')
            ->select('id', 'menuname', 'sub_route', 'menu_module','menu_id')
                ->where('status','1');
            ;
    }
}
