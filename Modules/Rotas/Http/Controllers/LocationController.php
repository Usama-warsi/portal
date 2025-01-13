<?php

namespace Modules\Rotas\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Rotas\Entities\Location;
use App\Models\User;

use Illuminate\Routing\Controller;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(Auth::user()->isAbleTo('location manage'))
        {
        $created_by = Auth::user()->id;
        $employees = User::where('workspace_id',getActiveWorkSpace())->emp()->get()->pluck('name', 'id');
        $locations = Location::where('created_by', creatorId())->get();


        return view('rotas::location.index',compact('locations','employees'));
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
        if(Auth::user()->isAbleTo('location create'))
        {
            $user = Auth::user();
        $created_by = $user->id;
        $employees   = User::where('workspace_id',getActiveWorkSpace())->where('created_by', '=', Auth::user()->id)->emp()->get()->pluck('name', 'id');
        return view('rotas::location.create',compact('employees'));
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
        $set_location_id = implode(',',$request->employees);
        $location = new Location();
        $location->name         = $request->input('name');
        $location->address      = $request->input('address');
        $employees     = $set_location_id;
        $location->employees = $employees;
        $location->workspace        = getActiveWorkSpace();
        $location->created_by   = $created_by;
        $location->save();

        $insert_id = $location->id;
        $employees = $request->employees;
        return redirect()->back()->with('success', __('Location Add Successfully'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('rotas::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if(Auth::user()->isAbleTo('location edit'))
        {
            $location = Location::find($id);
            $user = Auth::user();
            $created_by = $user->id;

        $employees = User::where('workspace_id',getActiveWorkSpace())->emp()->get()->pluck('name', 'id');
        return view('rotas::location.edit',compact('location','employees'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        // return view('rotas::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if(\Auth::user()->isAbleTo('location edit'))
        {
        $location = Location::find($id);
        // $old_location_detail = User::select('id','user_id','location_id')->whereRaw('FIND_IN_SET('.$location->id.',location_id)')->get()->toArray();
        $user = Auth::user();
        $created_by = $user->id;
        $set_location_id = implode(',',$request->employees);

        $employee = $request->employees;
        $location['name']         = $request->input('name');
        $location['address']      = $request->input('address');
        $employees     = $set_location_id;
        $location->employees = $employees;
        $location->save();


        $location_id = $location->id;
        $new_employees = $request->employees;
        $old_employees = [];
        if(!empty($old_location_detail))
        {
            $old_employees = array_column($old_location_detail,'user_id');
        }


        $add_locations = [];
        $remove_locations = [];
        if(!empty($new_employees)) {
            $add_locations = array_diff($new_employees,$old_employees);
            $remove_locations = array_diff($old_employees,$new_employees);
        } else {
            $remove_locations = $old_employees;
        }

        return redirect()->back()->with('success', __('Location Update Successfully'));


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
    public function destroy($id)
    {
        if(Auth::user()->isAbleTo('location delete'))
        {
            $location = Location::find($id);
            $location->delete();
            return redirect()->back()->with('success', __('Delete Succsefully'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
