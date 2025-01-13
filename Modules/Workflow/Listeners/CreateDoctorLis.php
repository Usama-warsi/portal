<?php

namespace Modules\Workflow\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Workflow\Entities\Workflow;
use Modules\Workflow\Entities\WorkflowModule;
use Modules\Workflow\Entities\WorkflowModuleField;
use Modules\Workflow\Entities\WorkflowUtility;
use Modules\HospitalManagement\Events\CreateDoctor;

class CreateDoctorLis
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
    public function handle(CreateDoctor $event)
    {
        if(module_is_active('Workflow'))
        {
            $doctor = $event->doctor;
            $request = $event->request;
            $workflow_module = WorkflowModule::where('submodule','New Doctor')->first();

            if($workflow_module)
            {
                $workflows = Workflow::where('event',$workflow_module->id)->where('workspace',$doctor->workspace)->where('created_by',$doctor->created_by)->get();
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

                                if($workflow_module_field->field_name == 'Specialization')
                                {
                                    $status = $symbolToOperator[$symbol]($request->specialization,$condition->value);
                                }
                                else if($workflow_module_field->field_name == 'Gender')
                                {
                                    $status = $symbolToOperator[$symbol](strtolower($request->gender),$condition->value);
                                }
                                else if($workflow_module_field->field_name == 'Years Of Experience')
                                {
                                    $status = $symbolToOperator[$symbol]($request->years_of_experience,$condition->value);
                                }
                                else if($workflow_module_field->field_name == 'Consultation Fee')
                                {
                                    $status = $symbolToOperator[$symbol]($request->consultation_fee,$condition->value);
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
                        WorkflowUtility::call_do_this($workflow->id,$doctor);
                    }
                }
            }

        }
    }
}
