<?php

namespace App\Models\SupperAdmin\Menu;

use Illuminate\Database\Eloquent\Model;
use App\Models\SupperAdmin\Menu\SuperAdminMenu;
use App\Models\SupperAdmin\Menu\SuperAdminModules;

class SuperAdminSubmenu extends Model
{
    protected $table = "super_admin_submenus";

    protected $fillable = [
        'menuname',
        'menu_module',
        'menu_id',
        'sub_route',
        'status',
    ];
    public function submenu()
    {
        return $this->belongsTo(SuperAdminMenu::class, 'menu_id', 'id')
            ->select('id', 'Menuname');
    }

    public function module()
    {
        return $this->belongsTo(SuperAdminModules::class, 'menu_module', 'id')
            ->select('id', 'modulename', 'icon');
    }
}
