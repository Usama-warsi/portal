<?php

namespace Modules\ZoomMeeting\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ZoomMeeting\Traits\ZoomMeetingTrait as TraitsZoomMeetingTrait;
use Illuminate\Support\Facades\DB;
use Modules\Appointment\Entities\Schedule;
use Modules\ZoomMeeting\Entities\GeneralMetting;
use Modules\ZoomMeeting\Entities\ZoomMeeting;
use Modules\Appointment\Events\AppointmentStatus;
use Modules\ZoomMeeting\Http\Controllers\ZoomMeetingController;

class CreateAppointmentLis
{
    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;
    const MEETING_URL = "https://api.zoom.us/v2/";
    use TraitsZoomMeetingTrait;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(AppointmentStatus $event)
    {
        $datas = $event->schedule;
        $appointment = Schedule::where('unique_id', '=', $datas->unique_id)->first();
        if ($datas->meeting_type == 'Zoom Meeting' && $datas->status == 'Approved') {
            $start_time = strtotime($datas->start_time);
            $end_time = strtotime($datas->end_time);
            $duration = ($end_time - $start_time) / 60;
            $data['topic'] = $datas->appointment->name;
            $data['start_time'] = date('y:m:d', strtotime($datas->date)) . date(' H:i:s', strtotime($datas->start_time));
            $data['duration'] = (int)$duration;
            $data['password'] = '';
            $data['host_video'] = 0;
            $data['participant_video'] = 0;

            try {
                $zoommeeting_create = new ZoomMeetingController();
                $meeting_create = $zoommeeting_create->createmitting($data);
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', __('Invalid access token'));
            }
            \Log::info('Meeting');
            \Log::info((array)$meeting_create);

            if (isset($meeting_create['success']) &&  $meeting_create['success'] == true) {
                $meeting_id = isset($meeting_create['data']['id']) ? $meeting_create['data']['id'] : 0;
                $start_url = isset($meeting_create['data']['start_url']) ? $meeting_create['data']['start_url'] : '';
                $join_url = isset($meeting_create['data']['join_url']) ? $meeting_create['data']['join_url'] : '';
                $status = isset($meeting_create['data']['status']) ? $meeting_create['data']['status'] : '';
                DB::beginTransaction();
                try {
                    $new = new ZoomMeeting();
                    $new->title = $datas->appointment->name;
                    $new->meeting_id = $meeting_id;
                    $new->start_date = date('y:m:d', strtotime($datas->date)) . date(' H:i:s', strtotime($datas->start_time));
                    $new->duration = $duration;
                    $new->start_url = $start_url;
                    $new->join_url = $join_url;
                    $new->password = '';
                    $new->status = 'waiting';
                    $new->created_by = \Auth::user()->id;
                    $new->workspace_id = getActiveWorkSpace();
                    if ($new->save()) {
                        $m_new = new GeneralMetting();
                        $m_new->m_id = $new->id;
                        $m_new->user_id = $datas->users->id;
                        $m_new->save();
                        DB::commit();
                    }

                    if ($appointment) {
                        $appointment->start_url = $start_url;
                        $appointment->join_url = $join_url;
                        $appointment->save();
                    }

                    return redirect()->back()->with('success', __('Meeting created successfully.'));
                } catch (\Exception $e) {
                    DB::rollback();
                    return redirect()->back()->with('error', $e->getMessage());
                }
            } else {
                return redirect()->back()->with('error', __('Meeting not created.'));
            }
        }
    }
}
