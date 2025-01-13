<?php

namespace Modules\ActivityLog\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\ActivityLog\Entities\AllActivityLog;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if(module_is_active('ActivityLog'))
        {
            if (Auth::user()->isAbleTo('activitylog manage')) {
                $creatorId = creatorId();
                $getActiveWorkSpace = getActiveWorkSpace();

                $modules = AllActivityLog::select('module')
                            ->where('created_by', '=', $creatorId)
                            ->where('workspace', '=', $getActiveWorkSpace)
                            ->groupBy('module')
                            ->get()
                            ->pluck('module')
                            ->toArray();

                $activitys = AllActivityLog::join('users', 'all_activity_logs.user_id', '=', 'users.id')
                            ->select('all_activity_logs.*', 'users.name', 'users.type')
                            ->where('all_activity_logs.created_by', '=', $creatorId)
                            ->where('all_activity_logs.workspace', '=', $getActiveWorkSpace)
                            ->orderBy('all_activity_logs.created_at', 'desc')
                            ->get();
                if ($modules) {
                    $activitys = AllActivityLog::join('users', 'all_activity_logs.user_id', '=', 'users.id')
                                ->select('all_activity_logs.*', 'users.name', 'users.type')
                                ->where('all_activity_logs.created_by', '=', $creatorId)
                                ->where('all_activity_logs.workspace', '=', $getActiveWorkSpace)
                                ->where('all_activity_logs.module', '=', $modules[0])
                                ->orderBy('all_activity_logs.created_at', 'desc')
                                ->get();
                }
                if (!empty($request->filter)) {
                    $activitys = AllActivityLog::join('users', 'all_activity_logs.user_id', '=', 'users.id')
                                ->select('all_activity_logs.*', 'users.name', 'users.type')
                                ->where('all_activity_logs.created_by', '=', $creatorId)
                                ->where('all_activity_logs.workspace', '=', $getActiveWorkSpace)
                                ->where('all_activity_logs.module', '=', $request->filter)
                                ->orderBy('all_activity_logs.created_at', 'desc')
                                ->get();
                }
                if (!empty($request->staff)) {
                    $activitys = AllActivityLog::join('users', 'all_activity_logs.user_id', '=', 'users.id')
                                ->select('all_activity_logs.*', 'users.name', 'users.type')
                                ->where('all_activity_logs.created_by', '=', $creatorId)
                                ->where('all_activity_logs.workspace', '=', $getActiveWorkSpace)
                                ->where('all_activity_logs.user_id', '=', $request->staff)
                                ->where('all_activity_logs.module', '=', $request->filter)
                                ->orderBy('all_activity_logs.created_at', 'desc')
                                ->get();
                }
                $staffs = User::where('created_by', '=', $creatorId)->where('workspace_id', '=', $getActiveWorkSpace)->orWhere('id',$creatorId)->get();

                return view('activitylog::index', compact('activitys', 'modules' , 'staffs'))->with('i', (request()->input('page', 1) - 1) * 10);
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('activitylog::create');
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
        return redirect()->route('activitylog.index')->with('error', __('Permission Denied.'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('activitylog::edit');
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
        if (Auth::user()->isAbleTo('activitylog delete')) {
            $activity = AllActivityLog::find($id);
            $activity->delete();

            return redirect()->back()->with('success', __('Activity successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
