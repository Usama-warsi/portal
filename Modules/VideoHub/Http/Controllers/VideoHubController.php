<?php

namespace Modules\VideoHub\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Lead\Entities\Deal;
use Modules\Lead\Entities\Lead;
use Modules\ProductService\Entities\ProductService;
use Modules\PropertyManagement\Entities\Property;
use Modules\SalesAgent\Entities\Program;
use Modules\Taskly\Entities\Project;
use Modules\VideoHub\Entities\VideoHubComment;
use Modules\VideoHub\Entities\VideoHubModule;
use Modules\VideoHub\Entities\VideoHubVideo;
use Modules\VideoHub\Events\CreateVideoHubComment;
use Modules\VideoHub\Events\CreateVideoHubVideo;
use Modules\VideoHub\Events\DestroyVideoHubVideo;
use Modules\VideoHub\Events\UpdateVideoHubVideo;

class VideoHubController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function index(Request $request)
    {
        if (module_is_active('VideoHub')) {
            if (Auth::user()->isAbleTo('videohub manage')) {

                $creatorId          = creatorId();
                $getActiveWorkSpace = getActiveWorkSpace();
                $active_modules     = ActivatedModule();
                $filter_module      = $request->filter;
                if ($request->filter == 'Items') {
                    $filter_module = 'Product Service';
                } elseif ($request->filter == 'Insurance') {
                    $filter_module = 'Insurance Management';
                } elseif ($request->filter == 'Property Manage') {
                    $filter_module = 'Property Management';
                } elseif ($request->filter == 'Rental') {
                    $filter_module = 'Rental Management';
                } elseif ($request->filter == 'Business Mapping') {
                    $filter_module = 'Business Process Mapping';
                }

                $videos = VideoHubVideo::where('created_by', $creatorId)
                            ->where('workspace', $getActiveWorkSpace)
                            ->withCount('comments')
                            ->get();

                $modules = VideoHubModule::select('video_hub_modules.module', 'add_ons.name as module_name')
                            ->join('add_ons', 'video_hub_modules.module', '=', 'add_ons.name')
                            ->groupBy('video_hub_modules.module', 'add_ons.name')
                            ->get()
                            ->pluck('module', 'module')
                            ->toArray();

                if (!empty($filter_module)) {
                    $videos = VideoHubVideo::where('created_by', $creatorId)
                                ->where('workspace', $getActiveWorkSpace)
                                ->withCount('comments')
                                ->where('module', '=', $filter_module)
                                ->get();
                }
                if (!empty($request->sub_module)) {
                    $videos = VideoHubVideo::where('created_by', $creatorId)
                                ->where('workspace', $getActiveWorkSpace)
                                ->withCount('comments')
                                ->where('module', '=', $filter_module)
                                ->where('sub_module_id', '=', $request->sub_module)
                                ->get();
                }
                if (!empty($request->item)) {
                    if ($filter_module == 'Project' || $filter_module == 'Property Management' || $filter_module == 'Sales Agent') {
                        $videos = VideoHubVideo::where('created_by', $creatorId)
                                    ->where('workspace', $getActiveWorkSpace)
                                    ->withCount('comments')
                                    ->where('module', '=', $filter_module)
                                    ->where('item_id', '=', $request->item)
                                    ->get();
                    } else {
                        $videos = VideoHubVideo::where('created_by', $creatorId)
                                    ->where('workspace', $getActiveWorkSpace)
                                    ->withCount('comments')
                                    ->where('module', '=', $filter_module)
                                    ->where('sub_module_id', '=', $request->sub_module)
                                    ->where('item_id', '=', $request->item)
                                    ->get();
                    }
                }
                return view('videohub::videos.index', compact('videos','modules','active_modules'));
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
        if (Auth::user()->isAbleTo('video create')) {
            $active_modules = ActivatedModule();
            $modules = VideoHubModule::select('video_hub_modules.module', 'add_ons.name as module_name')
                        ->join('add_ons', 'video_hub_modules.module', '=', 'add_ons.name')
                        ->groupBy('video_hub_modules.module', 'add_ons.name')
                        ->get()
                        ->pluck('module', 'module')
                        ->toArray();

            $mp4_msg = null;
            $admin_setting = getAdminAllSetting();
            $allowed_extensions = explode(',', isset($admin_setting['local_storage_validation']) ? $admin_setting['local_storage_validation'] : '');

            if (in_array("mp4", $allowed_extensions)) {
                $mp4_msg = '';
            } else {
                $mp4_msg = 'You can`t upload mp4 video because superadmin has not allowed it in storage settings.';
            }

            return view('videohub::videos.create', compact('modules', 'mp4_msg','active_modules'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('video create')) {

            $creatorId = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();

            if ($request->video_type == 'video_file') {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'title'     => 'required',
                        'module'    => 'required',
                        'video'     => 'required | mimes:mp4,ogx,oga,ogv,ogg,webm',
                    ]
                );
            }
            if ($request->video_type == 'video_url') {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'title'     => 'required',
                        'module'    => 'required',
                        'video'     => 'required',
                    ]
                );
            }
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $video                   = new VideoHubVideo();
            $video['title']          = $request->title;
            if ($request->module == 'Items') {
                $video['module'] = 'Product Service';
            } elseif ($request->module == 'Insurance') {
                $video['module'] = 'Insurance Management';
            } elseif ($request->module == 'Property Manage') {
                $video['module'] = 'Property Management';
            } elseif ($request->module == 'Rental') {
                $video['module'] = 'Rental Management';
            } elseif ($request->module == 'Business Mapping') {
                $video['module'] = 'Business Process Mapping';
            } else {
                $video['module'] = $request->module;
            }
            // $video['module']         = ($request->module == 'Items') ? 'Product Service' : $request->module;
            if (!empty($request->sub_module_create)) {
                $video['sub_module_id']  = $request->sub_module_create;
            } else {
                $video['sub_module_id']  = '';
            }
            if (!empty($request->item)) {
                $video['item_id']        = $request->item;
            } else {
                $video['item_id']        = '';
            }

            if ($request->hasFile('thumbnail')) {
                $filenameWithExt    = $request->file('thumbnail')->getClientOriginalName();
                $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension          = $request->file('thumbnail')->getClientOriginalExtension();
                $fileName           = 'thumbnail_' . $filename . time() . rand() . '.' . $extension;
                $upload_thumbnail   = upload_file($request, 'thumbnail', $fileName, 'Video_Hub');

                if ($upload_thumbnail['flag'] == 1) {
                    $url            = $upload_thumbnail['url'];
                } else {
                    return redirect()->back()->with('error', $upload_thumbnail['msg']);
                }
                $video['thumbnail'] = $url;
            }
            if ($request->video_type == 'video_file') {
                if ($request->video) {
                    $filenameWithExt    = $request->file('video')->getClientOriginalName();
                    $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $uploadedVideo      = $request->file('video');
                    $extension          = $request->file('video')->getClientOriginalExtension();
                    $fileName           = 'video_' . $filename . time() . rand() . '.' . $extension;
                    $upload_video       = upload_file($request, 'video', $fileName, 'Video_Hub');

                    if ($upload_video['flag'] == 1) {
                        $url            = $upload_video['url'];
                    } else {
                        return redirect()->back()->with('error', $upload_video['msg']);
                    }
                    $video['video']     = $url;
                }
            } else {
                $video['video'] = $request->video;
            }

            $video['type']           = $request->video_type;
            $video['description']    = $request->description;
            $video['workspace']      = $getActiveWorkSpace;
            $video['created_by']     = $creatorId;
            $video->save();

            event(new CreateVideoHubVideo($request,$video));

            return redirect()->back()->with('success', __('Video Successfully Added.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if (Auth::user()->isAbleTo('video view')) {
            $video = VideoHubVideo::find($id);
            if (!empty($video)) {

                $comments       = VideoHubComment::where('video_id', $id)->where('parent', 0)->with('commentUser')->with('subComment')->get();

                return view('videohub::videos.show', compact('video', 'comments'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('video edit')) {
            $video          = VideoHubVideo::find($id);
            $active_modules = ActivatedModule();
            $modules = VideoHubModule::select('video_hub_modules.module', 'add_ons.name as module_name')
                        ->join('add_ons', 'video_hub_modules.module', '=', 'add_ons.name')
                        ->groupBy('video_hub_modules.module', 'add_ons.name')
                        ->get()
                        ->pluck('module', 'module')
                        ->toArray();

            $mp4_msg        = null;
            $admin_setting = getAdminAllSetting();
            $allowed_extensions = explode(',', isset($admin_setting['local_storage_validation']) ? $admin_setting['local_storage_validation'] : '');

            if (in_array("mp4", $allowed_extensions)) {
                $mp4_msg = '';
            } else {
                $mp4_msg = 'You can`t upload mp4 video because superadmin has not allowed it in storage settings.';
            }

            return view('videohub::videos.edit', compact('video', 'modules', 'mp4_msg','active_modules'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
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
        if (Auth::user()->isAbleTo('video edit')) {

            $creatorId = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();

            if ($request->video_type == 'video_file') {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'title'         => 'required',
                        'module_edit'   => 'required',
                    ]
                );
                if (!empty($request->video)) {
                    $validator = \Validator::make(
                        $request->all(),
                        [
                            'video'     => 'mimes:mp4,ogx,oga,ogv,ogg,webm',
                        ]
                    );
                }
            }
            if ($request->video_type == 'video_url') {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'title'         => 'required',
                        'module_edit'   => 'required',
                        'video'         => 'required',
                    ]
                );
            }
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $video                      = VideoHubVideo::find($id);
            $video->title               = $request->title;
            if ($request->module_edit == 'Items') {
                $video->module = 'Product Service';
            } elseif ($request->module_edit == 'Insurance') {
                $video->module = 'Insurance Management';
            } elseif ($request->module_edit == 'Property Manage') {
                $video->module = 'Property Management';
            } elseif ($request->module_edit == 'Rental') {
                $video->module = 'Rental Management';
            } elseif ($request->module == 'Business Mapping') {
                $video['module'] = 'Business Process Mapping';
            } else {
                $video->module = $request->module_edit;
            }
            // $video->module              = ($request->module_edit == 'Items') ? 'Product Service' : $request->module_edit;
            if (!empty($request->sub_module_edit)) {
                $video->sub_module_id   = $request->sub_module_edit;
            } else {
                $video->sub_module_id   = '';
            }
            if (!empty($request->item)) {
                $video->item_id         = $request->item;
            } else {
                $video->item_id         = '';
            }

            if ($request->hasFile('thumbnail')) {
                $filenameWithExt    = $request->file('thumbnail')->getClientOriginalName();
                $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension          = $request->file('thumbnail')->getClientOriginalExtension();
                $fileName           = 'thumbnail_' . $filename . time() . rand() . '.' . $extension;
                $upload_thumbnail   = upload_file($request, 'thumbnail', $fileName, 'Video_Hub');

                if ($upload_thumbnail['flag'] == 1) {
                    $url = $upload_thumbnail['url'];
                } else {
                    return redirect()->back()->with('error', $upload_thumbnail['msg']);
                }
                $video->thumbnail   = $url;
            }
            if ($request->video_type == 'video_file') {
                if ($request->video && !empty($request->video)) {
                    $filenameWithExt    = $request->file('video')->getClientOriginalName();
                    $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $uploadedVideo      = $request->file('video');
                    $extension          = $request->file('video')->getClientOriginalExtension();
                    $fileName           = 'video_' . $filename . time() . rand() . '.' . $extension;
                    $upload_video       = upload_file($request, 'video', $fileName, 'Video_Hub');

                    if ($upload_video['flag'] == 1) {
                        $url = $upload_video['url'];
                    } else {
                        return redirect()->back()->with('error', $upload_video['msg']);
                    }
                    $video->video       = $url;
                }
            } else {
                $video->video = $request->video;
            }

            $video->type            = $request->video_type;
            $video->description     = $request->description;
            $video->workspace       = $getActiveWorkSpace;
            $video->created_by      = $creatorId;
            $video->save();

            event(new UpdateVideoHubVideo($request,$video));

            return redirect()->back()->with('success', __('Video Successfully Updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('video delete')) {
            $video      = VideoHubVideo::findOrFail($id);
            $comments    = VideoHubComment::where('video_id',$id)->get();
            $video->delete();
            if (!empty($comments)) {
                foreach ($comments as $comment) {
                    $comment->delete();
                }
            }

            event(new DestroyVideoHubVideo($video));

            return redirect()->back()->with('success', 'Video successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function module(Request $request)
    {
        if (!empty($request->module)) {
            $video_modules = VideoHubModule::where('module', $request->module)->get()->pluck('sub_module', 'id');
            $response = [
                'video_modules'   => $video_modules,
                'module'          => $request->module,
            ];
            return response()->json($response);
        }
    }
    public function getfield(Request $request)
    {
        if (!empty($request->module)) {
            $sub_module = VideoHubModule::find($request->module);
            $creatorId = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();


            if (!empty($sub_module->sub_module) || !empty($sub_module->field_json)) {

                $field_data = json_decode($sub_module->field_json);
                $data       = null;
                $itemId     = $request->itemId;

                foreach ($field_data->field as $value) {
                    if ($value->field_type == "select") {
                        if ($value->model_name == 'Lead') {
                            $data['Lead'] = Lead::where('created_by', $creatorId)->where('workspace_id', $getActiveWorkSpace)->get()->pluck('name', 'id');
                        } elseif ($value->model_name == 'Deal') {
                            $data['Deal'] = Deal::where('created_by', $creatorId)->where('workspace_id', $getActiveWorkSpace)->get()->pluck('name', 'id');
                        } elseif ($value->model_name == 'Project') {
                            $data['Project'] = Project::where('created_by', $creatorId)->where('workspace', $getActiveWorkSpace)->projectonly()->get()->pluck('name', 'id');
                        } elseif ($value->model_name == 'product' || $value->model_name == 'service' || $value->model_name == 'parts' || $value->model_name == 'rent' || $value->model_name == 'music institute') {
                            $data[$value->label] = ProductService::where('created_by', $creatorId)->where('workspace_id', $getActiveWorkSpace)->where('type',$value->model_name)->get()->pluck('name', 'id');
                        } elseif ($value->model_name == 'Property') {
                            $data[$value->label] = Property::where('created_by', $creatorId)->where('workspace', $getActiveWorkSpace)->get()->pluck('name', 'id');
                        } elseif ($value->model_name == 'Programs') {
                            $data[$value->label] = Program::where('created_by', $creatorId)->where('workspace', $getActiveWorkSpace)->get()->pluck('name', 'id');
                        } else {
                            return redirect()->back()->with('error', __('Permission denied.'));
                        }
                    }
                }
                $returnHTML = view('videohub::videos.inputfields', compact('sub_module', 'data', 'request', 'field_data', 'itemId'))->render();
                $response = [
                    'is_success'    => true,
                    'message'       => '',
                    'html'          => $returnHTML,
                ];
                return response()->json($response);
            }
        }
    }
    public function List(Request $request)
    {
        if (module_is_active('VideoHub')) {
            if (Auth::user()->isAbleTo('videohub manage')) {
                $creatorId = creatorId();
                $getActiveWorkSpace = getActiveWorkSpace();
                $active_modules     = ActivatedModule();
                $filter_module      = $request->filter;
                if ($request->filter == 'Items') {
                    $filter_module = 'Product Service';
                } elseif ($request->filter == 'Insurance') {
                    $filter_module = 'Insurance Management';
                } elseif ($request->filter == 'Property Manage') {
                    $filter_module = 'Property Management';
                } elseif ($request->filter == 'Rental') {
                    $filter_module = 'Rental Management';
                } elseif ($request->filter == 'Business Mapping') {
                    $filter_module = 'Business Process Mapping';
                }

                $videos = VideoHubVideo::select('video_hub_videos.*','add_ons.name as module_name')
                            ->join('add_ons', 'video_hub_videos.module', '=', 'add_ons.name')
                            ->groupBy('video_hub_videos.id','video_hub_videos.module', 'add_ons.name')
                            ->where('video_hub_videos.created_by', $creatorId)
                            ->where('video_hub_videos.workspace', $getActiveWorkSpace)
                            ->orderBy('video_hub_videos.created_at', 'asc')
                            ->get();

                $modules = VideoHubModule::select('video_hub_modules.module', 'add_ons.name as module_name')
                            ->join('add_ons', 'video_hub_modules.module', '=', 'add_ons.name')
                            ->groupBy('video_hub_modules.module', 'add_ons.name')
                            ->get()
                            ->pluck('module', 'module')
                            ->toArray();

                if (!empty($filter_module)) {
                    $videos = VideoHubVideo::select('video_hub_videos.*','add_ons.name as module_name')
                                ->join('add_ons', 'video_hub_videos.module', '=', 'add_ons.name')
                                ->groupBy('video_hub_videos.id','video_hub_videos.module', 'add_ons.name')
                                ->where('video_hub_videos.created_by', $creatorId)
                                ->where('video_hub_videos.workspace', $getActiveWorkSpace)
                                ->orderBy('video_hub_videos.created_at', 'asc')
                                ->where('video_hub_videos.module', '=', $filter_module)
                                ->get();
                }
                if (!empty($request->sub_module)) {
                    $videos = VideoHubVideo::select('video_hub_videos.*','add_ons.name as module_name')
                                ->join('add_ons', 'video_hub_videos.module', '=', 'add_ons.name')
                                ->groupBy('video_hub_videos.id','video_hub_videos.module', 'add_ons.name')
                                ->where('video_hub_videos.created_by', $creatorId)
                                ->where('video_hub_videos.workspace', $getActiveWorkSpace)
                                ->orderBy('video_hub_videos.created_at', 'asc')
                                ->where('video_hub_videos.module', '=', $filter_module)
                                ->where('video_hub_videos.sub_module_id', '=', $request->sub_module)
                                ->get();
                }
                if (!empty($request->item)) {
                    if ($filter_module == 'Project' || $filter_module == 'Property Management' || $filter_module == 'Sales Agent') {
                        $videos = VideoHubVideo::select('video_hub_videos.*','add_ons.name as module_name')
                                    ->join('add_ons', 'video_hub_videos.module', '=', 'add_ons.name')
                                    ->groupBy('video_hub_videos.id','video_hub_videos.module', 'add_ons.name')
                                    ->where('video_hub_videos.created_by', $creatorId)
                                    ->where('video_hub_videos.workspace', $getActiveWorkSpace)
                                    ->orderBy('video_hub_videos.created_at', 'asc')
                                    ->where('video_hub_videos.module', '=', $filter_module)
                                    ->where('video_hub_videos.item_id', '=', $request->item)
                                    ->get();
                    } else {
                        $videos = VideoHubVideo::select('video_hub_videos.*','add_ons.name as module_name')
                                    ->join('add_ons', 'video_hub_videos.module', '=', 'add_ons.name')
                                    ->groupBy('video_hub_videos.id','video_hub_videos.module', 'add_ons.name')
                                    ->where('video_hub_videos.created_by', $creatorId)
                                    ->where('video_hub_videos.workspace', $getActiveWorkSpace)
                                    ->orderBy('video_hub_videos.created_at', 'asc')
                                    ->where('video_hub_videos.module', '=', $filter_module)
                                    ->where('video_hub_videos.sub_module_id', '=', $request->sub_module)
                                    ->where('video_hub_videos.item_id', '=', $request->item)
                                    ->get();
                    }

                }
                return view('videohub::videos.list', compact('videos','modules','active_modules'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function videoCommentStore(Request $request, $video_id)
    {
        if (Auth::user()->isAbleTo('video comment create') || Auth::user()->isAbleTo('video comment reply')){

            $getActiveWorkSpace = getActiveWorkSpace();

            $video = VideoHubVideo::find($video_id);
            $validator = \Validator::make(
                $request->all(),
                [
                    'comment' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('videos.show', \Crypt::encrypt($video_id))->with('error', $messages->first());
            }
            if ($request->hasFile('file')) {
                $filenameWithExt = $request->file('file')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('file')->getClientOriginalExtension();
                $fileName = 'file_' . $filename . time() . rand() . '.' . $extension;


                $upload_file = upload_file($request, 'file', $fileName, 'Video_Hub');
                if ($upload_file['flag'] == 1) {
                    $url = $upload_file['url'];
                } else {
                    return redirect()->back()->with('error', $upload_file['msg']);
                }
            }

            $comments             = new VideoHubComment();
            $comments->video_id   = $video->id;
            $comments->file       = !empty($fileName) ? $fileName : '';
            $comments->comment    = $request->comment;
            $comments->parent     = !empty($request->parent) ? $request->parent : 0;
            $comments->comment_by = \Auth::user()->id;
            $comments->workspace  = $getActiveWorkSpace;
            $comments->save();

            event(new CreateVideoHubComment($request,$video,$comments));

            return redirect()->back()->with('success', __('Comment Successfully Posted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function videoCommentReply($video_id, $comment_id)
    {
        return view('videohub::videos.commentReply', compact('video_id', 'comment_id'));
    }
}
