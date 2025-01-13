<?php

namespace Modules\Workflow\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Fleet\Events\CreateFuel;
use Modules\Workflow\Entities\Workflow;
use Modules\Workflow\Entities\WorkflowModule;
use Modules\Workflow\Entities\WorkflowModuleField;
use Modules\Workflow\Entities\WorkflowUtility;

class CreateFuelLis
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
    public function handle(CreateFuel $event)
    {
        if(module_is_active('Workflow'))
        {
            $fuels = $event->fuel;
            $request = $event->request;
            $workflow_module = WorkflowModule::where('submodule','New Fuel History')->first();

            if($workflow_module)
            {
                $workflows = Workflow::where('event',$workflow_module->id)->where('workspace',$fuels->workspace)->where('created_by',$fuels->created_by)->get();
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

                                if($workflow_module_field->field_name == 'Driver Name')
                                {
                                    $status = $symbolToOperator[$symbol]($request->driver_name,$condition->value);
                                }
                                else if($workflow_module_field->field_name == 'Vehicle name')
                                {
                                    $status = $symbolToOperator[$symbol]($request->vehicle_name,$condition->value);
                                }
                                else if($workflow_module_field->field_name == 'Fuel Type')
                                {
                                    $status = $symbolToOperator[$symbol]($request->fuel_type,$condition->value);
                                }

                                else if($workflow_module_field->field_name == 'Total price')
                                {
                                    $status = $symbolToOperator[$symbol]($request->total_cost,$condition->value);
                                }

                                else if($workflow_module_field->field_name == 'Gallons/Liters of Fuel')
                                {
                                    $status = $symbolToOperator[$symbol]($request->quantity,$condition->value);
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

                        WorkflowUtility::call_do_this($workflow->id,$fuels);
                    }
                }
            }

        }
    }
}
