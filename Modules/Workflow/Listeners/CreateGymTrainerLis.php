<?php

namespace Modules\Workflow\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Workflow\Entities\Workflow;
use Modules\Workflow\Entities\WorkflowModule;
use Modules\Workflow\Entities\WorkflowModuleField;
use Modules\Workflow\Entities\WorkflowUtility;
use Modules\GymManagement\Events\CreateGymTrainer;

class CreateGymTrainerLis
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CreateGymTrainer $event)
    {
        if(module_is_active('Workflow'))
        {
            $gymtrainer = $event->gymtrainer;
            $request = $event->request;
            $workflow_module = WorkflowModule::where('submodule','New Trainer')->first();

            if($workflow_module)
            {
                $workflows = Workflow::where('event',$workflow_module->id)->where('workspace',$gymtrainer->workspace)->where('created_by',$gymtrainer->created_by)->get();
                $condition_symbol = Workflow::$condition_symbol;

                $symbolToOperator = [
                    '>' => function ($a, $b) { return $a > $b; },
                    '<' => function ($a, $b) { return $a < $b; },
                    '=' => function ($a, $b) { return $a == $b; },
                    '!=' => function ($a, $b) { return $a != $b; },
                ];
                foreach ($workflows as $key => $workflow)
                {
                    $conditions = !empty($workflow->json_data) ? json_decode($workflow->json_data) : [];
                    $status = false;

                    foreach ($conditions as $key => $condition)
                    {
                        if($condition->value)
                        {
                            $workflow_module_field = WorkflowModuleField::find($condition->preview_type);

                            if(!empty($workflow_module_field))
                            {
                                $symbol = array_key_exists($condition->condition,$condition_symbol) ? $condition_symbol[$condition->condition] : '=';

                                if($workflow_module_field->field_name == 'Skill')
                                {
                                    $Skills = $request->skill_id;
                                    foreach($Skills as $Skill)
                                    {
                                        $status = $symbolToOperator[$symbol]($Skill, $condition->value);
                                    }
                                }
                                else if($workflow_module_field->field_name == 'Member')
                                {
                                    $members = $request->member_id;
                                    foreach($members as $member)
                                    {
                                        $status = $symbolToOperator[$symbol]($member, $condition->value);
                                    }
                                }
                                else if($workflow_module_field->field_name == 'Country')
                                {
                                    $status = $symbolToOperator[$symbol]($request->country,$condition->value);
                                }
                                else if($workflow_module_field->field_name == 'City')
                                {
                                    $status = $symbolToOperator[$symbol]($request->city,$condition->value);
                                }
                                else if($workflow_module_field->field_name == 'State')
                                {
                                    $status = $symbolToOperator[$symbol]($request->state,$condition->value);
                                }
                                else{
                                    break;
                                }
                            }
                        }
                        
                        if($status == false)
                        {
                            break;
                        }
                    }

                    if($status == true)
                    {

                        WorkflowUtility::call_do_this($workflow->id,$gymtrainer);
                    }
                }
            }

        }
    }
}
