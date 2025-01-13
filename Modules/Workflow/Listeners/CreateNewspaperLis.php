<?php

namespace Modules\Workflow\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Workflow\Entities\Workflow;
use Modules\Workflow\Entities\WorkflowModule;
use Modules\Workflow\Entities\WorkflowModuleField;
use Modules\Workflow\Entities\WorkflowUtility;
use Modules\Newspaper\Events\CreateNewspaper;

class CreateNewspaperLis
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
    public function handle(CreateNewspaper $event)
    {
        if(module_is_active('Workflow'))
        {
            $newspaper = $event->newspaper;
            $request = $event->request;
            $workflow_module = WorkflowModule::where('submodule','New Newspaper')->first();

            if($workflow_module)
            {
                $workflows = Workflow::where('event',$workflow_module->id)->where('workspace',$newspaper->workspace)->where('created_by',$newspaper->created_by)->get();
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

                                if($workflow_module_field->field_name == 'Varient')
                                {
                                    $status = $symbolToOperator[$symbol]($request->varient,$condition->value);
                                }
                                else if($workflow_module_field->field_name == 'Tax')
                                {
                                    $status = $symbolToOperator[$symbol]($request->taxes,$condition->value);
                                }
                                else if($workflow_module_field->field_name == 'Quantity')
                                {
                                    $status = $symbolToOperator[$symbol]($request->quantity,$condition->value);
                                }
                                else if($workflow_module_field->field_name == 'Price')
                                {
                                    $status = $symbolToOperator[$symbol]($request->price,$condition->value);
                                }
                                else if($workflow_module_field->field_name == 'Seles Price')
                                {
                                    $status = $symbolToOperator[$symbol]($request->seles_price,$condition->value);
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

                        WorkflowUtility::call_do_this($workflow->id,$newspaper);
                    }
                }
            }

        }
    }
}
