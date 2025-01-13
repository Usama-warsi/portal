<?php

namespace Modules\LMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LMS\Entities\PageOption;
use Illuminate\Support\Facades\Auth;
use Modules\LMS\Entities\Store;
use Modules\LMS\Events\CreateCustomPage;
use Modules\LMS\Events\DestroyCustomPage;
use Modules\LMS\Events\UpdateCustomPage;

class PageOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(Auth::user()->isAbleTo('custom page manage'))
        {
            $store_id    = Auth::user();
            $store_settings = Store::where('workspace_id', getActiveWorkSpace())->where('created_by', creatorId())->first();
            $pageoptions = PageOption::where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->get();
            // Get the remote domain
            $store = Store::where('id',$store_settings->id)->where('enable_domain' , 'on')->first();

            // If the subdomain exists
            $sub_store = Store::where('id', $store_settings->id)->where('enable_subdomain' , 'on')->first();


            return view('lms::pageoption.index', compact('pageoptions','store_id','store_settings','store','sub_store'));
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
        if(\Auth::user()->isAbleTo('custom page create'))
        {
            return view('lms::pageoption.create');
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
        if(\Auth::user()->isAbleTo('custom page create'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                'name' => 'required|max:120',
                                'contents' => 'required',
                            ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            $data     = [
                'name' => $request->name,
                'contents' => $request->contents,
                'enable_page_header' => !empty($request->enable_page_header) ? $request->enable_page_header : 'off',
                'workspace_id' => getActiveWorkSpace(),
                'created_by' => creatorId(),
            ];
            $pageOption = PageOption::create($data);
            event(new CreateCustomPage($request, $pageOption));

            return redirect()->back()->with('success', __('Page Successfully added!'));
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
        if(\Auth::user()->isAbleTo('custom page edit'))
        {
            $pageOption = PageOption::find($id);
            return view('lms::pageoption.edit', compact('pageOption'));
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
        if(\Auth::user()->isAbleTo('custom page edit'))
        {
            $pageOption = PageOption::find($id);
            $validator = \Validator::make(
                $request->all(), [
                                'name' => 'required|max:120',
                                'contents' => 'required',
                            ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $pageOption->name     = $request->name;
            $pageOption->contents = $request->contents;
            $pageOption->enable_page_header = $request->enable_page_header;
            $pageOption->update();

            event(new UpdateCustomPage($request, $pageOption));

            return redirect()->back()->with('success', __('Page Successfully Updated!'));
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
        if(\Auth::user()->isAbleTo('custom page delete'))
        {
            $pageOption = PageOption::find($id);

            event(new DestroyCustomPage($pageOption));

            $pageOption->delete();

            return redirect()->back()->with('success', __('Page Deleted!'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
