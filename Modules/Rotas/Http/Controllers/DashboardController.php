<?php

namespace Modules\Rotas\Http\Controllers;

use App\Models\Order;
use App\Models\Plan;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Dflydev\DotAccessData\Util;
use Modules\Rotas\Entities\Branch;
use Modules\Rotas\Entities\Designation;
use Modules\Rotas\Entities\Rota;
use Modules\Rotas\Entities\RotaUtility;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::check()) {

            $company_settings = getCompanyAllSetting();

            if (Auth::user()->isAbleTo('rotas dashboard manage')) {
                $break_paid = (!empty($company_settings['break_paid'])) ? $company_settings['break_paid'] : 'paid';
                $include_unpublished_shifts = (!empty($company_settings['include_unpublished_shifts'])) ? $company_settings['include_unpublished_shifts'] : 0;

                $userId = Auth::id();

                $created_by = creatorId();
                $userType = Auth::user()->type;
                $var = RotaUtility::manage();
                // $employee = "Modules\\".$var."\\Entities\\Employee"::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->toArray();
                $employee = 'Modules\\' . $var . '\\Entities\\Employee'::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->toArray();

                // $employee = Employee::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->toArray();


                if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {

                    // $employee = "Modules\\".$var."\\Entities\\Employee"::where('user_id', $userId)->get()->toArray();
                    $employee = "Modules\\".$var."\\Entities\\Employee"::where('user_id', $userId)->get()->toArray();
                }
                $employee_id = '0';
                $employee_data = [];
                if (!empty($employee)) {

                    $employee_id = [];
                    $employee_id = implode(',', array_column($employee, 'id'));
                    foreach ($employee as $employee_info) {
                        $employee_data[$employee_info['id']] = $employee_info['name'];
                    }
                }
                $designations = Designation::where('created_by', $created_by)->get()->toArray();
                // show price
                if (Auth::user()->type != 'company') {
                    // $company_setting_data = "Modules\\".$var."\\Entities\\Employee"::Where('id', $created_by)->first();
                    $company_setting_data = "Modules\\".$var."\\Entities\\Employee"::Where('id', $created_by)->first();
                    if (!(empty($company_setting_data))) {

                        $emp_show_rotas_price = (!empty($company_settings['emp_show_rotas_price'])) ? $company_settings['emp_show_rotas_price'] : 0;
                    }
                } else {
                    $emp_show_rotas_price = 1;
                }

                $published_shifts = 1;
                if ($include_unpublished_shifts == 1) {
                    $published_shifts = ' 0 = 0';
                }

                // feed calender
                $rotas_dates = Rota::select('rotas_date')->whereRaw('user_id IN ('.$employee_id.')')->where('publish', $published_shifts)->whereRaw('shift_status != "disable"')->orderBy('rotas_date', 'asc')->groupBy('rotas_date')->get()->toArray();
                $rotas_date = [];
                if (!empty($rotas_dates)) {
                    $rotas_date = [];
                    $rotas_date = array_column($rotas_dates, 'rotas_date');
                }

                $count_role_id = [];
                foreach ($rotas_date as $date) {

                    $rotas = Rota::where('user_id', $employee_id)->whereRaw('rotas_date = "' . $date . '" ')->whereRaw($published_shifts)->whereRaw('shift_status != "disable"')->get()->toArray();
                    if (!empty($rotas)) {
                        foreach ($rotas as $rota) {
                            $profile_data = "Modules\\".$var."\\Entities\\Employee"::whereRaw('employee_id = ' . $rota['user_id'] . ' ')->first();
                            $color        = '#8492a6';
                            $nameee       = '-';
                            $location = "Modules\\".$var."\\Entities\\Designation"::where('id', $rota['designation_id'])->first();
                            $count_role_id[$date][$rota['designation_id']][] = array(
                                'id' => $rota['id'],

                                'designation_id' => $rota['designation_id'],
                                'time_diff_in_minut' => $rota['time_diff_in_minut'],
                                'break_time' => $rota['break_time'],
                                'start_time' => $rota['start_time'],
                                'end_time' => $rota['end_time'],
                                'data' => $employee_data[$rota['user_id']] . ' (' . $rota['start_time'] . ' - ' . $rota['end_time'] . ') ',
                            );
                        }
                    }
                }

                $feed_calender = [];
                $i = -1;
                foreach ($count_role_id as $feed_key => $count_role_ids) {
                    $i++;
                    $html = '';
                    $roll_cnt = '';
                    $cnt_employee1 = 0;
                    $daily_expence = 0;
                    if (!empty($count_role_ids)) {


                        $tooltip = '';
                        $location = '';
                        $css = '';
                        $emp_time = '';
                        $cnt_employee = 0;

                        foreach ($count_role_ids as $feed_designation_id => $feed_designation_data) {

                            $cnt_employee += count($feed_designation_data);
                            $user_data = '';
                            if (!empty($feed_designation_data)) {
                                $time_counter = 0;
                                foreach ($feed_designation_data as $feed_user_id => $feed_user_data) {

                                    $user_data .= $feed_user_data['data'] . '&#013;';
                                    $time_counter = $feed_user_data['time_diff_in_minut'];
                                    if ($break_paid != 'paid') {
                                        $time_counter = $feed_user_data['time_diff_in_minut'] - $feed_user_data['break_time'];
                                    }
                                    $time_counter = $time_counter / 60;

                                    $daily_expence1 = 0;

                                    $default_salarys_array = [];
                                    if (!empty($feed_user_data['default_salary'])) {
                                        $default_salarys_array = json_decode($feed_user_data['default_salary'], true);
                                    }

                                    $custome_salary_array = [];
                                    if (!empty($feed_user_data['custome_salary'])) {
                                        $custome_salary_array = json_decode($feed_user_data['custome_salary'], true);
                                    }

                                    if (!empty($custome_salary_array) && !empty($feed_user_data['role_id'])) {

                                        if (
                                            !empty($custome_salary_array[$feed_user_data['role_id']]) &&
                                            !empty($custome_salary_array[$feed_user_data['role_id']]['custom_salary_by_hour'])
                                        ) {
                                            $daily_expence1 = $time_counter * $custome_salary_array[$feed_user_data['role_id']]['custom_salary_by_hour'];
                                            if (!empty($custome_salary_array[$feed_user_data['role_id']]['custom_salary_by_shift'])) {
                                                $daily_expence1 = $daily_expence1 + $custome_salary_array[$feed_user_data['role_id']]['custom_salary_by_shift'];
                                            }
                                        } elseif (!empty($default_salarys_array)) {
                                            if (!empty($default_salarys_array['salary']) && $default_salarys_array['salary_per'] == 'hourly') {
                                                $daily_expence1 = $time_counter * $default_salarys_array['salary'];
                                            }
                                        }
                                    } else {
                                        if (!empty($default_salarys_array)) {
                                            if (!empty($default_salarys_array['salary']) && $default_salarys_array['salary_per'] == 'hourly') {
                                                $daily_expence1 = $time_counter * $default_salarys_array['salary'];
                                            }
                                        }
                                    }
                                    $daily_expence += $daily_expence1;
                                }
                            }

                            $tooltip .= $user_data;
                        }
                        $roll_cnt .= '<div class="badge1" style="' . $css . ' " title="' . $tooltip . '">' . $cnt_employee . '</div> ';
                        $cnt_employee1 += $cnt_employee;

                        $text_color = (Auth::user()->mode != 'dark') ? 'text-dark' : 'text-white';


                        $feed_calender[$i] = array(
                            'start' => $feed_key,
                            'end' => $feed_key,
                            'className' => 'bg-transparent',
                            'html' => '<div>' . $roll_cnt . '<div class=" ' . $text_color . ' opacity-50 mt-2" style=" font-size: 12px; "> <span title="' . __('Employees') . ' ' . $cnt_employee1 . '"><i class="fas fa-user" aria-hidden="true"></i> <span>' . $cnt_employee1 . '</span></span> &nbsp;&nbsp;  ' . '  </div></div>',
                        );
                    }
                }
                if (Auth::user()->type == 'company') {
                    $current_month_rotas = Rota::whereMonth('rotas_date', date('m'))
                        ->whereYear('rotas_date', date('Y'))
                        ->where('publish', 1)
                        ->where('shift_status', 'enable')
                        ->where('create_by', $created_by)
                        ->where('workspace', getActiveWorkSpace())
                        ->OrderBy('rotas_date', 'ASC')
                        ->OrderBy('start_time', 'ASC')
                        ->get();
                    $feed_calender = json_encode($feed_calender);
                    return view('rotas::dashboard.index', compact('feed_calender', 'designations', 'current_month_rotas'));
                } else {
                    $current_month_rotas = Rota::whereMonth('rotas_date', date('m'))
                        ->whereYear('rotas_date', date('Y'))
                        ->where('publish', 1)
                        ->where('shift_status', 'enable')
                        ->where('user_id', $employee_id)
                        ->where('workspace', getActiveWorkSpace())
                        ->OrderBy('rotas_date', 'ASC')
                        ->OrderBy('start_time', 'ASC')
                        ->get();
                    $feed_calender = json_encode($feed_calender);
                    return view('rotas::dashboard.index', compact('feed_calender', 'designations', 'current_month_rotas'));
                }
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function location_filter(Request $request)
    {
        $var = RotaUtility::manage();
        $location_id = $request->location_id;

        $userId = Auth::id();
        $user = Auth::user();
        $created_by = creatorId();
        $userType = Auth::user()->type;


        $break_paid = (!empty(company_setting('break_paid'))) ? company_setting('break_paid') : 'paid';
        $include_unpublished_shifts = (!empty(company_setting('include_unpublished_shifts'))) ? company_setting('include_unpublished_shifts') : 0;

        // show price
        if (Auth::user()->type != 'company') {
            $company_setting_data = "Modules\\".$var."\\Entities\\Employee"::Where('id', $created_by)->first();
            if (!(empty($company_setting_data))) {

                $emp_show_rotas_price = (!empty(company_setting('emp_show_rotas_price'))) ? company_setting('emp_show_rotas_price') : 0;
            }
        } else {
            $emp_show_rotas_price = 1;
        }

        $published_shifts = ' publish = 1 ';
        if ($include_unpublished_shifts == 1) {
            $published_shifts = ' 0 = 0';
        }

        $employee = "Modules\\".$var."\\Entities\\Employee"::where('created_by', $created_by)->orwhere('id', $created_by)->get()->toArray();

        if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
            $employee = "Modules\\".$var."\\Entities\\Employee"::where('user_id', $userId)->get()->toArray();
        }
        $employee_id = '';
        $employee_data = [];
        if (!empty($employee)) {
            $employee_id = [];
            $employee_id = implode(',', array_column($employee, 'id'));
            foreach ($employee as $employee_info) {
                $employee_data[$employee_info['id']] = $employee_info['name'];
            }
        }

        $rotas_dates = Rota::select('rotas_date')->whereRaw('user_id IN (' . $employee_id . ')')->whereRaw('shift_status != "disable"')->whereRaw($published_shifts)->groupBy('rotas_date')->get()->toArray();

        if (!empty($location_id)) {
            $rotas_dates = Rota::select('rotas_date')->whereRaw('user_id IN (' . $employee_id . ')')->whereRaw('designation_id = ' . $location_id . ' ')->whereRaw('shift_status != "disable"')->whereRaw($published_shifts)->groupBy('rotas_date')->get()->toArray();
        }
        $rotas_date = [];
        if (!empty($rotas_dates)) {
            $rotas_date = [];
            $rotas_date = array_column($rotas_dates, 'rotas_date');
        }

        $count_role_id = [];
        foreach ($rotas_date as $date) {

            $rotas = Rota::whereRaw('user_id IN (' . $employee_id . ')')->whereRaw('rotas_date = "' . $date . '" ')->whereRaw($published_shifts)->whereRaw('shift_status != "disable"')->get()->toArray();
            if (!empty($location_id)) {
                $rotas = Rota::whereRaw('user_id IN (' . $employee_id . ')')->whereRaw('rotas_date = "' . $date . '" ')->whereRaw($published_shifts)->whereRaw('shift_status != "disable"')->whereRaw('designation_id = ' . $location_id . ' ')->get()->toArray();
            }
            if (!empty($rotas)) {
                $role_id = [];
                foreach ($rotas as $rota) {
                    $profile_data = "Modules\\".$var."\\Entities\\Employee"::whereRaw('employee_id = ' . $rota['user_id'] . ' ')->first();
                    $color = '#8492a6';
                    $nameee = '-';
                    $roll_ids = '-';

                    $location = "Modules\\".$var."\\Entities\\Designation"::whereRaw('id = ' . $rota['designation_id'] . ' ')->first();

                    $count_role_id[$date][$rota['designation_id']][] = array(
                        'id'                => $rota['id'],
                        'designation_id'       => $rota['designation_id'],
                        'time_diff_in_minut' => $rota['time_diff_in_minut'],
                        'break_time'        => $rota['break_time'],
                        'start_time'        => $rota['start_time'],
                        'end_time'          => $rota['end_time'],
                        'data'              => $employee_data[$rota['user_id']] . ' (' . $rota['start_time'] . ' - ' . $rota['end_time'] . ') ' . $location->name,
                    );
                }
            }
        }
        $feed_calender = [];
        $i = -1;

        foreach ($count_role_id as $feed_key => $count_role_ids) {
            $i++;
            $html = '';
            $roll_cnt = '';
            $cnt_employee1 = 0;
            $daily_expence = 0;
            if (!empty($count_role_ids)) {

                $tooltip = '';
                $location = '';
                $css = '';
                $emp_time = '';
                $cnt_employee = 0;

                foreach ($count_role_ids as $feed_designation_id => $feed_designation_data) {
                    $cnt_employee += count($feed_designation_data);

                    $user_data = '';
                    if (!empty($feed_designation_data)) {
                        $time_counter = 0;
                        foreach ($feed_designation_data as $feed_user_id => $feed_user_data) {
                            $user_data .= $feed_user_data['data'] . '&#013;';
                            $time_counter = $feed_user_data['time_diff_in_minut'];
                            if ($break_paid != 'paid') {
                                $time_counter = $feed_user_data['time_diff_in_minut'] - $feed_user_data['break_time'];
                            }

                            $time_counter = $time_counter / 60;

                            $daily_expence1 = 0;

                            $default_salarys_array = [];
                            if (!empty($feed_user_data['default_salary'])) {
                                $default_salarys_array = json_decode($feed_user_data['default_salary'], true);
                            }

                            $custome_salary_array = [];
                            if (!empty($feed_user_data['custome_salary'])) {
                                $custome_salary_array = json_decode($feed_user_data['custome_salary'], true);
                            }

                            if (!empty($custome_salary_array) && !empty($feed_user_data['role_id'])) {
                                if (
                                    !empty($custome_salary_array[$feed_user_data['role_id']]) &&
                                    !empty($custome_salary_array[$feed_user_data['role_id']]['custom_salary_by_hour'])
                                ) {
                                    $daily_expence1 = $time_counter * $custome_salary_array[$feed_user_data['role_id']]['custom_salary_by_hour'];
                                    if (!empty($custome_salary_array[$feed_user_data['role_id']]['custom_salary_by_shift'])) {
                                        $daily_expence1 = $daily_expence1 + $custome_salary_array[$feed_user_data['role_id']]['custom_salary_by_shift'];
                                    }
                                } elseif (!empty($default_salarys_array)) {
                                    if (!empty($default_salarys_array['salary']) && $default_salarys_array['salary_per'] == 'hourly') {
                                        $daily_expence1 = $time_counter * $default_salarys_array['salary'];
                                    }
                                }
                            } else {
                                if (!empty($default_salarys_array)) {
                                    if (!empty($default_salarys_array['salary']) && $default_salarys_array['salary_per'] == 'hourly') {
                                        $daily_expence1 = $time_counter * $default_salarys_array['salary'];
                                    }
                                }
                            }
                            $daily_expence += $daily_expence1;
                        }
                    }
                    $tooltip .= $user_data;
                }
                $cnt_employee . '</div> ';
                $cnt_employee1 += $cnt_employee;
                // }

                $text_color = (Auth::user()->mode != 'dark') ? 'text-dark' : 'text-white';
                $priceee  = ($emp_show_rotas_price == 1) ? ' <span><span>' . currency_format_with_sym($daily_expence) . '</span></span>' : '';
                $feed_calender[$i] = array(
                    'start' => $feed_key,
                    'end' => $feed_key,
                    'className' => 'bg-transparent',
                    'html' => '<div><div class="' . $text_color . ' opacity-50 mt-2" style=" font-size: 13px; "> <span data-toggle="tooltip" title="' . __('Employees') . ' ' . $cnt_employee1 . '"><i class="fas fa-user" aria-hidden="true"></i> <span>' . $cnt_employee1 . '</span></span> &nbsp;&nbsp;  ' . $priceee . '  </div></div>',
                );
            }
        }
        return $feed_calender;
    }


    public function dayView()
    {
        $var = RotaUtility::manage();

        $date_formate = Rota::CompanyDateFormat('Y-m-d');
        $today        = date($date_formate);
        $today1       = date('Y-m-d');

        $userId     = Auth::id();
        $user       = Auth::user();
        $created_by = creatorId();

        $employee = "Modules\\".$var."\\Entities\\Employee"::where('created_by', $created_by)->get()->toArray();

        if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
            $employee = "Modules\\".$var."\\Entities\\Employee"::where('user_id', $userId)->get()->toArray();
        }


        $employee_id   = '';
        $employee_data = [];
        if (!empty($employee)) {


            $employee_id = [];
            $employee_id = implode(',', array_column($employee, 'id'));

            foreach ($employee as $employee_info) {
                $employee_data[$employee_info['id']] = $employee_info['name'];
            }
        }
        $break_paid                 = (!empty(company_setting('break_paid'))) ? company_setting('break_paid') : 'paid';
        $include_unpublished_shifts = (!empty(company_setting('include_unpublished_shifts'))) ? company_setting('include_unpublished_shifts') : 0;

        // show price
        if (Auth::user()->type != 'company') {
            $company_setting_data = "Modules\\".$var."\\Entities\\Employee"::Where('id', $created_by)->first();
            if (!(empty($company_setting_data))) {
                $emp_show_rotas_price = (!empty(company_setting('emp_show_rotas_price'))) ? company_setting('emp_show_rotas_price') : 0;
            }
        } else {
            $emp_show_rotas_price = 1;
        }

        $published_shifts = ' publish = 1 ';
        if ($include_unpublished_shifts == 1) {
            $published_shifts = ' 0 = 0';
        }

        $loaction_option = [];
        $locations       = [];
        if (Auth::user()->type == 'company') {
            $locations = "Modules\\".$var."\\Entities\\Designation"::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();
        }

        if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
            $locations_id = "Modules\\".$var."\\Entities\\Employee"::where('user_id', $userId)->first();
            if (!empty($locations_id->branch_id)) {
                $locations = "Modules\\".$var."\\Entities\\Designation"::where('id', $locations_id->designation_id . ')')->get();
            }
        }

        $location_option = [];
        foreach ($locations as $location) {
            $location_option[$location->id] = $location->name;
        }
        $rotas = Rota::whereDate('rotas_date', $today1)->where('user_id', $employee_id)->whereRaw($published_shifts)->whereRaw('shift_status != "disable"')->orderBy('rotas_date', 'asc')->get();


        return view('rotas::dashboard.dayview', compact('today', 'rotas', 'employee_data', 'location_option'));
    }

    public function dayview_filter(Request $request)
    {
        $var = RotaUtility::manage();

        $date          = $request->date;
        $date_type     = $request->date_type;
        $emp_name      = $request->emp_name;
        $loaction_name = $request->loaction_name;

        $userId     = Auth::id();
        $user       = Auth::user();
        $created_by = creatorId();


        if ($date_type == 'add_date') {
            $date = date('Y-m-d', strtotime($date . ' +1 day'));
        } else if ($date_type == 'sub_date') {
            $date = date('Y-m-d', strtotime($date . ' -1 day'));
        }

        $where = [];
        if (!empty($emp_name)) {
            $employee = $emp_name;
        } else {
            if (Auth::user()->type == 'company') {
                $employee = "Modules\\".$var."\\Entities\\Employee"::where('created_by', $created_by)->orwhere('id', $created_by)->pluck('id')->toArray();
            } else {

                $employee = "Modules\\".$var."\\Entities\\Employee"::where('user_id', $userId)->pluck('id')->toArray();
            }
        }

        $loc_wh = ' 0 = 0 ';
        if (!empty($loaction_name)) {
            $loaction_arr = implode(',', $loaction_name);
            if (!empty($loaction_arr)) {
                $loc_wh = 'designation_id IN (' . $loaction_arr . ')';
            }
        }
        $include_unpublished_shifts = (!empty(company_setting('include_unpublished_shifts'))) ? company_setting('include_unpublished_shifts') : 0;

        $published_shifts = ' publish = 1 ';
        if ($include_unpublished_shifts == 1) {
            $published_shifts = ' 0 = 0';
        }
        $rotas = Rota::whereDate('rotas_date', $date)->whereIn('user_id', $employee)->whereRaw($loc_wh)->whereRaw($published_shifts)->whereRaw('shift_status != "disable"')->orderBy('rotas_date', 'asc')->get();
        $date2 = 'select * from `rotas` where date(`rotas_date`) = "' . $date . '" and `user_id` in (' . implode(",", $employee) . ') and  0 = 0  and  publish = 1  and shift_status != "disable" order by `rotas_date` asc';
        $rotas = Rota::whereDate('rotas_date', $date)->whereIn('user_id', $employee)->whereRaw($loc_wh)->whereRaw($published_shifts)->whereRaw('shift_status != "disable"')->orderBy('rotas_date', 'asc')->get();


        $returnHTML           = view('rotas::dashboard.dayview_filter', compact('rotas'))->render();
        $return['returnHTML'] = $returnHTML;
        $return['date']       = $date;
        $return['date2']      = $date2;

        return response()->json($return);
    }

    public function userView()
    {
        $var = RotaUtility::manage();

        $date_formate   = Rota::CompanyDateFormat('Y-m-d');
        $today          = date($date_formate);
        $today1         = date('Y-m-d');
        $cur_month_year = date('m-Y');
        $cur_month      = date('m');
        $cur_year       = date('Y');

        $userId     = Auth::id();
        $user       = Auth::user();
        $created_by = creatorId();

        $employee = "Modules\\".$var."\\Entities\\Employee"::where('created_by', $created_by)->get()->toArray();

        if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
            $employee = "Modules\\".$var."\\Entities\\Employee"::where('user_id', $userId)->get()->toArray();
        }


        $employee_data = [];
        $employee_id = [];
        if (!empty($employee)) {
            foreach ($employee as $employee_info) {
                $employee_data[$employee_info['id']] = $employee_info['name'];
            }
        }
        if (Auth::user()->type == 'company') {
            $employee_id = "Modules\\".$var."\\Entities\\Employee"::where('created_by', $created_by)->orwhere('id', $created_by)->pluck('id')->toArray();
        } else {
            $employee_id = "Modules\\".$var."\\Entities\\Employee"::where('user_id', $userId)->pluck('id')->toArray();
        }

        $break_paid                 = (!empty(company_setting('break_paid'))) ? company_setting('break_paid') : 'paid';
        $include_unpublished_shifts = (!empty(company_setting('include_unpublished_shifts'))) ? company_setting('include_unpublished_shifts') : 0;

        // show price
        if (Auth::user()->type != 'company') {
            $company_setting_data = "Modules\\".$var."\\Entities\\Employee"::Where('id', $created_by)->first();
            if (!(empty($company_setting_data))) {
                $emp_show_rotas_price = (!empty(company_setting('emp_show_rotas_price'))) ? company_setting('emp_show_rotas_price') : 0;
            }
        } else {
            $emp_show_rotas_price = 1;
        }

        $published_shifts = ' publish = 1 ';
        if ($include_unpublished_shifts == 1) {
            $published_shifts = ' 0 = 0';
        }

        $loaction_option = [];
        $locations       = [];
        if (Auth::user()->type == 'company') {
            $locations = "Modules\\".$var."\\Entities\\Designation"::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();
        }
        if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
            $locations_id = "Modules\\".$var."\\Entities\\Employee"::where('user_id', $userId)->first();
            if (!empty($locations_id->branch_id)) {
                $locations = "Modules\\".$var."\\Entities\\Designation"::where('id', $locations_id->designation_id . ')')->get();
            }
        }

        $location_option = [];
        foreach ($locations as $location) {
            $location_option[$location->id] = $location->name;
        }

        $rotas = Rota::whereMonth('rotas_date', $cur_month)->whereYear('rotas_date', $cur_year)->whereIn('user_id', $employee_id)->whereRaw($published_shifts)->whereRaw('shift_status != "disable"')->orderBy('rotas_date', 'asc')->get();

        return view('rotas::dashboard.Userview', compact('today', 'rotas', 'employee_data', 'location_option', 'cur_month_year', 'cur_month', 'cur_year'));
    }

    public function userviewfilter(Request $request)
    {
        $var = RotaUtility::manage();

        $date          = $request->date;
        $date_type     = $request->date_type;
        $emp_name      = $request->emp_name;
        $loaction_name = $request->loaction_name;
        $role_name     = $request->role_name;

        $userId     = Auth::id();
        $user       = Auth::user();
        $created_by = creatorId();
        $cur_month  = date('m');
        $cur_year   = date('Y');

        if (!empty($date)) {
            $cur_month = date("m", strtotime($date . '-01'));
            $cur_year  = date("Y", strtotime($date . '-01'));
        }

        if ($date_type == 'add_date') {
            $date = date('Y-m-d', strtotime($date . ' +1 day'));
        } else if ($date_type == 'sub_date') {
            $date = date('Y-m-d', strtotime($date . ' -1 day'));
        }

        $where = [];
        if (!empty($emp_name)) {
            $employee = $emp_name;
        } else {
            if (Auth::user()->type == 'company') {
                $employee = "Modules\\".$var."\\Entities\\Employee"::where('created_by', $created_by)->orwhere('id', $created_by)->pluck('id')->toArray();
            } else {
                $employee = "Modules\\".$var."\\Entities\\Employee"::where('user_id', $userId)->pluck('id')->toArray();
            }
        }

        $loc_wh = ' 0 = 0 ';
        if (!empty($loaction_name)) {
            $loaction_arr = implode(',', $loaction_name);
            if (!empty($loaction_arr)) {
                $loc_wh = 'designation_id IN (' . $loaction_arr . ')';
            }
        }
        $include_unpublished_shifts = (!empty(company_setting('include_unpublished_shifts'))) ? company_setting('include_unpublished_shifts') : 0;

        $published_shifts = ' publish = 1 ';
        if ($include_unpublished_shifts == 1) {
            $published_shifts = ' 0 = 0';
        }

        $rotas = Rota::whereMonth('rotas_date', $cur_month)->whereYear('rotas_date', $cur_year)->whereIn('user_id', $employee)->whereRaw($loc_wh)->whereRaw($published_shifts)->whereRaw('shift_status != "disable"')->orderBy('rotas_date', 'asc')->get();


        $returnHTML           = view('rotas::dashboard.Userview_filter', compact('rotas'))->render();
        $return['returnHTML'] = $returnHTML;

        return response()->json($return);
    }
}
