<?php

namespace Modules\Rotas\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Rotas\Entities\Employee;



class Rota extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'issued_by',
        'rotas_date',
        'start_time',
        'end_time',
        'break_time',
        'time_diff_in_minut',
        'note',
        'designation_id',
        'publish',
        'shift_status',
        'shift_cancel_employee_msg',
        'shift_cancel_owner_msg',
        'weekly_hour',
        'custom_day_off',
        'employees',
        'workspace',
        'create_by'
    ];

    protected static function newFactory()
    {
        return \Modules\Rotas\Database\factories\RotaFactory::new();
    }
    public static function getWeekArray($date_formate = 'Y-m-d', $week = 0, $start_day = 'monday')
    {
        $days_name['monday'] = 0;
        $days_name['tuesday'] = 1;
        $days_name['wednesday'] = 2;
        $days_name['thursday'] = 3;
        $days_name['friday'] = 4;
        $days_name['saturday'] = 5;
        $days_name['sunday'] = 6;
        $week_date[] = date($date_formate, strtotime($week . "day", strtotime('monday this week')));
        $week_date[] = date($date_formate, strtotime($week . "day", strtotime('tuesday this week')));
        $week_date[] = date($date_formate, strtotime($week . "day", strtotime('wednesday this week')));
        $week_date[] = date($date_formate, strtotime($week . "day", strtotime('thursday this week')));
        $week_date[] = date($date_formate, strtotime($week . "day", strtotime('friday this week')));
        $week_date[] = date($date_formate, strtotime($week . "day", strtotime('saturday this week')));
        $week_date[] = date($date_formate, strtotime($week . "day", strtotime('sunday this week')));
        return $week_date;
    }

    public static function CompanyDateFormat($default = 'Y-m-d')
    {
        $user = Auth::user();

        $created_by = $user->id;

        $value = (!empty($default)) ? $default : 'Y-m-d';
        $company_setting_data = Rota::Where('id', $created_by)->first();
        if (!(empty($company_setting_data->company_setting))) {
            $company_setting_array = json_decode($company_setting_data->company_setting, true);
            $value = (!empty($company_setting_array['company_date_format'])) ? $company_setting_array['company_date_format'] : $value;
        }
        return $value;
    }

    public function getrotauser()
    {
        return $this->HasOne('Modules\Hrm\Entities\Employee', 'id', 'user_id');
    }
    public function designation()
    {
        return $this->HasOne('Modules\Hrm\Entities\Designation', 'id', 'designation_id');
    }

    public static function customDatesrange($date1, $date2, $format = 'd-m-Y')
    {
        $dates = array();
        $current = strtotime($date1);
        $date2 = strtotime($date2);
        $stepVal = '+1 day';
        while ($current <= $date2) {
            $dates[] = date($format, $current);
            $current = strtotime($stepVal, $current);
        }
        return $dates;
    }

    public static function getdaterotas($date = 0, $user_id = 0, $location_id = 0, $role_id = 0)
    {
        $data = '';
        if ($user_id != 0 && $date != 0) {
            $location_id_where = ' 0 = 0 ';
            if ($location_id != 0) {
                $location_id_where = ' location_id =  "' . $location_id . '"';
            }

            $role_id_where = ' 0 = 0 ';
            if ($role_id != 0) {
                $role_id_where = ' role_id =  ' . $role_id;
            }

            $rotas = Rota::whereRaw('rotas_date = "' . $date . '"')->whereRaw('user_id = ' . $user_id . '')->whereRaw($location_id_where)->whereRaw($role_id_where)->get();

            if (!empty($rotas)) {
                $numItems = count($rotas);
                $i = 0;
                foreach ($rotas as $rota) {
                    $time = $rota['start_time'] . '-' . $rota['end_time'];
                    $role_name = '';

                    $hr = '<hr>';
                    if (++$i === $numItems) {
                        $hr = '';
                    }
                    $data .= '<span>' . $time . ' </span>' . $role_name . $hr;
                }
            }
        }
        return (!empty($data)) ? $data : '-';
    }



    public static function userprofile($id = '')
    {
        $profile_pic = '';
        $profile_pic_path = 'uploads/users-avatar/avatar.png';
        $default_profile_pic = $profile_pic_path;
        if (!empty($id)) {
            $profile = Employee::where('user_id', $id)->first();
            if (!empty($profile) && !empty($profile->profile_pic)) {
                $default_profile_pic = $profile->profile_pic;
            }
        }
        return $default_profile_pic;
    }

    public static function getBranchWiseUser($branch = 0, $department = 0, $designations = 0, $user_id = 0)
    {

        $userId = Auth::id();
        $user = Auth::user();
        $created_by = creatorId();
        $role_where = ' 0 = 0 ';

        if ($user_id != 0) {
            $employees = Employee::Where('id', $user_id);
        } else {
            $employees = Employee::Where('user_id', Auth::user()->id);
        }
        if ($designations != 0) {
            $employees->where('designations_id', $designations);
        }
        $employees = $employees->get();
        $employee_data = [];
        if (count($employees) != 0) {
            foreach ($employees as $key => $employee) {
                $profiles = Employee::where('user_id', Auth::user()->id)->where('workspace', getActiveWorkSpace())->get();
                if (count($profiles) != 0) {
                    $employee->full_name = $employee['name'];
                    $employee_data[$key] = $employee;
                }
            }
        }
        return $employee_data;
    }

    public static function getdaterotasreport($date = '', $id = '')
    {
        $created_by = creatorId();
        if ($date == '' && $id == '') {
            return '';
        }

        $tr = '';
        $employee = Employee::where('user_id', $id)->where('created_by', $created_by)->first();
        if (!empty($employee)) {
            $rotas_query = Rota::Where('user_id', $employee->id)->Where('rotas_date', $date);
            $rotas = $rotas_query->where('publish', 1)->where('shift_status', 'enable')->get();
            if (!empty($rotas)) {
                foreach ($rotas as $key => $rota) {
                    $clr = '';
                    $clr = 'style="background-color:#eeeeee"';
                    $td1 = '<td >' . date('D d F Y', strtotime($date)) . '</td>';
                    $td2 = '<td ' . $clr . '>' . $employee->name . '</td>';
                    $td3 = '<td ' . $clr . '>' . date('h:i A', strtotime($rota->start_time)) . '</td>';
                    $td4 = '<td ' . $clr . '>' . date('h:i A', strtotime($rota->end_time)) . '</td>';
                    $tr .= '<tr>' . $td1 . $td2 . $td3 . $td4 . '</tr>';
                }
            }
        }
        return $tr;
    }

    public static function WorkSchedule()
    {
        $work_schedule = company_setting('WorkSchedule');
        $work_schedule = json_decode($work_schedule, true, 512, JSON_FORCE_OBJECT);

        if (!empty($work_schedule)) {
            return $work_schedule;
        }
    }

    public static function week_day_by_setting($week = 0, $created_by = 0)
    {
        $company_settings = getCompanyAllSetting();

        $week = $week * 7;
        $employee_data = Employee::whereRaw('id = ' . $created_by . ' ')->first();
        $start_day = isset($company_settings['company_week_start']) ? $company_settings['company_week_start'] : 'monday';
        return $week_date = Rota::getWeekArray('Y-m-d', $week, $start_day);
    }

    // Rotas Module Code //


    public static function getWorkSchedule($id = 0, $week = 0, $designation_id = 0)
    {
        $week1 = $week;
        $week = $week * 7;
        $week_date = \Modules\Rotas\Entities\Rota::getWeekArray('Y-m-d', $week);

        $tr = '';
        $rotas_time = '';
        $table_date = [];
        $availabilitie_data = array('', '', '', '', '', '', '');
        $flg = 0;
        $employee_data = '';
        $class1 = ' ';
        $user_profile_img = '';
        $show_avatars_on_rota = 0;

        $user_type = Auth::user()->type;
        $login_userId = Auth::id();

        $user123 = Auth::user();
        $created_by = creatorId();
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

        $login_employee = Employee::Where('user_id', $login_userId)->first();

        // $setting_data = Employee::Where('id', $created_by)->OrWhere('created_by', $created_by)->first();

        $break_paid = 'paid';
        $emp_hide_rotas_hour = 0;
        $company_settings = getCompanyAllSetting();


        // $show_avatars_on_rota = (!empty(company_setting('emp_show_avatars_on_rota'))) ? company_setting('emp_show_avatars_on_rota') : 0;
        $break_paid = isset($company_settings['break_paid']) ? $company_settings['break_paid'] : 'paid';
        $emp_hide_rotas_hour = isset($company_settings['emp_hide_rotas_hour']) ? $company_settings['emp_hide_rotas_hour'] : 0;

        $emp_show_rotas_price = 0;
        if (Auth::user()->type != 'company') {
            $emp_show_rotas_price = (isset($company_settings['emp_show_rotas_price']) && $company_settings['emp_show_rotas_price'] == 1) ? $company_settings['emp_show_rotas_price'] : 0;

        } else {
            $emp_show_rotas_price = 1;
        }
        $emp_show_avatars_on_rota = (isset($company_settings['emp_show_avatars_on_rota']) && $company_settings['emp_show_avatars_on_rota'] == 1) ? $company_settings['emp_show_avatars_on_rota'] : 0;
        $manage_add_shift = 1;

        if ($id != 0) {
            $employee = Employee::Where('id', $id)->first();
            $user = User::Where('id', $employee->user_id)->first();
            $employee_data = Employee::Where('employee_id', $id)->first();
            // $view_setting = Employee::whereRaw('employee_id = ' . $created_by . ' ')->first();
            $view_setting_array = [];
            if (isset($company_settings['see_note'])) {
                $view_setting_array = $company_settings['see_note'];
            }
            if (isset($user->avatar) && $emp_show_avatars_on_rota == 1) {
                $user_profile_img = '<div class="text-center"><img  class="avatar rounded-circle avatar-sm" style="
                max-width: 40px;" id="blah" src="' . get_file($user->avatar) . '"></div>';
            }
            if (isset($company_settings['see_note']) && $company_settings['see_note'] == 'none' && !in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                $class1 = 'd-none ';
            }
            if (isset($company_settings['see_note']) && $company_settings['see_note'] == 'self' && !in_array(Auth::user()->type, Auth::user()->not_emp_type) && $login_employee->id != $id) {
                $class1 = 'd-none ';
            }
            if (isset($company_settings['see_note']) && $company_settings['see_note'] == 'all' && !in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                $class1 = '';
            }

            //availabilities
            $availabilities = \Modules\Rotas\Entities\Availability::Where('employee_id', $id)->get()->toArray();
            if (!empty($availabilities)) {
                foreach ($availabilities as $availabilitie) {
                    if ((!empty($availabilitie['start_date']) && in_array($availabilitie['start_date'], $week_date)) || (!empty($availabilitie['end_date']) && in_array($availabilitie['end_date'], $week_date))) {
                        $flg = 1;
                        $repeat_week = $availabilitie['repeat_week'];
                        $availability_json =  json_decode($availabilitie['availability_json'], true);
                        if (!empty($availability_json)) {
                            $availabilitie_data[0] = '';
                            $availabilitie_data[1] = '';
                            $availabilitie_data[2] = '';
                            $availabilitie_data[3] = '';
                            $availabilitie_data[4] = '';
                            $availabilitie_data[5] = '';
                            $availabilitie_data[6] = '';
                            foreach ($availability_json as $availability_json_data) {
                                $availability_string1 = '';
                                $availability_string = [];
                                foreach ($availability_json_data['periods'] as $periods) {
                                    if ($periods['backgroundColor'] == 'rgba(0, 200, 0, 0.5)') {
                                        $availability_string[] = '<span class="text-success">' . $periods['start'] . ' - ' . $periods['end'] . '</span>';
                                        $availability_string1 .= '<span class="text-success">' . $periods['start'] . ' - ' . $periods['end'] . '</span><br>';
                                    }
                                    if ($periods['backgroundColor'] == 'rgba(200, 0, 0, 0.5)') {
                                        $availability_string[] = '<span class="text-danger">' . $periods['start'] . ' - ' . $periods['end'] . '</span>';
                                        $availability_string1 .= '<span class="text-danger">' . $periods['start'] . ' - ' . $periods['end'] . '</span><br>';
                                    }
                                }
                                $week_days = $availability_json_data['day'];
                                $availability_sts = ($availability_display == 'hide') ? 'style="display:none;"' : '';
                                $availabilitie_data[$week_days] = '<div class="availability_table_box" ' . $availability_sts . '">' . $availability_string1 . '</div>';
                            }
                        }
                    }
                }
            }
            //if no availabilities in week
            if ($flg == 0) {
                $prev_availabilities = \Modules\Rotas\Entities\Availability::Where('employee_id', $id)->where('repeat_week', '!=', 0)->orderBy('start_date', 'desc')->first();
                if (!empty($prev_availabilities)) {
                    $prev_repeat_week = $prev_availabilities['repeat_week'];
                    $prev_availability_json =  json_decode($prev_availabilities['availability_json'], true);
                    if ($prev_repeat_week != 0) {
                        $prev_start_date = $prev_availabilities['start_date'];
                        $repet_week = 7 * $prev_repeat_week;
                        $add_week_after_date = date("Y-m-d", strtotime($repet_week . " day", strtotime($prev_start_date)));
                        $prev_repeat_week1 = $prev_repeat_week;
                        $response1 = '';
                        if ($prev_repeat_week == 1) {
                        }
                        if ($prev_repeat_week == 2) {
                            $response1 = $this->getRepeatweekDate($prev_repeat_week1, $prev_repeat_week1, $prev_start_date, $week_date);
                        }
                        if ($prev_repeat_week == 3) {
                            $response1 = $this->getRepeatweekDate($prev_repeat_week1, $prev_repeat_week1, $prev_start_date, $week_date);
                        }
                        if ($prev_repeat_week == 4) {
                            $response1 = $this->getRepeatweekDate($prev_repeat_week1, $prev_repeat_week1, $prev_start_date, $week_date);
                        }

                        if ((!empty($response1) && in_array($response1, $week_date)) || ($prev_repeat_week == 1)) {
                            $repeat_week = $prev_repeat_week;
                            $availability_json =  json_decode($prev_availabilities['availability_json'], true);
                            if (!empty($availability_json)) {
                                $availabilitie_data[0] = '';
                                $availabilitie_data[1] = '';
                                $availabilitie_data[2] = '';
                                $availabilitie_data[3] = '';
                                $availabilitie_data[4] = '';
                                $availabilitie_data[5] = '';
                                $availabilitie_data[6] = '';
                                foreach ($availability_json as $availability_json_data) {
                                    $availability_string1 = '';
                                    $availability_string = [];
                                    foreach ($availability_json_data['periods'] as $periods) {
                                        if ($periods['backgroundColor'] == 'rgba(0, 200, 0, 0.5)') {
                                            $availability_string[] = '<span class="text-success">' . $periods['start'] . ' - ' . $periods['end'] . '</span>';
                                            $availability_string1 .= '<span class="text-danger">' . $periods['start'] . ' - ' . $periods['end'] . '</span><br>';
                                        }
                                        if ($periods['backgroundColor'] == 'rgba(200, 0, 0, 0.5)') {
                                            $availability_string[] = '<span class="text-danger">' . $periods['start'] . ' - ' . $periods['end'] . '</span>';
                                            $availability_string1 .= '<span class="text-danger">' . $periods['start'] . ' - ' . $periods['end'] . '</span><br>';
                                        }
                                    }
                                    $week_days = $availability_json_data['day'];
                                    $availabilitie_data[$week_days] = '<div class="availability_table_box" style="display:none;">' . $availability_string1 . '</div>';
                                }
                            }
                        }
                    }
                }
            }

            $rotas_time = array('', '', '', '', '', '', '');
            $time_counter = 0;
            $count_shift = 0;
            $shift_hour = [];
            foreach ($week_date as $key => $date) {
                // show Rotas(Time Schedule)
                // count weekly hour
                $rotas = \Modules\Rotas\Entities\Rota::select('*', DB::raw('TIMEDIFF(end_time,start_time) as time_between'))->Where('user_id', $id)->Where('rotas_date', $date)->get()->toArray();
                if (!empty($rotas)) {
                    $rotas_time1 = '';
                    foreach ($rotas as $rota) {
                        // show Rotas(Time Schedule)
                        $manage_location = [];
                        $class2 = 0;

                        $border_color = '';

                        $update = '';
                        $shift_unavav_request = '';
                        $delete = '';
                        $notes = '';
                        if ($rota['note'] != "" && $rota['note'] != null) {
                            $notes = '<a href="#" class="action-item only_rotas bg-transparent p-0 ' . $class1 . '" title="' . str_replace('"', "'", $rota['note']) . '"><i class="fas fa-comment"></i></a>';
                        }

                        if (Auth::user()->type == 'company') {
                            if ($rota['shift_status'] != 'enable') {
                                $shift_status  = '';
                                if ($rota['shift_status'] == 'request') {
                                    $shift_status = 'shift_unavelble_reuqest';
                                }
                                if ($rota['shift_status'] == 'disable') {
                                    $shift_status = 'shift_unavelble';
                                }

                                $shift_unavav_request = '<a href="#" class="action-item only_rotas ' . $shift_status . '"  data-size="md" data-ajax-popup="true" data-title="' . __('Unavailability Requested') . '" title="' . __('Shift Unavailability Requested') . '"
                                data-url="' . route('rotas.shift.response', ['id' => $rota['id']]) . '"><i class="fas fa-ban"></i></a>';
                                $update = '';
                            } else {
                                $shift_unavav_request = '';
                                $update = '<a href="#" class="action-item edit_rotas only_rotas bg-transparent p-0" data-ajax-popup="true" data-title="' . __('Edit Shift') . '" data-size="md" data-url="' . route('rota.edit', $rota['id']) . '"  title="' . __('Edit Shift') . '">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>';
                            }
                        }
                        if (Auth::user()->isAbleTo('rota delete')) {
                            $delete = '<span>
                                        <a href="#" title="' . __('Delete') . '" class="delete_rotas_action delete_rotas only_rotas bg-transparent p-0 action-item" data-ajax-popup="false" id="' . $rota['id'] . '" action_url="' . route('rota.destroy', $rota['id']) . '" >
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <form method="POST" action="' . route('rota.destroy', $rota['id']) . '" id="delete-form-' . $rota['id'] . '" class="d-none">
                                        <input name="_method" type="hidden" value="DELETE">
                                        <input name="_token" type="hidden" value="' . csrf_token() . '">
                                    </form></span>';
                        }


                        // shift unavelble request
                        $cancel_shift = '';
                        if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                            $shift_unavelble_by_emp = '';
                            $title_unavability = '';
                            $shift_unavability_ajax = 'data-size="md" data-ajax-popup="true" data-title="' . __('Shift Cancel') . '"  data-url="' . route('rotas.shift.cancel', ['id' => $rota['id']]) . '"';
                            if ($rota['shift_status'] == 'request') {
                                $shift_unavelble_by_emp = 'shift_unavelble';
                                $title_unavability = __('Shift unavailable request');
                                $shift_unavability_ajax = 'data-size="md" data-ajax-popup="true" data-title="' . __('Shift Cancel') . '"  data-url="' . route('rotas.shift.cancel', ['id' => $rota['id']]) . '"';
                            } elseif ($rota['shift_status'] == 'disable') {
                                $shift_unavelble_by_emp = 'shift_unavelble_done';
                                $title_unavability = __('Shift unavailable request approved');
                                $shift_unavability_ajax = '';
                            }
                            $cancel_shift = '<a href="#" class="action-item only_rotas ' . $shift_unavelble_by_emp . ' " title="' . $title_unavability . '" ' . $shift_unavability_ajax . '><i class="fas fa-ban" ></i></a>';
                        }

                        $time_format = (!empty($view_setting_array['company_time_format'])) ? $view_setting_array['company_time_format'] : 12;

                        $timee1 = date('h:i a', strtotime($rota['start_time'])) . ' - ' . date('h:i a', strtotime($rota['end_time']));
                        if ($time_format == 12) {
                            $timee1 = date('h:i a', strtotime($rota['start_time'])) . ' - ' . date('h:i a', strtotime($rota['end_time']));
                        }
                        if ($time_format == 24) {
                            $timee1 = date('H:i', strtotime($rota['start_time'])) . ' - ' . date('H:i', strtotime($rota['end_time']));
                        }

                        $ccc = (!empty($border_color)) ? 'border-color:' . $border_color . ';' : '';
                        $publish_shift = '';
                        if ($rota['publish'] == 0) {
                            $publish_shift = 'opacity-50';
                        }

                        $rotas_time1 .= '<div class=" rounded rotas_time rotas_time1 draggable-class ' . $publish_shift . '" data-rotas-id="' . $rota['id'] . '" style=" ' . $ccc . '">
                                            <b class="text-dark">' . $timee1 . '</b><br>
                                            <span class="text-secondary"> ' . ' </span>
                                            <div class="float-right d-flex">
                                                ' . $notes . '
                                                ' . $update . '
                                                ' . $delete . '
                                                ' . $cancel_shift . '
                                                ' . $shift_unavav_request . '
                                            </div>
                                            <sapn class="clearfix"></span>
                                        </div>';
                        $rotas_time[$key] = $rotas_time1;
                        // count weekly hour

                        if ($rota['shift_status'] != 'disable') {
                            $time_mimim = $rota['time_diff_in_minut'];
                            if ($break_paid != 'paid') {
                                $time_mimim = $rota['time_diff_in_minut'] - $rota['break_time'];
                            }
                            $time_diff = $time_mimim / 60;
                            $time_counter = $time_counter + $time_diff;
                            $count_shift++;
                        } else {
                            $time_diff = 0;
                        }

                        // count weekly hour Shift wise
                        $shift_hour[$rota['designation_id']][] = $time_diff;
                    }
                }
            }

            $table_dateds = [];
            // leave
            if (!empty($employee)) {
                foreach ($week_date as $key => $date) {
                    $leave = Leave::Where('employee_id', $id)->Where('status', 'Approved')->Where('start_date', '<=', $date)->Where('end_date', '>=', $date)->first();

                    $tooltip = '';
                    $badge_class = '';
                    $tooltip2 = '';

                    if (!empty($leave)) {
                        $leave_type = LeaveType::Where('id', $leave->leave_type_id)->where('workspace', getActiveWorkSpace())->first();
                        $badge_class = '';
                        $leave_type = !empty($leave_type) ? $leave_type->title : 'other';
                        $leave_date = date('l d M Y', strtotime($leave['start_date']));
                        $leave_sts = ($leave_display == 'hide') ? 'style="display:none;"' : '';

                        if ($leave['start_date'] != $leave['end_date']) {
                            $leave_date = $leave_date . ' ' . date('l d M Y', strtotime($leave['end_date']));
                        }
                        $tooltip = $employee['name'] . ' ' . __('has') . ' ' . __('Holiday') . ' for ' . $leave_date;
                        $tooltip2 = '<div class="text-center text-info holiday_leave" ' . $leave_sts . ' title="' . $tooltip . '">' . $leave_type . '</div>';
                    }

                    $add = '';
                    if (\Auth::user()->isAbleTo('rota create')) {
                        if ($manage_add_shift != 0) {
                            $add = '<a href="#" class="action-item bg-transparent  add_rotas"  data-size="md" data-ajax-popup="true" data-title="' . __('Add New Shift') . '" title="' . __('Add New Shift') . '" data-url="' . route('rota.create', ['id' => $id, 'date' => $date]) . '"><i class="fas fa-plus" ></i></a>';
                        }
                    }
                    $table_date[] = '<td class="' . $badge_class . ' min_width-170 droppable-class ui-sortable" data-date="' . $date . '" data-id="' . $id . '">
                                                ' . $tooltip2 . '
                                                <button type="button" class="add_shift1 availability_table_boxbtn" > <b>' . $availabilitie_data[$key] . '</b> </button>
                                                ' . $add . '
                                                ' . $rotas_time[$key] . '
                                            </td>';
                }
            }

            // day off

            $profile_data = Employee::select('work_schedule', 'user_id', 'id', 'custom_day_off')->Where('id', $id)->get();
            if (count($profile_data) > 0 && (!empty($profile_data[0]['work_schedule']) || $profile_data[0]['work_schedule'] != null)) {
                $work_schedule = json_decode($profile_data[0]['work_schedule'], true);
                $i = 0;
                foreach ($work_schedule as $days) {
                    if ($days == 'day_off') {
                        $tooltip = __('This is ') . '' . $employee['first_name'] . '  ' . __('Day Off');
                        $day_off_sts = ($day_off == 'hide') ? 'style="display:none;"' : '';
                        $tooltip2 = '<div class="text-center text-danger day_off_leave ws_day_off_leave" ' . $day_off_sts . ' data-date="' . $week_date[$i] . '"  title="' . $tooltip . '">' . __('Day Off') . '</div>';

                        $add = '';
                        if (\Auth::user()->isAbleTo('rota create')) {
                            if ($manage_add_shift != 0) {
                                $add = '<a href="#" class="action-item mr-2 add_rotas"  data-size="md" data-ajax-popup="true" data-title="' . __('Add New Shift') . '" title="' . __('Add New Shift') . '" data-url="' . route('rota.create', ['id' => $id, 'date' => $week_date[$i]]) . '" ><i class="fas fa-plus"></i></a>';
                            }
                        }

                        $table_date[$i] =   '<td class="' . $badge_class . ' min_width-170 droppable-class" data-date="' . $week_date[$i] . '" data-id="' . $id . '">
                                                ' . $tooltip2 . '
                                                <button type="button" class="add_shift1 availability_table_boxbtn" > <b>' . $availabilitie_data[$i] . '</b> </button>
                                                ' . $add . '
                                                ' . $rotas_time[$i] . '
                                            </td>';
                    }
                    $i++;
                }
            }
            // custom_day_off
            $custom_day_off = '';
            $ci = 0;
            $profile_data_df = Employee::select('custom_day_off')->Where('id', $id)->first();
            foreach ($week_date as $key => $date) {
                if (!empty(($profile_data_df['custom_day_off']))) {
                    $custom_day_off = json_decode($profile_data_df['custom_day_off'], true);
                    if (in_array($date, $custom_day_off)) {
                        $tooltip = __('This is ') . '' . $employee['first_name'] . '  ' . __('Day Off');
                        $day_off_sts = ($day_off == 'hide') ? 'style="display:none;"' : '';
                        $tooltip2 = '<div class="text-center text-danger day_off_leave cus_day_off_leave" ' . $day_off_sts . ' data-date="' . $date . '"  title="' . $tooltip . '" data-placement="top" data-html="true" data-toggle="tooltip">' . __('Day Off') . '</div>';

                        $add = '';
                        if (\Auth::user()->isAbleTo('rota create')) {
                            if ($manage_add_shift != 0) {
                                $add = '<a href="#" class="action-item mr-2 add_rotas "  data-size="md" data-ajax-popup="true" data-title="' . __('Add New Shift') . '" title="' . __('Add New Shift') . '" data-url="' . route('rota.create', ['id' => $id, 'date' => $week_date[$ci]]) . '" ><i class="fas fa-plus"></i></a>';
                            }
                        }

                        $table_date[$ci] =   '<td class=" min_width-170 droppable-class" data-date="' . $date . '" data-id="' . $id . '">
                                                ' . $tooltip2 . '
                                                <button type="button" class="add_shift1 availability_table_boxbtn" > <b>' . $availabilitie_data[$ci] . '</b> </button>
                                                ' . $add . '
                                                ' . $rotas_time[$ci] . '
                                            </td>';
                    }
                }
                $ci++;
            }
        }

        $floot_value = 0;
        $hours = 0;
        $minute = 0;
        $working_hour = '';
        if ($emp_hide_rotas_hour == 0) {
            $working_hour = __('0 hours ');
            if (!empty($time_counter)) {
                $hours = floor($time_counter);
                $floot_value = $time_counter - $hours;
                $minute = 60 * $floot_value / 1;
                $working_hour = $hours . ' ' . __('hours') . ' ' . round($minute) . ' ' . __('minute');
            }
            if (!empty($employee_data)) {
                if ($hours > $employee_data['weekly_hour'] && !empty(!empty($employee_data['weekly_hour']))) {
                    $tooltip = $employee['name'] . ' ' . __('is contracted') . ' ' . $employee_data['weekly_hour'] . ' ' . __('hours per week');
                    $working_hour = '<span class="text-danger" data-toggle="tooltip" title="' . $tooltip . '" data-placement="top" data-html="true">' . $working_hour . '</span>, ';
                }
            }
        }

        // count _weekly salary
        if ($id != 0) {
            $employee_data = Employee::Where('user_id', $id)->where('workspace', getActiveWorkSpace())->first();

            $default_salarys_array = [];
            if (!empty($employee_data) &&  !empty($employee_data['default_salary'])) {
                $default_salarys_array = json_decode($employee_data['default_salary'], true);
            }

            $custome_salary_array = [];
            if (!empty($employee_data) &&  !empty($employee_data['custome_salary'])) {
                $custome_salary_array = json_decode($employee_data['custome_salary'], true);
            }

            $total = 0;
            $role_wise_total1 = [];
            $role_wise_total = 0;
            $salary_tooltip = [];
            $salary_tooltip_shift = [];
            $role_wise_total123 = 0;
            $currency_symbol = isset($company_settings['company_currency_symbol']) ? $company_settings['company_currency_symbol'] : '$';

            foreach ($shift_hour as $role_key => $shift_hours) {

                if (!empty($role_key)) {
                    $role_wise_total = 0;
                    if (!empty($custome_salary_array[$role_key]['custom_salary_by_hour'])) {
                        $role_wise_total = $custome_salary_array[$role_key]['custom_salary_by_hour'] * array_sum($shift_hours);
                        $salary_tooltip[$role_key] = round(array_sum($shift_hours)) . '  ' . $currency_symbol . __(' @') . ' ' . $custome_salary_array[$role_key]['custom_salary_by_hour'] . ' ' . __(' per hour');
                    }
                    if (!empty($custome_salary_array[$role_key]['custom_salary_by_shift'])) {
                        if (is_array($shift_hours) && count($shift_hours) > 0) {
                            $role_wise_total += count($shift_hours) * $custome_salary_array[$role_key]['custom_salary_by_shift'];
                        }
                        $salary_tooltip_shift[$role_key] = currency_format_with_sym(++$role_wise_total123) . '  ' . __(' @') . ' ' . $custome_salary_array[$role_key]['custom_salary_by_shift'] . ' ' . __(' per shift');
                    }

                    if ($role_wise_total == 0) {
                        if (!empty($default_salarys_array['salary']) && $default_salarys_array['salary_per'] == 'hourly') {
                            $role_wise_total = array_sum($shift_hours) * $default_salarys_array['salary'];
                            $salary_tooltip[$role_key] = currency_format_with_sym(array_sum($shift_hours)) . ' ' . __(' @') . ' ' . $default_salarys_array['salary'] . '' . __(' per hour');
                        }
                    }
                    $role_wise_total1[$role_key] = $role_wise_total;
                } else {
                    $role_wise_total = 0;
                    if (!empty($default_salarys_array['salary']) && $default_salarys_array['salary_per'] == 'hourly') {
                        $role_wise_total = array_sum($shift_hours) * $default_salarys_array['salary'];
                        $salary_tooltip['no_role'] = currency_format_with_sym(array_sum($shift_hours)) . '  ' . __(' @') . ' ' . $default_salarys_array['salary'] . '' . __(' per hour');
                    }
                    $role_wise_total1['no_role'] = $role_wise_total;
                }
            }
        }

        $salary_tooltip1 = '';
        if (!empty($salary_tooltip)) {
            $salary_tooltip1 = __('Hourly cost') . '&#013;' . implode('&#013;', $salary_tooltip);
        }

        $salary_tooltip_shift1 = '';
        if (!empty($salary_tooltip_shift)) {
            $salary_tooltip1 .= '&#013;' . __('Shift cost') . '&#013;' . implode('&#013;', $salary_tooltip_shift);
        }

        $role_wise_total2 = 0;

        if (!empty($role_wise_total1) && count($role_wise_total1) != 0 && $emp_show_rotas_price == 1) {
            foreach ($role_wise_total1 as $role_wise_total11) {
                $role_wise_total2 = $role_wise_total2 + $role_wise_total11;
            }
            $role_wise_total2 = ' <span title="' . $salary_tooltip1 . '"  >( ' . currency_format_with_sym($role_wise_total2) . ' ) </span>';
        } else {
            $role_wise_total2 = '';
        }


        $admin_idss = 0;
        if (Auth::user()->type == 'company' || !in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
            $admin_idss = 1;
        }
        if (Auth::User()->id && !in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
            $admin_idss = 1;
        }
        $time_counter = ($admin_idss == 1) ? '<div class="planner_working_hour">' . '<span class="theme-text-color">' . $working_hour . ' </span> ' . $role_wise_total2 . '<span class="theme-bg text-white planner_working_hour_main"> $00 </span>' . '</div>' : '';

        return '<tr class="d-nowne" data-user-id="' . $id . '"><td class="text-center">' . $user_profile_img . '<div><span>' . $employee->name . '</span><br>' . $time_counter . ' </div></td>' . $table_date[0] . ' ' . $table_date[1] . ' ' . $table_date[2] . ' ' . $table_date[3] . ' ' . $table_date[4] . ' ' . $table_date[5] . ' ' . $table_date[6] . '</tr>';

        // return '<tr class="d-nowne" data-user-id="' . $id . '"><td class="text-center">' . $user_profile_img . '<div><span>' . $employee->name . '</span><br>' . $time_counter . ' </div></td>' . implode('', $table_date) . $table_date[0] . ' ' . $table_date[1] . ' ' . $table_date[2] . ' ' . $table_date[3] . ' ' . $table_date[4] . ' ' . $table_date[5] . ' ' . $table_date[6] . '</tr>';
    }

    //rota salary

    public static function getCompanyWeeklyUserSalary($week = 0, $create_by = '', $designation_id = 0, $role_id = 0)
    {
        $week = $week * 7;
        $week_date =  \Modules\Rotas\Entities\Rota::getWeekArray('Y-m-d', $week);
        $tr_hour = array('-', '-', '-', '-', '-', '-', '-');
        $tr_cost = array(0, 0, 0, 0, 0, 0, 0);
        $tr_cost1 = [];
        $working_hour1 = 0;
        $profiles_datas = '';
        $weekly_money = 0;
        $user123 = Auth::user();
        $created_by = creatorId();

        $company_settings = getCompanyAllSetting();

        $break_paid = isset($company_settings['break_paid']) ? $company_settings['break_paid'] : 'paid';

        if (!empty($week_date)) {
            foreach ($week_date as $week_date_key => $date) {
                $profiles_datas = Employee::select('id', 'employee_id', 'salary')->where('workspace', getActiveWorkSpace())->where('created_by', creatorId());
                if (!empty($designation_id) && $designation_id != 0) {
                    $profiles_datas->whereRaw('FIND_IN_SET(' . $designation_id . ',designation_id)');
                }
                $profiles_datas = $profiles_datas->get()->toArray();
                if (!empty($profiles_datas)) {
                    $employee = implode(',', array_column($profiles_datas, 'id'));

                    $date_rotas = Rota::select(DB::raw('SUM(time_diff_in_minut) as time_diff_in_minut'), DB::raw('SUM(break_time) as break_time'))->WhereRaw('shift_status != "disable"')->WhereRaw('user_id IN(' . $employee . ')')->WhereRaw('rotas_date = "' . $date . '"');
                    if (!empty($designation_id) && $designation_id != 0) {
                        $date_rotas->WhereRaw('designation_id = ' . $designation_id . '');
                    }
                    $date_rotas = $date_rotas->groupBy('rotas_date')->get()->toArray();
                    $tr_hour[$week_date_key] = '-';
                    if (!empty($date_rotas)) {

                        $working_hour1 += $date_rotas[0]['time_diff_in_minut']  - $date_rotas[0]['break_time'];
                        $time = $date_rotas[0]['time_diff_in_minut'];
                        if ($break_paid != 'paid') {
                            $time = $time - $date_rotas[0]['break_time'];
                        }
                        $time = $time / 60;
                        $h1 = (int)$time;
                        $m1 = $time - (int)$time;
                        $m2 = 60 * $m1 / 1;
                        $m2 = (!empty($m2)) ? $m2 : 00;
                        $total_time =  $h1 . '' . __('Hour ') . ' ' . (int)$m2 . __('Minute');
                        $tr_hour[$week_date_key] = $total_time;
                    }

                    $time_counter = 0;
                    $role_hour = [];
                    $hour_cost = 0;
                }
            }
        }
        if ($working_hour1 > 0) {
            $wtime         = $working_hour1 / 60;
            $wh1           = (int)$wtime;
            $wm1           = $wtime - (int)$wtime;
            $wm2           = 60 * $wm1 / 1;
            $wm2           = (!empty($m2)) ? $wm2 : 00;
            $working_hour1 = $wh1 . '' . __('Hour ') . ' ' . (int)$wm2 . __('Minute');
        }

        if (Auth::user()->type != 'company') {

            $company_setting_data = Employee::Where('id', $created_by)->where('workspace', getActiveWorkSpace())->first();
            if (!(empty($company_setting_data))) {
                $emp_show_rotas_price = isset($company_settings['emp_show_rotas_price']) ? $company_settings['emp_show_rotas_price'] : 0;
            }
        } else {
            $emp_show_rotas_price = 1;
        }
        $tr1 = '<tr class="text-center"> <th><span>' . __('Hours') . '</span> <span>' . $working_hour1 . '</span></th> <td class="min_width-170">' . $tr_hour[0] . '</td> <td class="min_width-170">' . $tr_hour[1] . '</td> <td class="min_width-170">' . $tr_hour[2] . '</td> <td class="min_width-170">' . $tr_hour[3] . '</td> <td class="min_width-170">' . $tr_hour[4] . '</td> <td class="min_width-170">' . $tr_hour[5] . '</td> <td class="min_width-170">' . $tr_hour[6] . '</td> </tr>';


        return $tr1 . '';
    }


}
