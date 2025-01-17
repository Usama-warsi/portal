<?php

namespace Modules\Rotas\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Rotas\Entities\LeaveType;
use Illuminate\Support\Facades\Auth;
use Modules\Rotas\Entities\Leave;

class RotaleaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(Auth::user()->isAbleTo('rotaleavetype manage'))
        {
            $leavetypes = LeaveType::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get();

            return view('rotas::leavetype.index', compact('leavetypes'));
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
        if(Auth::user()->isAbleTo('rotaleavetype create'))
        {
            return view('rotas::leavetype.create');
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
        if(Auth::user()->isAbleTo('rotaleavetype create'))
        {
            $validator = \Validator::make(
                $request->all(), [
                'title' => 'required',
                'days' => 'required|gt:0',
            ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $leavetype             = new LeaveType();
            $leavetype->title      = $request->title;
            $leavetype->days       = $request->days;
            $leavetype->workspace   = getActiveWorkSpace();
            $leavetype->created_by = Auth::user()->id;
            $leavetype->save();

            return redirect()->route('leavestype.index')->with('success', __('Leave Type  successfully created.'));
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
        return view('rotas::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $leavetype = LeaveType::find($id);
        if(Auth::user()->isAbleTo('rotaleavetype edit'))
        {

            if($leavetype->created_by == creatorId() && $leavetype->workspace == getActiveWorkSpace())
            {

                return view('rotas::leavetype.edit', compact('leavetype'));
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
    public function update(Request $request, $id)
    {
        $leavetype = LeaveType::find($id);
        if(Auth::user()->isAbleTo('rotaleavetype edit'))
        {
            if($leavetype->created_by == creatorId() && $leavetype->workspace == getActiveWorkSpace())
            {
                $validator = \Validator::make(
                    $request->all(), [
                    'title' => 'required',
                    'days' => 'required|gt:0',
                ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $leavetype->title = $request->title;
                $leavetype->days  = $request->days;
                $leavetype->save();

                return redirect()->route('leavestype.index')->with('success', __('Leave Type successfully updated.'));
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
        $leavetype = LeaveType::find($id);

        if(Auth::user()->isAbleTo('rotaleavetype delete'))
        {
            if($leavetype->created_by == creatorId() && $leavetype->workspace == getActiveWorkSpace())
            {
                $leave     = Leave::where('leave_type_id',$leavetype->id)->where('workspace',getActiveWorkSpace())->get();
                if(count($leave) == 0)
                {
                    $leavetype->delete();
                }
                else
                {
                    return redirect()->route('leavestype.index')->with('error', __('This leavetype has leave. Please remove the leave from this leavetype.'));
                }
                return redirect()->route('leavestype.index')->with('success', __('Leave Type successfully deleted.'));
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

}
