<?php

namespace Modules\Retainer\Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();
        Artisan::call('cache:clear');
        $module = 'Retainer';

        $permissions  = [
            'retainer manage',
            'retainer create',
            'retainer edit',
            'retainer delete',
            'retainer show',
            'retainer duplicate',
            'retainer send',
            'retainer convert invoice',
            'retainer payment create',
            'retainer payment delete',
            'retainer product delete',
        ];

        $company_role = Role::where('name', 'company')->first();
        foreach ($permissions as $key => $value) {
            $table = Permission::where('name', $value)->where('module', $module)->exists();
            if (!$table) {
                $permission = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => $module,
                        'created_by' => 0,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
                if (!$company_role->hasPermission($value)) {
                    $company_role->givePermission($permission);
                }
            }
        }
    }
}
