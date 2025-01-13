<?php

namespace Modules\Workflow\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Workflow\Entities\Workflow;
use Modules\Workflow\Entities\WorkflowModule;
use Modules\Workflow\Entities\WorkflowModuleField;
use Modules\Workflow\Entities\WorkflowUtility;
use Modules\ChildcareManagement\Events\CreateInquiry;

class CreateInquiryLis
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
    public function handle(CreateInquiry $event)
    {
        if(module_is_active('Workflow'))
        {

            $inquiry = $event->inquiry;
            $request = $event->request;
            $workflow_module = WorkflowModule::where('submodule','New Inquiry')->first();

            if($workflow_module)
            {
                $workflows = Workflow::where('event',$workflow_module->id)->where('workspace',$inquiry->workspace)->where('created_by',$inquiry->created_by)->get();
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

                                if($workflow_module_field->field_name == 'Date Of Birth')
                                {
                                    $status = $symbolToOperator[$symbol]($request->child_dob,$condition->value);
                                }
                                else if($workflow_module_field->field_name == 'Inquiry Date')
                                {
                                    $status = $symbolToOperator[$symbol]($request->inquiry_date,$condition->value);
                                }
                                else if($workflow_module_field->field_name == 'Child Age')
                                {
                                    $status = $symbolToOperator[$symbol]($request->child_age,$condition->value);
                                }
                                else if($workflow_module_field->field_name == 'Gender')
                                {
                                    $status = $symbolToOperator[$symbol]($request->child_gender,$condition->value);
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

                        WorkflowUtility::call_do_this($workflow->id,$inquiry);
                    }
                }
            }

        }
    }
}
