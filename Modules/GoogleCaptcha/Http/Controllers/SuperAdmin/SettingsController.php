<?php
// This file use for handle super admin setting page

namespace Modules\GoogleCaptcha\Http\Controllers\SuperAdmin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($settings)
    {
        $google_recaptcha_version=['v2-checkbox' => __('v2'),'v3' => __('v3')];
        return view('googlecaptcha::super-admin.settings.index',compact('settings','google_recaptcha_version'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }
}
