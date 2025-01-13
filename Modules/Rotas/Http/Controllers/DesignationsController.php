<?php

namespace Modules\Rotas\Http\Controllers;

use App\Models\Setting;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rawilk\Settings\Support\Context;
use Illuminate\Support\Facades\Auth;
use Modules\Rotas\Entities\Branch;
use Modules\Rotas\Entities\Department;
use Modules\Rotas\Entities\Designation;



class DesignationsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(Auth::user()->isAbleTo('rotadesignation manage'))
        {
            $designations = Designation::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get();

            return view('rotas::designation.index', compact('designations'));
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
        if(\Auth::user()->isAbleTo('rotadesignation create'))
        {
            $departments = Department::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');

            return view('rotas::designation.create', compact('departments'));
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
        if(\Auth::user()->isAbleTo('rotadesignation create'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'department_id' => 'required',
                                   'name' => 'required|max:20',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            try
            {
                $branch = Department::where('id',$request->department_id)->where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->first()->branch->id;
            }
            catch(\Exception $e)
            {
                $branch = null;
            }
            $designation                = new Designation();
            $designation->branch_id     = $branch;
            $designation->department_id = $request->department_id;
            $designation->name          = $request->name;
            $designation->workspace  = getActiveWorkSpace();
            $designation->created_by    = Auth::user()->id;
            $designation->save();

            return redirect()->route('designations.index')->with('success', __('Designation successfully created.'));
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
    public function edit(Designation $designation)
    {
        if(\Auth::user()->isAbleTo('designation edit'))
        {
            if($designation->created_by == creatorId() &&  $designation->workspace  == getActiveWorkSpace())
            {
                $departments = Designation::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
                return view('rotas::designation.edit', compact('designation', 'departments'));
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
        return view('rotas::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Designation $designation)
    {
        if(\Auth::user()->isAbleTo('designation edit'))
        {
            if($designation->created_by == creatorId() &&  $designation->workspace  == getActiveWorkSpace())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'department_id' => 'required',
                                       'name' => 'required|max:20',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                try
                {
                    $branch = Department::where('id',$request->department_id)->where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->first()->branch->id;
                }
                catch(Exception $e)
                {
                    $branch = null;
                }
                $designation->branch_id     = $branch;
                $designation->department_id = $request->department_id;
                $designation->name          = $request->name;
                $designation->save();

                return redirect()->route('designations.index')->with('success', __('Designation  successfully updated.'));
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
    public function destroy(Designation $designation)
    {
        if(\Auth::user()->isAbleTo('designation delete'))
        {
            if($designation->created_by == creatorId() &&  $designation->workspace  == getActiveWorkSpace())
            {
                $employee     = Employee::where('designation_id',$designation->id)->where('workspace',getActiveWorkSpace())->get();
                if(count($employee) == 0)
                {
                    $designation->delete();
                }
                else
                {
                    return redirect()->route('designations.index')->with('error', __('This designation has employees. Please remove the employee from this designation.'));
                }
                return redirect()->route('designations.index')->with('success', __('Designation successfully deleted.'));
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

    public function DesignationsNameEdit()
    {
        if(Auth::user()->isAbleTo('rotadesignation name edit'))
        {
            return view('rotas::designation.designationnameedit');
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
    public function saveDesignationsName(Request $request)
    {
        if(Auth::user()->isAbleTo('rotadesignation name edit'))
        {
            $validator = \Validator::make($request->all(),
            [
                'hrm_designation_name' => 'required',
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

                return redirect()->route('designations.index')->with('success', __('Designation Name successfully updated.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
