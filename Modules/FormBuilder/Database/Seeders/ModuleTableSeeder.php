<?php

namespace Modules\FormBuilder\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\FormBuilder\Entities\FormBuilderModule;

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
            'Lead','Deal'
        ];
        foreach($sub_module as $sm){
            $check = FormBuilderModule::where('module','Lead')->where('submodule',$sm)->first();
            if(!$check){
                $new = new FormBuilderModule();
                $new->module = 'Lead';
                $new->submodule = $sm;
                $new->type = 'company';
                $new->save();
            }
        }

        $sub_module = [
            'Project'
        ];
        foreach($sub_module as $sm){
            $check = FormBuilderModule::where('module','Taskly')->where('submodule',$sm)->first();
            if(!$check){
                $new = new FormBuilderModule();
                $new->module = 'Taskly';
                $new->submodule = $sm;
                $new->type = 'company';
                $new->save();
            }
        }

        $sub_module = [
            'Machine'
        ];
        foreach($sub_module as $sm){
            $check = FormBuilderModule::where('module','MachineRepairManagement')->where('submodule',$sm)->first();
            if(!$check){
                $new = new FormBuilderModule();
                $new->module = 'MachineRepairManagement';
                $new->submodule = $sm;
                $new->type = 'company';
                $new->save();
            }
        }

        $sub_module = [
            'Location'
        ];
        foreach($sub_module as $sm){
            $check = FormBuilderModule::where('module','CMMS')->where('submodule',$sm)->first();
            if(!$check){
                $new = new FormBuilderModule();
                $new->module = 'CMMS';
                $new->submodule = $sm;
                $new->type = 'company';
                $new->save();
            }
        }

        $sub_module = [
            'Contact','Opportunities'
        ];
        foreach($sub_module as $sm){
            $check = FormBuilderModule::where('module','Sales')->where('submodule',$sm)->first();
            if(!$check){
                $new = new FormBuilderModule();
                $new->module = 'Sales';
                $new->submodule = $sm;
                $new->type = 'company';
                $new->save();
            }
        }

        $sub_module = [
            'Contract'
        ];
        foreach($sub_module as $sm){
            $check = FormBuilderModule::where('module','Contract')->where('submodule',$sm)->first();
            if(!$check){
                $new = new FormBuilderModule();
                $new->module = 'Contract';
                $new->submodule = $sm;
                $new->type = 'company';
                $new->save();
            }
        }

        $sub_module = [
            'Book','Article'
        ];
        foreach($sub_module as $sm){
            $check = FormBuilderModule::where('module','Internalknowledge')->where('submodule',$sm)->first();
            if(!$check){
                $new = new FormBuilderModule();
                $new->module = 'Internalknowledge';
                $new->submodule = $sm;
                $new->type = 'company';
                $new->save();
            }
        }

        $sub_module = [
            'Note'
        ];
        foreach($sub_module as $sm){
            $check = FormBuilderModule::where('module','Notes')->where('submodule',$sm)->first();
            if(!$check){
                $new = new FormBuilderModule();
                $new->module = 'Notes';
                $new->submodule = $sm;
                $new->type = 'company';
                $new->save();
            }
        }
    }
}
