<?php

namespace Modules\Newsletter\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Newsletter\Entities\Newsletters;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (\Auth::user()->isAbleTo('newsletter history manage')) {

            $newsletters = Newsletters::where('workspace_id',getActiveWorkSpace())->orderBy('created_at', 'DESC')->get();
             return view('newsletter::historys.index',compact('newsletters'));
        }
        else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }


    public function create()
    {
        return view('newsletter::historys.index');
    }


    public function store(Request $request)
    {
        //
    }


    public function edit($id)
    {
        return view('newsletter::historys.index');
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function show($id)
    {

        if (\Auth::user()->isAbleTo('newsletter history show')) {
            $newsletter = Newsletters::find($id);
            return view('newsletter::historys.view', compact('newsletter'));
        }else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function destroy($id)
    {

        if (\Auth::user()->isAbleTo('newsletter history delete')) {
            $newsletter = Newsletters::find($id);
            $newsletter->delete();
            return redirect()->route('newsletter-history.index')->with('success', __('History deleted successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
