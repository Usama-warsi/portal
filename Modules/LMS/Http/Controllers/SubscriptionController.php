<?php

namespace Modules\LMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LMS\Entities\Subscription;
use Illuminate\Support\Facades\Auth;
use Modules\LMS\Entities\Store;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->isAbleTo('subscriber manage'))
        {
            if(Auth::check())
            {
                $subs = Subscription::where('workspace_id',getActiveWorkSpace())->get();

                return view('lms::Subscription.index', compact('subs'));
            }else{
                return redirect()->back()->with('error', __('You need Login'));
            }
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
        if(\Auth::user()->isAbleTo('subscriber create'))
        {
            return view('lms::Subscription.create');
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
        if(\Auth::user()->isAbleTo('subscriber create'))
        {


            $validator = \Validator::make(
                $request->all(), [
                    'email' => 'required',
                            ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
            }


            $subscription               = new Subscription();
            $subscription->email        = $request->email;
            $subscription->workspace_id = getActiveWorkSpace();
            $subscription->save();

            return redirect()->back()->with('success', __('Email added!'));
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
        return view('lms::edit');
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
    public function destroy(Subscription $subscription)
    {
        if(\Auth::user()->isAbleTo('subscriber delete'))
        {
            $subscription->delete();

            return redirect()->back()->with(
                'success', __('subscription Deleted!')
            );
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function store_email(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'email' => 'required|email',
                           ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $store = Store::find($id);
        $subscription             = new Subscription();
        $subscription['email']    = $request->email;
        $subscription['workspace_id'] = $store->workspace_id;
        $subscription->save();

        return redirect()->back()->with('success', __('Succefully Subscribe'));
    }
}
