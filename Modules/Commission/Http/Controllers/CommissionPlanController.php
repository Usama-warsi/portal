<?php

namespace Modules\Commission\Http\Controllers;

use App\Models\Invoice;
use App\Models\User;
use App\Models\Role;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Commission\Entities\CommissionModule;
use Modules\Commission\Entities\CommissionPlan;
use Modules\Commission\Events\CreateCommissionPlan;
use Modules\Commission\Events\DestroyCommissionPlan;
use Modules\Commission\Events\UpdateCommissionPlan;
use Modules\Taskly\Entities\Project;
use Modules\Taskly\Entities\Task;

class CommissionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $commissionPlans = CommissionPlan::where('created_by', '=', creatorId())->where('workspace', '=', getActiveWorkSpace())->get();

        return view('commission::commissionPlan.index', compact('commissionPlans'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (\Auth::user()->isAbleTo('commission plan create')) {

            $roles = Role::where('created_by', \Auth::user()->id)->whereNotIn('name', ['company', 'super admin'])->pluck('name', 'id');
            $commissionPlan = [];

            foreach ($roles as $role) {
                $user = \App\Models\User::where('type', $role)->where('created_by', '=', creatorId())->where('workspace_id', '=', getActiveWorkSpace())->pluck('name', 'id')->toArray();
                $commissionPlan[$role] = $user;
            }
            return view('commission::commissionPlan.create', compact('commissionPlan'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (\Auth::user()->isAbleTo('commission plan create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name'    => 'required',
                    'date'    => 'required',
                    'user_id' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('commission-plan.index')->with('error', $messages->first());
            }

            $date = explode('to', $request->date);
            $commissionPlan             = new CommissionPlan();
            $commissionPlan->name       = $request->name;
            $commissionPlan->start_date = isset($date[0]) ? $date[0] : '';
            $commissionPlan->end_date   = isset($date[1]) ? $date[1] : '';
            $commissionPlan->user_id    = implode(',', $request->user_id);
            $commissionPlan->created_by = creatorId();
            $commissionPlan->workspace  = getActiveWorkSpace();
            $commissionPlan->save();
            event(new CreateCommissionPlan($request, $commissionPlan));

            return redirect()->route('commission-plan.edit', $commissionPlan)->with('success', __('Commission successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return redirect()->back()->with('error', __('Permission denied.'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */

    public function edit($id)
    {
        if (\Auth::user()->isAbleTo('commission plan edit')) {
            $commissionPlanId = CommissionPlan::find($id);
            if ($commissionPlanId) {
                $roles = Role::where('created_by', \Auth::user()->id)->whereNotIn('name', ['company', 'super admin'])->pluck('name', 'id');
                $commissionPlan = [];

                foreach ($roles as $role) {
                    $user = \App\Models\User::where('type', $role)->where('created_by', '=', creatorId())->where('workspace_id', '=', getActiveWorkSpace())->pluck('name', 'id')->toArray();
                    $commissionPlan[$role] = $user;
                }
                $modules = CommissionModule::select('module', 'submodule')->get();
                $commissionModule = [];
                foreach ($modules as $module) {
                    $sub_modules = CommissionModule::select('id', 'module', 'submodule')->where('module', $module->module)->get();
                    $temp = [];
                    foreach ($sub_modules as $sub_module) {
                        $temp[$sub_module->id] = $sub_module->submodule;
                    }
                    $commissionModule[Module_Alias_Name($module->module)] = $temp;
                }
                $date = $commissionPlanId->start_date . ' to ' . $commissionPlanId->end_date;
                $getCommStr = json_decode($commissionPlanId->commission_str, true);
                return view('commission::commissionPlan.edit', compact('commissionPlan', 'commissionPlanId', 'commissionModule', 'date', 'getCommStr'));
            } else {
                return redirect()->route('commission-plan.index')->with('error', __('CommissionPlan not found.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if (\Auth::user()->isAbleTo('commission plan edit')) {


            $commissionPlanId = commissionPlan::find($id);
            $checklist_items = $request->checklist_items;
            foreach ($checklist_items as $key => $checklist_item) {
                if ($request->conditional == 'ladder_of_invoice') {
                    $checklist_items[$key]['commission_total'] = null;
                } else {
                    $checklist_items[$key]['from_amount'] = null;
                    $checklist_items[$key]['to_amount'] = null;
                    $checklist_items[$key]['commission'] = null;

                }
            }

            $jsonData = [
                'commissionstr'   => $request->commissionstr,
                'conditional'     => $request->conditional,
                'checklist_items' => $checklist_items,
                'project_type'    => $request->project_type,
                'project_id'      => $request->project_id,
                'task_id'         => $request->task_id,
            ];
            $data = json_encode($jsonData);

            $date = explode('to', $request->date);

            $commissionPlanId->commission_str  = $data;
            $commissionPlanId->start_date      = isset($date[0]) ? $date[0] : '';
            $commissionPlanId->end_date        = isset($date[1]) ? $date[1] : '';
            $commissionPlanId->commission_module  = $request->commissionstr;
            $commissionPlanId->user_id         = implode(",", $request->user_id);
            $commissionPlanId->commission_type = $request->percentage_type;
            $commissionPlanId->created_by      = creatorId();
            $commissionPlanId->workspace       = getActiveWorkSpace();


            $commissionPlanId->update();
            $roles = Role::where('created_by', \Auth::user()->id)->whereNotIn('name', ['company', 'super admin'])->pluck('name', 'id');
            $commissionPlan = [];

            foreach ($roles as $role) {
                $user = \App\Models\User::where('type', $role)->pluck('name', 'id')->toArray();
                $commissionPlan[$role] = $user;
            }
            $modules = CommissionModule::select('module', 'submodule')->get();
            $commissionModule = [];
            foreach ($modules as $module) {
                $sub_modules = CommissionModule::select('id', 'module', 'submodule')->where('module', $module->module)->get();
                $temp = [];
                foreach ($sub_modules as $sub_module) {
                    $temp[$sub_module->id] = $sub_module->submodule;
                }
                $commissionModule[Module_Alias_Name($module->module)] = $temp;
            }

            $date = $commissionPlanId->start_date . ' to ' . $commissionPlanId->end_date;
            $getCommStr = json_decode($commissionPlanId->commission_str, true);

            event(new UpdateCommissionPlan($request, $commissionPlanId));

            return view('commission::commissionPlan.edit', compact('commissionPlan', 'commissionPlanId', 'commissionModule', 'date', 'getCommStr'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(CommissionPlan $commissionPlan)
    {
        if (\Auth::user()->isAbleTo('commission plan delete')) {
            event(new DestroyCommissionPlan($commissionPlan));
            $commissionPlan->delete();

            return redirect()->back()->with('success', __('CommissionPlan successfully deleted!'));
        }
    }

    public function attribute(Request $request)
    {
        $attributeIds = $request->attribute_id;

        $methods          = ['GET' => 'GET', 'POST' => 'POST'];
        $projects         = \Modules\Taskly\Entities\Project::where('workspace', getActiveWorkSpace())->where('created_by', '=', creatorId())->get()->pluck('name', 'id');
        $commissionStr    = CommissionModule::where('id', $attributeIds)->get()->pluck('submodule')->toArray();
        $commissionPlanId = commissionPlan::find($request->commissionPlanId);

        $commission_str   = json_decode($commissionPlanId->commission_str, true);
        if (isset($commission_str['commissionstr']) != $attributeIds) {
            $commission_str = [];
        }
        $returnHTML       =  view('commission::commissionPlan.append', compact('methods', 'commissionStr', 'commissionPlanId', 'commission_str', 'projects'))->render();

        $responseData = [
            'is_success' => true,
            'message' => '',
            'html' => $returnHTML,
        ];


        return response()->json($responseData);
    }

    public function gettask(Request $request)
    {
        $task = \Modules\Taskly\Entities\Task::where('project_id', $request->project_id)->where('workspace', '=', getActiveWorkSpace())->get()->pluck('title', 'id');
        return response()->json($task);
    }

    public function getagent(Request $request)
    {
        if (!empty($request->selectedPlan)) {
            $agent = CommissionPlan::where('id', $request->selectedPlan)->where('workspace', getActiveWorkSpace())->where('created_by', '=', creatorId())->pluck('user_id')->first();
            if ($agent) {
                $user = \App\Models\User::whereIn('id', explode(',', $agent))->where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->pluck('name', 'id');
            }
            return response()->json($user);
        }
    }
}
