<?php

namespace Modules\Rotas\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Rotas\Entities\Availability;
use Modules\Rotas\Entities\Employee;
use Modules\Rotas\Events\CreateAvailability;
use Modules\Rotas\Events\DestroyAvailability;
use Modules\Rotas\Events\UpdateAvailability;

class AvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(Auth::user()->isAbleTo('availability manage'))
        {
            $user = Auth::user();
            $userId = Auth::id();
            $availabilitys = Availability::where('user_id',$userId)->get();
            if(Auth::user()->type=='company') {
                $availabilitys = Availability::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get();
            }

            $employees = Employee::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name','id');
            $filter_employees = [];
            $filter_employees['all0'] = __('Select all');
            if(!empty($employees))
            {
                foreach($employees as $employee)
                {
                    $employee;

                }
            }
            return view('rotas::availability.index',compact('availabilitys','filter_employees'));
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
        if(Auth::user()->isAbleTo('availability create'))
        {
            $user = Auth::user();
            $employee = [];
            $employees = Employee::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();
            $filter_employees = [];
            if(!empty($employees))
            {
                foreach($employees as $employee)
                {
                    $filter_employees[$employee['id']] = $employee['name'];
                }
            }
            return view('rotas::availability.create', compact('employee','filter_employees'));
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
        if (Auth::user()->isAbleTo('availability create'))
        {
            $availability_json = [];
            $availability_json2 = [];
            $availability_json3 = [];
            $availability_json_impload = '';
            if(!empty($request->timetable) && is_array($request->timetable)) {
                foreach($request->timetable as $key => $timetable) {
                    $availability_json2 = [];
                    $availability_json[$key]['day'] = $key;
                    $availability_json2['day'] = $key;
                    if(!empty($timetable) && is_array($timetable)) {
                        foreach($timetable as $key2 => $timetable_time) {
                            $start = explode(' - ',$timetable_time['time'])[0];
                            $end = explode(' - ',$timetable_time['time'])[1];
                            $backgroundColor =  ($timetable_time['availability'] == 'availability') ? 'rgba(0, 200, 0, 0.5)' : 'rgba(200, 0, 0, 0.5)';
                            $availability_json[$key]['periods'][] = array('start' => $start,'end' => $end, 'backgroundColor' => $backgroundColor);
                            $availability_json2['periods'][] = array('start' => $start,'end' => $end, 'backgroundColor' => $backgroundColor);
                        }
                    }
                    $availability_json3[] = json_encode($availability_json2);
                }
            }
            $user = Employee::where('id',$request->employee_id)->first();

            $availability_json_impload = '['. implode(',',$availability_json3) .']';

            $availability = new Availability();
            $availability->user_id =$user->user_id;
            $availability->employee_id = $request->employee_id;
            $availability->name = $request->name;
            $availability->start_date = $request->start_date;
            $availability->end_date = (!empty($request->end_date)) ? $request->end_date : NULL ;
            $availability->repeat_week = (!empty($request->end_date)) ? 0 : $request->repeat_week ;
            $availability->availability_json = $availability_json_impload;
            $availability->workspace          = getActiveWorkSpace();
            $availability->created_by = creatorId();
            $availability->save();

            event(new CreateAvailability($request,$availability));





        return redirect()->back()->with('success', __('Availability Successfully Created.'));
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
    public function edit($id)
    {
        if(Auth::user()->isAbleTo('availability edit'))
        {
            $availability = Availability::find($id);
            $employees = Employee::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();
            $filter_employees = [];
            if(!empty($employees))
            {
                foreach($employees as $employee)
                {
                    $filter_employees[$employee['id']] = $employee['name'];
                }
            }
            return view('rotas::availability.edit', compact('employee','filter_employees','availability'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

     public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('availability edit'))
        {
            $availability = Availability::find($id);
            $availability_json = [];
            $availability_json2 = [];
            $availability_json3 = [];
            $availability_json_impload = '';
            if(!empty($request->timetable) && is_array($request->timetable)) {
                foreach($request->timetable as $key => $timetable) {
                    $availability_json2 = [];
                    $availability_json[$key]['day'] = $key;
                    $availability_json2['day'] = $key;

                    if(!empty($timetable) && is_array($timetable)) {
                        foreach($timetable as $key2 => $timetable_time) {
                            $start = explode(' - ',$timetable_time['time'])[0];
                            $end = explode(' - ',$timetable_time['time'])[1];
                            $backgroundColor =  ($timetable_time['availability'] == 'availability') ? 'rgba(0, 200, 0, 0.5)' : 'rgba(200, 0, 0, 0.5)';
                            $availability_json[$key]['periods'][] = array('start' => $start,'end' => $end, 'backgroundColor' => $backgroundColor);
                            $availability_json2['periods'][] = array('start' => $start,'end' => $end, 'backgroundColor' => $backgroundColor);
                        }
                    }
                    $availability_json3[] = json_encode($availability_json2);
                }
            }
            $user = Employee::where('id',$request->employee_id)->first();

            $availability_json_impload = '['. implode(',',$availability_json3) .']';

            $availability->user_id =$user->user_id;
            $availability->employee_id = $request->employee_id;
            $availability->name = $request->name;
            $availability->start_date = $request->start_date;
            $availability->end_date = (!empty($request->end_date)) ? $request->end_date : NULL ;
            $availability->repeat_week = (!empty($request->end_date)) ? 0 : $request->repeat_week ;
            $availability->availability_json = $availability_json_impload;
            $availability->workspace          = getActiveWorkSpace();
            $availability->created_by = creatorId();
            $availability->save();

            event(new UpdateAvailability($request,$availability));

            return redirect()->back()->with('success', __('Your availability pattern has been added'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('availability delete'))
        {
            $availability = Availability::find($id);

            event(new DestroyAvailability($availability));


            $availability->delete();

            return redirect()->back()->with('success', __('Availability Delete Succsefully'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

}
