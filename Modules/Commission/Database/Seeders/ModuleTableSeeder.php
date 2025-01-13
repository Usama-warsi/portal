<?php

namespace Modules\Commission\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Commission\Entities\CommissionModule;


class ModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $sub_module = [
            'Invoice'
        ];
        foreach($sub_module as $sm){
            $check = CommissionModule::where('module','Account')->where('submodule',$sm)->first();
            if(!$check){
                $new = new CommissionModule();
                $new->module = 'Account';
                $new->submodule = $sm;
                $new->save();
            }
        }

        $sub_module = [
            'Project'
        ];
        foreach($sub_module as $sm){
            $check = CommissionModule::where('module','Taskly')->where('submodule',$sm)->first();
            if(!$check){
                $new = new CommissionModule();
                $new->module = 'Taskly';
                $new->submodule = $sm;
                $new->save();
            }
        }

        $sub_module = [
            'Sales Invoice'
        ];
        foreach($sub_module as $sm){
            $check = CommissionModule::where('module','Sales')->where('submodule',$sm)->first();
            if(!$check){
                $new = new CommissionModule();
                $new->module = 'Sales';
                $new->submodule = $sm;
                $new->save();
            }
        }

    }
}
