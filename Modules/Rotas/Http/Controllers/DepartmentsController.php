<?php

namespace Modules\Rotas\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Rotas\Entities\Branch;
use Modules\Rotas\Entities\Department;
use Modules\Rotas\Entities\Designation;
use Modules\Hrm\Entities\Employee;

use Rawilk\Settings\Support\Context;


class DepartmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(Auth::user()->isAbleTo('rotadepartment manage'))
        {
            $departments = Department::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get();
            return view('rotas::department.index', compact('departments'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        return view('rotas::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if(Auth::user()->isAbleTo('rotadepartment create'))
        {
            $branch = Branch::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            return view('rotas::department.create', compact('branch'));
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
        if(Auth::user()->isAbleTo('rotadepartment create'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'branch_id' => 'required',
                                   'name' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $department             = new Department();
            $department->branch_id  = $request->branch_id;
            $department->name       = $request->name;
            $department->workspace  = getActiveWorkSpace();
            $department->created_by = \Auth::user()->id;
            $department->save();

            return redirect()->route('departments.index')->with('success', __('Department  successfully created.'));
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
    public function edit(Department $department )
    {
        if(Auth::user()->isAbleTo('rotadepartment edit'))
        {
            if($department->created_by == creatorId() &&  $department->workspace  == getActiveWorkSpace())
            {
                $branch = Branch::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');

                return view('rotas::department.edit', compact('department', 'branch'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
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
    public function update(Request $request, Department $department)
    {
        if(Auth::user()->isAbleTo('rotadepartment edit'))
        {
            if($department->created_by == creatorId() &&  $department->workspace  == getActiveWorkSpace())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'branch_id' => 'required',
                                       'name' => 'required|max:20',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                // update Designation branch id
                Designation::where('department_id',$department->id)->where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->update(['branch_id' => $request->branch_id]);

                $department->branch_id = $request->branch_id;
                $department->name      = $request->name;
                $department->save();

                return redirect()->route('departments.index')->with('success', __('Department successfully updated.'));
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
    public function destroy(Department $department)
    {
        if(Auth::user()->isAbleTo('rotadepartment delete'))
        {
            if($department->created_by == creatorId() &&  $department->workspace  == getActiveWorkSpace())
            {
                $employee     = Employee::where('department_id',$department->id)->where('workspace',getActiveWorkSpace())->get();
                if(count($employee) == 0)
                {
                    Designation::where('department_id',$department->id)->delete();
                    $department->delete();
                }
                else
                {
                    return redirect()->route('departments.index')->with('error', __('This department has employees. Please remove the employee from this department.'));
                }
                return redirect()->route('departments.index')->with('success', __('Department successfully deleted.'));
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

    public function DepartmentsNameEdit()
    {
        if(Auth::user()->isAbleTo('rotadepartment name edit'))
        {
            return view('rotas::department.departmentnameedit');
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
    public function saveDepartmentsName(Request $request)
    {
        if(Auth::user()->isAbleTo('rotadepartment name edit'))
        {
            $validator = \Validator::make($request->all(),
            [
                'hrm_department_name' => 'required',
            ]);

            if($validator->fails()){
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            else
            {
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
                \Settings::context($userContext)->set('hrm_department_name', $request->hrm_department_name);

                return redirect()->route('departments.index')->with('success', __('Department Name successfully updated.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
