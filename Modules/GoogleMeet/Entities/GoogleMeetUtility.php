<?php

namespace Modules\GoogleMeet\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Role;
use App\Models\Permission;

class GoogleMeetUtility extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\GoogleMeet\Database\factories\GoogleMeetUtilityFactory::new();
    }

    public static function GivePermissionToRoles($role_id = null,$rolename = null)
    {
        $client_permissions=[

            'googlemeet manage',
            'googlemeet show'

        ];

        $staff_permissions=[

            'googlemeet manage',
            'googlemeet show'
        ];

        if($role_id == Null)
        {
            // client
            $roles_c = Role::where('name','client')->get();
            foreach($roles_c as $role)
            {
                foreach($client_permissions as $permission_c){
                    $permission = Permission::where('name',$permission_c)->first();
                    if(!$role->hasPermission($permission_c))
                    {
                        $role->givePermission($permission_c);
                    }
                }
            }

            // staff
            $roles_v = Role::where('name','staff')->get();

            foreach($roles_v as $role)
            {
                foreach($staff_permissions as $permission_v){
                    $permission = Permission::where('name',$permission_v)->first();
                    if(!$role->hasPermission($permission_v))
                    {
                        $role->givePermission($permission_v);
                    }
                }
            }

        }
        else
        {
            if($rolename == 'client')
            {
                $roles_c = Role::where('name','client')->where('id',$role_id)->first();
                foreach($client_permissions as $permission_c){
                    $permission = Permission::where('name',$permission_c)->first();
                    if(!$roles_c->hasPermission($permission_c))
                    {
                        $roles_c->givePermission($permission_c);
                    }
                }
            }
            elseif($rolename == 'staff')
            {
                $roles_v = Role::where('name','staff')->where('id',$role_id)->first();
                foreach($staff_permissions as $permission_v){
                    $permission = Permission::where('name',$permission_v)->first();
                    if(!$roles_v->hasPermission($permission_v))
                    {
                        $roles_v->givePermission($permission_v);
                    }
                }
            }
        }

    }
}
