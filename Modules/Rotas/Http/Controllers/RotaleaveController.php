<?php

namespace Modules\Rotas\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Modules\Rotas\Entities\Employee;
use Modules\Rotas\Entities\Leave;
use Modules\Rotas\Entities\LeaveType;
use Modules\Rotas\Events\RotaleaveStatus;

class RotaleaveController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function index()
    {
        if(Auth::user()->isAbleTo('rotaleave manage'))
        {
            // if(!in_array(Auth::user()->type, Auth::user()->not_emp_type))
            // {
            //     $leaves   = Leave::where('user_id', '=', Auth::user()->id)->where('workspace',getActiveWorkSpace())->orderBy('id', 'desc')->get();
            // }
            // else
            // {
            //     $leaves = Leave::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->orderBy('id', 'desc')->get();
            // }
            if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                $leaves = Leave::join('users', 'leaves.user_id', '=', 'users.id')
                    ->join('leave_types', 'leaves.leave_type_id', '=', 'leave_types.id')
                    ->where('leaves.user_id', '=', Auth::user()->id)
                    ->where('leaves.workspace', getActiveWorkSpace())
                    ->orderBy('leaves.id', 'desc')
                    ->get();
            } else {
                $leaves = Leave::join('users', 'leaves.user_id', '=', 'users.id')
                    ->join('leave_types', 'leaves.leave_type_id', '=', 'leave_types.id')
                    ->where('leaves.created_by', '=', creatorId())
                    ->where('leaves.workspace', getActiveWorkSpace())
                    ->orderBy('leaves.id', 'desc')
                    ->get();
            }
            return view('rotas::leave.index', compact('leaves'));
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
        if(Auth::user()->isAbleTo('rotaleave create'))
        {
            if(!in_array(Auth::user()->type, Auth::user()->not_emp_type))
            {
                 $employees = Employee::where('user_id', '=',Auth::user()->id)->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            }
            else
            {
                $employees = Employee::where('workspace',getActiveWorkSpace())->where('created_by', '=', creatorId())->get()->pluck('name', 'id');
            }
            $leavetypes      = LeaveType::where('workspace',getActiveWorkSpace())->where('created_by', '=', creatorId())->get();

            return view('rotas::leave.create', compact('employees', 'leavetypes'));
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
        if(Auth::user()->isAbleTo('rotaleave create'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'leave_type_id' => 'required',
                                   'start_date' => 'required|after:yesterday',
                                   'end_date' => 'required',
                                   'leave_reason' => 'required',
                                   'remark' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $leave_type = LeaveType::find($request->leave_type_id);
            $startDate = new \DateTime($request->start_date);
            $endDate = new \DateTime($request->end_date);
            $endDate->add(new \DateInterval('P1D'));

            $total_leave_days = !empty($startDate->diff($endDate)) ? $startDate->diff($endDate)->days : 0;
            if($leave_type->days >= $total_leave_days)
            {
                $leave    = new Leave();
                if (in_array(Auth::user()->type, Auth::user()->not_emp_type))
                {
                    $employee = Employee::where('id', '=', $request->employee_id)->first();
                    $leave->employee_id = $request->employee_id;
                    $leave->user_id = $employee->user_id;
                }
                else
                {
                    $employee = Employee::where('user_id', '=', Auth::user()->id)->first();
                    if(!empty($employee))
                    {
                        $leave->user_id = Auth::user()->id;
                        $leave->employee_id = $employee->id;
                    }
                    else
                    {
                        return redirect()->back()->with('error', __('Apologies, the employee data is currently unavailable. Please provide the necessary employee details.'));
                    }
                }

                $leave->leave_type_id    = $request->leave_type_id;
                $leave->applied_on       = date('Y-m-d');
                $leave->start_date       = $request->start_date;
                $leave->end_date         = $request->end_date;
                $leave->total_leave_days = $total_leave_days;
                $leave->leave_reason     = $request->leave_reason;
                $leave->remark           = $request->remark;
                $leave->status           = 'Pending';
                $leave->workspace        = getActiveWorkSpace();
                $leave->created_by       = Auth::user()->id;
                $leave->save();

                return redirect()->route('rota-leave.index')->with('success', __('Leave  successfully created.'));
            }
            else{
                return redirect()->back()->with('error', __('Leave type '.$leave_type->name.' is provide maximum '.$leave_type->days."  days please make sure your selected days is under ". $leave_type->days.' days.'));
            }
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
        if(Auth::user()->isAbleTo('rotaleave edit'))
        {
            $leave = Leave::find($id);
            if($leave->created_by == creatorId() &&  $leave->workspace  == getActiveWorkSpace())
            {
                $employees = Employee::where('workspace',getActiveWorkSpace())->where('created_by', '=', creatorId())->get()->pluck('name', 'id');
                $leavetypes      = LeaveType::where('workspace',getActiveWorkSpace())->where('created_by', '=', creatorId())->get();

                return view('rotas::leave.edit', compact('leave', 'employees', 'leavetypes'));
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
    public function update(Request $request,$id)
    {
        if(Auth::user()->isAbleTo('rotaleave edit'))
        {
            $leave = Leave::find($id);
            if($leave->created_by == creatorId() &&  $leave->workspace  == getActiveWorkSpace())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'leave_type_id' => 'required',
                                       'start_date' => 'required|date',
                                       'end_date' => 'required',
                                       'leave_reason' => 'required',
                                       'remark' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                $leave_type = LeaveType::find($request->leave_type_id);
                $startDate = new \DateTime($request->start_date);
                $endDate = new \DateTime($request->end_date);
                $endDate->add(new \DateInterval('P1D'));

                $total_leave_days = !empty($startDate->diff($endDate)) ? $startDate->diff($endDate)->days : 0;
                if($leave_type->days >= $total_leave_days)
                {
                    if (in_array(Auth::user()->type, Auth::user()->not_emp_type))
                    {
                        $employee = Employee::where('id', '=', $request->employee_id)->first();
                        $leave->employee_id = $request->employee_id;
                        $leave->user_id = $employee->user_id;
                    }
                    else
                    {
                        $employee = Employee::where('user_id', '=', creatorId())->first();
                        $leave->user_id = \Auth::user()->id;
                        $leave->employee_id = $employee->id;
                    }
                    if(!empty($request->status))
                    {
                        $leave->status    = $request->status;
                    }
                    $leave->start_date       = $request->start_date;
                    $leave->end_date         = $request->end_date;
                    $leave->total_leave_days = $total_leave_days;
                    $leave->leave_reason     = $request->leave_reason;
                    $leave->remark           = $request->remark;
                    $leave->save();

                    return redirect()->route('rota-leave.index')->with('success', __('Leave successfully updated.'));
                }
                else{
                    return redirect()->back()->with('error', __('Leave type '.$leave_type->name.' is provide maximum '.$leave_type->days."  days please make sure your selected days is under ". $leave_type->days.' days.'));
                }
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
    public function destroy($id)
    {
        if(Auth::user()->isAbleTo('rotaleave delete'))
        {
            $leave = Leave::find($id);
            if($leave->created_by == creatorId() &&  $leave->workspace  == getActiveWorkSpace() && $leave->status == 'Pending')
            {
                $leave->delete();

                return redirect()->route('rota-leave.index')->with('success', __('Leave successfully deleted.'));
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


    public function jsoncount(Request $request)
    {
        $leave_counts = LeaveType::select(\DB::raw('COALESCE(SUM(leaves.total_leave_days),0) AS total_leave, leave_types.title, leave_types.days,leave_types.id'))->leftjoin(
            'leaves',
            function ($join) use ($request)
            {
                $join->on('leaves.leave_type_id', '=', 'leave_types.id');
                $join->where('leaves.user_id', '=', $request->employee_id);
                $join->where('leaves.status', '=', 'Approved');
            }
            )->where('leave_types.created_by', '=', creatorId())->groupBy('leave_types.id')->get();

        return $leave_counts;

    }
    public function action($id)
    {
        if(Auth::user()->isAbleTo('rotaleave approver manage'))
        {
            $leave     = Leave::find($id);
            $employee  = User::find($leave->user_id);
            $leavetype = LeaveType::find($leave->leave_type_id);
            return view('rotas::leave.action', compact('employee', 'leavetype', 'leave'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }

    }
    public function changeaction(Request $request)
    {
        if(Auth::user()->isAbleTo('rotaleave approver manage'))
        {
            $leave = Leave::find($request->leave_id);
            $leave->status = $request->status;
            $company_settings = getCompanyAllSetting();

            if($leave->status == 'Approved')
            {
                $startDate               = new \DateTime($leave->start_date);
                $endDate                 = new \DateTime($leave->end_date);
                $total_leave_days        = $startDate->diff($endDate)->days;
                $leave->total_leave_days = $total_leave_days;
            }
            $leave->save();
            event(new RotaleaveStatus($leave));

            if(isset($company_settings['Leave Status']) && $company_settings['Leave Status']  == true)
            {
            $User     = User::where('id', $leave->user_id)->where('workspace_id', '=',  getActiveWorkSpace())->first();
            $uArr = [
                'leave_email'=>$User->email,
                'leave_status_name'=>$User->name,
                'leave_status'=> $request->status,
                'leave_reason'=>$leave->leave_reason,
                'leave_start_date'=>$leave->start_date,
                'leave_end_date'=>$leave->end_date,
                'total_leave_days'=>$leave->total_leave_days,
            ];
            try{

                $resp = EmailTemplate::sendEmailTemplate('Leave Status', [$User->email], $uArr);
            }
            catch(\Exception $e)
            {
                $resp['error'] = $e->getMessage();
            }
            return redirect()->route('rota-leave.index')->with('success', __('Leave status successfully updated.'). ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
         }
         return redirect()->back()->with('success', __('Leave status successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
