<?php

namespace Modules\LMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LMS\Entities\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StoreAnalyticController extends Controller
{
    public function index()
    {
        if(\Auth::user()->isAbleTo('lms store analytics'))
        {
            $user      = Auth::user();
            $store     = Store::where('workspace_id',getActiveWorkSpace())->first();
            $slug      = $store->slug;
            $chartData = $this->getOrderChart(['duration' => 'month','slug'=>$slug]);

            $visitor_url   = DB::table('course_visitors')->selectRaw("count('*') as total, referer")->where('slug', $slug)->groupBy('referer')->orderBy('total', 'DESC')->get();
            $user_device   = DB::table('course_visitors')->selectRaw("count('*') as total, device")->selectRaw("device, SUM(pageview) as totalPageview")->where('slug', $slug)->groupBy('device')->orderBy('device', 'DESC')->get();
            $user_browser  = DB::table('course_visitors')->selectRaw("count('*') as total, browser")->selectRaw("browser, SUM(pageview) as totalPageview")->where('slug', $slug)->groupBy('browser')->orderBy('browser', 'DESC')->get();
            $user_platform = DB::table('course_visitors')->selectRaw("count('*') as total, platform")->selectRaw("platform, SUM(pageview) as totalPageview")->where('slug', $slug)->groupBy('platform')->orderBy('platform', 'DESC')->get();

            $devicearray          = [];
            $devicearray['label'] = [];
            $devicearray['data']  = [];
            foreach($user_device as $name => $device)
            {
                if(!empty($device->device))
                {
                    $devicearray['label'][] = $device->device;
                }
                else
                {
                    $devicearray['label'][] = 'Other';
                }
                $devicearray['data'][] = $device->totalPageview;
            }

            $browserarray          = [];
            $browserarray['label'] = [];
            $browserarray['data']  = [];

            foreach($user_browser as $name => $browser)
            {
                $browserarray['label'][] = $browser->browser;
                $browserarray['data'][]  = $browser->totalPageview;
            }
            $platformarray          = [];
            $platformarray['label'] = [];
            $platformarray['data']  = [];

            foreach($user_platform as $name => $platform)
            {
                $platformarray['label'][] = $platform->platform;
                $platformarray['data'][]  = $platform->totalPageview;
            }


            return view('lms::store-analytic', compact('chartData','visitor_url','devicearray', 'browserarray', 'platformarray'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getOrderChart($arrParam)
    {
        $slug  = $arrParam['slug'];

        $arrDuration = [];
        if($arrParam['duration'])
        {
            if($arrParam['duration'] == 'month')
            {
                $previous_month = strtotime("-1 month +2 day");
                for($i = 0; $i < 15; $i++)
                {
                    $arrDuration[date('Y-m-d', $previous_month)] = date('d-M', $previous_month);
                    $previous_month                              = strtotime(date('Y-m-d', $previous_month) . " +1 day");
                }
            }
        }
        $arrTask          = [];
        $arrTask['label'] = [];
        $arrTask['data']  = [];

        foreach($arrDuration as $date => $label)
        {
            $data['visitor'] = DB::table('course_visitors')->select(DB::raw('count(*) as total'))->where('slug', $slug)->whereDate('created_at', '=', $date)->first();
            $uniq            = DB::table('course_visitors')->select('ip')->distinct()->where('slug', $slug)->whereDate('created_at', '=', $date)->get();

            $data['unique']           = $uniq->count();
            $arrTask['label'][]       = $label;
            $arrTask['data'][]        = $data['visitor']->total;
            $arrTask['unique_data'][] = $data['unique'];
        }

        return $arrTask;
    }

    public function StoreAnalytic()
    {
        if(\Auth::user()->isAbleTo('lms store analytics'))
        {
            $user      = Auth::user();
            $store     = Store::where('workspace_id',getActiveWorkSpace())->first();
            $slug      = $store->slug;
            $chartData = $this->getOrderChart(['duration' => 'month','slug'=>$slug]);


            $user_device   = DB::table('course_visitors')->selectRaw("count('*') as total, device")->selectRaw("device, SUM(pageview) as totalPageview")->where('slug', $slug)->groupBy('device')->orderBy('device', 'DESC')->get();
            $user_browser  = DB::table('course_visitors')->selectRaw("count('*') as total, browser")->selectRaw("browser, SUM(pageview) as totalPageview")->where('slug', $slug)->groupBy('browser')->orderBy('browser', 'DESC')->get();
            $user_platform = DB::table('course_visitors')->selectRaw("count('*') as total, platform")->selectRaw("platform, SUM(pageview) as totalPageview")->where('slug', $slug)->groupBy('platform')->orderBy('platform', 'DESC')->get();

            $devicearray          = [];
            $devicearray['label'] = [];
            $devicearray['data']  = [];
            foreach($user_device as $name => $device)
            {
                if(!empty($device->device))
                {
                    $devicearray['label'][] = $device->device;
                }
                else
                {
                    $devicearray['label'][] = 'Other';
                }
                $devicearray['data'][] = $device->totalPageview;
            }

            $browserarray          = [];
            $browserarray['label'] = [];
            $browserarray['data']  = [];

            foreach($user_browser as $name => $browser)
            {
                $browserarray['label'][] = $browser->browser;
                $browserarray['data'][]  = $browser->totalPageview;
            }
            $platformarray          = [];
            $platformarray['label'] = [];
            $platformarray['data']  = [];

            foreach($user_platform as $name => $platform)
            {
                $platformarray['label'][] = $platform->platform;
                $platformarray['data'][]  = $platform->totalPageview;
            }


            return view('lms::company.settings.store-analytic', compact('chartData','devicearray', 'browserarray', 'platformarray'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
