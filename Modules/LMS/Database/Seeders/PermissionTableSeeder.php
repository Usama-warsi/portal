<?php

namespace Modules\LMS\Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Model;
use Modules\LMS\Entities\LmsUtility;

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
        $permission  = [
            'lms manage',
            'lms dashboard manage',
            'course manage',
            'course create',
            'course edit',
            'course delete',
            'course category manage',
            'course category create',
            'course category edit',
            'course category delete',
            'course subcategory manage',
            'course subcategory create',
            'course subcategory edit',
            'course subcategory delete',
            'header create',
            'header edit',
            'header delete',
            'chapter create',
            'chapter edit',
            'chapter delete',
            'practice file edit',
            'practice file delete',
            'course faq create',
            'course faq edit',
            'course faq delete',
            'custom page manage',
            'custom page create',
            'custom page edit',
            'custom page delete',
            'blog manage',
            'blog create',
            'blog edit',
            'blog delete',
            'subscriber manage',
            'subscriber create',
            'subscriber delete',
            'course coupon manage',
            'course coupon create',
            'course coupon edit',
            'course coupon delete',
            'course coupon show',
            'course order manage',
            'course order create',
            'course order edit',
            'course order delete',
            'course order show',
            'lms setup manage',
            'student manage',
            'student create',
            'student edit',
            'student delete',
            'student show',
            'student logs manage',
            'student logs show',
            'student logs delete',
            'lms report manage',
            'lms store analytics',
            'lms pixel fields create',
            'lms pixel fields delete',
            'lms pwa settings'
        ];

        $company_role = Role::where('name','company')->first();
        foreach ($permission as $key => $value)
        {
            $table = Permission::where('name',$value)->where('module','LMS')->exists();
            if(!$table)
            {
                $permission = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => 'LMS',
                        'created_by' => 0,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
                if(!$company_role->hasPermission($value))
                {
                    $company_role->givePermission($permission);
                }
            }
        }
        LmsUtility::defaultdata();
    }
}
