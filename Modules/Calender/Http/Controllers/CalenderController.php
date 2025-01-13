<?php

namespace Modules\Calender\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use Modules\Calender\Entities\CalenderUtility;

class CalenderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (Auth::user()->isAbleTo('calander manage')) {
            $events = [];
            $type = [];
            $type['due_invoice'] = 'Due Invoice';
            $type['due_bill'] = 'Due Bill';
            $type['holiday'] = 'Holiday';
            $type['event'] = 'Event';
            $type['task'] = 'Deal Task';
            $type['projecttask'] = 'Project Due Task';
            $type['meeting'] = 'Meeting';
            $type['zoom_meeting'] = 'Zoom Meeting';
            $type['lead'] = 'Lead';
            $type['call'] = 'Call';
            $type['leave'] = 'Leave';
            $type['rotas'] = 'Rota';
            $type['interview_schedule'] = 'Interview Schedule';
            $type['appointment'] = 'Appointment';
            $type['vcard_appointment'] = 'vCard Appointment';
            $type['work_order'] = 'Work Order';
            $type['google_meet'] = 'Google Meet';
            $type['todo'] = 'To Do';

            if ($request->get('calendar_type') == 'google_calendar') {
                $events = CalenderUtility::getCalendarData($request->type);
                if (isset($events['error'])) {
                    return redirect()->back()->with('error', $events['error']);
                }
            } else {
                if (module_is_active('Hrm')) {
                    if ($request->type == "holiday" || $request->type == null) {
                        $holidays = \Modules\Hrm\Entities\Holiday::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace());
                        if (!empty($request->date)) {

                            $date_range = explode(' to ', $request->date);
                            $holidays->where('start_date', '>=', $date_range[0]);
                            $holidays->where('end_date', '<=', $date_range[1]);
                        }
                        $holidays = $holidays->get();
                        foreach ($holidays as $key => $holiday) {
                            $data = [
                                'title' => $holiday->occasion,
                                'start' => $holiday->start_date,
                                'end' => $holiday->end_date,
                                'className' => 'event-danger',
                            ];
                            array_push($events, $data);
                        }
                    }
                    if ($request->type == "event" || $request->type == null) {
                        $hrm_events = \Modules\Hrm\Entities\Event::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace());
                        if (!empty($request->date)) {
                            $date_range = explode(' to ', $request->date);
                            $hrm_events->where('start_date', '>=', $date_range[0]);
                            $hrm_events->where('end_date', '<=', $date_range[1]);
                        }
                        $hrm_events = $hrm_events->get();
                        foreach ($hrm_events as $key => $hrm_event) {
                            $data = [
                                'id' => $hrm_event->id,
                                'title' => $hrm_event->title,
                                'start' => $hrm_event->start_date,
                                'end' => $hrm_event->end_date,
                                'className' => $hrm_event->color,
                            ];
                            array_push($events, $data);
                        }
                    }
                    if ($request->type == "leave" || $request->type == null) {
                        $hrm_leaves = \Modules\Hrm\Entities\Leave::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace());
                        if (!empty($request->date)) {
                            $date_range = explode(' to ', $request->date);
                            $hrm_leaves->where('start_date', '>=', $date_range[0]);
                            $hrm_leaves->where('end_date', '<=', $date_range[1]);
                        }
                        $hrm_leaves = $hrm_leaves->get();

                        foreach ($hrm_leaves as $key => $hrm_leave) {
                            $data = [
                                'id' => $hrm_leave->id,
                                'title' => $hrm_leave->leave_reason,
                                'start' => $hrm_leave->start_date,
                                'end' => $hrm_leave->end_date,
                                'className' => 'event-primary',
                            ];
                            array_push($events, $data);
                        }
                    }
                } else {
                    unset($type['holiday']);
                    unset($type['event']);
                    unset($type['leave']);
                }

                if (module_is_active('Lead')) {
                    if ($request->type == "task" || $request->type == null) {
                        $tasks = \Modules\Lead\Entities\DealTask::where('workspace', getActiveWorkSpace());
                        if (!empty($request->date)) {
                            $date_range = explode(' to ', $request->date);
                            $tasks->where('date', '>=', $date_range[0]);
                        }
                        $tasks = $tasks->get();
                        foreach ($tasks as $key => $task) {
                            $data = [
                                'title' => $task->name,
                                'start' => $task->date,
                                'className' => ($task->status) ? 'event-success border-success' : 'event-warning border-warning',
                            ];
                            array_push($events, $data);
                        }
                    }
                } else {
                    unset($type['task']);
                }
                if (module_is_active('Account') || module_is_active('Taskly')) {
                    if ($request->type == "due_invoice" || $request->type == null) {
                        if (module_is_active('Account') && module_is_active('Taskly')) {
                            $invoices = \App\Models\Invoice::where('workspace', getActiveWorkSpace())->where('created_by', '=', creatorId());
                        } elseif (module_is_active('Taskly')) {
                            $invoices = \App\Models\Invoice::where('workspace', getActiveWorkSpace())->where('created_by', '=', creatorId())->where('invoice_module', 'taskly');
                        } elseif (module_is_active('Account')) {
                            $invoices = \App\Models\Invoice::where('workspace', getActiveWorkSpace())->where('created_by', '=', creatorId())->where('invoice_module', 'account');
                        }
                        $invoices = $invoices->get();
                        foreach ($invoices as $key => $invoice) {
                            $data = [
                                'title' => $invoice->customer->name,
                                'start' => $invoice->due_date,
                                'className' => 'event-warning border-warning',
                            ];

                            array_push($events, $data);
                        }
                    }
                } else {
                    unset($type['due_invoice']);
                }
                if (module_is_active('Account')) {
                    if ($request->type == "due_bill" || $request->type == null) {
                        $bills = \Modules\Account\Entities\Bill::where('workspace', getActiveWorkSpace())->where('created_by', '=', creatorId());
                        $bills = $bills->get();
                        foreach ($bills as $key => $bill) {
                            $data = [
                                'title' => $bill->vendor->name,
                                'start' => $bill->due_date,
                                'className' => 'event-warning',
                            ];

                            array_push($events, $data);
                        }
                    }
                } else {
                    unset($type['due_bill']);
                }

                if (module_is_active('Taskly')) {
                    if ($request->type == "projecttask" || $request->type == null) {
                        $project_tasks = \Modules\Taskly\Entities\Task::where('workspace', getActiveWorkSpace());
                        $project_tasks = $project_tasks->get();
                        foreach ($project_tasks as $key => $project_task) {
                            $data = [
                                'id' => $project_task->id,
                                'title' => $project_task->title,
                                'start' => $project_task->due_date,
                                'className' => ($project_task->status) ? 'event-success border-success' : 'event-warning border-warning',
                            ];
                            array_push($events, $data);
                        }
                    }
                } else {
                    unset($type['projecttask']);
                }
                if (module_is_active('Sales')) {
                    if ($request->type == "meeting" || $request->type == null) {
                        $meetings = \Modules\Sales\Entities\Meeting::where('workspace', getActiveWorkSpace());
                        if (!empty($request->date)) {
                            $date_range = explode(' to ', $request->date);
                            $meetings->where('start_date', '>=', $date_range[0]);
                            $meetings->where('end_date', '<=', $date_range[1]);
                        }
                        $meetings = $meetings->get();
                        foreach ($meetings as $key => $meeting) {
                            $data = [
                                'id' => $meeting->id,
                                'title' => $meeting->name,
                                'start' => $meeting->start_date,
                                'end' => $meeting->end_date,
                                'className' => ($meeting->status) ? 'event-success border-success' : 'event-warning border-warning',
                            ];

                            array_push($events, $data);
                        }
                    }
                    if ($request->type == "call" || $request->type == null) {
                        $calls = \Modules\Sales\Entities\Call::where('workspace', getActiveWorkSpace());
                        if (!empty($request->date)) {
                            $date_range = explode(' to ', $request->date);
                            $calls->where('start_date', '>=', $date_range[0]);
                            $calls->where('end_date', '<=', $date_range[1]);
                        }
                        $calls = $calls->get();

                        foreach ($calls as $key => $call) {
                            $data = [
                                'id' => $call->id,
                                'title' => $call->name,
                                'start' => $call->start_date,
                                'end' => $call->end_date,
                                'className' => 'event-success border-success',
                            ];

                            array_push($events, $data);
                        }
                    }
                } else {
                    unset($type['meeting']);
                    unset($type['call']);
                }

                if (module_is_active('ZoomMeeting')) {

                    if ($request->type == "zoom_meeting" || $request->type == null) {

                        $zoommeetings = \Modules\ZoomMeeting\Entities\ZoomMeeting::where('workspace_id', getActiveWorkSpace());
                        if (!empty($request->date)) {
                            $date_range = explode(' to ', $request->date);
                            $zoommeetings->where('start_date', '>=', $date_range[0]);
                            $zoommeetings->where('end_date', '<=', $date_range[1]);
                        }
                        $zoommeetings = $zoommeetings->get();
                        foreach ($zoommeetings as $key => $zoommeeting) {
                            $data = [
                                'id' => $zoommeeting->id,
                                'title' => $zoommeeting->title,
                                'start' => $zoommeeting->start_date,
                                'end' => $zoommeeting->end_date,
                                'className' => ($zoommeeting->status) ? 'event-success border-success' : 'event-warning border-warning',
                            ];
                            array_push($events, $data);
                        }
                    }
                } else {
                    unset($type['zoom_meeting']);
                }

                if (module_is_active('Lead')) {
                    if ($request->type == "lead" || $request->type == null) {
                        $leads = \Modules\Lead\Entities\Lead::where('workspace_id', getActiveWorkSpace());
                        if (!empty($request->date)) {
                            $date_range = explode(' to ', $request->date);
                            $leads->where('date', '>=', $date_range[0]);
                        }
                        $leads = $leads->get();
                        foreach ($leads as $key => $lead) {
                            $data = [
                                'id' => $lead->id,
                                'title' => $lead->name,
                                'start' => $lead->date,
                                'className' => 'event-primary',
                            ];
                            array_push($events, $data);
                        }
                    }
                } else {
                    unset($type['lead']);
                }
                if (module_is_active('Recruitment')) {
                    if ($request->type == "interview_schedule" || $request->type == null) {
                        $interview_schedules = \Modules\Recruitment\Entities\InterviewSchedule::where('workspace', getActiveWorkSpace());
                        if (!empty($request->date)) {
                            $date_range = explode(' to ', $request->date);
                            $interview_schedules->where('date', '>=', $date_range[0]);
                        }
                        $interview_schedules = $interview_schedules->get();
                        foreach ($interview_schedules as $key => $schedule) {
                            $data = [
                                'id' => $schedule->id,
                                'title' => !empty($schedule->applications) ? (!empty($schedule->applications->jobs) ? $schedule->applications->jobs->title : '') : '',
                                'start' => $schedule->date,
                                'className' => 'event-primary',
                            ];
                            array_push($events, $data);
                        }
                    }
                } else {
                    unset($type['interview_schedule']);
                }
                if (module_is_active('Rotas')) {
                    if ($request->type == "rotas" || $request->type == null) {
                        $rotas = \Modules\Rotas\Entities\Rota::where('workspace', getActiveWorkSpace());
                        if (!empty($request->date)) {
                            $date_range = explode(' to ', $request->date);
                            $rotas->where('date', '>=', $date_range[0]);
                        }
                        $rotas = $rotas->get();
                        foreach ($rotas as $key => $rota) {
                            $data = [
                                'id' => $rota->id,
                                'title' => $rota->note,
                                'start' => $rota->rotas_date,
                                'className' => 'event-primary',
                            ];
                            array_push($events, $data);
                        }
                    }
                } else {
                    unset($type['rotas']);
                }
                if (module_is_active('Appointment')) {
                    if ($request->type == "appointment" || $request->type == null) {
                        $schedule = \Modules\Appointment\Entities\Schedule::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->where('status', 'Approved');

                        if (!empty($request->date)) {
                            $date_range = explode(' to ', $request->date);
                            $schedule->where('start_time', '>=', $date_range[0]);
                            $schedule->where('end_time', '<=', $date_range[1]);
                        }
                        $schedule = $schedule->get();
                        foreach ($schedule as $key => $schedule) {
                            $data = [
                                'title' => $schedule->appointment->name,
                                'start' => $schedule->date,
                                'end' => $schedule->date,
                                'className' => ($schedule->status) ? 'event-success border-success' : 'event-warning border-warning',
                            ];
                            array_push($events, $data);
                        }
                    }
                } else {
                    unset($type['appointment']);
                }

                if (module_is_active('VCard')) {
                    if ($request->type == "vcard_appointment" || $request->type == null) {

                        $appointents = \Modules\VCard\Entities\AppointmentDetails::where('created_by', creatorId())->where('workspace', getActiveWorkSpace());
                        $appointents = $appointents->get();
                        foreach ($appointents as $key => $appointent) {
                            $time = explode('-', $appointent->time);
                            $stime = isset($time[0]) ? trim($time[0]) . ':00' : '00:00:00';
                            $etime = isset($time[1]) ? trim($time[1]) . ':00' : '00:00:00';
                            $start_date = date("Y-m-d", strtotime($appointent->date)) . ' ' . $stime;
                            $end_date = date("Y-m-d", strtotime($appointent->date)) . ' ' . $etime;
                            $data = [
                                'title' => $appointent->name . ' - ' . $appointent->getBussinessName(),
                                'start' => $start_date,
                                'end' => $end_date,
                                'className' => 'event-danger',
                            ];
                            array_push($events, $data);
                        }
                    }
                } else {
                    unset($type['vcard_appointment']);
                }

                if (module_is_active('CMMS')) {
                    if ($request->type == "work_order" || $request->type == null) {
                        $work_order = \Modules\CMMS\Entities\Workorder::where('workspace', getActiveWorkSpace());

                        $work_order = $work_order->get();
                        foreach ($work_order as $key => $workorder) {
                            $data = [
                                'id' => $workorder->id,
                                'title' => $workorder->wo_name,
                                'start' => date("Y-m-d"),
                                'end' => $workorder->date,
                                'className' =>  'event-primary',
                            ];
                            array_push($events, $data);
                        }
                    }
                } else {
                    unset($type['rotas']);
                }

                if (module_is_active('GoogleMeet')) {
                    if ($request->type == "google_meet" || $request->type == null) {
                        $Meetings = \Modules\GoogleMeet\Entities\GoogleMeet::where('created_by', '=', creatorId())->where('workspace_id', getActiveWorkSpace());
                        $Meetings = $Meetings->get();
                        foreach ($Meetings as $Meeting) {
                            $data = [
                                'id' => $Meeting->id,
                                'title' => $Meeting->title,
                                'start' => $Meeting->start_date,
                                'end' => date('Y-m-d', strtotime(+$Meeting->duration . "minutes", strtotime($Meeting->start_date))),
                                'className' =>  'event-primary',
                            ];
                            array_push($events, $data);
                        }
                    }
                } else {
                    unset($type['google_meet']);
                }
                if (module_is_active('ToDo')) {
                    if ($request->type == "todo" || $request->type == null) {
                        $schedule = \Modules\ToDo\Entities\ToDo::where('created_by', '=', creatorId())->where('workspace_id', getActiveWorkSpace());

                        if (!empty($request->date)) {
                            $date_range = explode(' to ', $request->date);
                            $schedule->where('start_time', '>=', $date_range[0]);
                            $schedule->where('due_date', '<=', $date_range[1]);
                        }
                        $schedule = $schedule->get();
                        foreach ($schedule as $key => $schedule) {
                            $data = [
                                'title' => $schedule->title,
                                'start' => $schedule->start_date,
                                'end' => $schedule->due_date,
                                'className' => 'event-primary',
                            ];
                            array_push($events, $data);
                        }
                    }
                } else {
                    unset($type['todo']);
                }
            }


            return view('calender::index', compact('events', 'type'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('calender::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return redirect()->back();
        return view('calender::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('calender::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }


    public function saveGoogleCalenderSettings(Request $request)
    {
        if ($request->google_calendar_enable == 'on') {
            $validator = \Validator::make($request->all(), [
                'google_calender_id' => 'required|string',
                'google_calender_json_file' => 'required',
            ]);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
        }


        if ($request->hasFile('google_calender_json_file')) {
            $filenameWithExt = $request->file('google_calender_json_file')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('google_calender_json_file')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            $uplaod = upload_file($request, 'google_calender_json_file', $fileNameToStore, 'Calendar', ['mimes:json']);


            if ($uplaod['flag'] == 1) {
                // Delete Old File
                $old_file = company_setting('google_calender_json_file');


                if (!empty($old_file) && check_file($old_file)) {
                    delete_file($old_file);
                }

            } else {
                return redirect()->back()->with('error', $uplaod['msg']);
            }
        }
        $getActiveWorkSpace = getActiveWorkSpace();
        $creatorId = creatorId();

        if ($request->has('google_calendar_enable')) {
            $post = $request->all();

            unset($post['_token'],$post['google_calender_json_file']);
            $url = $uplaod['url'];
            if($request->google_calender_json_file)
            {
                $post['google_calender_json_file'] = $url;
            }

            foreach ($post as $key => $value) {
                // Define the data to be updated or inserted
                $data = [
                    'key' => $key,
                    'workspace' => $getActiveWorkSpace,
                    'created_by' => $creatorId,
                ];


                Setting::updateOrInsert($data, ['value' => $value]);
            }
        } else {
            $data = [
                'key' => 'google_calendar_enable',
                'workspace' => $getActiveWorkSpace,
                'created_by' => $creatorId,
            ];


            Setting::updateOrInsert($data, ['value' => 'off']);
        }
        // Settings Cache forget
        AdminSettingCacheForget();
        comapnySettingCacheForget();
        return redirect()->back()->with('success', 'Google Calendar setting successfully updated.');
    }
}
