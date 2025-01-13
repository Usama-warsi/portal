<?php

namespace Modules\LMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\LMS\Entities\Course;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Modules\LMS\Entities\CourseCategory;
use Modules\LMS\Entities\CourseFaq;
use Modules\LMS\Entities\CourseSubcategory;
use Modules\LMS\Entities\Header;
use Modules\LMS\Entities\LmsUtility;
use Modules\LMS\Entities\PracticesFiles;
use Modules\LMS\Events\CreateCourse;
use Modules\LMS\Events\CreatePracticeFile;
use Modules\LMS\Events\DestroyCourse;
use Modules\LMS\Events\DestroyPracticeFile;
use Modules\LMS\Events\UpdateCourse;
use Modules\LMS\Events\UpdateCourseSeo;
use Modules\LMS\Events\UpdatePracticeFile;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(Auth::user()->isAbleTo('course manage'))
        {
            $courses    = Course::select('courses.*', 'course_categories.name as category_name')->leftJoin('course_categories', 'courses.category', '=', 'course_categories.id')->where('courses.workspace_id',getActiveWorkSpace())->where('courses.created_by', creatorId())->get();
            $category    = CourseCategory::where('workspace_id',getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name','id');
            return view('lms::course.index',compact('courses','category'));
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
        if(\Auth::user()->isAbleTo('course create'))
        {
            $user = getActiveWorkSpace();
            $category = CourseCategory::where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->get();
            $preview_type = [
                'Video File' => 'Video File',
                'Image'=> 'Image',
                'iFrame'=> 'iFrame',
            ];
            $level = LmsUtility::course_level();
            return view('lms::course.create',compact('level','category','preview_type'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if(\Auth::user()->isAbleTo('course create'))
        {
            $validator = Validator::make(
                $request->all(), [
                                'title' => 'required|max:120',
                                'course_requirements' => 'required',
                                'course_description' => 'required',
                                'lang' => 'required',
                                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $has_discount                = isset($request->has_discount) ? 'on' : 'off';
            $is_free                     = isset($request->is_free) ? 'on' : 'off';
            $is_preview                  = isset($request->is_preview) ? 'on' : null;
            $has_certificate             = isset($request->has_certificate) ? 'on' : null;
            $course                      = new Course();
            $course->title               = $request->title;
            $course->course_requirements = $request->course_requirements;
            $course->course_description	 = $request->course_description	;
            $course->level               = $request->level;
            $course->lang                = $request->lang;
            $course->duration            = $request->duration;
            if($has_certificate == 'on'){
                $course->has_certificate = 'on';
            }else{
                $course->has_certificate = 'off';
            }

            if(isset($request->category))
            {
                $validator = Validator::make($request->all(), [
                    'subcategory' => 'required',
                ]);
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }
                $course->type = "Course";
                $course->category = $request->category;
                $course->sub_category = implode(',',$request->subcategory);
            }

            if($is_free == 'off')
            {
                $validator       = Validator::make($request->all(), ['price' => 'required',]);
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }
                $course->price = $request->price;

                if($has_discount == 'on'){
                    $validator = Validator::make($request->all(), ['discount' => 'required',]);
                    if($validator->fails())
                    {
                        $messages = $validator->getMessageBag();
                        return redirect()->back()->with('error', $messages->first());
                    }
                    $course->has_discount = 'on';
                    $course->discount = $request->discount;
                }else{
                    $course->has_discount = 'off';
                    $course->discount = null;
                }
            }else{
                $course->is_free = 'on';
                $course->price = null;
                $course->discount = null;
                $course->has_discount = 'off';
            }

            if($is_preview == 'on')
            {
                $course->is_preview = $request->is_preview;
                $course->preview_type = $request->preview_type;

                if(!empty($request->preview_image))
                {
                    $filenameWithExt  = $request->File('preview_image')->getClientOriginalName();
                    $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension        = $request->file('preview_image')->getClientOriginalExtension();
                    $fileNameToStores = $filename . '_' . time() . '.' . $extension;

                    $path = upload_file($request,'preview_image',$fileNameToStores,'preview_image');
                    if($path['flag'] == 1){
                        $url = $path['url'];
                        $course->preview_content = $url;

                    }else{
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }
                if(!empty($request->preview_video))
                {
                    $ext = $request->file('preview_video')->getClientOriginalExtension();
                    $fileName = 'video_' . time() . rand() . '.' . $ext;

                    $path = upload_file($request, 'preview_video', $fileName, 'preview_video');
                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                        $course->preview_content = $url;

                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }
                if(!empty($request->preview_iframe))
                {
                    $course->preview_content = $request->preview_iframe;
                }
            }else{
                $course->is_preview = 'off';
            }

            if(!empty($request->thumbnail))
            {
                $filenameWithExt  = $request->File('thumbnail')->getClientOriginalName();
                $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension        = $request->file('thumbnail')->getClientOriginalExtension();
                $fileNameToStores = $filename . '_' . time() . '.' . $extension;

                $path = upload_file($request,'thumbnail',$fileNameToStores,'thumbnail');
                if($path['flag'] == 1){
                    $url = $path['url'];
                    $course->thumbnail = $url;
                }else{
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }
            $course->featured_course = !empty($request->featured_course)?$request->featured_course:'off';
            $course->type = "Course";
            $course->status = 'Active';
            $course->meta_keywords = $request->meta_keywords;
            $course->meta_description = $request->meta_description;
            $course->workspace_id = getActiveWorkSpace();;
            $course->created_by = creatorId();

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages );
            }

            if(!empty($request->meta_image))
            {
                $filenameWithExt  = $request->File('meta_image')->getClientOriginalName();
                $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension        = $request->file('meta_image')->getClientOriginalExtension();
                $fileNameToStoresmetaimage =  'meta_image'.'_' . time(). '.' . $extension;

                $path = upload_file($request,'meta_image',$fileNameToStoresmetaimage,'course_meta_image');
                if($path['flag'] == 1){
                    $url = $path['url'];
                    $course->meta_image = $url;
                }else{
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }
            $course->save();
            $course_id = Crypt::encrypt($course->id);

            event(new CreateCourse($request, $course));

            return redirect()->route('course.edit',$course_id)->with('success', __('Course created successfully!'));
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
        if(\Auth::user()->isAbleTo('course edit'))
        {
            $course          = Course::find(Crypt::decrypt($id));
            $category        = CourseCategory::where('workspace_id', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name','id');
            $sub_category    = CourseSubcategory::where('category',$course->category)->get()->pluck('name','id');
            $level           = LmsUtility::course_level();
            $status          = LmsUtility::status();
            $course_id       = $id;
            $headers         = Header::where('course',Crypt::decrypt($id))->get();
            $practices_files = PracticesFiles::where('course_id',Crypt::decrypt($id))->get();
            $faqs = CourseFaq::where('course_id',Crypt::decrypt($id))->get();
            $preview_type = [
                'Video File' => 'Video File',
                'Image'=> 'Image',
                'iFrame'=> 'iFrame',
            ];

            return view('lms::course.edit',compact('course','category','sub_category','level','status','headers','practices_files','faqs','course_id','preview_type'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Course $course)
    {
        if(Auth::user()->isAbleTo('course edit'))
        {
            $validator = Validator::make(
                $request->all(), [
                                'title' => 'required|max:120',
                                'course_requirements' => 'required',
                                'course_description' => 'required',
                                'lang' => 'required',
                            ]
            );

            $has_discount        = isset($request->has_discount) ? 'on' : 'off';
            $is_free        = isset($request->is_free) ? 'on' : 'off';
            $is_preview        = isset($request->is_preview) ? 'on' : null;
            $has_certificate        = isset($request->has_certificate) ? 'on' : null;
            $course->title = $request->title;
            $course->course_requirements = $request->course_requirements;
            $course->course_description	 = $request->course_description	;
            $course->level = $request->level;
            $course->lang = $request->lang;
            $course->duration = $request->duration;
            if($has_certificate == 'on'){
                $course->has_certificate = 'on';
            }else{
                $course->has_certificate = 'off';
            }
            if($is_free == 'off'){
                $validator = Validator::make($request->all(), ['price' => 'required',]);
                $course->is_free = 'off';
                $course->price = $request->price;

                if($has_discount == 'on'){
                    $validator = Validator::make($request->all(), ['discount' => 'required',]);
                    $course->has_discount = 'on';
                    $course->discount = $request->discount;
                }else{
                    $course->has_discount = 'off';
                    $course->discount = null;
                }
            }else{
                $course->is_free = 'on';
                $course->price = null;
                $course->discount = null;
                $course->has_discount = 'off';
            }
            if(isset($request->category))
            {
                $course->category = $request->category;
            }
            if(isset($request->subcategory)){
                $course->sub_category = implode(',',$request->subcategory);
            }
            if($is_preview == 'on')
            {
                $course->is_preview = $request->is_preview;
                $course->preview_type = $request->preview_type;
                if(!empty($request->preview_image))
                {
                    $filenameWithExt  = $request->File('preview_image')->getClientOriginalName();
                    $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension        = $request->file('preview_image')->getClientOriginalExtension();
                    $fileNameToStores = $filename . '_' . time() . '.' . $extension;

                    $path = upload_file($request,'preview_image',$fileNameToStores,'preview_image');

                    if($path['flag'] == 1){
                        $url = $path['url'];
                        $course->preview_content = $url;
                    }else{
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }
                if(!empty($request->preview_video))
                {
                    $filenameWithExt  = $request->File('preview_video')->getClientOriginalName();
                    $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension        = $request->file('preview_video')->getClientOriginalExtension();
                    $fileNameToStores = $filename . '_' . time() . '.' . $extension;

                    $path = upload_file($request,'preview_video',$fileNameToStores,'preview_video');

                    if($path['flag'] == 1){
                        $url = $path['url'];
                        $course->preview_content = $url;
                    }else{
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }
            }else{
                $course->is_preview = 'off';
            }
            if(!empty($request->thumbnail))
            {
                if(!empty($course->thumbnail))
                {
                    delete_file($course->thumbnail);
                }
                $filenameWithExt  = $request->File('thumbnail')->getClientOriginalName();
                $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension        = $request->file('thumbnail')->getClientOriginalExtension();
                $fileNameToStores = $filename . '_' . time() . '.' . $extension;

                $path = upload_file($request,'thumbnail',$fileNameToStores,'thumbnail');

                if($path['flag'] == 1){
                    $url = $path['url'];
                    $course->thumbnail = $url;
                }else{
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }
            $course->featured_course = !empty($request->featured_course)?$request->featured_course:'off';
            $course->type = "Course";
            $course->status = $request->status;

            $course->created_by = creatorId();
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
            }
            $course->update();

            event(new UpdateCourse($request, $course));

            return redirect()->back()->with('success', __('Course updated successfully!'));
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
    public function destroy(Course $course)
    {
        if(\Auth::user()->isAbleTo('course delete'))
        {
            if(!empty($course->preview_content))
            {
                event(new DestroyCourse($course));
                if($course->preview_type == 'Video File')
                {
                    delete_file($course->preview_content);
                }
                elseif($course->preview_type == 'Image')
                {
                    delete_file($course->preview_content);
                }
            }
            if(!empty($course->thumbnail))
            {
                delete_file($course->thumbnail);
            }
            $course->delete();
            return redirect()->back()->with('success', __('Course deleted successfully.'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getsubcategory(Request $request)
    {
        $subcategory = CourseSubcategory::where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->where('category', $request->category_id)->get()->pluck('name', 'id')->toArray();
        return response()->json($subcategory);
    }

    public function practicesFiles(Request $request,$id)
    {
        $course_id = Crypt::decrypt($id);
        $file_name = [];
        if(!empty($request->file) && count($request->file) > 0)
        {
            foreach($request->file as $key => $file)
            {
                $filenameWithExt = $request->file('file')[$key]->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       =$request->file('file')[$key]->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $file_name[]     = $fileNameToStore;

                $path = multi_upload_file($file,'file',$fileNameToStore,'practices');
                if($path['flag'] == 1){
                    $url = $path['url'];
                }else{
                    return response()->json([
                            'status' => 'error',
                            'error' =>  __($path['msg']),
                        ]
                    );
                }
            }
        }
        foreach($file_name as $file)
        {
            $practices_file =
                PracticesFiles::create(
                [
                    'course_id' => $course_id,
                    'file_name' => $file,
                    'files' => $url,
                ]
            );
            event(new CreatePracticeFile($request,$practices_file));
        }
        return response()->json([
                'status' => 'success',
                'success' =>  __('Successfully added!'),
            ]
        );
    }

    public function editFileName($id)
    {
        if(\Auth::user()->isAbleTo('practice file edit'))
        {
            $file_name = PracticesFiles::find($id);
            return view('lms::course.editFileName',compact('file_name'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function updateFileName(Request $request,$id)
    {
        if(\Auth::user()->isAbleTo('practice file edit'))
        {
            $practices_file = PracticesFiles::find($id);
            $practices_file->file_name = $request->file_name;
            $practices_file->update();

            event(new UpdatePracticeFile($request,$practices_file));
            return redirect()->back()->with('success', __('Filename updated successfully') );
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function fileDelete($id)
    {
        if(\Auth::user()->isAbleTo('practice file delete'))
        {
            $practices_file = PracticesFiles::find($id);

            event(new DestroyPracticeFile($practices_file));
            if(!empty($practices_file->files))
            {
                if(!file_exists(base_path($practices_file->files)))
                {
                    $content = DB::table('practices_files')->where('id ', '=', $id)->delete();
                    return response()->json(
                        [
                            'error' => __('File not exists in folder!'),
                            'id' => $id,
                        ]
                    );
                }
                else
                {
                    unlink(base_path($practices_file->files));
                    DB::table('practices_files')->where('id', '=', $id)->delete();
                    return response()->json(
                        [
                            'success' => __('Record deleted successfully!'),
                            'id' => $id,
                        ]
                    );
                }
            }
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function CourseSeoUpdate(Request $request, $id)
    {
        if(\Auth::user()->isAbleTo('course edit'))
        {
            $course = Course::find($id);
            $course->meta_keywords = $request->meta_keywords;
            $course->meta_description = $request->meta_description;
            if(!empty($request->meta_image))
            {
                if(!empty($course->meta_image))
                {
                    delete_file($course->meta_image);
                }
                $filenameWithExt  = $request->File('meta_image')->getClientOriginalName();
                $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension        = $request->file('meta_image')->getClientOriginalExtension();
                $fileNameToStoresmetaimage =  'meta_image'.'_' . $id. '.' . $extension;

                $path = upload_file($request,'meta_image',$fileNameToStoresmetaimage,'course_meta_image');
                if($path['flag'] == 1){
                    $url = $path['url'];
                    $course->meta_image = $url;
                }else{
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }
            if(!empty($request->meta_image)){
                $course->meta_image = $url;
            }

            $course->update();

            event(new UpdateCourseSeo($request,$course));
            return redirect()->back()->with('success', __('Course updated successfully!'). ((isset($result) && $result!=1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

}
