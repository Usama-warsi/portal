<?php

namespace Modules\LMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Routing\Controller;
use Modules\LMS\Entities\CourseFaq;
use Modules\LMS\Events\CreateCourseFaq;
use Modules\LMS\Events\DestroyCourseFaq;
use Modules\LMS\Events\UpdateCourseFaq;

class CourseFaqController extends Controller
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
        if(\Auth::user()->isAbleTo('course faq create'))
        {
            return view('lms::faqs.create',compact('id'));
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
        if(\Auth::user()->isAbleTo('course faq create'))
        {
            $id = Crypt::decrypt($id);
            $faqs = new CourseFaq();
            $faqs->course_id = $id;
            $faqs->question = $request->question;
            $faqs->answer = $request->answer;
            $faqs->save();

            event(new CreateCourseFaq($request, $faqs));
            return redirect()->back()->with('success', __('Created successfully!'));
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
    public function edit($faq,$course_id)
    {
        if(\Auth::user()->isAbleTo('course faq edit'))
        {
            $faq = CourseFaq::find($faq);
            return view('lms::faqs.edit',compact('faq','course_id'));
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
    public function update(Request $request, $faq_id,$course_id)
    {
        if(\Auth::user()->isAbleTo('course faq edit'))
        {
            $faqs = CourseFaq::find($faq_id);
            $faqs->question = $request->question;
            $faqs->answer = $request->answer;
            $faqs->update();

            event(new UpdateCourseFaq($request, $faqs));
            return redirect()->back()->with('success', __('Updated successfully!'));
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
    public function destroy($faq_id,$course_id)
    {
        if(\Auth::user()->isAbleTo('course faq delete'))
        {
            $faqs = CourseFaq::find($faq_id);

            event(new DestroyCourseFaq($faqs));
            $faqs->delete();
            return redirect()->back()->with('success', __('Faq deleted successfully!'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
