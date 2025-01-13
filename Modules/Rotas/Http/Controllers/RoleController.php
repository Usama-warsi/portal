<?php

namespace Modules\Rotas\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Rotas\Entities\Role;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(Auth::user()->isAbleTo('role manage'))
        {
            $user = Auth::user();
            $created_by = $user->id;
            $roles = Role::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get();
            $employees = User::where('workspace_id',getActiveWorkSpace())->emp()->get()->pluck('name', 'id');

            return view('rotas::employeerole.index',compact('roles'));
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
        if(Auth::user()->isAbleTo('role create'))
        {
            $user = Auth::user();
            $created_by = $user->id;
            $employees = User::where('workspace_id',getActiveWorkSpace())->emp()->get()->pluck('name', 'id');
            return view('rotas::employeerole.create',compact('employees'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {


        $user = Auth::user();
        $created_by = $user->id;

        $set_role_id = implode(',',$request->employees);
        $role = new Role();
        $role['name']           = $request->input('name');
        $role['color']          = $request->input('color');
        $employees     = $set_role_id;
        $role->employees = $employees;
        $role['default_break']  = $request->input('default_break');
        $role->workspace        = getActiveWorkSpace();
        $role['created_by']     = $created_by;
        $role->save();
        $insert_id = $role->id;
        $employees = $request->employees;


        return redirect()->back()->with('success', __('Role Add Successfully'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return redirect()->route('rotas::employeerole.index');
        return view('rotas::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if(Auth::user()->isAbleTo('role edit'))
        {
            $role = Role::find($id);
            $user = Auth::user();
            $created_by = $user->id;
            $employees = User::where('workspace_id',getActiveWorkSpace())->emp()->get()->pluck('name', 'id');
            return view('rotas::employeerole.edit',compact('employees','role'));
        }
        else
        {
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
        $role = Role::find($id);
        $user = Auth::user();
        $created_by = $user->id;
        $set_role_id = implode(',',$request->employees);
        $employee = $request->employees;
        $role['name']           = $request->input('name');
        $role['color']          = $request->input('color');
        $employees     = $set_role_id;

        $role->employees = $employees;
        $role['default_break']  = $request->input('default_break');
        $role->created_by    = creatorId();
        $role->save();

        $role_id = $role->id;
        $new_employees = $request->employees;
        $old_employees = [];
        if(!empty($old_role_detail))
        {
            $old_employees = array_column($old_role_detail,'user_id');
        }


        $add_roles = [];
        $remove_roles = [];
        if(!empty($new_employees)) {
            $add_roles = array_diff($new_employees,$old_employees);
            $remove_roles = array_diff($old_employees,$new_employees);
        } else {
            $remove_roles = $old_employees;
        }
        return redirect()->back()->with('success', __('Role Update Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if(Auth::user()->isAbleTo('role delete'))
        {
            $role = Role::find($id);

            $role->delete();
            return redirect()->back()->with('success', __('Role Delete Succsefully'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        //
    }
}
