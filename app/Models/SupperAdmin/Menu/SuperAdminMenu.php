<?php

namespace App\Models\SupperAdmin\Menu;

use Illuminate\Database\Eloquent\Model;
use App\Models\SupperAdmin\Menu\SuperAdminModules; // import related model

class SuperAdminMenu extends Model
{
    protected $table = 'super_admin_menus';

    protected $fillable = [
        'Module_id',
        'Menuname',
        'route',
        'status',
    ];

    // Relationship: each menu belongs to one module
    public function module()
    {
        return $this->belongsTo(SuperAdminModules::class, 'Module_id', 'id')
            ->select('id', 'modulename', 'icon');
    }
    

}
