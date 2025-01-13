<?php

namespace Modules\LMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LMS\Entities\CourseCategory;
use Modules\LMS\Events\CreateCourseCategory;
use Modules\LMS\Events\DestroyCourseCategory;
use Modules\LMS\Events\UpdateCourseCategory;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->isAbleTo('course category manage'))
        {
            $categorise    = CourseCategory::where('workspace_id',getActiveWorkSpace())->where('created_by', creatorId())->get();
            return view('lms::category.index',compact('categorise'));
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
        if(\Auth::user()->isAbleTo('course category create'))
        {
            return view('lms::category.create');
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
        if(\Auth::user()->isAbleTo('course category create'))
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

            $category = new CourseCategory();
            if(!empty($request->category_image))
            {
                $filenameWithExt  = $request->file('category_image')->getClientOriginalName();
                $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension        = $request->file('category_image')->getClientOriginalExtension();
                $fileNameToStores = $filename . '_' . time() . '.' . $extension;

                $path = upload_file($request,'category_image',$fileNameToStores,'category_image');

                if($path['flag'] == 1){
                    $url = $path['url'];
                    $category->category_image = $url;
                }else{
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }
            $category->name = $request->name;
            $category->description = $request->description;
            $category->workspace_id = getActiveWorkSpace();
            $category->created_by = creatorId();
            $category->save();

            event(new CreateCourseCategory($request, $category));

            return redirect()->back()->with('success', __('Category created successfully!'));
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
        if(\Auth::user()->isAbleTo('course category edit'))
        {
            $category = CourseCategory::find($id);
            return view('lms::category.edit',compact('category'));
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
        if(\Auth::user()->isAbleTo('course category edit'))
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
            $category = CourseCategory::find($id);
            if(!empty($request->category_image))
            {
                if(!empty($category->category_image))
                {
                    delete_file($category->category_image);
                }
                $filenameWithExt  = $request->file('category_image')->getClientOriginalName();
                $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension        = $request->file('category_image')->getClientOriginalExtension();
                $fileNameToStores = $filename . '_' . time() . '.' . $extension;

                $path = upload_file($request,'category_image',$fileNameToStores,'category_image');
                if($path['flag'] == 1){
                    $url = $path['url'];
                    $category->category_image = $url;
                }else{
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }
            $category->name = $request->name;
            $category->description = $request->description;
            $category->update();

            event(new UpdateCourseCategory($request, $category));

            return redirect()->back()->with('success', __('Category updated successfully!'));
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
        if(\Auth::user()->isAbleTo('course category delete'))
        {
            $category = CourseCategory::find($id);

            event(new DestroyCourseCategory($category));
            if(!empty($category->category_image))
            {
                delete_file($category->category_image);
            }
            $category->delete();
            return redirect()->back()->with('success', __('Category deleted successfully.'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
