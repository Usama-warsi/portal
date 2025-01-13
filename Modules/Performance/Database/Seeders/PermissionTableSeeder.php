<?php

namespace Modules\Performance\Database\Seeders;

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
        $module = 'Performance';

        $permission  = [
            'performance manage',
            'indicator manage',
            'indicator create',
            'indicator edit',
            'indicator delete',
            'indicator show',
            'appraisal manage',
            'appraisal create',
            'appraisal edit',
            'appraisal delete',
            'appraisal show',
            'goaltracking manage',
            'goaltracking create',
            'goaltracking edit',
            'goaltracking delete',
            'goal type manage',
            'goal type create',
            'goal type edit',
            'goal type delete',
            'performancetype manage',
            'performancetype create',
            'performancetype edit',
            'performancetype delete',
            'competencies manage',
            'competencies create',
            'competencies edit',
            'competencies delete',
        ];

        $company_role = Role::where('name', 'company')->first();
        foreach ($permission as $key => $value) {
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
