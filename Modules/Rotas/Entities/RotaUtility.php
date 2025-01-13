<?php

namespace Modules\Rotas\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Role;
use App\Models\Permission;

class RotaUtility extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Rotas\Database\factories\RotaUtilityFactory::new();
    }
    public static function GivePermissionToRoles($role_id = null, $rolename = null)
    {
        $staff_permission = [

            'rotas manage',
            'rotas dashboard manage',
            'rotas work schedule manage',
            'rota manage',
            'availability manage',
            'availability create',
            'availability edit',
            'availability delete',
        ];

        if ($role_id == Null) {

            // staff
            $roles_v = Role::where('name', 'staff')->get();

            foreach ($roles_v as $role) {
                foreach ($staff_permission as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    // $role->givePermission($permission);
                    if(!$role->hasPermission($permission_v))
                    {
                        $role->givePermission($permission);
                    }
                }
            }
        } else {
            if ($rolename == 'staff') {
                $roles_v = Role::where('name', 'staff')->where('id', $role_id)->first();
                foreach ($staff_permission as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if(!empty($permission)){
                        // $roles_v->givePermission($permission);
                        if(!$roles_v->hasPermission($permission_v))
                        {
                            $roles_v->givePermission($permission);
                        }
                    }
                }
            }
        }
    }

    public static function manage()
    {
        if(module_is_active('Hrm'))
        {
            $var = 'Hrm';
        }
        else
        {
            $var = 'Rotas';
        }

        return $var;
    }
}
