<?php

namespace Modules\Spreadsheet\Entities;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Related extends Model
{
    use HasFactory;

    protected $table = 'spreadsheet_releted';

    protected $fillable = [
        'related',
        'model_name',
    ];

    protected static function newFactory()
    {
        return \Modules\Spreadsheet\Database\factories\RelatedFactory::new();
    }

    public static function GivePermissionToRoles($role_id = null,$rolename = null)
    {
        $client_permissions=[
            'spreadsheet view',
            'spreadsheet edit',
        ];

        $vendor_permissions=[
            'spreadsheet view',
            'spreadsheet edit',
        ];

        $staff_permissions=[
            'spreadsheet view',
            'spreadsheet edit',
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
                        $role->givePermission($permission);
                    }
                }
            }

            // vendor
            $roles_v = Role::where('name','vendor')->get();

            foreach($roles_v as $role)
            {
                foreach($vendor_permissions as $permission_v){
                    $permission = Permission::where('name',$permission_v)->first();
                    if(!$role->hasPermission($permission_v))
                    {
                        $role->givePermission($permission);
                    }
                }
            }

             // staff
             $roles_s = Role::where('name','staff')->get();

             foreach($roles_s as $role)
             {
                 foreach($staff_permissions as $permission_s){
                    $permission = Permission::where('name',$permission_s)->first();
                    if(!$role->hasPermission($permission_s))
                    {
                        $role->givePermission($permission);
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
                        $roles_c->givePermission($permission);
                    }
                }
            }
            elseif($rolename == 'vendor')
            {
                $roles_v = Role::where('name','vendor')->where('id',$role_id)->first();
                foreach($vendor_permissions as $permission_v){
                    $permission = Permission::where('name',$permission_v)->first();
                    if(!$roles_v->hasPermission($permission_v))
                    {
                        $roles_v->givePermission($permission);
                    }
                }
            }
            elseif($rolename == 'staff')
            {
                $roles_s = Role::where('name','staff')->where('id',$role_id)->first();
                foreach($staff_permissions as $permission_s){
                    $permission = Permission::where('name',$permission_s)->first();
                    if(!$roles_s->hasPermission($permission_s))
                    {
                        $roles_s->givePermission($permission);
                    }
                }
            }
        }

    }

    //get views data via submodule name
    public static function get_view_to_stack_hook()
    {
        $views =[
            'Projects'           => 'taskly::projects.show',
            'Contracts'          => 'contract::contracts.index',
            'Proposal '          => 'proposal.index',
            'Invoice'            => 'invoice.index',
            'Lead'               => 'lead::leads.index',
        ];

        return $views;
    }
}
