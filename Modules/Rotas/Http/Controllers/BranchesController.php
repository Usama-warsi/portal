<?php

namespace Modules\Rotas\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Rotas\Entities\Branch;
use Modules\Rotas\Entities\Department;
use Modules\Rotas\Entities\Designation;
use Illuminate\Support\Facades\Auth;
use Modules\Rotas\Entities\Employee;
use Nette\Schema\Context;

// use Rawilk\Settings\Support\Context;



class BranchesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(Auth::user()->isAbleTo('rotabranch manage'))
        {
            $branches = Branch::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get();
            return view('rotas::branch.index',compact('branches'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if(Auth::user()->isAbleTo('rotabranch create'))
        {
            return view('rotas::branch.create');
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if(Auth::user()->isAbleTo('rotabranch create'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $branch             = new Branch();
            $branch->name       = $request->name;
            $branch->workspace  = getActiveWorkSpace();
            $branch->created_by = Auth::user()->id;
            $branch->save();

            return redirect()->route('branches.index')->with('success', __('Branch successfully created.'));
        }
        else
        {
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
        return view('rotas::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Branch $branch)
    {
        if(Auth::user()->isAbleTo('rotabranch edit'))
        {
            if($branch->created_by == creatorId() &&  $branch->workspace  == getActiveWorkSpace())
            {
                return view('rotas::branch.edit', compact('branch'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Branch $branch)
    {
        if(Auth::user()->isAbleTo('rotabranch edit'))
        {
            if($branch->created_by == creatorId() &&  $branch->workspace  == getActiveWorkSpace())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $branch->name = $request->name;
                $branch->save();
                return redirect()->route('branches.index')->with('success', __('Branch successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Branch $branch)
    {
        if(Auth::user()->isAbleTo('rotabranch delete'))
        {
            if($branch->created_by == creatorId() &&  $branch->workspace  == getActiveWorkSpace())
            {
                $employee     = Employee::where('branch_id',$branch->id)->where('workspace',getActiveWorkSpace())->get();
                if(count($employee) == 0)
                {
                    Department::where('branch_id',$branch->id)->delete();
                    Designation::where('branch_id',$branch->id)->delete();
                    $branch->delete();
                }
                else
                {
                    return redirect()->route('branches.index')->with('error', __('This branch has employees. Please remove the employee from this branch.'));
                }

                return redirect()->route('branches.index')->with('success', __('Branch successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function BranchesNameEdit()
    {
        if(Auth::user()->isAbleTo('rotabranch name edit'))
        {
            return view('rotas::branch.branchnameedit');
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
    public function saveBranchesName(Request $request)
    {
        if(Auth::user()->isAbleTo('rotabranch name edit'))
        {
            $validator = \Validator::make($request->all(),
            [
                'hrm_branch_name' => 'required',
            ]);

            if($validator->fails()){
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            else
            {
                $userContext = new Context(['user_id' => creatorId(),'workspace_id'=>getActiveWorkSpace()]);
                \Settings::context($userContext)->set('hrm_branch_name', $request->hrm_branch_name);
                $post = $request->all();
                unset($post['_token']);

                foreach ($post as $key => $value) {
                    // Define the data to be updated or inserted
                    $data = [
                        'key' => $key,
                        'workspace' => getActiveWorkSpace(),
                        'created_by' => creatorId(),
                    ];
                    // Check if the record exists, and update or insert accordingly
                    Setting::updateOrInsert($data, ['value' => $value]);
                }
                // Settings Cache forget
                comapnySettingCacheForget();
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
