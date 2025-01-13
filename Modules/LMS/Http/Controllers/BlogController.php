<?php

namespace Modules\LMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LMS\Entities\Blog;
use Modules\LMS\Entities\BlogSocial;
use Modules\LMS\Events\CreateBlog;
use Modules\LMS\Events\DestroyBlog;
use Modules\LMS\Events\UpdateBlog;

class BlogController extends Controller
{
    public function index()
    {
        if(\Auth::user()->isAbleTo('blog manage'))
        {
            $blogs    = Blog::where('workspace_id', getActiveWorkSpace())->where('created_by',creatorId())->get();

            return view('lms::blog.index', compact('blogs'));
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
        if(\Auth::user()->isAbleTo('blog create'))
        {
            return view('lms::blog.create');
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
        if(\Auth::user()->isAbleTo('blog create'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                'title' => 'required|max:120',
                            ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            if(!empty($request->blog_cover_image))
            {
                $extension           = $request->file('blog_cover_image')->getClientOriginalExtension();
                $fileNameToStoreBlog = 'blog' . '_' . time() . '.' . $extension;

                $path = upload_file($request,'blog_cover_image',$fileNameToStoreBlog,'store_logo');
                if($path['flag'] == 1){
                    $url = $path['url'];
                }else{
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }
            $blog                   = new Blog();
            $blog->title            = $request->title;
            $blog->blog_cover_image = !empty($fileNameToStoreBlog) ? $url : null;
            $blog->detail           = $request->detail;
            $blog->workspace_id     = getActiveWorkSpace();
            $blog->created_by       = creatorId();
            $blog->save();

            event(new CreateBlog($request, $blog));

            return redirect()->back()->with('success', __('Blog Successfully added!'));
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
    public function edit(Blog $blog)
    {
        if(\Auth::user()->isAbleTo('blog edit'))
        {
            return view('lms::blog.edit', compact('blog'));
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
    public function update(Request $request, Blog $blog)
    {
        if(\Auth::user()->isAbleTo('blog edit'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                'title' => 'required|max:120',
                            ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            if(!empty($request->blog_cover_image))
            {
                if(!empty($blog->blog_cover_image))
                {
                    delete_file($blog->blog_cover_image);
                }
                $extension           = $request->file('blog_cover_image')->getClientOriginalExtension();
                $fileNameToStoreBlog = 'blog' . '_' . time() . '.' . $extension;

                $path = upload_file($request,'blog_cover_image',$fileNameToStoreBlog,'store_logo');
                if($path['flag'] == 1){
                    $url = $path['url'];
                }else{
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }

            $blog->title = $request->title;
            if(!empty($fileNameToStoreBlog))
            {
                $blog->blog_cover_image = $url;
            }
            $blog->detail = $request->detail;
            $blog->update();

            event(new UpdateBlog($request, $blog));

            return redirect()->back()->with('success', __('Blog Successfully Updated!'));
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
    public function destroy(Blog $blog)
    {
        if(\Auth::user()->isAbleTo('blog delete'))
        {
            event(new DestroyBlog($blog));
            if(!empty($blog->blog_cover_image))
            {
                delete_file($blog->blog_cover_image);
            }
            $blog->delete();

            return redirect()->back()->with('success', __('Blog Deleted!'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function socialBlog()
    {
            $socialblog = BlogSocial::where('workspace_id',getActiveWorkSpace())->first();
            if(!empty($socialblog))
            {
                return view('lms::blog.socialblog', compact('socialblog'));
            }
            else
            {
                return view('lms::blog.store_soicalblog');
            }

    }

    public function storeSocialblog(Request $request)
    {
            if(isset($request->blog_id) && !empty($request->blog_id))
            {
                $blogsocial = BlogSocial::find($request->blog_id);
            }
            else
            {
                $blogsocial = '';
            }

            if(empty($blogsocial))
            {
                $blogsocial                       = new BlogSocial();
                $blogsocial->enable_social_button = isset($request->enable_social_button) ? 'on' : 'off';
                $blogsocial->enable_email         = isset($request->enable_email) ? 'on' : 'off';
                $blogsocial->enable_twitter       = isset($request->enable_twitter) ? 'on' : 'off';
                $blogsocial->enable_facebook      = isset($request->enable_facebook) ? 'on' : 'off';
                $blogsocial->enable_googleplus    = isset($request->enable_googleplus) ? 'on' : 'off';
                $blogsocial->enable_linkedIn      = isset($request->enable_linkedIn) ? 'on' : 'off';
                $blogsocial->enable_pinterest     = isset($request->enable_pinterest) ? 'on' : 'off';
                $blogsocial->enable_stumbleupon   = isset($request->enable_stumbleupon) ? 'on' : 'off';
                $blogsocial->enable_whatsapp      = isset($request->enable_whatsapp) ? 'on' : 'off';
                $blogsocial->workspace_id         = getActiveWorkSpace();
                $blogsocial->created_by           = creatorId();
                $blogsocial->save();
            }
            else
            {
                $blogsocial->enable_social_button = isset($request->enable_social_button) ? 'on' : 'off';
                $blogsocial->enable_email         = isset($request->enable_email) ? 'on' : 'off';
                $blogsocial->enable_twitter       = isset($request->enable_twitter) ? 'on' : 'off';
                $blogsocial->enable_facebook      = isset($request->enable_facebook) ? 'on' : 'off';
                $blogsocial->enable_googleplus    = isset($request->enable_googleplus) ? 'on' : 'off';
                $blogsocial->enable_linkedIn      = isset($request->enable_linkedIn) ? 'on' : 'off';
                $blogsocial->enable_pinterest     = isset($request->enable_pinterest) ? 'on' : 'off';
                $blogsocial->enable_stumbleupon   = isset($request->enable_stumbleupon) ? 'on' : 'off';
                $blogsocial->enable_whatsapp      = isset($request->enable_whatsapp) ? 'on' : 'off';
                $blogsocial->workspace_id         = getActiveWorkSpace();
                $blogsocial->created_by           = creatorId();
                $blogsocial->update();
            }

            return redirect()->back()->with('success', __('Social Blog Successfully added!'));

    }
}
