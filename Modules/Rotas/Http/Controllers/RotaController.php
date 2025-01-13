<?php

namespace Modules\Rotas\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Modules\Rotas\Entities\Branch;
use Modules\Rotas\Entities\Department;
use Modules\Rotas\Entities\Designation;
use Modules\Rotas\Entities\Employee;
use Modules\Rotas\Entities\Rota;
use Modules\Rotas\Http\Mail\SendRotas;
use Illuminate\Support\Facades\Mail;
use Modules\Rotas\Events\AddDayoff;
use Modules\Rotas\Events\CreateRota;
use Modules\Rotas\Events\DestroyRota;
use Modules\Rotas\Events\SendRotasViaEmail;
use Modules\Rotas\Events\UpdateRota;
use Modules\Rotas\Events\UpdateWorkSchedule;
use Nette\Schema\Context;
use App\Models\Setting;

class RotaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $company_settings = getCompanyAllSetting();
        if (Auth::user()->isAbleTo('rota manage')) {

            $created_by = creatorId();
            if (Auth::user()->type == 'company') {
                $user = Employee::where('workspace', getActiveWorkSpace())->where('created_by', '=', creatorId())->first();

            } else {
                $user = Employee::where('user_id', Auth::user()->id)->where('workspace', getActiveWorkSpace())->first();
            }

            $branch = Branch::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $branch->prepend('All', '');
            $department = Department::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $department->prepend('All', '');

            $designation = Designation::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('id', 'name');


            $first_location = '';

            $employee_permission = [];

        if(!in_array(Auth::user()->type, Auth::user()->not_emp_type))
        {
                $emp_only_see_own_rota = (isset($company_settings['emp_only_see_own_rota']) && $company_settings['emp_only_see_own_rota'] == 1) ? $user->id : 0 ;
                $employee_permission['emp_only_see_own_rota'] = (isset($company_settings['emp_only_see_own_rota'])) ? $user : 0 ;

            $profile = Employee::whereRaw('user_id = '.$user->id.' ')->first();
            if(!empty($profile['designation_id']))
            {

                $designation_id = explode(',', $profile['designation_id']);

                $first_location = (!empty($designation_id[0])) ? $designation_id[0] : '';
            }
        }
         else
        {
            $emp_only_see_own_rota = 0;
        }

            $datetime1 = date_create(date('Y-m-d'));
            $datetime2 =  date_create(date('Y-m-d'));
            $interval = date_diff($datetime1, $datetime2);
            $temp_week = $interval->format("%r%a") / 7;

            $temp_week = (int) $temp_week;
            $date_formate = Rota::CompanyDateFormat('d M Y');
            $week = 0;
            $start_day = (isset($company_settings['company_week_start'])) ? $company_settings['company_week_start'] : 'monday';
            $week_date = Rota::getWeekArray($date_formate, $week, $start_day);
            $emp_setting = Auth::user()->employee_setting;
            $day_off = 'hide';
            $leave_display = 'hide';
            $availability_display = 'hide';
            if (!empty($emp_setting)) {
                $emp_setting = json_decode($emp_setting, true);
                $day_off = (!empty($emp_setting['day_off']) && $emp_setting['day_off'] == 'show') ? 'show' : 'hide';
                $leave_display = (!empty($emp_setting['leave_display']) && $emp_setting['leave_display'] == 'show') ? 'show' : 'hide';
                $availability_display = (!empty($emp_setting['availability_display']) && $emp_setting['availability_display'] == 'show') ? 'show' : 'hide';
            }
            $employees = Rota::getBranchWiseUser(0,0,0,$emp_only_see_own_rota);

            $week_date['week_start'] = date('Y-m-d', strtotime($week_date[0]));
            $week_date['week_end'] = date('Y-m-d', strtotime($week_date[6]));
            return view('rotas::rota.index', compact('week_date', 'employees', 'temp_week', 'branch', 'department', 'designation', 'first_location', 'created_by', 'day_off', 'leave_display', 'availability_display'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {

        if (Auth::user()->isAbleTo('rota create')) {

            $user_id = $request->id;
            $date = $request->date;
            $created_by = creatorId();

            return view('rotas::rota.create', compact('user_id', 'date'));
        } else {
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
        if (Auth::user()->isAbleTo('rota create')) {


            $start_time    = str_replace(':', '.', $request->start_time);
            $end_time      = str_replace(':', '.', $request->end_time);
            $employee = Employee::where('id',$request->user_id)->first();
            $time_override = Rota::WhereRaw('user_id =' . $request->user_id)->WhereRaw('rotas_date = "' . $request->rotas_date . '"')->WhereRaw(
                '(
                                        ( start_time = "' . $start_time . '" AND end_time = "' . $end_time . '" ) or
                                        ("' . $start_time . '" BETWEEN start_time and end_time or "' . $end_time . '" BETWEEN start_time and end_time ) or
                                        (start_time BETWEEN "' . $start_time . '" and "' . $end_time . '" or end_time BETWEEN "' . $start_time . '" and "' . $end_time . '" )
                                    )'
            )->get()->toArray();

            if (count($time_override) == 0) {
                $diff_in_minutes = 0;
                $to              = \Carbon\Carbon::createFromFormat('H:i', $request->start_time);
                $from            = \Carbon\Carbon::createFromFormat('H:i', $request->end_time);
                if ($from == $to) {
                    $diff_in_minutes = 1440;
                } elseif ($from > $to) {
                    $diff_in_minutes = $to->diffInMinutes($from);
                } elseif ($from < $to) {
                    $to         = $request->start_time;
                    $to_array   = explode(':', $to);
                    $to_minutes = 1440 - ($to_array[0] * 60 + $to_array[1]);

                    $from         = $request->end_time;
                    $from_array   = explode(':', $from);
                    $from_minutes = $from_array[0] * 60 + $from_array[1];

                    $diff_in_minutes = $to_minutes + $from_minutes;
                }

                $rotas                       = new Rota();
                $rotas['user_id']            = $request->user_id;
                $rotas['rotas_date']         = $request->rotas_date;
                $rotas['start_time']         = $request->start_time;
                $rotas['end_time']           = $request->end_time;
                $rotas['break_time']         = $request->break_time;
                $rotas['time_diff_in_minut'] = $diff_in_minutes;
                $rotas['designation_id']     = $employee->designation_id;
                $rotas['note']               = $request->note;
                $rotas['publish']            = 0;
                $rotas['workspace']           = getActiveWorkSpace();
                $rotas['create_by']          = creatorId();
                $rotas->save();


                event(new CreateRota($request,$rotas));

                $return['status'] = 'success';
                $return['msg']    = __('Shift successfully added');
                return response()->json($return);
            } else
            {
                $employee = User::where('id', $time_override[0]['user_id'])->first();
                $name     = 'user';
                if (!empty($employee)) {
                    $name = $employee->name;
                }
                $return['status'] = 'error';
                $return['msg']    = __('This Shift clashes ') . '' . $name . ' ' . date("g:i a", strtotime($time_override[0]['start_time'])) . ' - ' . date("g:i a", strtotime($time_override[0]['end_time'])) . '' . __(' shift');

                return response()->json($return);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return redirect()->route('rota.index');
        return view('rotas::show');
        // return view('rotas::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('rota edit')) {
            $rota = Rota::find($id);
            $user_id = $request->id;
            $date    = $request->date;

            $user           = \Auth::user();
            $branches       = Branch::where('created_by', '=', Auth::user()->id)->where('workspace', getActiveWorkSpace())->get()->first();
            $first_location = (!empty($branches->id)) ? $branches->id : 0;

            return view('rotas::rota.edit', compact('user_id', 'date', 'branches', 'rota'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
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
        if (Auth::user()->isAbleTo('rota edit')) {
            $user_id = $request->id;;
            $rota = Rota::find($id);
            $start_time    = str_replace(':', '.', $request->start_time);
            $end_time      = str_replace(':', '.', $request->end_time);
            $time_override = Rota::WhereRaw('id != ' . $rota->id . ' ')->WhereRaw('user_id =' . $request->user_id . ' ')->WhereRaw('rotas_date = "' . $request->rotas_date . '"')->WhereRaw(
                '(
                                        ( start_time = "' . $start_time . '" AND end_time = "' . $end_time . '" ) or
                                        (' . $start_time . ' BETWEEN start_time and end_time or ' . $end_time . ' BETWEEN start_time and end_time ) or
                                        (start_time BETWEEN ' . $start_time . ' and ' . $end_time . ' or end_time BETWEEN ' . $start_time . ' and ' . $end_time . ' )
                                    )'
            )->get()->toArray();

            if (count($time_override) == 0) {
                $diff_in_minutes = 0;
                $to              = \Carbon\Carbon::createFromFormat('H:i', $request->start_time);
                $from            = \Carbon\Carbon::createFromFormat('H:i', $request->end_time);
                if ($from == $to) {
                    $diff_in_minutes = 1440;
                } elseif ($from > $to) {
                    $diff_in_minutes = $to->diffInMinutes($from);
                } elseif ($from < $to) {
                    $to         = $request->start_time;
                    $to_array   = explode(':', $to);
                    $to_minutes = 1440 - ($to_array[0] * 60 + $to_array[1]);

                    $from         = $request->end_time;
                    $from_array   = explode(':', $from);
                    $from_minutes = $from_array[0] * 60 + $from_array[1];

                    $diff_in_minutes = $to_minutes + $from_minutes;
                }
                $rota['start_time']         = $request->start_time;
                $rota['end_time']           = $request->end_time;
                $rota['break_time']         = $request->break_time;
                $rota['time_diff_in_minut'] = $diff_in_minutes;
                $rota['note']               = $request->note;
                $rota->save();

                event(new UpdateRota($request,$rota));

                $return['status'] = 'success';
                $return['msg']    = __('Shift successfully updated');

                return response()->json($return);
            } else {
                $employee = User::where('id', $time_override[0]['user_id'])->first();
                $name     = 'user';
                if (!empty($employee)) {
                    $name = $employee->name;
                }

                $return['status'] = 'error';
                $return['msg']    = __('This Shift clashes ') . '' . $name . ' ' . date("g:i a", strtotime($time_override[0]['start_time'])) . ' - ' . date("g:i a", strtotime($time_override[0]['end_time'])) . '' . __(' shift');

                return response()->json($return);
            }
        } else {
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
        if (Auth::user()->isAbleTo('rota delete')) {
            $rota = Rota::find($id);
            $rota->delete();

            event(new DestroyRota($rota));

            $return['status'] = 'success';
            $return['msg']    = __('Delete Succsefully');

            return response()->json($return);
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function week_sheet(Request $request)
    {

        $created_by = creatorId();
        $company_settings = getCompanyAllSetting();

        if(!in_array(Auth::user()->type, Auth::user()->not_emp_type) && (isset($company_settings['emp_only_see_own_rota']) && $company_settings('emp_only_see_own_rota') == 1))
        {
            $employee = Employee::where('user_id', Auth::user()->id)->where('workspace', getActiveWorkSpace())->get();

        }
        else
        {
            $employee = Employee::where('workspace', getActiveWorkSpace());
            if (!empty($request->designation_id)) {
                $employee->where('designation_id', $request->designation_id);
            }
            $employee = $employee->get();
        }
        $week1 = 0;
        if (!empty($request->week)) {
            $week1 = $request->week;
        }

        $week = $request->week;
        $week = $week * 7;
        $designation_id = !empty($request->designation_id) ? $request->designation_id : 0;

        $start_day = (isset($company_settings['company_week_start'])) ? $company_settings['company_week_start'] : 'monday';
        $date_formate = Rota::CompanyDateFormat('d M Y');
        $week_date1 = Rota::getWeekArray($date_formate, $week, $start_day);
        $week_date = Rota::getWeekArray('Y-m-d', $week, $start_day);
        $week_date123 = Rota::getWeekArray('d M Y', $week, $start_day);
        $where = '0 = 0 ';



        $table_date = [];
        $thead = '<thead> <tr class="text-center">
            <th></th>
            <th><span>' . __(date('D', strtotime($week_date1[0]))) . '</span><br><span>' . __($week_date1[0]) . '</span></th>
            <th><span>' . __(date('D', strtotime($week_date1[1]))) . '</span><br><span>' . __($week_date1[1]) . '</span></th>
            <th><span>' . __(date('D', strtotime($week_date1[2]))) . '</span><br><span>' . __($week_date1[2]) . '</span></th>
            <th><span>' . __(date('D', strtotime($week_date1[3]))) . '</span><br><span>' . __($week_date1[3]) . '</span></th>
            <th><span>' . __(date('D', strtotime($week_date1[4]))) . '</span><br><span>' . __($week_date1[4]) . '</span></th>
            <th><span>' . __(date('D', strtotime($week_date1[5]))) . '</span><br><span>' . __($week_date1[5]) . '</span></th>
            <th><span>' . __(date('D', strtotime($week_date1[6]))) . '</span><br><span>' . __($week_date1[6]) . '</span></th>
            </tr></thead>';
        $thead2 = '
            <th></th>
            <th><span>' . __(date('D', strtotime($week_date1[0]))) . '</span><br><span>' . __($week_date1[0]) . '</span></th>
            <th><span>' . __(date('D', strtotime($week_date1[1]))) . '</span><br><span>' . __($week_date1[1]) . '</span></th>
            <th><span>' . __(date('D', strtotime($week_date1[2]))) . '</span><br><span>' . __($week_date1[2]) . '</span></th>
            <th><span>' . __(date('D', strtotime($week_date1[3]))) . '</span><br><span>' . __($week_date1[3]) . '</span></th>
            <th><span>' . __(date('D', strtotime($week_date1[4]))) . '</span><br><span>' . __($week_date1[4]) . '</span></th>
            <th><span>' . __(date('D', strtotime($week_date1[5]))) . '</span><br><span>' . __($week_date1[5]) . '</span></th>
            <th><span>' . __(date('D', strtotime($week_date1[6]))) . '</span><br><span>' . __($week_date1[6]) . '</span></th>';


        $tbody = '';
        if (count($employee) != 0) {
            foreach ($employee as $emp) {
                $tbody .= Rota::getWorkSchedule($emp->id, $week1, $designation_id);
            }
        } else {
            $tbody = '<tr>
                        <td colspan="8">
                            <div class="text-center">
                                <i class="fas fa-map-marker-alt text-primary fs-40"></i>
                                <h2>' . __('Opps...') . '</h2>
                                <h6>' . __('User not assign this designation.') . ' </h6>
                                <h6 class="d-none"> ' . __('Please assign user in this designation.') . ' </h6>
                            </div>
                        </td>
                    </tr>';
        }

        // 2nd table in page bottom
        $week_exp = '';
        if (Auth::user()->type == 'company') {
            $week_exp = Rota::getCompanyWeeklyUserSalary($week1, $created_by, $designation_id);
        }
        $array = array('table' => $thead . '<tbody>' . $tbody . '</tbody><tfoot class="bt2"></tfoot>', 'title' => $week_date1[0] . ' - ' . $week_date1[6], 'week_exp' => $week_exp, 'thead' => $thead2);
        return $array;
    }

    public function un_publish_week(Request $request)
    {

        if (Auth::user()->isAbleTo('rota unpublish-week')) {

            $company_settings = getCompanyAllSetting();


            $array = array('status' => 'error', 'msg' => __('Please Try Again'));
            $userId = Auth::id();
            $user = Auth::user();
            $created_by = creatorId();
            $week = (!empty($request->week)) ? $request->week * 7 : 0 * 7;
            $designation_id = $request->designation_id;
            $start_day = (isset($company_settings['company_week_start'])) ? $company_settings['company_week_start'] : 'monday';
            $week_date = Rota::getWeekArray('Y-m-d', $week, $start_day);

            if ($designation_id != 0) {
                $employee_data = Employee::where('designation_id', $designation_id)->get()->pluck('id')->toArray();
                $rotas = Rota::whereIn('user_id', $employee_data)->where('create_by', creatorId())->whereRaw('rotas_date BETWEEN "' . $week_date[0] . '" AND "' . $week_date[6] . '" ')->get()->toArray();
            } else {
                $rotas = Rota::where('create_by', creatorId())->whereRaw('rotas_date BETWEEN "' . $week_date[0] . '" AND "' . $week_date[6] . '" ')->get()->toArray();
            }
            if (!empty($rotas)) {

                foreach ($rotas as $rota) {
                    $rota_data = Rota::find($rota['id']);
                    $rota_data->publish = 0;
                    $rota_data->save();
                }
                $array = array('status' => 'success', 'msg' => __('Shift Un-Publish Successfully'));

            }
            return $array;
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function publish_week(Request $request)
    {
        if (Auth::user()->isAbleTo('rota publish-week')) {

            $company_settings = getCompanyAllSetting();

            $array = array('status' => 'error', 'msg' => __('Please Try Again'));
            $userId = Auth::id();
            $user = Auth::user();

            $week = (!empty($request->week)) ? $request->week * 7 : 0 * 7;
            $created_by  = creatorId();
            $designation_id = $request->designation_id;
            $start_day = (isset($company_settings['company_week_start'])) ? $company_settings['company_week_start'] : 'monday';
            $week_date = Rota::getWeekArray('Y-m-d', $week, $start_day);
            if ($designation_id != 0) {
                $employee_data = Employee::where('designation_id', $designation_id)->get()->pluck('id')->toArray();
                $rotas = Rota::whereIn('user_id', $employee_data)->where('create_by', creatorId())->whereRaw('rotas_date BETWEEN "' . $week_date[0] . '" AND "' . $week_date[6] . '" ')->get()->toArray();
            } else {
                $rotas = Rota::where('create_by', creatorId())->whereRaw('rotas_date BETWEEN "' . $week_date[0] . '" AND "' . $week_date[6] . '" ')->get()->toArray();
            }

            if (!empty($rotas)) {
                foreach ($rotas as $rota) {
                    $rota_data = Rota::find($rota['id']);
                    $rota_data->publish = 1;
                    $rota_data->save();
                }
                $array = array('status' => 'success', 'msg' => __('Shift Publish Successfully'));
            }
            return $array;

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    // drag and drop copy

    public function shift_copy(Request $request)
    {
        if (Auth::user()->isAbleTo('rota copy-week-shift')) {

            $array      = array(
                'status' => 'error',
                'msg' => __('Please Try Again.'),
            );
            $userId = Auth::id();
            $user = Auth::user();
            $employee = Employee::where('id',$request->drop_user_id)->first();
            $created_by = creatorId();

            $drag_rotas_data  = Rota::whereRaw('id = ' . $_POST['rotas_id'])->first()->toArray();
            $rotas_start_time = $drag_rotas_data['start_time'];
            $rotas_end_time   = $drag_rotas_data['end_time'];
            $rotas_break_time = $drag_rotas_data['break_time'];

            if (Auth::user()->type == 'company') {

                $time_override = Rota::WhereRaw('user_id = ' . $request->drop_user_id . ' ')->WhereRaw('rotas_date = "' . $request->drop_date . '"')->WhereRaw(
                    '(
                                            ( start_time = "' . $rotas_start_time . '" AND end_time = "' . $rotas_end_time . '" ) or
                                            ("' . $rotas_start_time . '" BETWEEN start_time and end_time or "' . $rotas_end_time . '" BETWEEN start_time and end_time ) or
                                            (start_time BETWEEN "' . $rotas_start_time . '" and "' . $rotas_end_time . '" or end_time BETWEEN "' . $rotas_start_time . '" and "' . $rotas_end_time . '" )
                                            )'
                )->first();

                $role_name  = '';
                $role_color = 'border-color : rgb(132, 146, 166);';

                if (empty($time_override)) {
                    $diff_in_minutes = 0;
                    $to              = \Carbon\Carbon::createFromFormat('H:i', $rotas_start_time);
                    $from            = \Carbon\Carbon::createFromFormat('H:i', $rotas_end_time);
                    if ($from == $to) {
                        $diff_in_minutes = 1440;
                    } elseif ($from > $to) {
                        $diff_in_minutes = $to->diffInMinutes($from);
                    } elseif ($from < $to) {
                        $to         = $rotas_start_time;
                        $to_array   = explode(':', $to);
                        $to_minutes = 1440 - ($to_array[0] * 60 + $to_array[1]);

                        $from         = $rotas_end_time;
                        $from_array   = explode(':', $from);
                        $from_minutes = $from_array[0] * 60 + $from_array[1];

                        $diff_in_minutes = $to_minutes + $from_minutes;
                    }

                    $rotas                     = new Rota();
                    $rotas->user_id            = $request->drop_user_id;
                    $rotas->issued_by          = $userId;
                    $rotas->rotas_date         = $request->drop_date;
                    $rotas->start_time         = $rotas_start_time;
                    $rotas->end_time           = $rotas_end_time;
                    $rotas->break_time         = $rotas_break_time;
                    $rotas->time_diff_in_minut = $diff_in_minutes;
                    $rotas->note               = $drag_rotas_data['note'];
                    $rotas->designation_id     = $employee->designation_id;
                    $rotas->publish            = 0;
                    $rotas->create_by          = $created_by;
                    $rotas->save();

                    $insert_id = $rotas->id;
                    $time      = date('h:i a', strtotime($rotas_start_time)) . ' - ' . date('h:i a', strtotime($rotas_end_time));
                    $shift     = '<b class="text-dark">' . $time . '</b><br>
                                    <span class="text-secondary"> ' . $role_name . ' </span>
                                    <div class="float-right d-block">
                                        <a href="#" class="action-item edit_rotas only_rotas bg-transparent p-0" data-toggle="tooltip" title="' . $drag_rotas_data['note'] . '"><i class="far fa-comment"></i></a>
                                        <a href="#" class=" action-item edit_rotas only_rotas bg-transparent p-0" data-ajax-popup="true" data-title="' . __('Edit Shift') . '" data-size="md" data-url="' . route('rota.edit', $insert_id) . '">
                                            <i class="far fa-pencil-alt" data-toggle="tooltip"  title="' . __('Edit Shift') . '"></i>
                                        </a>

                                        <a href="#" class="delete_rotas_action delete_rotas only_rotas bg-transparent p-0 action-item" data-confirm="' . __('Are You Sure?') . '|' . __('All data will be lost permanentaly. Do you want to continue?') . '" data-confirm-yes=document.getElementById("delete-form-' . $insert_id . '").submit(); >
                                            <i class="far fa-trash" data-toggle="tooltip" data-original-title="' . __('Delete') . '" ></i>
                                        </a>
                                        <form method="POST" action="' . route('rota.destroy', $insert_id) . '" id="delete-form-' . $insert_id . '">
                                            <input name="_method" type="hidden" value="DELETE">
                                            <input name="_token" type="hidden" value="' . csrf_token() . '">
                                        </form>
                                    </div>
                                    <sapn class="clearfix">
                                </sapn>';


                    $array = array(
                        'status' => 'success',
                        'msg' => __('Shift Add Successfully'),
                        'shift' => $shift,
                        'insert_id' => $insert_id,
                    );
                } else {
                    $employee = User::where('id', $request->drop_user_id)->first();
                    $name     = (!empty($employee->name)) ? $employee->name : __(' employee ');
                    $msg      = __('This Shift clashes ') . '' . $name . ' ' . date("g:i a", strtotime($time_override['start_time'])) . ' - ' . date("g:i a", strtotime($time_override['end_time'])) . ' ' . $role_name . '' . __(' shift');
                    $array    = array(
                        'status' => 'error',
                        'msg' => $msg,
                    );
                }
            } else {
                $array = array(
                    'status' => 'error',
                    'msg' => __('Permission denied.'),
                );
            }
        } else {

            $array = array(
                'status' => 'error',
                'msg' => __('Permission denied.'),
            );
        }
        return $array;
    }

    // copy rotas for next week

    public function copy_week_sheet(Request $request)
    {
        if (Auth::user()->isAbleTo('rota shift-copy')) {
            $rotas_id_array = $request->rotas_id_array;
            $error_msg = [];
            if (Auth::user()->type == 'company' && $rotas_id_array !== null) {
                foreach ($rotas_id_array as $key => $rotas_id) {
                    $drag_rotas_data = Rota::where('id', $rotas_id)->first()->toArray();
                    $rotas_start_time = $drag_rotas_data['start_time'];
                    $rotas_end_time = $drag_rotas_data['end_time'];
                    $rotas_date = $drag_rotas_data['rotas_date'];
                    $drop_user_id = $drag_rotas_data['user_id'];
                    $designation_id = $drag_rotas_data['designation_id'];
                    $note = $drag_rotas_data['note'];
                    $created_by = $drag_rotas_data['create_by'];
                    $drop_rotas_date = date('Y-m-d', strtotime($rotas_date . ' + 7 days'));
                    $time_override = Rota::WhereRaw('user_id = ' . $drop_user_id . ' ')
                        ->WhereRaw('rotas_date = "' . $drop_rotas_date . '"')
                        ->WhereRaw('(
                                            ( start_time = "' . $rotas_start_time . '" AND end_time = "' . $rotas_end_time . '" ) or
                                            ("' . $rotas_start_time . '" BETWEEN start_time and end_time or "' . $rotas_end_time . '" BETWEEN start_time and end_time ) or
                                            (start_time BETWEEN "' . $rotas_start_time . '" and "' . $rotas_end_time . '" or end_time BETWEEN "' . $rotas_start_time . '" and "' . $rotas_end_time . '" )
                                        )')
                        ->first();
                    if (empty($time_override)) {
                        $diff_in_minutes = 0;
                        $to = \Carbon\Carbon::createFromFormat('H:i', $rotas_start_time);
                        $from = \Carbon\Carbon::createFromFormat('H:i', $rotas_end_time);
                        if ($from == $to) {
                            $diff_in_minutes = 1440;
                        } elseif ($from > $to) {
                            $diff_in_minutes = $to->diffInMinutes($from);
                        } elseif ($from < $to) {
                            $to = $rotas_start_time;
                            $to_array = explode(':', $to);
                            $to_minutes = 1440 - ($to_array[0] * 60 + $to_array[1]);
                            $from = $rotas_end_time;
                            $from_array = explode(':', $from);
                            $from_minutes = $from_array[0] * 60 + $from_array[1];
                            $diff_in_minutes = $to_minutes + $from_minutes;
                        }
                        $rotas = new Rota();
                        $rotas->user_id = $drop_user_id;
                        $rotas->issued_by = Auth::user()->id;
                        $rotas->rotas_date = $drop_rotas_date;
                        $rotas->start_time = $rotas_start_time;
                        $rotas->end_time = $rotas_end_time;
                        $rotas->time_diff_in_minut = $diff_in_minutes;
                        $rotas->designation_id = $designation_id;
                        $rotas->note = $note;
                        $rotas->publish = 0;
                        $rotas->create_by = $created_by;
                        $rotas->save();
                    } else {
                        $employee = Employee::where('id', $drop_user_id)->first();
                        $name = (!empty($employee->first_name)) ? $employee->first_name : __('employee');
                        $error_msg[] = __('This Shift clashes ') . '' . $name . ' ' . date("g:i a", strtotime($time_override['start_time'])) . ' - ' . date("g:i a", strtotime($time_override['end_time'])) . ' ' . __(' shift') . '';
                    }
                }
                $array = array('status' => 'success', 'msg' => __('Rotas copy succefully in next week.') . implode('<br>', $error_msg));
            } else {
                $array = array('status' => 'error', 'msg' => __('Permission denied.'));
            }
            return response()->json($array);
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function hidedayoff(Request $request)
    {
        if (Auth::user()->isAbleTo('rota hide/show day off')) {

            $day_off_sts = $request->hide_day_off;

            $userId = Auth::id();
            $user = Auth::user();

            $employees = User::find($userId);
            if (!empty($employees->employee_setting)) {
                $setting = json_decode($employees->employee_setting, true);
                if (!empty($setting['day_off'])) {
                    $setting['day_off'] = $day_off_sts;
                    $employees->employee_setting = json_encode($setting);
                    $employees->save();
                } else {
                    $new_setting['day_off'] = $day_off_sts;
                    $setting = array_merge($new_setting, $setting);
                    $employees->employee_setting = json_encode($setting);

                    $employees->save();
                }
            } else {
                $setting['day_off'] = $day_off_sts;
                $employees->employee_setting = json_encode($setting);
                $employees->save();
            }

            return $employees->employee_setting;
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function hideleave(Request $request)
    {
        if (Auth::user()->isAbleTo('rota hide/show leave')) {


            $leave_display = $request->leave_display;
            $userId = Auth::id();
            $user = Auth::user();

            $employees = User::find($userId);
            if (!empty($employees->employee_setting)) {
                $setting = json_decode($employees->employee_setting, true);
                if (!empty($setting['leave_display'])) {
                    $setting['leave_display'] = $leave_display;
                    $employees->employee_setting = json_encode($setting);
                    $employees->save();
                } else {
                    $new_setting['leave_display'] = $leave_display;
                    $setting = array_merge($new_setting, $setting);
                    $employees->employee_setting = json_encode($setting);
                    $employees->save();
                }
            } else {
                $setting['leave_display'] = $leave_display;
                $employees->employee_setting = json_encode($setting);
                $employees->save();
            }

            return $employees->employee_setting;
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function hideavailability(Request $request)
    {
        if (Auth::user()->isAbleTo('rota hide/show availability')) {
            $availability_display = $request->availability_display;
            $userId = Auth::id();
            $user = Auth::user();

            $employees = User::find($userId);
            if (!empty($employees->employee_setting)) {
                $setting = json_decode($employees->employee_setting, true);
                if (!empty($setting['availability_display'])) {
                    $setting['availability_display'] = $availability_display;
                    $employees->employee_setting = json_encode($setting);
                    $employees->save();
                } else {
                    $new_setting['availability_display'] = $availability_display;
                    $setting = array_merge($new_setting, $setting);
                    $employees->employee_setting = json_encode($setting);
                    $employees->save();
                }
            } else {
                $setting['availability_display'] = $availability_display;
                $employees->employee_setting = json_encode($setting);
                $employees->save();
            }
            return $employees->employee_setting;
            return view('rotas::rota.index', compact('week_date', 'employees', 'temp_week', 'branch', 'department', 'designation'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function send_email_rotas(Request $request)
    {

        if (Auth::user()->isAbleTo('rota send mail'))
        {
            $company_settings = getCompanyAllSetting();

            if(isset($company_settings['Rotas']) && $company_settings['Rotas']  == true)
            {

                $week        = 0;
                $designation_id = $request->designation_id;

                $date = Rota::week_day_by_setting($week = 0, creatorId());

                $has_users = Employee::where('id', Auth::user()->id)->orwhere('created_by', Auth::user()->id)->get()->toArray();
                $all_locations  = Designation::where('created_by', creatorId())->get()->toArray();
                $location_datas = [];
                if (!empty($all_locations)) {

                    foreach ($all_locations as $all_location) {
                        $location_datas[$all_location['id']] = $all_location['name'];
                    }
                }

                    if (!empty($has_users)) {

                    $setconfing =  SetConfigEmail();
                    if ($setconfing ==  true) {
                        foreach ($has_users as $has_user) {

                            $rotas_data = Rota::whereRaw('user_id =' . $has_user['id'] . '')->whereRaw('publish = 1')->whereRaw(' rotas_date BETWEEN "' . $date[0] . '" AND "' . $date[6] . '"')->get()->toArray();

                            if (!empty($has_user['email'])) {
                                $resp = '';
                                try {
                                    $resp =  Mail::to($has_user['email'])->send(new SendRotas($rotas_data, $location_datas, $has_user['id'], $date));
                                } catch (\Exception $e) {

                                    $smtp_error = '<br><span class="text-danger">' . __('E-Mail has been not sent due to SMTP configuration') . '<span>';
                                }
                            }
                        }
                    } else {
                        $error = __('Something went wrong please try again ');
                    }
                }
                event(new SendRotasViaEmail($request,$rotas_data));
                return response()->json(
                    [
                        'status' => 'success',
                        'message' => __('Mail Send Successfully').((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''),

                    ]
                );

                return redirect()->back()->with('success', __('Mail Send successfully!') . ((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }
            else{
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => __('Rotas notification is off')
                    ]
                );
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function shift_cancel(Request $request, $id)
    {
        $rota = Rota::find($id);
        return view('rotas::rota.shift_cancel', compact('rota'));
    }

    public function shift_disable(Request $request)
    {
        $rota_id = $request->rota_id;
        $shift_status = $request->shift_status;
        $shift_cancel_employee_msg = $request->shift_cancel_employee_msg;

        $rota_id = Rota::find($rota_id);
        $rota_id->shift_status = $request->shift_status;
        $rota_id->shift_cancel_employee_msg = $request->shift_cancel_employee_msg;
        $rota_id->save();

        return redirect()->back()->with('success', __('Shift unavailability request sent.'));
    }

    public function shift_disable_response(Request $request, $id)
    {
        $rota_id = $id;
        $rota = Rota::find($id);
        $user_data = User::find($rota->user_id);
        $f_name = $user_data->first_name;

        $name = (!empty(trim($f_name))) ? $f_name : __('User');

        $msg = $name . __(' has requested unavailability for this shift.');

        return view('rotas::rota.shift_cancel_response', compact('rota', 'msg'));
    }

    public function shift_disable_reply(Request $request)
    {
        $rota = Rota::find($request->rota_id);
        $rota->shift_status = $request->shift_status;
        $rota->shift_cancel_owner_msg = $request->shift_cancel_owner_msg;
        $rota->save();
        $stutas = ($request->shift_status == 'disable') ? __('Approve') : __('Deny');
        return redirect()->back()->with('success', __('Shift Request ') . $stutas . '.');
    }

    public function clear_week(Request $request)
    {
        if (Auth::user()->isAbleTo('rota clear week')) {

            $created_by = $request->created_by;
            $week = (!empty($request->week)) ? $request->week : 0;
            $week = $week * 7;
            $company_settings = getCompanyAllSetting();


            $employee_data= Employee::whereRaw('id = ' . creatorId() . ' ')->first();

            $start_day = (isset($company_settings['company_week_start'])) ? $company_settings['company_week_start'] : 'monday';
            $week_date = Rota::getWeekArray('Y-m-d', $week, $start_day);

            $designation_id = (!empty($request->designation_id)) ? $request->designation_id : 0;
            $created_by = (!empty($request->created_by)) ? $request->created_by : 0;
            $end_date = (!empty($request->end_date)) ? $request->end_date : 0;
            $start_date = (!empty($request->start_da)) ? $request->start_date : 0;
            $rotas = Rota::WhereRaw('create_by = ' . $created_by)
                ->WhereRaw('rotas_date BETWEEN "' . $week_date[0] . '" AND "' . $week_date[6] . '"');
            if($request->designation_id){
                $rotas =  $rotas->where('designation_id',$request->designation_id);
            }
            $rotas = $rotas->delete();
            $array = array('status' => 'success', 'msg' => __('Delete Succsefully'));
            return $array;
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function add_dayoff(Request $request)
    {
        if (Auth::user()->isAbleTo('rota day off')) {

            $array     = array(
                'status' => 'success',
                'msg' => __('Please Try Again'),
            );
            $date[]    = $request->date;
            $click_day = date('l', strtotime($request->date));

            $date_status = '';
            if (!empty($request->user_id) && !empty($request->date)) {
                $profile = Employee::whereRaw('id' . '=' . $request->user_id . '')->where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->first();
                if (!empty($profile->custom_day_off)) {
                    $date_array   = json_decode($profile->custom_day_off, true);

                    $date_inarray = in_array($request->date, $date_array);
                    if ($date_inarray) {
                        $remove_date = array_diff($date_array, $date);
                        $date_array  = (!empty($remove_date)) ? array_values($remove_date) : '';
                        $date_status = '';
                        $msg         = __('Remove day off successfully');
                    } else {
                        array_push($date_array, $request->date);
                        $date_status = 'add';

                        $date_status = '<div class="text-center text-danger day_off_leave cus_day_off_leave ui-sortable-handle" data-date="' . $request->date . '" data-placement="top" data-html="true" data-toggle="tooltip" title="' . __('This is') . __(' Day Off') . '">' . __(' Day Off') . '</div>';
                        $msg         = __('Add day off successfully');

                    }
                } else {

                    $date_array[] = $request->date;
                    $date_status  = 'add';
                    $date_status  = '<div class="text-center text-danger day_off_leave cus_day_off_leave ui-sortable-handle" data-date="' . $request->date . '" data-placement="top" data-html="true" data-toggle="tooltip" title="' . __('This is'). __(' Day Off') . '">' . __(' Day Off') . '</div>';
                    $msg          = __('Add day off successfully');

                }

                $profile->custom_day_off = (!empty($date_array)) ? json_encode($date_array) : null;
                $profile->save();
                $array = array(
                    'status' => 'success',
                    'msg' => $msg,
                    'date_status' => $date_status,
                    'date' => $request->date,
                );

            event(new AddDayoff($request , $profile));

            }
            return response()->json($array);
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }
    public function print_rotas_popup(Request $request)
    {
        if (Auth::user()->isAbleTo('rota print')) {

            $week      = $request->week;
            $designation_id = $request->designation_id;
            $create_by = $request->created_by;

            $where = ' 0 = 0 ';

            $designation_id = $request->designation;

            if ($designation_id != 0 && !empty($designation_id)) {
                $where .= ' AND FIND_IN_SET(' . $designation_id . ', employees.designation_id) ';
            }


            $create_by = $request->create_by;
            if ($create_by != 0 && !empty($create_by)) {
                $where .= ' AND employees.created_by = ' . $create_by . ' ';
            }

            $users = User::select('users.*', 'employees.designation_id')->join('employees', 'users.id', '=', 'employees.user_id')->whereraw($where)->get();

            $user_array = [];
            if ($users) {
                foreach ($users as $key => $user) {
                    $user_array[$key]['id']   = $user->id;
                    $user_array[$key]['name'] = $user->name;
                }
            }

            return view('rotas::rota.printrotas', compact('user_array', 'week', 'create_by', 'designation_id'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function printrotasInvoice(Request $request)
    {
        if (Auth::user()->isAbleTo('rota print')) {
            if (!empty($request->user)) {
                $user_array = $request->user;
                $designation_id = $request->designation_id;
                $week = $request->week * 7;
                $company_settings = getCompanyAllSetting();


                $start_day = (isset($company_settings['company_week_start'])) ? $company_settings['company_week_start'] : 'monday';
                $week_date = Rota::getWeekArray('Y-m-d', $week, $start_day);

                $company_date_format = (isset($company_settings['company_date_format'])) ? $company_settings['company_date_format'] : 'd M Y';
                $week_date_formate = Rota::getWeekArray($company_date_format, $week, $start_day);

                $location_data = Employee::find($request->$designation_id);
                $users = [];

                if (!empty($user_array)) {
                    foreach ($user_array as $key => $value) {

                        $user_data = User::where('id', $value)->first();
                        $users[$key]['id'] = $value;
                        $users[$key]['name'] = $user_data->name;
                    }
                }

                return view('rotas::rota.rotastable', compact('week_date_formate', 'week_date', 'users', 'designation_id'));
            } else {
                return redirect()->back()->with('errors', __('Please select User'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function share_rotas_popup(Request $request)
    {

        if (Auth::user()->isAbleTo('rota share')) {

            $rota['designation_id']  = $request->designation;
            $rota['create_by']    = $request->create_by;
            $rota['week']         = $request->week;
            $rota['user_array']   = $request->user;
            return view('rotas::rota.shift_share', compact('rota'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function share_rotas_link(Request $request)
    {
        if (Auth::user()->isAbleTo('rota share')) {

            $query_string['create_by'] = $request->create_by;
            $query_string['week'] = $request->week;
            $query_string['user_array'] = $request->user_array;
            $query_string['share_password'] = $request->share_password;
            $query_string['password_sts'] = (!empty($request->share_password)) ? 1 : 0;
            $query_string['expiry_date'] = $request->expiry_date;

            $enc_url = Crypt::encrypt($query_string);
            $url = route('rotas.share', $enc_url);
            return response()->json([
                'status' => 'success',
                'message' => $url
            ]);
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function share_rotas(Request $request, $slug)
    {
            try {
                $decrypted_array = Crypt::decrypt($slug);
            } catch (\Throwable $th) {
                return redirect('login');
            }
            $user_array     = $decrypted_array['user_array'];
            $create_by      = $decrypted_array['create_by'];
            $week           = $decrypted_array['week'];
            $password_sts   = $decrypted_array['password_sts'];
            $expiry_date    = $decrypted_array['expiry_date'];


            if (!empty($decrypted_array['share_password'])) {
                $password       = $decrypted_array['share_password'];
            } else {
                $password       = '';
            }

            $users_name = Employee::whereraw('id in (' . $user_array . ')')->get();
            if (empty($users_name)) {
                $users_name = [];
            }

            $week_date = Rota::getWeekArray('Y-m-d', $week * 7, 'monday');

            $date = date('Y-m-d');
            $date_sts = 1;
            if (!empty($expiry_date) && $expiry_date < $date) {
                $date_sts = 0;
                $msg = __('This link is expired.');
                return view('rotas::rota.share_shift_table', compact('msg', 'date_sts'));
            }
            if ($password_sts == 1) {
                return view('rotas::rota.share_shift_table', compact('password_sts', 'slug', 'week_date', 'user_array', 'date_sts'));
            } else {
                $compact = ['users_name', 'week_date', 'password_sts', 'user_array', 'date_sts'];
                return view('rotas::rota.share_shift_table', compact($compact));
            }

    }

    public function slug_match(Request $request)
    {
        $confirm_password = $request->confirm_password;
        $slug = $request->slug;
        $decrypted_array = Crypt::decrypt($slug);

        if ($decrypted_array['share_password'] ==  $confirm_password) {
            $decrypted_array['password_sts'] = 0;
            $enc_url = Crypt::encrypt($decrypted_array);
            $return['status'] = 'success';
            $url = route("rotas.share", $enc_url);
            $return['url'] = $enc_url;
        } else {
            $return['status'] = 'error';
        }

        return response()->json($return);
    }
    public function rota_date_change(Request $request)
    {

        $s_date = $request->s_date;
        $e_date = $request->e_date;

        $week = $request->week * 7;
        $designation_id = $request->designation_id;
        $user_array = $request->user_array;
        $week_date = Rota::getWeekArray('Y-m-d', $week, 'monday');

        $customDates = Rota::customDatesrange($s_date, $e_date, 'Y-m-d');

        $th = '';

        foreach ($customDates as $date) {
            $th .= '<th class="bg-primary">' . $date . '</th>';
        }


        $thead = '<thead><tr><th class="bg-primary">' . __('Name') . '</th>' . $th . '</tr></thead>';

        if (!empty($user_array)) {
            $user_array = explode(',', $user_array);
            $tbody = '';
            $tr = '';
            if (!empty($user_array)) {
                foreach ($user_array as $key => $value) {
                    $tb = '';
                    foreach ($customDates as $date) {
                        $tb .= '<td>' . Rota::getdaterotas($date, $value, $designation_id) . '</td>';
                    }
                    $user = Employee::find($value);

                    $tbody .= '
                        <tr>
                            <td>' . $user->name . '</td>' . $tb . '
                        </tr>';
                }
            } else {
                $tbody = '<tr> <td colspan="8"> <h2> <center> ' . __("No Data Found")  . '  </center> </h2> </td> </tr>';
            }
            $return['status'] = 1;
            $return['msg'] = $thead . $tbody;
            $return['date_title'] = $week_date[0] . ' - ' . $week_date[6];
        } else {
            $return['status'] = 0;
        }

        return response()->json($return);
    }



    public function setting(Request $request)
    {
        $getActiveWorkSpace = getActiveWorkSpace();
        $creatorId = creatorId();
        $post = $request->all();
        unset($post['_token']);
        foreach ($post as $key => $value) {
            // Define the data to be updated or inserted
            $data = [
                'key' => $key,
                'workspace' => $getActiveWorkSpace,
                'created_by' => $creatorId,
            ];

            // Check if the record exists, and update or insert accordingly
            Setting::updateOrInsert($data, ['value' => $value]);
        }
        // Settings Cache forget
        comapnySettingCacheForget();
        return redirect()->back()->with('success','Rota Setting successfully updated.');
    }

    public function companyworkschedule(Request $request)
    {
        $getActiveWorkSpace = getActiveWorkSpace();
        $creatorId = creatorId();
        $work_schedule = json_encode($request->work_schedule);

        $data = [
                    'key' => 'WorkSchedule',
                    'workspace' => $getActiveWorkSpace,
                    'created_by' => $creatorId,
                    'value' => $work_schedule,
                ];

        Setting::updateOrInsert($data);

        // Settings Cache forget
        comapnySettingCacheForget();
        return redirect()->back()->with('success', 'Work Schedule Setting updated successfully');
    }

    public function workscheduleData(Request $request)
    {
        $employee = Employee::where('workspace', getActiveWorkSpace());
        if ($request->employee != 0) {
            $employee = $employee->where('id', $request->employee);
        }
        $employee = $employee->first();
        $employees = Employee::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');

        return view('rotas::rota.workschedule', compact('employees', 'employee'));
    }


    public function workscheduleDataSave(Request $request, $employee)
    {

        $validator = \Validator::make(
            $request->all(),
            [

                'work_schedule' => 'required'
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $arr = $request->work_schedule;
        $gf = json_encode($arr);

        $employees = Employee::find($employee);

        $employees->work_schedule = $gf;
        $employees->save();

        event(new UpdateWorkSchedule($request,$employees));

        return redirect()->back()->with('success', 'Employee work schedule set successfully.');
    }
}
