<?php
// This file use for handle company setting page

namespace Modules\LMS\Http\Controllers\Company;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($settings)
    {
        $store_settings = \Modules\LMS\Entities\Store::where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->first();
        $app_url = trim(env('APP_URL'), '/');
        $store_settings['store_url'] = $app_url . '/store-lms/' . $store_settings['slug'];

        $serverName = str_replace(
            [
                'http://',
                'https://',
            ], '', env('APP_URL')
        );
        $serverIp   = gethostbyname($serverName);
        if($serverIp != $serverName)
        {
            $serverIp;
        }
        else
        {
            $serverIp = request()->server('SERVER_ADDR');
        }
        $store_lang = $store_settings->lang;
        return view('lms::company.settings.index',compact('settings','serverIp','store_lang','store_settings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }
}
