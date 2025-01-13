<?php

namespace Modules\LMS\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\LMS\Entities\Store;
use Modules\LMS\Entities\LmsUtility;
use Modules\LMS\Entities\CourseCategory;
use Modules\LMS\Entities\PageOption;
use Modules\LMS\Entities\Blog;
use Modules\LMS\Entities\Course;
use Modules\LMS\Entities\Student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Modules\LMS\Entities\BlogSocial;
use Illuminate\Support\Facades\Crypt;
use Modules\LMS\Entities\Chapters;
use Modules\LMS\Entities\ChapterStatus;
use Modules\LMS\Entities\CourseFaq;
use Modules\LMS\Entities\CourseOrder;
use Modules\LMS\Entities\CourseOrderSummary;
use Modules\LMS\Entities\CourseVisitor;
use Modules\LMS\Entities\Header;
use Modules\LMS\Entities\LmsPixelFields;
use Modules\LMS\Entities\LmsQr;
use Modules\LMS\Entities\PracticesFiles;
use Modules\LMS\Entities\PurchasedCourse;
use Modules\LMS\Entities\Ratting;
use Modules\LMS\Entities\Wishlist;
use Modules\LMS\Events\CreateStudent;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->isAbleTo('store manage'))
        {
            $stores = Store::where('workspace_id', getActiveWorkSpace())->where('created_by',creatorId())->get();
            return view('lms::store.index', compact('stores'));
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if(\Auth::user()->isAbleTo('store create'))
        {
            $store = Store::where('workspace_id',getActiveWorkSpace())->first();
            return view('lms::store.create', compact('store'));
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $user        = \Auth::user();
        $company_settings = getCompanyAllSetting();
        $objStore   = Store::create(
            [
                'created_by' => creatorId(),
                'workspace_id' => getActiveWorkSpace(),
                'name' => $request['store_name'],
                'logo' => !empty($company_settings['logo']) ? $company_settings['logo'] : 'logo.png',
                'invoice_logo' => !empty($company_settings['logo']) ? $company_settings['logo'] : 'invoice_logo.png',
                'lang' => !empty($company_settings['default_language']) ? $company_settings['default_language'] : 'en',
                'currency' => !empty($company_settings['currency_symbol']) ? $company_settings['currency_symbol'] : '$',
                'currency_code' => !empty($company_settings['currency']) ? $company_settings['currency'] : 'USD',
            ]
        );
        // $objStore->enable_storelink = 'on';
        $objStore->theme_dir        = $request['themefile'];
        $objStore->store_theme      = $request['theme_color'];
        $objStore->header_name          = 'Course Certificate';
        $objStore->certificate_template = 'template1';
        $objStore->certificate_color    = 'b10d0d';
        $objStore->certificate_gradiant = 'color-one';
        $objStore->save();

        return redirect()->back()->with('Success', __('Successfully added!'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('lms::show');
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
    public function destroy($id)
    {
        //
    }

    public function changeLanquageStore($slug,$lang)
    {
        session(['lang' => $lang]);

        return redirect()->back()->with('success', __('Language change successfully.'));
    }

    public function storeSlug($slug)
    {
        $store   = Store::getStore($slug);
        if(isset($store->lang))
        {
            $lang = session()->get('lang');

            if(!isset($lang))
            {
             session(['lang' => $store->lang]);
             $storelang=session()->get('lang');
             \App::setLocale(isset($storelang) ? $storelang : 'en');
            }
            else
            {
                session(['lang' => $lang]);
                $storelang=session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
            }
        }
        if(!empty($slug) && $store != null)
        {
            $ip = $_SERVER['REMOTE_ADDR']; // your ip address here
            // $ip = '49.36.85.154'; // This is static ip address
            $query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));
            if (isset($query['status']) &&  $query['status'] != 'fail') {
                $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
                if ($whichbrowser->device->type == 'bot') {
                    return;
                }
                $referrer = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER']) : null;
                $course_visitor = CourseVisitor::whereDate('created_at',date('Y-m-d'))->where('slug',$slug)->where('ip',$ip)->where('platform',
                $whichbrowser->os->name)->where('device',GetDeviceType($_SERVER['HTTP_USER_AGENT']))->where('browser',$whichbrowser->browser->name)->first();
                if(!empty($course_visitor))
                {
                    $pageview = $course_visitor->pageview+1;
                }
                else{
                    $pageview = 1;
                }
                $data = [
                    'method' => !empty($_SERVER)?$_SERVER['REQUEST_METHOD']:'',
                    'referer' => !empty($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:null,
                    'languages' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
                    'useragent' => $_SERVER['HTTP_USER_AGENT'],
                    'device' => GetDeviceType($_SERVER['HTTP_USER_AGENT']),
                    'platform' =>$whichbrowser->os->name,
                    'browser' =>$whichbrowser->browser->name,
                    'ip' => $ip,
                    'slug' => $slug,
                    'pageview' => $pageview,
                ];
                $user =  CourseVisitor::whereDate('created_at', date('Y-m-d'))->updateOrCreate(['ip' => $ip,'slug'=>$slug,'platform'=>$whichbrowser->os->name,'device'=>GetDeviceType($_SERVER['HTTP_USER_AGENT']),'browser'=>$whichbrowser->browser->name,'platform'=>$whichbrowser->os->name],$data);
            }

            $demoStoreThemeSetting = LmsUtility::demoStoreThemeSetting($store->id);
            $page_slug_urls        = PageOption::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();
            $blog                  = Blog::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by);

            if(empty($store) || $store == null)
            {
                return redirect()->back()->with('error', __('Store not available'));
            }
            session(['slug' => $slug]);
            $cart = session()->get($slug);
            /**/
            $courses               = Course::select('courses.*', 'course_categories.name as category_name')->leftJoin('course_categories', 'courses.category', '=', 'course_categories.id')->where('courses.workspace_id', $store->workspace_id)->where('courses.created_by',$store->created_by)->where('courses.status', 'Active')->orderBy('id', 'DESC')->limit(4)->get();
            $special_offer_courses = Course::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->where('status', 'Active')->orderBy('id', 'DESC')->first();
            $categories = CourseCategory::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->orderBy('id', 'DESC')->limit(6)->get();

            $featured_course = Course::select('courses.*', 'course_categories.name as category_name')->leftJoin('course_categories', 'courses.category', '=', 'course_categories.id')->where('courses.workspace_id', $store->workspace_id)->where('courses.created_by',$store->created_by)->where('courses.featured_course', 'on')->orderBy('id', 'DESC')->limit(3)->get();
            $total_item = 0;
            /**/
            if(isset($cart['products']))
            {
                foreach($cart['products'] as $item)
                {
                    if(isset($cart) && !empty($cart['products']))
                    {
                        $total_item = count($cart['products']);
                    }
                    else
                    {
                        $total_item = 0;
                    }
                }
            }
            if (isset($cart['wishlist']))
            {
                $wishlist = $cart['wishlist'];
            } else {
                $wishlist = [];
            }
            $lang = $store->lang;

            // json data
            $getStoreThemeSetting = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
            $getStoreThemeSetting1 = [];

            if(!empty($getStoreThemeSetting['dashboard'])) {
                $getStoreThemeSetting = json_decode($getStoreThemeSetting['dashboard'], true);
                $getStoreThemeSetting1 = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
            }

            if (empty($getStoreThemeSetting)) {
                $path = asset('Modules/LMS/Resources/assets/image/'. $store->theme_dir . "/" . $store->theme_dir . ".json" );
                $getStoreThemeSetting = json_decode(file_get_contents($path), true);
            }

            $pixels = LmsPixelFields::where('store_id',$store->id)->get();
            $pixelScript = [];
            foreach ($pixels as $pixel) {

                if ( !$pixel->disabled ) {
                    $pixelScript[] = Store::pixelSourceCode( $pixel['platform'], $pixel['pixel_id'] );
                }
            }

            return view('lms::storefront.' . $store->theme_dir . '.index', compact('featured_course', 'special_offer_courses', 'categories', 'demoStoreThemeSetting', 'store', 'categories', 'total_item', 'courses', 'page_slug_urls', 'blog', 'slug', 'wishlist', 'getStoreThemeSetting','getStoreThemeSetting1','pixelScript'));

        }else{
            return redirect()->back();
        }
    }

    public function pageOptionSlug($slug, $page_slug=null)
    {
        if(!empty($page_slug))
        {
            $pageoption            = PageOption::where('slug', $page_slug)->first();
            if(!empty($pageoption))
            {
                $store                 = Store::where('workspace_id', $pageoption->workspace_id)->where('created_by',$pageoption->created_by)->first();
                if(isset($store->lang))
                {
                    $lang = session()->get('lang');

                    if(!isset($lang))
                    {
                        session(['lang' => $store->lang]);
                        $storelang=session()->get('lang');
                        \App::setLocale(isset($storelang) ? $storelang : 'en');
                    }
                    else
                    {
                        session(['lang' => $lang]);
                        $storelang=session()->get('lang');
                        \App::setLocale(isset($storelang) ? $storelang : 'en');
                    }
                }
                $page_slug_urls        = PageOption::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();
                $blog                  = Blog::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by);
                $demoStoreThemeSetting = LmsUtility::demoStoreThemeSetting($store->id);

                // json data
                $getStoreThemeSetting = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
                $getStoreThemeSetting1 = [];

                if(!empty($getStoreThemeSetting['dashboard'])) {
                    $getStoreThemeSetting = json_decode($getStoreThemeSetting['dashboard'], true);
                    $getStoreThemeSetting1 = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
                }
                if (empty($getStoreThemeSetting)) {
                    $path = asset('Modules/LMS/Resources/assets/image/'. $store->theme_dir . "/" . $store->theme_dir . ".json") ;
                    $getStoreThemeSetting = json_decode(file_get_contents($path), true);
                }

                return view('lms::storefront.' . $store->theme_dir . '.pageslug', compact('pageoption', 'demoStoreThemeSetting', 'slug', 'store', 'page_slug_urls', 'blog', 'getStoreThemeSetting', 'getStoreThemeSetting1'));
            }
            else{
                return redirect('/');
            }

        }else{
            return redirect('/');
        }
    }

    public function StoreBlog($slug)
    {
        $store                 = Store::getStore($slug);
        if(isset($store->lang))
        {
            $lang = session()->get('lang');

            if(!isset($lang))
            {
                session(['lang' => $store->lang]);
                $storelang=session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
            }
            else
            {
                session(['lang' => $lang]);
                $storelang=session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
            }
        }

        $page_slug_urls        = PageOption::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();
        $blogs                 = Blog::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();
        $demoStoreThemeSetting = LmsUtility::demoStoreThemeSetting($store->id);

        if(empty($store))
        {
            return redirect()->back()->with('error', __('Store not available'));
        }
        $category = CourseCategory::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();

        // json data
        $getStoreThemeSetting = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
        $getStoreThemeSetting1 = [];

        if(!empty($getStoreThemeSetting['dashboard'])) {
            $getStoreThemeSetting = json_decode($getStoreThemeSetting['dashboard'], true);
            $getStoreThemeSetting1 = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
        }
        if (empty($getStoreThemeSetting)) {
            $path = asset( 'Modules/LMS/Resources/assets/image/'. $store->theme_dir . "/" . $store->theme_dir . ".json") ;
            $getStoreThemeSetting = json_decode(file_get_contents($path), true);
        }

        return view('lms::storefront.' . $store->theme_dir . '.store_blog', compact('slug', 'demoStoreThemeSetting', 'store', 'page_slug_urls', 'blogs', 'category', 'getStoreThemeSetting', 'getStoreThemeSetting1'));
    }

    public function StoreBlogView($slug, $blog_id)
    {
        try {
            $blog_id  = \Illuminate\Support\Facades\Crypt::decrypt($blog_id);
        } catch(\RuntimeException $e) {
           return redirect()->back()->with('error',__('Blog not avaliable'));
        }
        // $blog_id               = Crypt::decrypt($blog_id);
        $store                 = Store::getStore($slug);
        if(isset($store->lang))
        {
            $lang = session()->get('lang');

            if(!isset($lang))
            {
                session(['lang' => $store->lang]);
                $storelang=session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
            }
            else
            {
                session(['lang' => $lang]);
                $storelang=session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
            }
        }
        $page_slug_urls        = PageOption::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();
        $blogs                 = Blog::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->where('id', $blog_id)->first();
        $blog                  = Blog::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->where('id', $blog_id)->get();
        $blog_loop             = Blog::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();

        $socialblogs           = BlogSocial::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->first();
        $demoStoreThemeSetting = LmsUtility::demoStoreThemeSetting($store->id);
        $socialblogsarr        = [];

        if(!empty($socialblogs))
        {
            $arrSocialDatas = $socialblogs->toArray();
            unset($arrSocialDatas['id'], $arrSocialDatas['enable_social_button'], $arrSocialDatas['store_id'], $arrSocialDatas['created_by'], $arrSocialDatas['created_at'], $arrSocialDatas['updated_at']);

            foreach($arrSocialDatas as $k => $v)
            {
                if($v == 'on')
                {
                    $newName = str_replace('enable_', '', $k);
                    array_push($socialblogsarr, strtolower($newName));
                }
            }
        }
        $socialblogsarr = json_encode($socialblogsarr);

        // json data
        $getStoreThemeSetting = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
        $getStoreThemeSetting1 = [];

        if(!empty($getStoreThemeSetting['dashboard'])) {
            $getStoreThemeSetting = json_decode($getStoreThemeSetting['dashboard'], true);
            $getStoreThemeSetting1 = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
        }
        if (empty($getStoreThemeSetting)) {
            $path =  asset( 'Modules/LMS/Resources/assets/image/'. $store->theme_dir . "/" . $store->theme_dir . ".json") ;
            $getStoreThemeSetting = json_decode(file_get_contents($path), true);
        }

        return view('lms::storefront.' . $store->theme_dir . '.store_blog_view', compact('blog', 'demoStoreThemeSetting', 'slug', 'store', 'page_slug_urls', 'blogs', 'blog_loop', 'socialblogs', 'socialblogsarr', 'getStoreThemeSetting', 'getStoreThemeSetting1'));
    }

    public function search($slug, Request $request, $search_category = null)
    {
        if($search_category != null)
        {
            try {
                $search_category = Crypt::decrypt($search_category);
            } catch(\RuntimeException $e) {
               return redirect()->back()->with('error',__('Category not avaliable'));
            }
        }
        $search_d              = ($request->all() != null) ? $request->search : null;
        $store                 = Store::getStore($slug);
        if(isset($store->lang))
        {
            $lang = session()->get('lang');

            if(!isset($lang))
            {
                session(['lang' => $store->lang]);
                $storelang=session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
            }
            else
            {
                session(['lang' => $lang]);
                $storelang=session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
            }
        }
        $blog                  = Blog::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by);
        $page_slug_urls        = PageOption::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();
        $demoStoreThemeSetting = LmsUtility::demoStoreThemeSetting($store->id);
        if(empty($store))
        {
            return redirect()->back()->with('error', __('Store not available'));
        }
        $category = CourseCategory::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();

        if($search_d == null && $search_category == null)
        {
            $courses = Course::select('courses.*', 'course_categories.name as category_name')->leftJoin('course_categories', 'courses.category', '=', 'course_categories.id')->where('courses.workspace_id', $store->workspace_id)->where('courses.created_by',$store->created_by)->where('courses.status', 'Active')->get();
        }
        else if($search_category != null)
        {
            $courses = Course::select('courses.*', 'course_categories.name as category_name')->leftJoin('course_categories', 'courses.category', '=', 'course_categories.id')->where('courses.workspace_id', $store->workspace_id)->where('courses.created_by',$store->created_by)->where('courses.status', 'Active')->where('category', $search_category)->get();
        }
        else
        {
            $courses = Course::select('courses.*', 'course_categories.name as category_name')->leftJoin('course_categories', 'courses.category', '=', 'course_categories.id')->where('courses.workspace_id', $store->workspace_id)->where('courses.created_by',$store->created_by)->where('courses.status', 'Active')->where('courses.title', 'like', '%' . $search_d . '%')->get();
        }

        // json data
        $getStoreThemeSetting = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
        $getStoreThemeSetting1 = [];

        if(!empty($getStoreThemeSetting['dashboard'])) {
            $getStoreThemeSetting = json_decode($getStoreThemeSetting['dashboard'], true);
            $getStoreThemeSetting1 = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
        }
        if (empty($getStoreThemeSetting)) {
            $path = asset('Modules/LMS/Resources/assets/image/'. $store->theme_dir . "/" . $store->theme_dir . ".json") ;
            $getStoreThemeSetting = json_decode(file_get_contents($path), true);
        }

        return view('lms::storefront.' . $store->theme_dir . '.search.index', compact('search_category', 'demoStoreThemeSetting', 'blog', 'store', 'page_slug_urls', 'courses', 'search_d', 'category', 'slug', 'getStoreThemeSetting', 'getStoreThemeSetting1'));
    }

    public function filter($slug, Request $request)
    {
        $store          = Store::where('slug', $slug)->first();
        if(isset($store->lang))
        {
            $lang = session()->get('lang');

            if(!isset($lang))
            {
            session(['lang' => $store->lang]);
            $storelang=session()->get('lang');
            \App::setLocale(isset($storelang) ? $storelang : 'en');
            }
            else
            {
                session(['lang' => $lang]);
                $storelang=session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
            }
        }
        $blog           = Blog::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->count();
        $page_slug_urls = PageOption::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();

        if(empty($store))
        {
            return redirect()->back()->with('error', __('Store not available'));
        }
        $is_free     = [
            'off' => 'off',
            'on' => 'on',
        ];
        $level       = LmsUtility::course_level();
        $search_data = '';
        $category    = CourseCategory::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get()->pluck('id');
        if($search_data != 'null' && !empty($request->all()))
        {
            $output = '';
            $data   = Course::select('courses.*', 'course_categories.name as category_name')->leftJoin('course_categories', 'courses.category', '=', 'course_categories.id')->where('courses.workspace_id', $store->workspace_id)->where('courses.created_by',$store->created_by)->whereIn('courses.level', (!empty($request->level) ? $request->level : $level))->whereIn('courses.category', (!empty($request->checked) ? $request->checked : $category))->whereIn('courses.is_free', (!empty($request->is_free) ? $request->is_free : $is_free))->where('courses.status', 'Active')->get();

            if(!empty($request->is_free) && empty($request->level) && empty($request->checked))
            {
                $data = Course::select('courses.*', 'course_categories.name as category_name')->leftJoin('course_categories', 'courses.category', '=', 'course_categories.id')->where('courses.workspace_id', $store->workspace_id)->where('courses.created_by',$store->created_by)->whereIn('courses.is_free', (!empty($request->is_free) ? $request->is_free : $is_free))->get();
            }
            if(!empty($request->level) && empty($request->is_free) && empty($request->checked))
            {
                $data = Course::select('courses.*', 'course_categories.name as category_name')->leftJoin('course_categories', 'courses.category', '=', 'course_categories.id')->where('courses.workspace_id', $store->workspace_id)->where('courses.created_by',$store->created_by)->whereIn('courses.level', (!empty($request->level) ? $request->level : $level))->get();
            }
            if(!empty($request->checked) && empty($request->level) && empty($request->is_free))
            {
                $data = Course::select('courses.*', 'course_categories.name as category_name')->leftJoin('course_categories', 'courses.category', '=', 'course_categories.id')->where('courses.workspace_id', $store->workspace_id)->where('courses.created_by',$store->created_by)->whereIn('courses.category', (!empty($request->checked) ? $request->checked : $category))->get();
            }
            $total_row = $data->count();
            $output    .= view('lms::storefront.' . $store->theme_dir . '.search.filter', compact('blog', 'data', 'total_row', 'store', 'slug'))->render();
            $data      = array(
                'table_data' => $output,
                'total_row' => $total_row,
                'slug' => $slug,
                'page_slug_urls' => $page_slug_urls,
            );

            return json_encode($data);
        }
        else
        {
            $output    = '';
            $data      = Course::where('store_id', $store->id)->where('status', 'Active')->get();
            $total_row = $data->count();
            $output    .= view('lms::storefront.' . $store->theme_dir . '.search.filter', compact('blog', 'data', 'total_row', 'store', 'slug'))->render();
            $data      = array(
                'table_data' => $output,
                'total_row' => $total_row,
                'slug' => $slug,
                'page_slug_urls' => $page_slug_urls,
            );

            return json_encode($data);
        }

    }

    public function userCreate($slug)
    {
        $store                 = Store::where('slug', $slug)->first();
        if(isset($store->lang))
        {
            $lang = session()->get('lang');

            if(!isset($lang))
            {
                session(['lang' => $store->lang]);
                $storelang=session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
            }
            else
            {
                session(['lang' => $lang]);
                $storelang=session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
            }
        }
        $blog                  = Blog::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by);
        $page_slug_urls        = PageOption::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();
        $demoStoreThemeSetting = LmsUtility::demoStoreThemeSetting($store->id);
        if(empty($store))
        {
            return redirect()->back()->with('error', __('Store not available'));
        }

        // json data
        $getStoreThemeSetting = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
        $getStoreThemeSetting1 = [];

        if(!empty($getStoreThemeSetting['dashboard'])) {
            $getStoreThemeSetting = json_decode($getStoreThemeSetting['dashboard'], true);
            $getStoreThemeSetting1 = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
        }
        if (empty($getStoreThemeSetting)) {
            $path =asset( 'Modules/LMS/Resources/assets/image/' . $store->theme_dir . "/" . $store->theme_dir . ".json") ;
            $getStoreThemeSetting = json_decode(file_get_contents($path), true);
        }

        return view('lms::storefront.' . $store->theme_dir . '.user.create', compact('blog', 'demoStoreThemeSetting', 'slug', 'store', 'page_slug_urls','getStoreThemeSetting','getStoreThemeSetting1'));
    }

    protected function userStore($slug, Request $request)
    {
        $store          = Store::where('slug', $slug)->first();
        $company_settings = getCompanyAllSetting($store->created_by,$store->workspace_id);
        $page_slug_urls = PageOption::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();
        $blog           = Blog::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by);

        if(empty($store))
        {
            return redirect()->back()->with('error', __('Store not available'));
        }
        $validate = Validator::make(
            $request->all(), [
                               'name' => [
                                   'required',
                                   'string',
                                   'max:255',
                               ],
                               'phone_number' => [
                                   'required',
                                   'max:255',
                               ],
                               'email' => [
                                   'required',
                                   'string',
                                   'email',
                                   'max:255',
                               ],
                               'password' => [
                                   'required',
                                   'string',
                                   'min:8',
                                   'confirmed',
                               ],
                           ]
        );
        $vali     = Student::where('email', $request->email)->where('store_id', $store->id)->where('phone_number', $request->phone_number)->count();
        if($validate->fails())
        {
            $message = $validate->getMessageBag();

            return redirect()->back()->with('error', $message->first());
        }
        elseif($vali > 0)
        {
            return redirect()->back()->with('error', __('Email already exists'));
        }

        $student               = new Student();
        $student->name         = $request->name;
        $student->email        = $request->email;
        $student->phone_number = $request->phone_number;
        $student->password     = Hash::make($request->password);
        $student->lang         = !empty($company_settings['defult_language']) ? $company_settings['defult_language'] : 'en';
        $student->avatar       = '';
        $student->store_id     = $store->id;

        $student->save();

        event(new CreateStudent($request, $student));

        return redirect()->route('student.home', $slug)->with('success', __('Account Created Successfully.'));
    }

    public function studentHome($slug)
    {
        $store                 = Store::getStore($slug);
        if(isset($store->lang))
        {
            $lang = session()->get('lang');

            if(!isset($lang))
            {
                session(['lang' => $store->lang]);
                $storelang=session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
            }
            else
            {
                session(['lang' => $lang]);
                $storelang=session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
            }
        }
        $blog                  = Blog::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by);
        $page_slug_urls        = PageOption::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();
        $demoStoreThemeSetting = LmsUtility::demoStoreThemeSetting($store->id);
        if(empty($store))
        {
            return redirect()->back()->with('error', __('Store not available'));
        }
        $purchased_course = Course::select('courses.*', 'course_categories.name as category_name')->leftJoin('course_categories', 'courses.category', '=', 'course_categories.id')->where('courses.workspace_id', $store->workspace_id)->where('courses.created_by',$store->created_by)->where('courses.status', 'Active')->get();

        // json data
        $getStoreThemeSetting = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
        $getStoreThemeSetting1 = [];

        if(!empty($getStoreThemeSetting['dashboard'])) {
            $getStoreThemeSetting = json_decode($getStoreThemeSetting['dashboard'], true);
            $getStoreThemeSetting1 = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
        }
        if (empty($getStoreThemeSetting)) {
            $path = asset( 'Modules/LMS/Resources/assets/image/'. $store->theme_dir . "/" . $store->theme_dir . ".json") ;
            $getStoreThemeSetting = json_decode(file_get_contents($path), true);
        }

        return view('lms::storefront.' . $store->theme_dir . '.student.index', compact('purchased_course', 'demoStoreThemeSetting', 'blog', 'slug', 'store', 'page_slug_urls', 'getStoreThemeSetting', 'getStoreThemeSetting1'));
    }

    public function wishlist($slug, $id)
    {
        if(LmsUtility::StudentAuthCheck($slug) == false)
        {

            return response()->json(
                [
                    'code' => 200,
                    'status' => 'error',
                    'error' => 'You need to login',
                ]
            );
        }
        else
        {

            $wl             = new Wishlist();
            $wishlist_count = Wishlist::where('course_id', $id)->where('student_id', Auth::guard('students')->id())->count();
            $wishlist_count_no = Wishlist::where('student_id', Auth::guard('students')->id())->count();

            if($wishlist_count > 0)
            {

                return response()->json(
                    [
                        'code' => 200,
                        'status' => 'error',
                        'error' => 'Already in wishlist',
                    ]
                );
            }
            else
            {
                $wl->course_id  = $id;
                $wl->student_id = Auth::guard('students')->id();
                $wl->save();

                return response()->json(
                    [
                        'code' => 200,
                        'status' => 'Success',
                        'success' => 'Added to wishlist',
                        'item_count' => $wishlist_count_no + 1,
                    ]
                );
            }
        }
    }

    public function wishlistpage($slug)
    {

        if(LmsUtility::StudentAuthCheck($slug) == false)
        {
            return redirect($slug . '/student-login');
        }
        else
        {
            $store                 = Store::getStore($slug);
            if(isset($store->lang))
            {
                $lang = session()->get('lang');

                if(!isset($lang))
                {
                session(['lang' => $store->lang]);
                $storelang=session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
                }
                else
                {
                    session(['lang' => $lang]);
                    $storelang=session()->get('lang');
                    \App::setLocale(isset($storelang) ? $storelang : 'en');
                }
            }
            $blog                  = Blog::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by);
            $page_slug_urls        = PageOption::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();
            $demoStoreThemeSetting = LmsUtility::demoStoreThemeSetting($store->id);
            if(empty($store))
            {
                return redirect()->back()->with('error', __('Store not available'));
            }
            $courses = Course::select('courses.*', 'course_categories.name as category_name')->leftJoin('course_categories', 'courses.category', '=', 'course_categories.id')->where('courses.workspace_id', $store->workspace_id)->where('courses.created_by',$store->created_by)->where('courses.status', 'Active')->get();

            // json data
            $getStoreThemeSetting = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
            $getStoreThemeSetting1 = [];

            if(!empty($getStoreThemeSetting['dashboard'])) {
                $getStoreThemeSetting = json_decode($getStoreThemeSetting['dashboard'], true);
                $getStoreThemeSetting1 = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
            }
            if (empty($getStoreThemeSetting)) {
                $path = asset('Modules/LMS/Resources/assets/image/'. $store->theme_dir . "/" . $store->theme_dir . ".json") ;
                $getStoreThemeSetting = json_decode(file_get_contents($path), true);
            }

            return view('lms::storefront.' . $store->theme_dir . '.student.wishlist', compact('blog', 'demoStoreThemeSetting', 'slug', 'page_slug_urls', 'page_slug_urls', 'store', 'courses', 'getStoreThemeSetting', 'getStoreThemeSetting1'));
        }
    }

    public function removeWishlist($slug, $id)
    {
        if(LmsUtility::StudentAuthCheck($slug) == false)
        {
            return redirect()->back()->with('error', __('You need to login!'));
        }
        else
        {
            $wishlist_count = Wishlist::where('course_id', $id)->where('student_id', Auth::guard('students')->id());
            $wishlist_count->delete();

            return redirect()->back()->with('success', __('Successfully Removed!'));
        }
    }

    public function addToCart(Request $request, $product_id, $slug, $variant_id = 0)
    {
        if($request->ajax())
        {
            $store = Store::where('slug', $slug)->get();
            if(empty($store))
            {
                return redirect()->back()->with('error', __('Store not available'));
            }

            $product = Course::find($product_id);
            $cart    = session()->get($slug);

            if(!empty($product->thumbnail))
            {
                $pro_img = get_file($product->thumbnail);
            }
            else
            {
                $pro_img = '';
            }

            $productname  = $product->title;
            $productprice = $product->price != 0 ? $product->price : 0;


            $time = time();
            // if cart is empty then this the first product
            if(!$cart || !$cart['products'])
            {
                if($variant_id > 0)
                {
                    $cart['products'][$time] = [
                        "product_id" => $product->id,
                        "product_name" => $productname,
                        "image" => $pro_img,
                        "price" => $productprice,
                        "id" => $product_id,
                        'variant_id' => $variant_id,
                    ];
                }
                else if($variant_id <= 0)
                {
                    $cart['products'][$time] = [
                        "product_id" => $product->id,
                        "product_name" => $productname,
                        "image" => $pro_img,
                        "price" => $productprice,
                        "id" => $product_id,
                        'variant_id' => 0,
                    ];
                }
                session()->put($slug, $cart);

                return response()->json(
                    [
                        'code' => 200,
                        'status' => 'Success',
                        'success' => $productname . __(' added to cart successfully!'),
                        'cart' => $cart['products'],
                        'item_count' => count($cart['products']),
                    ]
                );
            }

            // if cart not empty then check if this product exist then increment quantity

            if($variant_id > 0)
            {
                $key = false;
                foreach($cart['products'] as $k => $value)
                {
                    if($variant_id == $value['variant_id'])
                    {
                        $key = $k;
                    }
                }

                if($key !== false && isset($cart['products'][$key]['variant_id']) && $cart['products'][$key]['variant_id'] != 0)
                {
                    if(isset($cart['products'][$key]))
                    {
                        $cart['products'][$key]['quantity']         = $cart['products'][$key]['quantity'] + 1;
                        $cart['products'][$key]['variant_subtotal'] = $cart['products'][$key]['variant_price'] * $cart['products'][$key]['quantity'];

                        session()->put($slug, $cart);

                        return response()->json(
                            [
                                'code' => 200,
                                'status' => 'Success',
                                'success' => $productname . __(' added to cart successfully!'),
                                'cart' => $cart['products'],
                                'item_count' => count($cart['products']),
                            ]
                        );
                    }
                }
            }
            else if($variant_id <= 0)
            {
                $key = false;

                foreach($cart['products'] as $k => $value)
                {
                    if($product_id == $value['product_id'])
                    {
                        $key = $k;
                    }
                }

                if($key !== false)
                {
                    session()->put($slug, $cart);

                    return response()->json(
                        [
                            'code' => 200,
                            'status' => 'Error',
                            'exists' => 'exists',
                            'error' => $productname . __(' is Already in Cart!'),
                            'cart' => $cart['products'],
                            'item_count' => count($cart['products']),
                        ]
                    );
                }
            }
        }

        // if item not exist in cart then add to cart with quantity = 1
        if($variant_id > 0)
        {
            $cart['products'][$time] = [
                "product_id" => $product->id,
                "product_name" => $productname,
                "image" => $pro_img,
                "price" => $productprice,
                "id" => $product_id,
                'variant_id' => $variant_id,
                'product_description' => $product->course_description,
            ];
        }
        else if($variant_id <= 0)
        {
            $cart['products'][$time] = [
                "product_id" => $product->id,
                "product_name" => $productname,
                "image" => $pro_img,
                "price" => $productprice,
                "id" => $product_id,
                'variant_id' => 0,
                'product_description' => $product->course_description,
            ];
        }
        session()->put($slug, $cart);

        return response()->json(
            [
                'code' => 200,
                'status' => 'Success',
                'success' => $productname . __(' added to cart successfully!'),
                'cart' => $cart['products'],
                'item_count' => count($cart['products']),
            ]
        );
    }

    public function StoreCart($slug)
    {
        $store                 = Store::getStore($slug);
        if(isset($store->lang))
        {
            $lang = session()->get('lang');

            if(!isset($lang))
            {
                session(['lang' => $store->lang]);
                $storelang=session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
            }
            else
            {
                session(['lang' => $lang]);
                $storelang=session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
            }
        }

        $page_slug_urls        = PageOption::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();
        $blog                  = Blog::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by);
        $demoStoreThemeSetting = LmsUtility::demoStoreThemeSetting($store->id);
        if(empty($store))
        {
            return redirect()->back()->with('error', __('Store not available'));
        }
        $cart = session()->get($slug);
        if(!empty($cart))
        {
            $products = $cart;
            if(Auth::guard('students')->check())
            {
                foreach($products['products'] as $k => $product)
                {
                    if(in_array($product['product_id'], Auth::guard('students')->user()->purchasedCourse()))
                    {
                        $this->delete_cart_item($slug, $product['product_id']);
                    }
                }
            }
        }
        else
        {
            $products = '';
        }
        // json data
        $getStoreThemeSetting = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
        $getStoreThemeSetting1 = [];

        if(!empty($getStoreThemeSetting['dashboard'])) {
            $getStoreThemeSetting = json_decode($getStoreThemeSetting['dashboard'], true);
            $getStoreThemeSetting1 = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
        }
        if (empty($getStoreThemeSetting)) {
            $path = asset('Modules/LMS/Resources/assets/image/'. $store->theme_dir . "/" . $store->theme_dir . ".json") ;
            $getStoreThemeSetting = json_decode(file_get_contents($path), true);
        }

        return view('lms::storefront.' . $store->theme_dir . '.cart', compact('demoStoreThemeSetting', 'products', 'store', 'page_slug_urls', 'blog', 'slug', 'getStoreThemeSetting', 'getStoreThemeSetting1'));
    }

    public function delete_cart_item($slug, $id, $variant_id = 0)
    {
        $cart = session()->get($slug);

        foreach($cart['products'] as $key => $product)
        {
            if(($variant_id > 0 && $cart['products'][$key]['variant_id'] == $variant_id))
            {
                unset($cart['products'][$key]);
            }
            else if($cart['products'][$key]['product_id'] == $id && $variant_id == 0)
            {
                unset($cart['products'][$key]);
            }

        }

        $cart['products'] = array_values($cart['products']);

        session()->put($slug, $cart);

        return redirect()->back()->with('success', __('Item successfully Deleted.'));
    }

    public function checkout($slug, $courses_id, $total)
    {
        $cart                  = session()->get($slug);
        try {
            $c_id                  = Crypt::decrypt($courses_id);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Course Not Found.'));
        }
        $store                 = Store::getStore($slug);
        if(isset($store->lang))
        {
            $lang = session()->get('lang');

            if(!isset($lang))
            {
                session(['lang' => $store->lang]);
                $storelang=session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
            }
            else
            {
                session(['lang' => $lang]);
                $storelang=session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
            }
        }
        $order                 = CourseOrder::where('store_id', $store->id)->where('created_by',$store->created_by)->orderBy('id', 'desc')->first();
        $blog                  = Blog::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by);
        $page_slug_urls        = PageOption::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();
        $demoStoreThemeSetting = LmsUtility::demoStoreThemeSetting($store->id);

        // json data
        $getStoreThemeSetting = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
        $getStoreThemeSetting1 = [];

        if(!empty($getStoreThemeSetting['dashboard'])) {
            $getStoreThemeSetting = json_decode($getStoreThemeSetting['dashboard'], true);
            $getStoreThemeSetting1 = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
        }
        if (empty($getStoreThemeSetting)) {
            $path = asset('Modules/LMS/Resources/assets/image/'. $store->theme_dir . "/" . $store->theme_dir . ".json") ;
            $getStoreThemeSetting = json_decode(file_get_contents($path), true);
        }


        if(empty($store))
        {
            return redirect()->back()->with('error', __('Store not available'));
        }
        if(!empty($cart))
        {
            $products = $cart['products'];
        }
        else
        {
            return redirect()->back()->with('error', __('Please add to product into cart'));
        }
        if(!empty($order))
        {
            $order_id = '%23' . str_pad($order->id + 1, 4, "100", STR_PAD_LEFT);
        }
        else
        {
            $order_id = '%23' . str_pad(0 + 1, 4, "100", STR_PAD_LEFT);

        }
        if(!empty(Auth::guard('students')->user()))
        {
            $course = Course::where('workspace_id', $store->workspace_id)->whereIn('id', json_decode($c_id))->where('status', 'Active')->get();

            $encode_product = json_encode($products);
            if($total > 0)
            {
                return view('lms::storefront.' . $store->theme_dir . '.checkout', compact('order_id', 'demoStoreThemeSetting', 'order', 'encode_product', 'blog', 'slug', 'course', 'page_slug_urls', 'store'));
            }
            else
            {
                if($products)
                {
                    $student               = Auth::guard('students')->user();
                    $order                 = new CourseOrder();
                    $order->order_id       = time();
                    $order->name           = $student->name;
                    $order->card_number    = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year  = '';
                    $order->student_id     = $student->id;
                    $order->course         = json_encode($products);
                    $order->price          = 0;
                    $order->price_currency = $store->currency_code;
                    $order->txn_id         = '';
                    $order->payment_type   = 'Free';
                    $order->payment_status = 'success';
                    $order->receipt        = '';
                    $order->store_id       = $store['id'];
                    $order->save();

                    $purchased_course = new PurchasedCourse();

                    foreach($products as $course_id)
                    {
                        $purchased_course->course_id  = $course_id['product_id'];
                        $purchased_course->student_id = $student->id;
                        $purchased_course->order_id   = $order->id;
                        $purchased_course->save();

                        $student=Student::where('id',$purchased_course->student_id)->first();
                        $student->courses_id=$purchased_course->course_id;
                        $student->save();
                    }
                    session()->forget($slug);

                    return redirect()->route(
                        'store-complete.complete', [
                                                     $store->slug,
                                                     Crypt::encrypt($order->id),
                                                 ]
                    )->with('success', __('Transaction has been success'));

                }
                else
                {
                    return redirect()->back()->with('error', __('Cart is empty'));
                }
            }



        }
        else
        {
            $is_cart = true;

            return view('lms::storefront.' . $store->theme_dir . '.user.login', compact('blog', 'demoStoreThemeSetting', 'slug', 'store', 'page_slug_urls', 'is_cart', 'getStoreThemeSetting','getStoreThemeSetting1'));
        }
    }

    public function ViewCourse($slug, $course_id)
    {
        $store                 = Store::getStore($slug);
        if(isset($store->lang))
        {
            $lang = session()->get('lang');

            if(!isset($lang))
            {
                session(['lang' => $store->lang]);
                $storelang=session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
            }
            else
            {
                session(['lang' => $lang]);
                $storelang=session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
            }
        }
        $blog                  = Blog::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by);
        $page_slug_urls        = PageOption::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();
        $demoStoreThemeSetting = LmsUtility::demoStoreThemeSetting($store->id);
        if(empty($store))
        {
            return redirect()->back()->with('error', __('Store not available'));
        }

        try {
            $course_id   = \Illuminate\Support\Facades\Crypt::decrypt($course_id);
        } catch(\RuntimeException $e) {
           return redirect()->back()->with('error',__('Course not avaliable'));
        }
        $course = Course::where('id',$course_id)->with('tutor_id')->with('chapter_count','student_count')->first();
        if(empty($course) || $course == null)
        {
            return redirect()->back()->with('error', __('Course not available'));
        }

        $more_by_category   = Course::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->where('category', $course->category)->limit(4)->get();
        $category_name      = CourseCategory::find($course->category);
        $faqs               = CourseFaq::where('course_id', $course_id)->get();
        $chapter            = Chapters::where('course_id', $course->id)->get();
        $header             = Header::where('course', $course->id)->with('chapters_data')->get();
        $tutor_course_count = Course::where('workspace_id', $store->workspace_id)->where('created_by', $course->created_by)->where('status', 'Active')->get();
        $tutor_course_rel   = Course::where('workspace_id', $store->workspace_id)->where('created_by', $course->created_by)->where('status', 'Active')->limit(2)->get();
        /*Course Rating*/
        $course_ratings = Ratting::where('course_id', $course->id)->get();
        $ratting        = Ratting::where('course_id', $course->id)->where('rating_view', 'on')->sum('ratting');
        $user_count     = Ratting::where('course_id', $course->id)->where('rating_view', 'on')->count();

        $course['meta_keywords']     = Course::where('id', $course->id)->select('meta_keywords')->get();
        $course['meta_description']  = Course::where('id', $course->id)->select('meta_description')->get();

        if($user_count > 0)
        {
            $avg_rating = number_format($ratting / $user_count, 1);
        }
        else
        {
            $avg_rating = number_format($ratting / 1, 1);

        }
        /*Tutor Rating*/
        $tutor_id           = $tutor_course_count->pluck('created_by')->first();
        $tutor_ratings      = Ratting::where('tutor_id', $tutor_id)->where('slug', $slug)->get();
        $tutor_sum_ratting  = Ratting::where('tutor_id', $tutor_id)->where('slug', $slug)->where('rating_view', 'on')->sum('ratting');
        $tutor_count_rating = Ratting::where('tutor_id', $tutor_id)->where('slug', $slug)->where('rating_view', 'on')->count();
        if($tutor_count_rating > 0)
        {
            $avg_tutor_rating = number_format($tutor_sum_ratting / $tutor_count_rating, 1);
        }
        else
        {
            $avg_tutor_rating = number_format($tutor_sum_ratting / 1, 1);
        }
        $headers = $header;
        $header_first = $header->pluck('id')->first();

        $tutor = User::find($tutor_id);

        // json data
        $getStoreThemeSetting = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
        $getStoreThemeSetting1 = [];

        if(!empty($getStoreThemeSetting['dashboard'])) {
            $getStoreThemeSetting = json_decode($getStoreThemeSetting['dashboard'], true);
            $getStoreThemeSetting1 = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
        }
        if (empty($getStoreThemeSetting)) {
            $path = asset('Modules/LMS/Resources/assets/image/'.$store->theme_dir . "/" . $store->theme_dir . ".json" );
            $getStoreThemeSetting = json_decode(file_get_contents($path), true);
        }

        return view('lms::storefront.' . $store->theme_dir . '.viewcourse', compact('category_name', 'more_by_category', 'header_first', 'demoStoreThemeSetting', 'faqs', 'blog', 'slug', 'tutor_id', 'tutor_count_rating', 'avg_tutor_rating', 'tutor_ratings', 'course_ratings', 'avg_rating', 'store', 'page_slug_urls', 'course', 'chapter', 'header', 'tutor_course_count', 'tutor_course_rel', 'tutor', 'user_count', 'getStoreThemeSetting', 'getStoreThemeSetting1', 'headers'));
    }

    public function tutor($slug, $tutor_id)
    {
        try {
            $tutor_id   = \Illuminate\Support\Facades\Crypt::decrypt($tutor_id);
        } catch(\RuntimeException $e) {
           return redirect()->back()->with('error',__('Tutor not avaliable'));
        }
        // $tutor_id              = Crypt::decrypt($tutor_id);
        $store                 = Store::getStore($slug);
        if(isset($store->lang))
            {
                $lang = session()->get('lang');

                if(!isset($lang))
                {
                session(['lang' => $store->lang]);
                $storelang=session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
                }
                else
                {
                    session(['lang' => $lang]);
                    $storelang=session()->get('lang');
                    \App::setLocale(isset($storelang) ? $storelang : 'en');
                }
            }
        $blog                  = Blog::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by);
        $page_slug_urls        = PageOption::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();
        $demoStoreThemeSetting = LmsUtility::demoStoreThemeSetting($store->id);
        if(empty($store))
        {
            return redirect()->back()->with('error', __('Store not available'));
        }
        $tutor = User::find($tutor_id);

        $course = Course::where('id',$tutor_id)->with('student_count')->first();
        $tutor_course = Course::where('workspace_id', $store->workspace_id)->where('created_by', $tutor_id)->where('status', 'Active')->first();
        $courses      = Course::select('courses.*', 'course_categories.name as category_name')->leftJoin('course_categories', 'courses.category', '=', 'course_categories.id')->where('courses.workspace_id', $store->workspace_id)->where('courses.created_by', $tutor_id)->where('courses.status', 'Active')->get();

        $tutor_ratings = Ratting::where('tutor_id', $tutor_id)->where('slug', $slug)->get();
        $ratting       = Ratting::where('tutor_id', $tutor_id)->where('slug', $slug)->where('rating_view', 'on')->sum('ratting');
        $user_count    = Ratting::where('tutor_id', $tutor_id)->where('slug', $slug)->where('rating_view', 'on')->count();
        if($user_count > 0)
        {
            $avg_rating = number_format($ratting / $user_count, 1);
        }
        else
        {
            $avg_rating = number_format($ratting / 1, 1);

        }

        // json data
        $getStoreThemeSetting = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
        $getStoreThemeSetting1 = [];

        if(!empty($getStoreThemeSetting['dashboard'])) {
            $getStoreThemeSetting = json_decode($getStoreThemeSetting['dashboard'], true);
            $getStoreThemeSetting1 = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
        }
        if (empty($getStoreThemeSetting)) {
            $path = asset('Modules/LMS/Resources/assets/image/'. $store->theme_dir . "/" . $store->theme_dir . ".json") ;
            $getStoreThemeSetting = json_decode(file_get_contents($path), true);
        }

        return view('lms::storefront.' . $store->theme_dir . '.tutor', compact('demoStoreThemeSetting', 'tutor_course', 'blog', 'user_count', 'tutor', 'tutor_ratings', 'avg_rating', 'store', 'page_slug_urls', 'courses', 'course', 'slug', 'getStoreThemeSetting', 'getStoreThemeSetting1'));
    }

    public function userorder($slug, $order_id)
    {
        $id    = Crypt::decrypt($order_id);
        $store = Store::getStore($slug);
        $order = CourseOrder::where('id', $id)->first();


        $order_products = json_decode($order->course);
        if(!empty($order_products))
        {
            $sub_total = 0;
            foreach($order_products as $product)
            {
                $totalprice = $product->price;
                $sub_total  += $totalprice;
            }
        }

        if($order->discount_price == 'undefined'){
            $discount_price = 0;
        }else{
            $discount_price = str_replace('-' . $store->currency, '', $order->discount_price);
        }

        if(!empty($discount_price))
        {
            $grand_total = $sub_total - $discount_price;
        }
        else
        {
            $discount_price = 0;
            $grand_total    = $sub_total;
        }
        $student_data = Student::where('id', $order->student_id)->first();
        $order_id     = Crypt::encrypt($order->id);

        if(!empty($coupon))
        {
            if($coupon->enable_flat == 'on')
            {
                $discount_value = $coupon->flat_discount;
            }
            else
            {
                $discount_value = ($grand_total / 100) * $coupon->discount;
            }
        }

        return view('lms::storefront.' . $store->theme_dir . '.userorder', compact('slug', 'student_data', 'discount_price', 'order', 'store', 'grand_total', 'order_products', 'sub_total', 'order_id'));
    }

    public function fullscreen($slug, $course_id, $chapter_id = null, $type = null)
    {
        if(LmsUtility::StudentAuthCheck($slug) == false)
        {
            return redirect()->back()->with('error', __('You need to login!'));
        }
        else if(in_array(Crypt::decrypt($course_id), Auth::guard('students')->user()->purchasedCourse()))
        {
            $store = Store::getStore($slug);
            if(isset($store->lang))
            {
                $lang = session()->get('lang');

                if(!isset($lang))
                {
                session(['lang' => $store->lang]);
                $storelang=session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
                }
                else
                {
                    session(['lang' => $lang]);
                    $storelang=session()->get('lang');
                    \App::setLocale(isset($storelang) ? $storelang : 'en');
                }
            }
            if($chapter_id != null)
            {
                $chapter_id = Crypt::decrypt($chapter_id);
            }
            $course_id = Crypt::decrypt($course_id);
            $courses   = Course::find($course_id);
            $headers   = Header::where('course', $course_id)->get();

            $blog                  = Blog::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by);
            $page_slug_urls        = PageOption::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();
            $demoStoreThemeSetting = LmsUtility::demoStoreThemeSetting($store->id);

            /*NEXT PREVIOUS*/
            $c = Chapters::where('course_id', $courses->id);
            if($c->count() == 0)
            {
                return redirect()->back()->with('error', __('No Chapters Available!'));
            }
            $last_next       = $c->orderBy('id', 'desc')->first();
            $last_previous   = Chapters::where('course_id', $courses->id)->first();
            $current_chapter = '';
            $next            = '';
            $previous        = '';

            /*PROGRESS*/
            $student_id      = Auth::guard('students')->user()->id;
            $cs              = ChapterStatus::where('student_id', $student_id)->where('course_id', $course_id);
            $cs_complete     = ChapterStatus::where('student_id', $student_id)->where('course_id', $course_id)->where('status', 'Active')->get();
            $ChapterStatuss  = ChapterStatus::where('student_id', $student_id)->where('course_id', $course_id)->get();
            // $cs_complete     = $cs->where('status', 'Active')->get();
            // $ChapterStatuss  = $cs->get();
            $a               = 100 / $c->count();
            $progress        = (int)($a * $cs_complete->count());
            $practices_files = PracticesFiles::where('course_id', $course_id)->get();

            $cs              = ChapterStatus::where('student_id', $student_id)->where('course_id', $course_id);
            $cs_incomplete   = $cs->where('status', 'Inactive')->get();
            $cer_download    = $cs->first();

            if($type == 'next')
            {
                $next            = Chapters::where('id', '>', $chapter_id)->where('course_id', $course_id)->min('id');
                $current_chapter = Chapters::find($next);
            }
            else if($type == 'previous')
            {
                $previous        = Chapters::where('id', '<', $chapter_id)->where('course_id', $course_id)->max('id');
                $current_chapter = Chapters::find($previous);
            }
            else if(!empty($chapter_id))
            {
                $current_chapter = Chapters::find($chapter_id);
            }
            else
            {
                $current_chapter = $last_previous;
            }

            if(!empty($current_chapter))
            {
                $previous_id        = Chapters::where('id', '<', $current_chapter->id)->where('course_id', $course_id)->max('id');
                $previous_chapter   = Chapters::find($previous_id);

                $next_id            = Chapters::where('id', '>', $current_chapter->id)->where('course_id', $course_id)->min('id');
                $next_chapter       = Chapters::find($next_id);
            }

            // json data
            $getStoreThemeSetting = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
            $getStoreThemeSetting1 = [];

            if(!empty($getStoreThemeSetting['dashboard'])) {
                $getStoreThemeSetting = json_decode($getStoreThemeSetting['dashboard'], true);
                $getStoreThemeSetting1 = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
            }
            if (empty($getStoreThemeSetting)) {
                $path = asset('Modules/LMS/Resources/assets/image/'. $store->theme_dir . "/" . $store->theme_dir . ".json") ;
                $getStoreThemeSetting = json_decode(file_get_contents($path), true);
            }
        }
        else
        {
            return redirect()->back()->with('error', __('You need to Purchase this course!'));
        }

        return view('lms::storefront.' . $store->theme_dir . '.fullscreen', compact('practices_files', 'ChapterStatuss', 'demoStoreThemeSetting', 'blog', 'page_slug_urls', 'progress', 'previous', 'next', 'current_chapter', 'chapter_id', 'course_id', 'headers', 'store', 'courses', 'slug', 'last_next', 'last_previous', 'cer_download', 'cs_incomplete', 'getStoreThemeSetting', 'getStoreThemeSetting1', 'previous_chapter', 'next_chapter'));
    }

    public function checkbox($chapter_id, $course_id, $slug)
    {
        if(LmsUtility::StudentAuthCheck($slug) == false)
        {
            return response()->json(
                [
                    'code' => 200,
                    'status' => 'Error',
                    'error' => __('You need to login.....'),
                ]
            );
        }
        else
        {
            $id = Auth::guard('students')->user()->id;
            ChapterStatus::updateOrCreate(
                [
                    'student_id' => $id,
                    'chapter_id' => $chapter_id,
                    'course_id' => $course_id,
                ], [
                    'student_id' => $id,
                    'chapter_id' => $chapter_id,
                    'course_id' => $course_id,
                    'status' => 'Active',
                ]
            );

            $student_id     = Auth::guard('students')->user()->id;
            $chapters       = Chapters::where('course_id', $course_id);
            $chapter_status = ChapterStatus::where('student_id', $student_id)->where('course_id', $course_id);
            $active_count   = $chapter_status->where('status', 'Active')->get()->count();
            $sum            = 100 / $chapters->count();
            $progress       = (int)($sum * $active_count);

            return response()->json(
                [
                    'code' => 200,
                    'status' => 'Success',
                    'success' => __('Watched'),
                    'progress' => $progress,
                ]
            );
        }
    }

    public function removeCheckbox($chapter_id, $course_id, $slug)
    {
        if(LmsUtility::StudentAuthCheck($slug) == false)
        {
            return response()->json(
                [
                    'code' => 200,
                    'status' => 'Error',
                    'error' => __('You need to login'),
                ]
            );
        }
        else
        {
            $id = Auth::guard('students')->user()->id;
            ChapterStatus::updateOrCreate(
                [
                    'student_id' => $id,
                    'chapter_id' => $chapter_id,
                    'course_id' => $course_id,
                ], [
                    'student_id' => $id,
                    'chapter_id' => $chapter_id,
                    'course_id' => $course_id,
                    'status' => 'Inactive',
                ]
            );

            $student_id     = Auth::guard('students')->user()->id;
            $chapters       = Chapters::where('course_id', $course_id);
            $chapter_status = ChapterStatus::where('student_id', $student_id)->where('course_id', $course_id);
            $active_count   = $chapter_status->where('status', 'Active')->get()->count();
            $sum            = 100 / $chapters->count();
            $progress       = (int)($sum * $active_count);

            return response()->json(
                [
                    'code' => 200,
                    'status' => 'Success',
                    'success' => __('Unwatched'),
                    'progress' => $progress,
                ]
            );
        }
    }

    public function certificatedl(Request $request,$course_id)
    {
        $objUser  = \Auth::guard('students')->user();
        $settings = Store::saveCertificate();
        $gradiant = $settings->certificate_gradiant;
        $chap_id = Chapters::select('id','course_id','duration')->where('course_id',$course_id)->get();
        $times   = Chapters::pluck('duration')->toArray();

        $totaltime = str_replace(':', '.', LmsUtility::sum_time($chap_id));

        $hours  = floor($totaltime/60);
        $minute = floor($totaltime%60);
        $total_hour = sprintf('%02d:%02d', $hours, $minute);

        $course = Course::all();
        $stud   = Student::all();

        // $user   = Student::where('id', $objUser->id)->first();
        $course_id = Course::where('id',$course_id)->first();

        $student                = new \stdClass();
        $student->name          = $objUser->name;
        $student->course_name   =!empty($course_id->title)?$course_id->title:'-';
        $student->course_time   = $total_hour;

        return view('lms::setting.templates.' . $settings->certificate_template, compact('gradiant','settings','stud','course','student','total_hour','chap_id'));
    }

    public function complete($slug, $order_id)
    {
        $order = \Modules\LMS\Entities\CourseOrder::where('id', \Illuminate\Support\Facades\Crypt::decrypt($order_id))->first();
        $store = \Modules\LMS\Entities\Store::getStore($slug);

        return view('lms::storefront.' . $store->theme_dir . '.complete', compact('slug', 'store', 'order_id', 'order'));
    }

    public function studentindex()
    {
        if (Auth::user()->isAbleTo('student manage')) {
            $workspace_id  = getActiveWorkSpace();
            $store = Store::where('workspace_id',$workspace_id)->first();
            $students = Student::where('store_id',$store->id)->get();

            return view('lms::student.index', compact('students'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function studentShow($id)
    {
        if (Auth::user()->isAbleTo('student show')) {
            $Course_orders = CourseOrder::where('student_id',$id)->get();
            $orders = CourseOrder::where('student_id',$id)->get()->pluck('course');
            $decodedOrders = $orders->map(function ($item) {
                return json_decode($item, true);
            });
            $course_id =[];
            foreach ($decodedOrders as $order) {
                foreach ($order as $productId => $product) {
                    $course_id[] = $product['product_id'];
                }
            }
            $courses = Course::whereIn('id',$course_id)->get();
            $student = Student::where('id',$id)->first();

            $Course_orders_summarys = CourseOrderSummary::where('student_id', $id)->where('workspace', getActiveWorkSpace())->where('created_by',creatorId())->get();
            return view('lms::student.show', compact('Course_orders','student','courses','Course_orders_summarys'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function coursePayWithBank(Request $request, $slug)
    {
        $validator  = \Validator::make(
            $request->all(),
            [
                'bank_transfer_invoice' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error',$messages->first());
        }
        $filenameWithExt  = $request->file('bank_transfer_invoice')->getClientOriginalName();
        $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension        = $request->file('bank_transfer_invoice')->getClientOriginalExtension();
        $fileNameToStores = $filename . '_' . time() . '.' . $extension;

        $uplaod = upload_file($request,'bank_transfer_invoice',$fileNameToStores,'bank_transfer');
        if($uplaod['flag'] == 1)
        {
            $path = $uplaod['url'];
        }
        else
        {
            return redirect()->back()->with('error', __($uplaod['msg']));
        }


        $store    = Store::where('slug', $slug)->first();
        $cart     = session()->get($slug);
        $products = $cart['products'];

        $product_name   = [];
        $product_id     = [];
        $sub_totalprice = 0;
        $total_price = 0;
        if(!empty($products))
        {
            foreach($products as $key => $product)
            {
                $product_name[] = $product['product_name'];
                $product_id[]   = $product['id'];
                $sub_totalprice += $product['price'];
                $total_price += $product['price'];
            }
        }
        $coupon = [];
        if (isset($cart['coupon'])) {
            $coupon = $cart['coupon']['coupon'];
        }
        if (isset($cart['coupon']) && isset($cart['coupon'])) {
            if ($cart['coupon']['coupon']['enable_flat'] == 'off') {
                $discount_value = ($sub_totalprice / 100) * $cart['coupon']['coupon']['discount'];
                $total_price    = $sub_totalprice - $discount_value;
            } else {
                $discount_value = $cart['coupon']['coupon']['flat_discount'];
                $total_price    = $sub_totalprice - $discount_value;
            }
        }
        $company_settings = getCompanyAllSetting($store->created_by,$store->workspace_id);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        if(!empty($product))
        {
            $student_id            = Auth::guard('students')->user()->id;
            $course_order                 = new CourseOrder();
            $course_order->order_id       = $orderID;
            $course_order->name           = Auth::guard('students')->user()->name;
            $course_order->card_number    = '';
            $course_order->card_exp_month = '';
            $course_order->card_exp_year  = '';
            $course_order->student_id     = $student_id;
            $course_order->course         = json_encode($products);
            $course_order->price          = $total_price;
            $course_order->coupon         = !empty($cart['coupon']['coupon']['id']) ? $cart['coupon']['coupon']['id'] : '';
            $course_order->coupon_json    = json_encode($coupon);
            $course_order->discount_price = !empty($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
            $course_order->price_currency = !empty($company_settings['defult_currancy']) ? $company_settings['defult_currancy'] : 'USD';
            $course_order->payment_type   = __('Bank Transfer');
            $course_order->txn_id         = '';
            $course_order->payment_status = 'Pending';
            $course_order->receipt        = $path;
            $course_order->store_id       = $store['id'];
            $course_order->save();


            $msg = redirect()->route(
                'store-complete.complete', [
                                            $store->slug,
                                            \Illuminate\Support\Facades\Crypt::encrypt($course_order->id),
                                        ]
            )->with('success', __('Your Order request send successfully'));
           session()->forget($slug);

           return $msg;
        }
        else
        {
            return redirect()->back()->with('error', __('failed'));
        }
    }

    public function courseBankRequestEdit($id)
    {
        $course_order = CourseOrder::find($id);
        $company_setting = getCompanyAllSetting();
        if($course_order)
        {
            return view('lms::course_orders.course_action', compact('course_order','company_setting'));
        }
        else
        {
            return response()->json(['error' => __('Request data not found!')], 401);
        }
    }

    public function courseBankRequestupdate(Request $request, $order_id)
    {
        $course_order = CourseOrder::find($order_id);
        if($course_order && $course_order->payment_status == 'Pending')
        {
            $course_order->payment_status = $request->status;
            $course_order->update();

            if($request->status == 'Approved')
            {
                $product = $course_order->course;
                $products = json_decode($product);

                foreach($products as $course_id)
                {
                    $purchased_course = new PurchasedCourse();
                    $purchased_course->course_id  = $course_id->product_id;
                    $purchased_course->student_id = $course_order->student_id;
                    $purchased_course->order_id   = $course_order->id;
                    $purchased_course->save();

                    $student=Student::where('id',$purchased_course->student_id)->first();
                    $student->courses_id=$purchased_course->course_id;
                    $student->save();
                }
            }

            return redirect()->back()->with('success', __('Course payment successfully updated.'));
        }
        else
        {
            return response()->json(['error' => __('Request data not found!')], 401);
        }

    }

    public function Qrcode()
    {
        $store = Store::where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->first();
        if($store)
        {
            $app_url               = trim(env('APP_URL'), '/');
            $store['store_url'] = $app_url . '/store-lms/' . $store['slug'];
            $qr_detail = LmsQr::where('store_id',$store->id)->where('created_by',creatorId())->first();
        }
        return view('lms::company.settings.show_qrcode',compact('store','qr_detail'));
    }

}
