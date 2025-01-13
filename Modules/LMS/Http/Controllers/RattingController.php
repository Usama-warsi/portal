<?php

namespace Modules\LMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LMS\Entities\Course;
use Modules\LMS\Entities\Ratting;
use Modules\LMS\Entities\Store;
use Modules\LMS\Events\CreateRatting;
use Modules\LMS\Events\DestroyRatting;
use Modules\LMS\Events\UpdateRatting;

class RattingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('lms::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('lms::create');
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
        return view('lms::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Ratting $ratting, $id)
    {
        $rating = Ratting::where('id', $id)->first();
        $store    = Store::where('slug', $rating->slug)->first();
        return view('lms::storefront.' . $store->theme_dir .'.rating.edit', compact('rating'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request,$id)
    {
        $rating_view        = isset($request->rating_view) ? 'on' : null;
        $ratting   = Ratting::where('id', $id)->first();
        $validator = \Validator::make(
            $request->all(), [
                               'name' => 'required|max:120',
                               'title' => 'required|max:120',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        if($rating_view == 'on'){
            $ratting->rating_view = 'on';
        }else{
            $ratting->rating_view = 'off';
        }
        $ratting->name        = $request->name;
        $ratting->title       = $request->title;
        $ratting->ratting     = $request->rate;
        $ratting->description = $request->description;
        $ratting->update();

        event(new UpdateRatting($request, $ratting));

        return redirect()->back()->with('success', __('Rating Updated!'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $ratting = Ratting::where('id', $id)->first();

        event(new DestroyRatting($ratting));
        $ratting->delete();

        return redirect()->back()->with(
            'success', __('Rating Deleted!')
        );
    }

    public function rating($slug, $course_id)
    {
        $tutor_id = Course::where('id',$course_id)->pluck('created_by')->first();
        $store    = Store::where('slug', $slug)->first();
        if(isset($store->lang))
            {
                $lang = session()->get('lang');

                if(!isset($lang))
                {
                session(['lang' => $store->lang]);
                $storelang=session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
                }
                else
                {
                    session(['lang' => $lang]);
                    $storelang=session()->get('lang');
                    \App::setLocale(isset($storelang) ? $storelang : 'en');
                }
            }
        return view('lms::storefront.' . $store->theme_dir . '.rating.create', compact('slug', 'course_id','tutor_id'));
    }

    public function store_rating(Request $request, $slug, $course_id,$tutor_id)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'name' => 'required|max:120',
                               'title' => 'required|max:120',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $student = \Auth::guard('students')->user();
        $ratting              = new Ratting();
        $ratting->slug        = $slug;
        $ratting->course_id   = $course_id;
        $ratting->student_id  = !empty($student)?$student->id:null;
        $ratting->tutor_id    = $tutor_id;
        $ratting->rating_view = 'on';
        $ratting->name        = $request->name;
        $ratting->title       = $request->title;
        $ratting->ratting     = $request->rate;
        $ratting->description = $request->description;
        $ratting->save();

        event(new CreateRatting($request, $ratting));

        return redirect()->back()->with('success', __('Rating Created!'));
    }
}
