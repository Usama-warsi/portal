<?php

namespace Modules\Workflow\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Workflow\Entities\Workflow;
use Modules\Workflow\Entities\WorkflowModule;
use Modules\Workflow\Entities\WorkflowModuleField;
use Modules\Workflow\Entities\WorkflowUtility;
use Modules\GymManagement\Events\CreateMeasurement;

class CreateMeasurementLis
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
    public function handle(CreateMeasurement $event)
    {
        if(module_is_active('Workflow'))
        {

            $measurement = $event->measurement;
            $request = $event->request;
            $workflow_module = WorkflowModule::where('submodule','New Measurement')->first();

            if($workflow_module)
            {
                $workflows = Workflow::where('event',$workflow_module->id)->where('workspace',$measurement->workspace)->where('created_by',$measurement->created_by)->get();
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

                                if($workflow_module_field->field_name == 'Gender')
                                {
                                    $status = $symbolToOperator[$symbol](strtolower($request->gender),$condition->value);
                                }
                                else if($workflow_module_field->field_name == 'Age')
                                {
                                    $status = $symbolToOperator[$symbol]($request->age,$condition->value);
                                }
                                else if($workflow_module_field->field_name == 'Weight')
                                {
                                    $status = $symbolToOperator[$symbol]($request->weight,$condition->value);
                                }
                                else if($workflow_module_field->field_name == 'Height')
                                {
                                    $status = $symbolToOperator[$symbol]($request->height,$condition->value);
                                }
                                else if($workflow_module_field->field_name == 'Waist')
                                {
                                    $status = $symbolToOperator[$symbol]($request->waist,$condition->value);
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

                        WorkflowUtility::call_do_this($workflow->id,$measurement);
                    }
                }
            }

        }
    }
}
