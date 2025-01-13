<?php

namespace Modules\SalesAgent\Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\SalesAgent\Entities\SalesAgentUtility;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $super_admin = User::where('type','super admin')->first();
        if(!empty($super_admin))
        {
            $companies = User::where('type','company')->get();
            if(count($companies) > 0)
            {
                foreach ($companies as $key => $company) {
                    $role = Role::where('name','salesagent')->where('created_by',$company->id)->where('guard_name','web')->exists();
                    if(!$role)
                    {
                        $role                   = new Role();
                        $role->name             = 'salesagent';
                        $role->guard_name       = 'web';
                        $role->module           = 'SalesAgent';
                        $role->created_by       = $company->id;
                        $role->save();
                    }
                }
            }
        }

        SalesAgentUtility::GivePermissionToRoles();
    }
}
