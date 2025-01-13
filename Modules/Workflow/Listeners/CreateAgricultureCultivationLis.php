<?php

namespace Modules\Workflow\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Workflow\Entities\Workflow;
use Modules\Workflow\Entities\WorkflowModule;
use Modules\Workflow\Entities\WorkflowModuleField;
use Modules\Workflow\Entities\WorkflowUtility;
use Modules\AgricultureManagement\Events\CreateAgricultureCultivation;

class CreateAgricultureCultivationLis
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
    public function handle(CreateAgricultureCultivation $event)
    {
        if(module_is_active('Workflow'))
        {
            $agriculturecultivation = $event->agriculturecultivation;
            $request = $event->request;
            $workflow_module = WorkflowModule::where('submodule','New Agriculture Cultivation')->first();

            if($workflow_module)
            {
                $workflows = Workflow::where('event',$workflow_module->id)->where('workspace',$agriculturecultivation->workspace)->where('created_by',$agriculturecultivation->created_by)->get();
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

                                if($workflow_module_field->field_name == 'Farmer')
                                {
                                    $status = $symbolToOperator[$symbol]($request->farmer,$condition->value);
                                }
                                else if($workflow_module_field->field_name == 'Agriculture Cycle')
                                {
                                    $status = $symbolToOperator[$symbol]($request->agriculture_cycle,$condition->value);
                                }
                                else if($workflow_module_field->field_name == 'Department')
                                {
                                    $status = $symbolToOperator[$symbol]($request->department,$condition->value);
                                }
                                else if($workflow_module_field->field_name == 'Area')
                                {
                                    $status = $symbolToOperator[$symbol]($request->area,$condition->value);
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

                        WorkflowUtility::call_do_this($workflow->id,$agriculturecultivation);
                    }
                }
            }

        }
    }
}
