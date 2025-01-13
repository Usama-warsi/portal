<?php

namespace Modules\LMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Crypt;
use Modules\LMS\Entities\Header;
use Illuminate\Support\Facades\Auth;
use Modules\LMS\Events\CreateCourseHeader;
use Modules\LMS\Events\DestroyCourseHeader;
use Modules\LMS\Events\UpdateCourseHeader;

class HeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id)
    {
        if(Auth::user()->isAbleTo('header create'))
        {
            $id = Crypt::decrypt($id);
            return view('lms::headers.create',compact('id'));
        }
        else{
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request,$id)
    {
        if(Auth::user()->isAbleTo('header create'))
        {
            $header = new Header();
            $header->workspace_id = getActiveWorkSpace();
            $header->course = $id;
            $header->title = $request->title;
            $header->save();

            event(new CreateCourseHeader($request, $header));
            return redirect()->back()->with('success', __('Header created successfully!'));
        }
        else{
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
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id,$course_id)
    {
        if(Auth::user()->isAbleTo('header edit'))
        {
            $header = Header::find($id);
            return view('lms::headers.edit',compact('header','course_id'));
        }
        else{
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id,$course_id)
    {
        if(Auth::user()->isAbleTo('header edit'))
        {
            $header = Header::find($id);
            $header->title = $request->title;
            $header->save();
            event(new UpdateCourseHeader($request, $header));
            return redirect()->back()->with('success', __('Header updated successfully!'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id,$course_id)
    {
        if(Auth::user()->isAbleTo('header delete'))
        {
            $header = Header::find($id);

            event(new DestroyCourseHeader($header));

            $header->delete();
            return redirect()->back()->with('success', __('Header deleted successfully!'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
