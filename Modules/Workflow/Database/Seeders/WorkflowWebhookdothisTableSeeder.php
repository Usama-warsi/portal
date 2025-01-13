<?php

namespace Modules\Workflow\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class WorkflowWebhookdothisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $check = \Modules\Workflow\Entities\Workflowdothis::where('submodule','Send Webhook URL')->first();
        if(!$check){
            $new = new \Modules\Workflow\Entities\Workflowdothis();
            $new->submodule = 'Send Webhook URL';
            $new->module = 'Webhook';
            $new->type = 'company';
            $new->save();
        }

        // $this->call("OthersTableSeeder");
    }
}
