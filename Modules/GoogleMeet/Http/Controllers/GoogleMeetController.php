<?php

namespace Modules\GoogleMeet\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\GoogleMeet\Entities\GeneralMetting;
use Modules\GoogleMeet\Entities\GoogleMeet;
use Modules\GoogleMeet\Events\CreateGoogleMeet;
use Modules\GoogleMeet\Events\DestroyGoogleMeet;
use App\Models\Setting;
use App\Models\User;
use Google\Client;
use Google\Service\Calendar;
use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Google_Service_Calendar_EventAttendee;
class GoogleMeetController extends Controller
{
    public function index()
    {
        if (\Auth::user()->isAbleTo('googlemeet manage')) {
            if (\Auth::user()->type == 'company') {
                $meetings = GoogleMeet::get();
            } else {
                $meetings = GoogleMeet::where('workspace_id', getActiveWorkSpace())
                ->whereRaw('FIND_IN_SET(?, member_ids)', [\Auth::user()->id])
                ->get();
            }

            return view('googlemeet::index', compact('meetings'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->isAbleTo('googlemeet create')) {
            if (\Auth::user()->type == 'company') {
                $users = User::where('id', '!=', \Auth::user()->id)
                                ->where('created_by', '=', \Auth::user()->id)
                                ->where('workspace_id', getActiveWorkSpace())->pluck('name', 'id');
            } else {
                $users = User::where('id', '!=', \Auth::user()->id)
                                ->where('created_by', '=', \Auth::user()->created_by)
                                ->where('workspace_id', getActiveWorkSpace())->pluck('name', 'id');
            }

            return view('googlemeet::create', compact('users'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function createmeeting($data , $emailsToBeInvited, $user_id = null, $workspace = null)
    {
        try
        {
            $client = new Client();
            $client->setAuthConfig(base_path(company_setting('google_meet_json_file', $user_id, $workspace)));

            if($this->isGoogleTokenExpired($user_id, $workspace))
            {
                if($this->getNewAccessToken($user_id, $workspace)){
                    
                    $client->setAccessToken(company_setting('google_meet_token', $user_id, $workspace)); // Set the user's access token

                }else{

                    return false;
                }
            }else{

                $client->setAccessToken(company_setting('google_meet_token', $user_id, $workspace)); // Set the user's access token
            }
            
            // Create a Google Calendar event with Google Meet link
            $calendarService    = new Calendar($client);
            $event              = new Google_Service_Calendar_Event($data);

            // Add attendees to the event
            $attendees = [];
            foreach ($emailsToBeInvited as $email) {
                $attendee = new Google_Service_Calendar_EventAttendee();
                $attendee->setEmail($email);
                $attendee->setResponseStatus('needsAction');  // You can use 'accepted', 'tentative', or 'declined'
                $attendees[] = $attendee;
            }

            $event->attendees = $attendees;


            $calendarId         = 'primary'; // Use 'primary' for the user's primary calendar
            // $event              = $calendarService->events->insert($calendarId, $event, ['conferenceDataVersion' => 1]);
            $event              = $calendarService->events->insert($calendarId, $event, ['conferenceDataVersion' => 1 , 'sendNotifications' => true]);
            return  $event ; 

        } catch (\Throwable $e) {
            return false;
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('googlemeet create')) 
        {
            try 
            {
                $carbonDateTime =  Carbon::parse($request->start_date);
                $start_date     = $carbonDateTime->format('Y-m-d\TH:i:s.000');

                $carbonDateTime =  Carbon::parse(date('m/d/y H:i', strtotime(  + $request->duration . "minutes" , strtotime($request->start_date))));
                $end_date       = $carbonDateTime->format('Y-m-d\TH:i:s.000');

                // Retrieve users from the database
                $users = User::whereIn('id', $request->users)->get();
                $emails = $users->pluck('email')->toArray();

                $data = [
                    'summary'      => $request->title,
                    'description'  => $request->description,
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
                $event  = $this->createmeeting($data , $emails);

            } catch (\Throwable $e) {
                return redirect()->back()->with('error', __('Invalid access token'));
            }
            
            if ($event) {

                $meeting_id = isset($event->id) ? $event->id : 0;
                $start_url  = isset($event->hangoutLink )? $event->hangoutLink : '';
                $join_url   = isset($event->hangoutLink) ? $event->hangoutLink : '';
                $status     = isset($event->status) ? $event->status : '';

                try {
                    $new                = new GoogleMeet();
                    $new->title         = $request->title;
                    $new->description   = $request->description;
                    $new->meeting_id    = $meeting_id;
                    $new->start_date    = date('y:m:d H:i:s', strtotime($request->start_date));
                    $new->duration      = $request->duration;
                    $new->member_ids    = implode(',', $request->users);
                    $new->start_url     = $start_url;
                    $new->join_url      = $join_url;
                    $new->status        = $status;
                    $new->created_by    = \Auth::user()->id;
                    $new->workspace_id  = getActiveWorkSpace();
                    $new->save();

                    event(new CreateGoogleMeet($request, $new));

                    return redirect()->back()->with('success', __('Meeting created successfully.'));

                } catch (\Exception $e) {

                    return redirect()->back()->with('error', $e->getMessage());
                }

            } else {

                return redirect()->back()->with('error', __('Something went wrong!'));
            }

        } else {

            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show($id)
    {
        if (Auth::user()->isAbleTo('googlemeet show')) {
            $meeting = GoogleMeet::find($id);
            if ($meeting) {

                return view('googlemeet::show', compact('meeting'));
                
            } else {

                return redirect()->back()->with('success', __('Meeting not found.'));
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function deletemeeting($eventId)
    {
        try {
            $client = new Client();
            $client->setAuthConfig(base_path(company_setting('google_meet_json_file')));
    

            if($this->isGoogleTokenExpired())
            {
                if($this->getNewAccessToken()){
                    
                    $client->setAccessToken(company_setting('google_meet_token')); // Set the user's access token

                }else{

                    return false;
                }
            }else{

                $client->setAccessToken(company_setting('google_meet_token')); // Set the user's access token
            }

            // Rest of your code...
            $calendarService = new Calendar($client);
    
            // Set the calendar ID and event ID
            $calendarId = 'primary'; // Use 'primary' for the user's primary calendar
    
            // Delete the event
            $calendarService->events->delete($calendarId, $eventId);
            return true; // Event deleted successfully
    
        } catch (\Throwable $e) {
            // Handle the exception if needed
            return false; // Event deletion failed
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('googlemeet delete')) {
            $meeting = GoogleMeet::find($id);

            if ($meeting) {

                $this->deletemeeting($meeting->meeting_id);
                $meeting->delete();

                event(new DestroyGoogleMeet($meeting));

                return redirect()->back()->with('success', __('Meeting delete sucessfully.'));

            } else {
                return redirect()->back()->with('success', __('Meeting not found.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function setting(Request $request)
    {

        if($request->has('google_meet_json_file'))
        {
            $google_meet_json_file = time()."-google_meet_json_file." . $request->google_meet_json_file->getClientOriginalExtension();
            $path = upload_file($request,'google_meet_json_file',$google_meet_json_file,'google_meet_json',[]);
            if($path['flag']==0){
                return redirect()->back()->with('error', __($path['msg']));
            }
            
            // old img delete
            if(!empty(company_setting('google_meet_json_file')) && strpos(company_setting('google_meet_json_file'),'avatar.json') == false && check_file(company_setting('google_meet_json_file')))
            {
                delete_file(company_setting('google_meet_json_file'));
            }

            $getActiveWorkSpace = getActiveWorkSpace();
            $creatorId = creatorId();

            $post['google_meet_json_file']      = $path['url'];
            $post['google_meet_token']          = '';
            $post['google_meet_refresh_token']  = '';

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
        }

        return redirect()->back()->with('success', 'Google Meet setting save sucessfully.');
    }

    public function calender(Request $request)
    {
        if (Auth::user()->isAbleTo('googlemeet manage')) {
            $Meetings = GoogleMeet::where('created_by', '=', creatorId())->where('workspace_id', getActiveWorkSpace())->get();
            $calandar = [];
            foreach($Meetings as $Meeting)
            {
                $arr['id']        = $Meeting['id'];
                $arr['title']     = company_Time_formate($Meeting['start_date']).' '.$Meeting['title'];
                $arr['start']     = date('Y-m-d',strtotime($Meeting['start_date']));
                $arr['end']       = date('Y-m-d', strtotime(  + $Meeting['duration'] . "minutes" , strtotime($Meeting['start_date'])));
                $arr['className'] = 'event-primary';
                $arr['url']       = route('googlemeet.show', $Meeting['id']);
                $calandar[]     = $arr;
            }
            $calenderArray = array_merge($calandar);
            $calenderDatas  = json_encode($calenderArray);

            return view('googlemeet::calender', compact('calandar', 'calenderDatas', 'Meetings'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    // Get new access token
    public function getNewAccessToken($user_id = null, $workspace = null) 
    {
        try{
            $client = new Client();
            $client->setAuthConfig(base_path(company_setting('google_meet_json_file', $user_id, $workspace)));
            $newAccessToken = $client->fetchAccessTokenWithRefreshToken(company_setting('google_meet_refresh_token', $user_id, $workspace));

            $getActiveWorkSpace = getActiveWorkSpace();
            $creatorId = creatorId();

            $post['google_meet_token']          = $newAccessToken;
            $post['google_meet_refresh_token']  = $newAccessToken['refresh_token'];

            foreach ($post as $key => $value) {
                // Define the data to be updated or inserted
                $data = [
                    'key' => $key,
                    'workspace' => $getActiveWorkSpace,
                    'created_by' => $creatorId,
                ];

                // Check if the record exists, and update or insert accordingly
                Setting::updateOrInsert($data, ['value' => $value]);
                // Settings Cache forget
                comapnySettingCacheForget();
            }

            return  true;

        } catch (\Exception $e) {
            return false;
        }
    }

    // check access token
    public function isGoogleTokenExpired($user_id = null, $workspace = null) 
    {
        $googleClient = new Client();
        $googleClient->setAccessToken(company_setting('google_meet_token', $user_id, $workspace));
    
        if ($googleClient->isAccessTokenExpired()) {
            return true; // Token has expired
        }
        return false; // Token is still valid
    }

    // Authenticate with google
    public function redirectToGoogle()
    {
        try {
            $client = new Client();
            $client->setAuthConfig(base_path(company_setting('google_meet_json_file')));
            $client->setRedirectUri(route('auth.googlemeet.callback'));
            $client->addScope('https://www.googleapis.com/auth/drive');
            $client->addScope(Google_Service_Calendar::CALENDAR);
            $client->setAccessType('offline');
 
             return redirect($client->createAuthUrl());
 
         } catch (\Exception $e) {
             return redirect('/')->with('error',__($e->getMessage()));
         }
    }

     // Authenticate with google callback functon
    public function handleGoogleCallback(Request $request)
    {
         try {
             $client = new Client();
             $client->setAuthConfig(base_path(company_setting('google_meet_json_file')));
             $client->setRedirectUri(route('auth.googlemeet.callback'));
 
             $token = $client->fetchAccessTokenWithAuthCode($request->code);
 
             $getActiveWorkSpace = getActiveWorkSpace();
            $creatorId = creatorId();

            $post['google_meet_token']          = $token;

            if(isset($token['refresh_token'])){
 
                $post['google_meet_refresh_token']  = $token['refresh_token'];
            }
            

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
            
            return redirect()->route('googlemeet.index')->with('success', __('Authentication Successful')); // Redirect to dashboard after successful login
 
         } catch (\Exception $e) {
            return redirect('/')->with('error', __($e->getMessage()));
         }
    }
}
