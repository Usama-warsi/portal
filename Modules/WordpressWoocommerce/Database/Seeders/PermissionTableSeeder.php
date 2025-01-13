<?php

namespace Modules\WordpressWoocommerce\Database\Seeders;

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

        $permission  = [
            'wordpresswoocommerce manage',
            'woocommerce manage',
            'woocommerce customer manage',
            'woocommerce product manage',
            'woocommerce product create',
            'woocommerce product edit',
            'woocommerce order manage',
            'woocommerce order show',
            'woocommerce category manage',
            'woocommerce category create',
            'woocommerce category edit',
            'woocommerce coupon manage',
            'woocommerce tax manage',
            'woocommerce tax create',
            'woocommerce tax edit',
        ];

        $company_role = Role::where('name','company')->first();
        foreach ($permission as $key => $value)
        {
            $table = Permission::where('name',$value)->where('module','WordpressWoocommerce')->exists();
            if(!$table)
            {
                $data = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => 'WordpressWoocommerce',
                        'created_by' => 0,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );

                if(!$company_role->hasPermission($value)){
                    $company_role->givePermission($data);
                }
            }
        }
    }
}
