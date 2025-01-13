<?php

namespace Modules\GoogleMeet\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Appointment\Entities\AppointmentCallback;
use Modules\Appointment\Entities\Schedule;
use Modules\GoogleMeet\Entities\GoogleMeet;
use Modules\GoogleMeet\Http\Controllers\GoogleMeetController;
use App\Models\User;
use Carbon\Carbon;
use Modules\Appointment\Events\AppointmentCallbackEvent;

class AppointmentCallbackLis
{
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
    public function handle(AppointmentCallbackEvent $event)
    {
        $datas = $event->appointment_callback;
        $appointment = Schedule::where('unique_id', '=', $datas->unique_id)->first();
        $callback = AppointmentCallback::where('schedule_id', $appointment->id)->first();
        if ($appointment->meeting_type == 'Google Meet') {
            $emails = User::where('id', $datas->user_id)->pluck('email')->toArray();
            $carbonDateTime =  Carbon::parse($datas->date . $datas->start_time);
            $start_date     = $carbonDateTime->format('Y-m-d\TH:i:s.000');
            $start_time = strtotime($datas->start_time);
            $end_time = strtotime($datas->end_time);
            $duration = ($end_time - $start_time) / 60;
            $carbonDateTime =  Carbon::parse($datas->date . $datas->end_time);
            $end_date       = $carbonDateTime->format('Y-m-d\TH:i:s.000');
            $data = [
                'summary'      => $datas->appointment->name,
                'description'  => '',
                'start' => [
                    'dateTime' => $start_date,
                    'timeZone' => config('app.timezone'), // Set your timezone
                ],
                'end'          => [
                    'dateTime' =>  $end_date,
                    'timeZone' => config('app.timezone'),
                ],
                'conferenceData'    => [
                    'createRequest' => [
                        'requestId' => uniqid(),
                    ],
                ],
            ];
            $user_id = $appointment->created_by;
            $workspace = $appointment->workspace;
            $googleMeetController = new GoogleMeetController();
            $event = $googleMeetController->createmeeting($data, $emails, $user_id, $workspace);
            
            if ($event) {

                $meeting_id = isset($event->id) ? $event->id : 0;
                $start_url  = isset($event->hangoutLink) ? $event->hangoutLink : '';
                $join_url   = isset($event->hangoutLink) ? $event->hangoutLink : '';
                $status     = isset($event->status) ? $event->status : '';
                try {
                    $new                = new GoogleMeet();
                    $new->title         = $datas->appointment->name;
                    $new->description   = '';
                    $new->meeting_id    = $meeting_id;
                    $new->start_date    = date('y:m:d', strtotime($datas->date)) . date(' H:i:s', strtotime($datas->start_time));
                    $new->duration      = $duration;
                    $new->member_ids    = $datas->users->id;
                    $new->start_url     = $start_url;
                    $new->join_url      = $join_url;
                    $new->status        = $status;
                    $new->created_by    = $user_id;
                    $new->workspace_id  = $workspace;
                    $new->save();

                    if ($callback) {
                        $callback->start_url = $start_url;
                        $callback->join_url = $join_url;
                        $callback->save();
                    }

                    return redirect()->back()->with('success', __('Meeting created successfully.'));
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', $e->getMessage());
                }
            } else {
                return redirect()->back()->with('error', __('Something went wrong!'));
            }
        }
    }
}
