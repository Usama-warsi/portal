<?php

namespace Modules\LMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Modules\LMS\Entities\ChapterFiles;
use Illuminate\Support\Facades\Crypt;
use Modules\LMS\Entities\Chapters;
use Modules\LMS\Entities\LmsUtility;
use Modules\LMS\Events\CreateChapter;
use Modules\LMS\Events\DestroyChapter;
use Modules\LMS\Events\UpdateChapter;

class ChaptersController extends Controller
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
    public function create($course_id,$header_id)
    {
        if(Auth::user()->isAbleTo('chapter create'))
        {
            $chapter_type = LmsUtility::chapter_type();
            return view('lms::chapters.create',compact('header_id','chapter_type','course_id'));
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
    public function store(Request $request,$course_id,$header_id)
    {
        if(Auth::user()->isAbleTo('chapter create'))
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                $msdfgfg  = $messages->first();
                $msg['flag'] = 'error';
                $msg['msg']  = $msdfgfg;
                return $msg;
            }
            $chapters = new Chapters();
            $chapters->header_id = $header_id;
            $chapters->name = $request->name;
            $chapters->duration = $request->duration;
            $chapters->course_id = Crypt::decrypt($course_id);
            $chapters->chapter_description = $request->chapter_description;
            $chapters->type = $request->type;

            if(!empty($request->video_url)){
                $chapters->video_url = $request->video_url;
            }
            if(!empty($request->video_file)){
                $video = $request->file('video_file');
                $ext = $video->getClientOriginalExtension();
                $fileName = 'video_' . time() . rand() . '.' . $ext;

                $path = upload_file($request, 'video_file', $fileName, 'chapters');
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                    $chapters->video_file = $url;
                } else {
                    $res = [
                        'flag' => 0,
                        'msg' => $path['msg'],
                    ];
                    return $res;
                }
            }
            if(!empty($request->iframe)){
                $chapters->iframe = $request->iframe;
            }
            if(!empty($request->text_content)){
                $chapters->text_content = $request->text_content;
            }
            $file_name = [];
            $error_msg=[];
            if(!empty($request->multiple_files) && count($request->multiple_files) > 0)
            {
                foreach($request->multiple_files as $key => $file)
                {
                    $filenameWithExt = $request->file('multiple_files')[$key]->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       =$request->file('multiple_files')[$key]->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $path = multi_upload_file($file,'multiple_files',$fileNameToStore,'chapters');
                    if($path['flag'] == 1)
                    {
                        $url = $path['url'];
                        $file_name[] = $url;

                    }else{
                        $error_msg[] = $path['msg'];
                    }
                }

            }
            $chapters->save();
            foreach($file_name as $file)
            {
                $objStore = ChapterFiles::create(
                    [
                        'chapter_id' => $chapters->id,
                        'chapter_files' => $file,
                    ]
                );
            }

            event(new CreateChapter($request, $chapters));

            if($error_msg ){
                $error_msg = count($error_msg). $error_msg[0];
            }else{
                $error_msg = '';
            }

            if(!empty($chapters))
            {
                $msg['flag'] = 'success';
                $msg['msg']  = __('Content Created Successfully').$error_msg;
            }
            else
            {
                $msg['flag'] = 'error';
                $msg['msg']  = __('Content Failed to Create');
            }

            return $msg;
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
    public function edit($course_id,$id,$header_id)
    {
        if(\Auth::user()->isAbleTo('chapter edit'))
        {
            $chapters = Chapters::find($id);
            $chapter_type = LmsUtility::chapter_type();
            $file = ChapterFiles::where('chapter_id',$id)->get();
            return view('lms::chapters.edit',compact('chapters','chapter_type','header_id','file','course_id'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function ContentsUpdate(Request $request, $id)
    {
        if(\Auth::user()->isAbleTo('chapter edit'))
        {
            $chapters            = Chapters::find($id);
            $chapters->name      = $request->name;
            $chapters->duration  = $request->duration;
            $chapters->chapter_description = $request->chapter_description;
            $chapters->type                = $request->type;
            if(!empty($request->video_url))
            {
                $chapters->video_url = $request->video_url;
            }
            $file_name = [];
            if(!empty($request->video_file))
            {
                if(file_exists($chapters->video_file))
                {
                    delete_file($chapters->video_file);
                }
                $filenameWithExt  = $request->File('video_file')->getClientOriginalName();
                $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension        = $request->file('video_file')->getClientOriginalExtension();
                $fileNameToStores = $filename . '_' . time() . '.' . $extension;

                $path = upload_file($request,'video_file',$fileNameToStores,'chapters');

                if($path['flag'] == 1)
                {
                    $url = $path['url'];
                    $file_name[] = $url;
                }
                $chapters->video_file = $url;

            }
            if(!empty($request->iframe))
            {
                $chapters->iframe = $request->iframe;
            }
            if(!empty($request->text_content))
            {
                $chapters->text_content = $request->text_content;
            }
            $file_name = [];
            if(!empty($request->multiple_files) && count($request->multiple_files) > 0)
            {
                foreach($request->multiple_files as $key => $file)
                {
                    $filenameWithExt = $request->file('multiple_files')[$key]->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       =$request->file('multiple_files')[$key]->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                    $path = multi_upload_file($file,'multiple_files',$fileNameToStore,'chapters');

                    if($path['flag'] == 1)
                    {
                        $url = $path['url'];
                        $objStore = ChapterFiles::create(
                            [
                                'chapter_id' => $chapters->id,
                                'chapter_files' => $url,
                            ]
                        );
                    }else{
                        $file_name[] = $path['msg'];
                    }

                }
                if($file_name ){
                $error_msg= count($file_name). $file_name[0];
                }else{
                    $error_msg= '';
                }
            }
            $chapters->update();

            event(new UpdateChapter($request, $chapters));

            if(!empty($chapters))
            {
                $msg['flag'] = 'success';
                $msg['msg']  = __('Content Updated Successfully');
            }
            else
            {
                $msg['flag'] = 'error';
                $msg['msg']  = __('Content Failed to Update');
            }

            return $msg;
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id,$course_id,$header_id)
    {
        if(\Auth::user()->isAbleTo('chapter delete'))
        {
            $chapters = Chapters::find($id);

            event(new DestroyChapter($chapters));
            if(!empty($chapters->video_file))
            {
                delete_file($chapters->video_file);
            }
            $contents = ChapterFiles::where('chapter_id',$id)->get();
            foreach($contents as $content){
                $dir = delete_file($content->chapter_files);
                if(file_exists($dir)){
                    unlink($dir);
                }
            }
            ChapterFiles::where('chapter_id',$id)->delete();
            $chapters->delete();
            return redirect()->back()->with('success', __('Chapter deleted successfully.'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function fileDelete($id)
    {
        if(\Auth::user()->isAbleTo('chapter delete'))
        {
            $img_id = ChapterFiles::find($id);
            if(!empty($img_id->chapter_files))
            {
                if(!file_exists($img_id->chapter_files))
                {
                    $content = DB::table('chapter_files')->where('id ', '=', $id)->delete();
                    return response()->json(
                        [
                            'error' => __('File not exists in folder!'),
                            'id' => $id,
                        ]
                    );
                }
                else
                {
                    unlink($img_id->chapter_files);
                    DB::table('chapter_files')->where('id', '=', $id)->delete();
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

}
