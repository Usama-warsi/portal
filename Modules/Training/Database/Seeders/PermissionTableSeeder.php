<?php

namespace Modules\Training\Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

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
        $permission  = [
            'trainings manage',
            'training manage',
            'training create',
            'training edit',
            'training delete',
            'training show',
            'training update status',
            'trainingtype manage',
            'trainingtype create',
            'trainingtype edit',
            'trainingtype delete',
            'trainer manage',
            'trainer create',
            'trainer show',
            'trainer edit',
            'trainer delete',
        ];
        $company_role = Role::where('name', 'company')->first();
        foreach ($permission as $key => $value) {
            $table = Permission::where('name', $value)->where('module', 'Training')->exists();
            if (!$table) {
                $permission = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => 'Training',
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
