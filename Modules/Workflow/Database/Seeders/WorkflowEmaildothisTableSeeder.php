<?php

namespace Modules\Workflow\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class WorkflowEmaildothisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $check = \Modules\Workflow\Entities\Workflowdothis::where('submodule','Send Email Notification')->first();
        if(!$check){
            $new = new \Modules\Workflow\Entities\Workflowdothis();
            $new->submodule = 'Send Email Notification';
            $new->module = 'Email';
            $new->type = 'company';
            $new->save();
        }
        // $this->call("OthersTableSeeder");
    }
}
