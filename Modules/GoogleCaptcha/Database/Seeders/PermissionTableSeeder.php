<?php

namespace Modules\GoogleCaptcha\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use App\Models\Role;
use App\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        Artisan::call('cache:clear');

        $admin_permission  = [
            'recaptcha manage',
        ];

        $superAdminRole  = Role::where('name','super admin')->first();
        foreach ($admin_permission as $key => $value)
        {
            $table = Permission::where('name',$value)->where('module','GoogleCaptcha')->exists();
            if(!$table)
            {
                $data = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => 'GoogleCaptcha',
                        'created_by' => 0,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
                if(!$superAdminRole->hasPermission($value)){
                    $superAdminRole->givePermission($data);
                }
            }
        }
    }
}
