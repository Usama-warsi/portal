<?php

namespace Modules\ZoomMeeting\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use Modules\ZoomMeeting\Traits\ZoomMeetingTrait as TraitsZoomMeetingTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\ZoomMeeting\Entities\GeneralMetting;
use Modules\ZoomMeeting\Entities\ZoomMeeting;
use Modules\ZoomMeeting\Events\CreateZoommeeting;
use Modules\ZoomMeeting\Events\DestroyZoommeeting;

class ZoomMeetingController extends Controller
{
    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;
    const MEETING_URL = "https://api.zoom.us/v2/";
    use TraitsZoomMeetingTrait;

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('zoommeeting manage')) {
            if (\Auth::user()->type == 'company' || \Auth::user()->type == 'super admin') {
                $meetings = ZoomMeeting::select('zoom_meeting.*')
                ->join('general_meeting', 'zoom_meeting.id', '=', 'general_meeting.m_id')
                ->where('zoom_meeting.workspace_id', '=', getActiveWorkSpace()) // Add any additional conditions if needed
                ->with('users')
                ->groupBy('id')
                ->get();

            } else {
                $meetings = ZoomMeeting::select('zoom_meeting.*')
                ->join('general_meeting', 'zoom_meeting.id', '=', 'general_meeting.m_id')->where('general_meeting.user_id', \Auth::user()->id)
                ->where('zoom_meeting.workspace_id', '=', getActiveWorkSpace()) // Add any additional conditions if needed
                ->with('users')
                ->groupBy('id')
                ->get();
            }
            return view('zoommeeting::index', compact('meetings'));
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
        if (Auth::user()->isAbleTo('zoommeeting create')) {
            if (Auth::user()->type == 'company' || \Auth::user()->type == 'super admin') {
                $users = User::where('id', '!=', \Auth::user()->id)->where('created_by', '=', \Auth::user()->id)->where('workspace_id', getActiveWorkSpace())->pluck('name', 'id');
                // $users->prepend('Select User','');
            } else {
                $users = User::where('created_by', '=', \Auth::user()->created_by)->where('workspace_id', getActiveWorkSpace())->pluck('name', 'id');
                // $users->prepend('Select User','');
            }

            return view('zoommeeting::create', compact('users'));
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
        if (Auth::user()->isAbleTo('zoommeeting create')) {

            $validator = \Validator::make(
                $request->all(), [
                    'title' => 'required',
                    'duration' => 'required',
                    'start_date' => 'required',
                    'users' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('zoom-meeting.index')->with('error', $messages->first());
            }

            $data['topic'] = $request->title;
            $data['start_time'] = date('y:m:d H:i:s', strtotime($request->start_date));
            $data['duration'] = (int)$request->duration;
            $data['password'] = isset($request->password) ? $request->password : null;
            $data['host_video'] = 0;
            $data['participant_video'] = 0;

            try {
                $meeting_create = $this->createmitting($data);

            } catch (\Throwable $th) {
                return redirect()->back()->with('error', __('Invalid access token'));
            }

            if (isset($meeting_create['success']) &&  $meeting_create['success'] == true) {
                $meeting_id = isset($meeting_create['data']['id']) ? $meeting_create['data']['id'] : 0;
                $start_url = isset($meeting_create['data']['start_url']) ? $meeting_create['data']['start_url'] : '';
                $join_url = isset($meeting_create['data']['join_url']) ? $meeting_create['data']['join_url'] : '';
                $status = isset($meeting_create['data']['status']) ? $meeting_create['data']['status'] : '';
                DB::beginTransaction();
                try {
                    $new = new ZoomMeeting();
                    $new->title = $request->title;
                    $new->meeting_id = $meeting_id;
                    $new->start_date = date('y:m:d H:i:s', strtotime($request->start_date));
                    $new->duration = $request->duration;
                    $new->start_url = $start_url;
                    $new->password = $request->password;
                    $new->join_url = $join_url;
                    $new->status = $status;
                    $new->created_by = creatorId();
                    $new->workspace_id = getActiveWorkSpace();
                    if ($new->save()) {

                        foreach ($request->users as $user) {
                            $m_new = new GeneralMetting();
                            $m_new->m_id = $new->id;
                            $m_new->user_id = $user;
                            $m_new->save();
                            DB::commit();
                        }
                    }
                    event(new CreateZoommeeting($request, $new));


                    return redirect()->back()->with('success', __('Meeting created successfully.'));
                } catch (\Exception $e) {

                    DB::rollback();
                    return redirect()->back()->with('error', $e->getMessage());
                }
            } else {
                return redirect()->back()->with('error', __('Meeting not created.'));
            }
        } else {
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
        if (Auth::user()->isAbleTo('zoommeeting show')) {
            $zoom_meeting = ZoomMeeting::find($id);
            return view('zoommeeting::show', compact('zoom_meeting'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return redirect()->back()->with('error', __('Permission denied.'));
        return view('zoommeeting::edit');
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
        if (Auth::user()->isAbleTo('zoommeeting delete')) {
            $meeting = ZoomMeeting::find($id);
            if ($meeting) {
                GeneralMetting::where('m_id', $meeting->id)->delete();

                event(new DestroyZoommeeting($meeting));

                $meeting->delete();
                return redirect()->back()->with('success', __('Zoom meeting delete sucessfully.'));
            } else {
                return redirect()->back()->with('success', __('Zoom meeting not found.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function setting(Request $request)
    {
        if(Auth::user()->isAbleTo('zoommeeting manage'))
        {
            $validator = \Validator::make($request->all(), [
                'zoom_account_id' => 'required|string',
                'zoom_client_id' => 'required|string',
                'zoom_client_secret' => 'required|string'
            ]);
            if($validator->fails()){
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post = $request->all();
            $getActiveWorkSpace = getActiveWorkSpace();
            $creatorId = creatorId();

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
            return redirect()->back()->with('success','Zoom setting save sucessfully.');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function calender(Request $request)
    {
        if (Auth::user()->isAbleTo('zoommeeting manage')) {
            $zoomMeetings = ZoomMeeting::where('created_by', '=', creatorId())->where('workspace_id', getActiveWorkSpace())->get();
            $calandar = [];
            foreach($zoomMeetings as $zoomMeeting)
            {
                $arr['id']        = $zoomMeeting['id'];
                $arr['title']     = company_Time_formate($zoomMeeting['start_date']).' '.$zoomMeeting['title'];
                $arr['start']     = date('Y-m-d',strtotime($zoomMeeting['start_date']));
                $arr['end']       = $zoomMeeting['end_date'];
                $arr['className'] = 'event-primary';
                $arr['url']       = route('zoom-meeting.show', $zoomMeeting['id']);
                $calandar[]     = $arr;
            }
            $calenderArray = array_merge($calandar);
            $calenderDatas  = json_encode($calenderArray);
            return view('zoommeeting::calender', compact('calandar', 'calenderDatas', 'zoomMeetings'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
