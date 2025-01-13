<?php

namespace Modules\LMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LMS\Entities\CourseCategory;
use Modules\LMS\Entities\CourseSubcategory;
use Modules\LMS\Events\CreateCourseSubCategory;
use Modules\LMS\Events\DestroyCourseSubCategory;
use Modules\LMS\Events\UpdateCourseSubCategory;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->isAbleTo('course subcategory manage'))
        {
            $subcategorise = CourseSubcategory::select('course_subcategories.*', 'course_categories.name as category_name')->leftJoin('course_categories', 'course_subcategories.category', '=', 'course_categories.id')->where('course_subcategories.workspace_id',getActiveWorkSpace())->where('course_subcategories.created_by',creatorId())->get();
            return view('lms::subcategory.index',compact('subcategorise'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if(\Auth::user()->isAbleTo('course subcategory create'))
        {
            $category = CourseCategory::where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->get()->pluck('name', 'id');
            return view('lms::subcategory.create',compact('category'));
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
    public function store(Request $request)
    {
        if(\Auth::user()->isAbleTo('course subcategory create'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                'name' => 'required|max:120',
                            ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
            }

            $subcategory = new CourseSubcategory();
            $subcategory->name = $request->name;
            $subcategory->category = $request->category;
            $subcategory->workspace_id = getActiveWorkSpace();
            $subcategory->created_by = creatorId();
            $subcategory->save();

            event(new CreateCourseSubCategory($request, $subcategory));

            return redirect()->back()->with('success', __('Subcategory created successfully!'));
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
    public function edit($id)
    {
        if(\Auth::user()->isAbleTo('course subcategory edit'))
        {
            $subcategory = CourseSubcategory::find($id);
            $category = CourseCategory::where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->get()->pluck('name', 'id');
            return view('lms::subcategory.edit',compact('category','subcategory'));
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
    public function update(Request $request, $id)
    {
        if(\Auth::user()->isAbleTo('course subcategory edit'))
        {
            $validator = \Validator::make(
            $request->all(), [
                            'name' => 'required|max:120',
                        ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
            }
            $subcategory = CourseSubcategory::find($id);
            $subcategory->name = $request->name;
            $subcategory->category = $request->category;
            $subcategory->workspace_id = getActiveWorkSpace();
            $subcategory->created_by = creatorId();
            $subcategory->update();

            event(new UpdateCourseSubCategory($request, $subcategory));

            return redirect()->back()->with('success', __('Subcategory updated successfully!'));
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
    public function destroy($id)
    {
        if(\Auth::user()->isAbleTo('course subcategory delete'))
        {
            $subcategory = CourseSubcategory::find($id);
            event(new DestroyCourseSubCategory($subcategory));

            $subcategory->delete();
            return redirect()->back()->with('success', __('Subategory deleted successfully.'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
