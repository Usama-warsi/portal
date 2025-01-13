<?php

namespace Modules\LMS\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LMS\Entities\LmsUtility;
use Modules\LMS\Entities\Store;
use Modules\LMS\Entities\StoreThemeSetting;
use Illuminate\Support\Facades\Auth;
use Modules\LMS\Entities\Certificate;
use Modules\LMS\Entities\Course;
use Illuminate\Support\Facades\DB;
use Modules\LMS\Entities\CourseOrder;
use Modules\LMS\Entities\LmsPixelFields;
use Modules\LMS\Entities\LmsQr;

class LMSController extends Controller
{
    public function index()
    {
        if(Auth::user()->isAbleTo('lms dashboard manage'))
        {
            $store = Store::where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->first();
            $newproduct = Course::where('workspace_id', getActiveWorkSpace())->where('created_by',creatorId())->count();
            $products   = Course::where('workspace_id', getActiveWorkSpace())->where('created_by',creatorId())->limit(5)->get();
            $new_orders = CourseOrder::where('store_id', $store->id)->limit(5)->orderBy('id', 'DESC')->get();
            $course_orders     = CourseOrder::where('store_id', $store->id)->get();
            $chartData  = $this->getOrderChart(['duration' => 'week','store_id'=>$store->id]);
            $qr_detail = LmsQr::where('store_id',$store->id)->where('created_by',creatorId())->first();
            $users = User::find(creatorId());

            if($store)
            {
                $app_url               = trim(env('APP_URL'), '/');
                $store['store_url'] = $app_url . '/store-lms/' . $store['slug'];
            }

            $total_sale  = 0;
            $total_order = 0;
            if(!empty($course_orders))
            {
                $pro_qty   = 0;
                $item_id   = [];
                $total_qty = [];
                foreach($course_orders as $course_order)
                {
                    $order_array = json_decode($course_order->course);
                    $pro_id      = [];
                    foreach($order_array as $key => $item)
                    {
                        if(!empty($item_id))
                        {
                            if(!in_array($item->id, $item_id))
                            {
                                $item_id[] = $item->id;
                            }
                        }
                        else
                        {
                            if(!in_array($item->id, $item_id))
                            {
                                $item_id[] = $item->id;
                            }
                        }
                    }
                    $total_sale += $course_order['price'];
                    $total_order++;
                }
            }

            return view('lms::dashboard.dashboard', compact('products', 'total_sale', 'store', 'course_orders', 'total_order', 'newproduct', 'item_id', 'total_qty', 'chartData', 'new_orders','users','qr_detail'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function getOrderChart($arrParam)
    {
        $store = Store::find($arrParam['store_id']);
        $arrDuration = [];
        if($arrParam['duration'])
        {
            if($arrParam['duration'] == 'week')
            {
                $previous_week = strtotime("-2 week +1 day");
                for($i = 0; $i < 14; $i++)
                {
                    $arrDuration[date('Y-m-d', $previous_week)] = date('d-M', $previous_week);
                    $previous_week                              = strtotime(date('Y-m-d', $previous_week) . " +1 day");
                }
            }
        }

        $arrTask          = [];
        $arrTask['label'] = [];
        $arrTask['data']  = [];
        foreach($arrDuration as $date => $label)
        {
            if(Auth::user()->type == 'Owner')
            {
                $data = CourseOrder::select(DB::raw('count(*) as total'))->where('store_id', $store->id)->whereDate('created_at', '=', $date)->first();
            }
            else
            {
                $data = CourseOrder::select(DB::raw('count(*) as total'))->whereDate('created_at', '=', $date)->first();
            }

            $arrTask['label'][] = $label;
            $arrTask['data'][]  = $data->total;
        }

        return $arrTask;
    }



    public function LmsStoreSetting(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'name' => 'required|max:120',
                               'logo' => 'mimes:jpeg,png,jpg,gif,svg,pdf,doc|max:20480',
                               'invoice_logo' => 'mimes:jpeg,png,jpg,gif,svg,pdf,doc|max:20480',
                           ]
        );


        if($request->enable_domain == 'on')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'domains' => 'required',
                               ]
            );
        }
        if($request->enable_domain == 'enable_subdomain')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'subdomain' => 'required',
                               ]
            );
        }

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $store = Store::where('workspace_id',getActiveWorkSpace())->first();
        if(!empty($request->logo))
        {
            if(!empty($store->logo))
            {
                delete_file($store->logo);
            }
            $filenameWithExt = $request->file('logo')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('logo')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            $path = upload_file($request,'logo',$fileNameToStore,'lms_store_logo');
            if($path['flag'] == 1){
                $url = $path['url'];
            }
        }
        if(!empty($request->invoice_logo))
        {
            if(!empty($store->invoice_logo))
            {
                delete_file($store->invoice_logo);
            }
            $extension              = $request->file('invoice_logo')->getClientOriginalExtension();
            $fileNameToStoreInvoice = 'invoice_logo' . '_' . time() . '.' . $extension;

            $path = upload_file($request,'invoice_logo',$fileNameToStoreInvoice,'lms_store_logo');
            if($path['flag'] == 1){
                $url = $path['url'];
            }
        }

        if($request->enable_domain == 'enable_domain')
        {
            // Remove the http://, www., and slash(/) from the URL
            $input = $request->domains;
            // If URI is like, eg. www.way2tutorial.com/
            $input = trim($input, '/');
            // If not have http:// or https:// then prepend it
            if(!preg_match('#^http(s)?://#', $input))
            {
                $input = 'http://' . $input;
            }
            $urlParts = parse_url($input);
            // Remove www.
            $domain_name = preg_replace('/^www\./', '', $urlParts['host']);
            // Output way2tutorial.com
        }
        if($request->enable_domain == 'enable_subdomain')
        {
            // Remove the http://, www., and slash(/) from the URL
            $input = env('APP_URL');

            // If URI is like, eg. www.way2tutorial.com/
            $input = trim($input, '/');
            // If not have http:// or https:// then prepend it
            if(!preg_match('#^http(s)?://#', $input))
            {
                $input = 'http://' . $input;
            }

            $urlParts = parse_url($input);

            // Remove www.
            $subdomain_name = preg_replace('/^www\./', '', $urlParts['host']);
            // Output way2tutorial.com
            $subdomain_name = $request->subdomain . '.' . $subdomain_name;
        }

        $store['name']  = $request->name;
        $store['email']  = $request->email;
        if($request->enable_domain == 'enable_domain')
        {
            $store['domains'] = $domain_name;
        }
        $store['enable_storelink'] = ($request->enable_domain == 'enable_storelink' || empty($request->enable_domain)) ? 'on' : 'off';
        $store['enable_domain']    = ($request->enable_domain == 'enable_domain') ? 'on' : 'off';
        $store['enable_subdomain'] = ($request->enable_domain == 'enable_subdomain') ? 'on' : 'off';
        if($request->enable_domain == 'enable_subdomain')
        {
            $store['subdomain'] = $subdomain_name;
        }
        $store['enable_rating']     = $request->enable_rating ?? 'off';
        $store['blog_enable']       = $request->blog_enable ?? 'off';
        $store['about']             = $request->about;
        $store['tagline']           = $request->tagline;
        $store['storejs']           = $request->storejs;
        $store['whatsapp']          = $request->whatsapp;
        $store['facebook']          = $request->facebook;
        $store['instagram']         = $request->instagram;
        $store['twitter']           = $request->twitter;
        $store['youtube']           = $request->youtube;
        $store['footer_note']       = $request->footer_note;
        $store['address']           = $request->address;
        $store['city']              = $request->city;
        $store['state']             = $request->state;
        $store['zipcode']           = $request->zipcode;
        $store['country']           = $request->country;
        $store['lang']              = $request->store_default_language;
        if(!empty($fileNameToStore))
        {
            $store['logo'] = $url;
        }
        if(!empty($fileNameToStoreInvoice))
        {
            $store['invoice_logo'] = $url;
        }
        $store->update();
        return redirect()->back()->with('success','Store setting save sucessfully.');
    }

    public function changeTheme(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'theme_color' => 'required',
                               'themefile' => 'required',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $store                = Store::where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->first();
        $store['store_theme'] = $request->theme_color;
        $store['theme_dir']   = $request->themefile;
        $store->save();

        return redirect()->back()->with('success', __('Theme Successfully Updated.'));
    }

    public function Editproducts($slug, $theme)
    {
        $store = Store::where('slug', $slug)->first();
        $getStoreThemeSetting = LmsUtility::getStoreThemeSetting($store->id, $theme);
        $getStoreThemeSetting1 = [];

        if( empty($getStoreThemeSetting) || empty(trim($getStoreThemeSetting['dashboard'])) ) {
            //json file
            $path = asset( 'Modules/LMS/Resources/assets/image/'. $store->theme_dir . "/" . $store->theme_dir . ".json" );
            $getStoreThemeSetting = json_decode(file_get_contents($path), true);
        } else {
            $getStoreThemeSetting = json_decode($getStoreThemeSetting['dashboard'], true);
            $getStoreThemeSetting1 = LmsUtility::getStoreThemeSetting($store->id, $theme);
        }
        return view('lms::company.settings.edit_theme', compact('store', 'theme', 'getStoreThemeSetting','getStoreThemeSetting1'));
    }

    public function StoreEditProduct(Request $request, $slug, $theme)
    {
        $store = Store::where('slug', $slug)->first();
        $getStoreThemeSetting = LmsUtility::getStoreThemeSetting($store->id, $theme);
        if(!empty($getStoreThemeSetting['dashboard'])) {
            $getStoreThemeSetting = json_decode($getStoreThemeSetting['dashboard'], true);
        }

        $json = $request->array;
        foreach ($json as $key => $jsn) {

            foreach ($jsn['inner-list'] as $IN_key => $js)
            {

                if ($js['field_type'] == 'multi file upload')
                {
                    if (!empty($js['multi_image']))
                    {
                        foreach ($js['multi_image'] as $file)
                        {
                            $filenameWithExt = $file->getClientOriginalName();
                            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME) . '_brand';
                            $extension = $file->getClientOriginalExtension();
                            $fileNameToStore = $IN_key . '_' . rand(10, 100) . '_' . date('ymd') . time() . '.' . $extension;
                            $file_name[] = $fileNameToStore;

                            $path = multi_upload_file($file,'field_default_text',$fileNameToStore,$store->theme_dir.'/header');
                            if($path['flag'] == 1)
                            {
                                $url = $path['url'];
                            }else{
                                return redirect()->back()->with('error', __($path['msg']));
                            }
                            $new_path = $store->theme_dir . '/header/' . $fileNameToStore;
                            $json[$key]['inner-list'][$IN_key]['image_path'][] = $url;

                            $next_key_p_image = !empty($key_file) ? $key_file : 0;
                        }
                        if (!empty($jsn['prev_image']))
                        {
                            foreach ($jsn['prev_image'] as $p_key => $p_value) {
                                // $next_key_p_image = $next_key_p_image + 1;
                                $json[$key]['inner-list'][$IN_key]['image_path'][] = $p_value;
                            }
                        }
                    }else {
                        if(!empty($jsn['prev_image']))
                        {
                            foreach ($jsn['prev_image'] as $p_key => $p_value)
                            {
                                $json[$key]['inner-list'][$IN_key]['image_path'][] = $p_value;
                            }
                        }
                    }
                }
                if($js['field_type'] == 'photo upload')
                {
                    if ($jsn['array_type'] == 'multi-inner-list')
                    {

                        for ($i = 0; $i < $jsn['loop_number']; $i++)
                        {
                            if (!empty($json[$key][$js['field_slug']][$i]['image']) && gettype($json[$key][$js['field_slug']][$i]['image']) == 'object')
                            {
                                $file = $json[$key][$js['field_slug']][$i]['image'];

                                $filenameWithExt = $file->getClientOriginalName();
                                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME) ;
                                $extension = $file->getClientOriginalExtension();
                                $fileNameToStore = $i.'_'.rand(10,100).'_'.date('ymd') .time() .  '.'.$extension;
                                $file_name[] = $fileNameToStore;

                                $path = multi_upload_file($file,'field_default_text',$fileNameToStore,$store->theme_dir . '/header');
                                if($path['flag'] == 1){
                                    $url = $path['url'];
                                }else{
                                    return redirect()->back()->with('error', __($path['msg']));
                                }

                                if (!empty($file_name) && count($file_name) > 0) {
                                    $json[$key][$js['field_slug']][$i]['field_prev_text'] =  $url;
                                    $json[$key][$js['field_slug']][$i]['image'] = '';
                                }
                            } else{
                                $json[$key][$js['field_slug']][$i]['image'] = '';
                            }
                        }

                    } else {
                        if (gettype($js['field_default_text']) == 'object')
                        {
                            $file = $js['field_default_text'];
                            $filenameWithExt = $file->getClientOriginalName();
                            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME) ;
                            $extension = $file->getClientOriginalExtension();
                            $fileNameToStore = $filename  .date('ymd') .time() .  '.'.$extension;
                            $file_name[] = $fileNameToStore;

                            $requestImg['image'] = $file;
                            $myRequest = new Request();
                            $myRequest->request->add(['image' => $requestImg['image']]);
                            $myRequest->files->add(['image' => $requestImg['image']]);
                            $path = upload_file($myRequest,'image',$fileNameToStore,$store->theme_dir .'/header');
                            if (!empty($file_name) && count($file_name) > 0)
                            {
                                $post['Thumbnail Image'] =  $file_name;
                                foreach( $post['Thumbnail Image'] as $v)
                                {
                                    $headerImage = $store->theme_dir . '/header/' . $v;
                                }
                                $json[$key]['inner-list'][$IN_key]['field_default_text'] = $path['url'];
                            }
                        }

                    }
                }
            }
        }

        $json1 = json_encode($json);
        $store = Store::where('slug', $slug)->where('created_by', Auth::user()->id)->first();

        $where_array = [
            'name' => 'dashboard',
            'store_id' => $store->id,
            'theme_name' => $store->theme_dir,
        ];

        $update_create_array = [
            'name' => 'dashboard',
            'value' => $json1,
            'store_id' => $store->id,
            'theme_name' => $store->theme_dir,
            'created_by' => creatorId(),
        ];
        if(!empty($json1)) {
            StoreThemeSetting::updateOrCreate($where_array , $update_create_array);
        }

        return redirect()->back()->with('success', __('Successfully Saved!'). ((isset($result) && $result!=1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
    }

    public function saveCertificateSettings(Request $request)
    {
        $post = $request->all();
        unset($post['_token']);

        if(isset($post['certificate_template']) && (!isset($post['certificate_color']) || empty($post['certificate_color'])))
        {
            $post['certificate_color'] = "ffffff";
        }

        $store = Store::where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->first();
        $store                        = Store::find($store->id);
        $store->certificate_template  = $request->certificate_template;
        $store->certificate_color     = $request->certificate_color;
        $store->certificate_gradiant  = $request->gradiant;
        $store->header_name           = $request->header_name;
        $store->save();

        return redirect()->back()->with('success', __('Certificate Setting updated successfully'));
    }

    public function previewCertificate($template, $color,$gradiants)
    {
        $objUser  = Auth::user();
        $settings = Store::saveCertificate();

        if(!empty($user)){
            $course_id = Course::where('id' , $user->courses_id)->first();
        } else {
            $course_id = 0;
        }

        $certificate  = new Certificate();

        $student                = new \stdClass();
        $student->name          = '<Name>';
        $student->course_name   = '<Course Name>';
        $student->course_time   = '<Course Time>';

        $preview    = 1;
        $color      = '#' . $color;
        $font_color = LmsUtility::getFontColor($color);
        $gradiant   = $gradiants;

        return view('lms::company.settings.templates.' . $template, compact('certificate', 'preview', 'color', 'settings','student', 'font_color', 'gradiant','course_id'));
    }

    public function ChangeBlocks($slug, $theme)
    {
        $store = Store::where('slug', $slug)->first();
        $storethemesetting = StoreThemeSetting::where('store_id',$store->id)->where('theme_name',$theme)->where('created_by',creatorId())->first();
        if(empty($storethemesetting->block_order))
        {
            $storethemesetting['block_order'] = StoreThemeSetting::getDefaultThemeOrder($theme);
            $path = asset( 'Modules/LMS/Resources/assets/image/'. $store->theme_dir . "/" . $store->theme_dir . ".json" );
            $getStoreThemeSetting = json_decode(file_get_contents($path), true);
        }
        else{
            $storethemesetting['block_order'] = json_decode($storethemesetting['block_order']);
            $getStoreThemeSetting = LmsUtility::getStoreThemeSetting($store->id, $store->theme_dir);
            $getStoreThemeSetting = json_decode($getStoreThemeSetting['dashboard'], true);
        }
        return view('lms::company.settings.change_blocks', compact('store', 'theme', 'storethemesetting','getStoreThemeSetting'));
    }

    public function BlockSetting(Request $request,$slug, $theme)
    {
        $store = Store::where('slug', $slug)->first();
        $storethemesetting = StoreThemeSetting::where('store_id',$store->id)->where('theme_name',$theme)->where('created_by',creatorId())->first();
        $req_order = explode(",", $request->order);
        $order = [];
        foreach ($req_order as $key => $value) {
            $od = $key + 1;
            $order[$value] = $od;
        }
        if(!empty($storethemesetting))
        {
            $storethemesetting->block_order = json_encode($order);
            $storethemesetting->save();
        }
        else
        {
            $path = asset( 'Modules/LMS/Resources/assets/image/'. $store->theme_dir . "/" . $store->theme_dir . ".json" );
            $getStoreThemeSetting = json_decode(file_get_contents($path), true);
            $storethemesetting              = new StoreThemeSetting();
            $storethemesetting->name        = 'dashboard';
            $storethemesetting->value       = json_encode($getStoreThemeSetting);
            $storethemesetting->store_id    = $store->id;
            $storethemesetting->theme_name  = $theme;
            $storethemesetting->block_order = json_encode($order);
            $storethemesetting->created_by  = creatorId();
            $storethemesetting->save();
        }
        if(!empty($storethemesetting)) {
            $getStoreThemeSetting = json_decode($storethemesetting['value'], true);
        }
        $json =[];
        foreach ($getStoreThemeSetting as $key => $jsn)
        {
            if($jsn['section_slug'] == 'banner')
            {
                $jsn['section_enable'] = $request['banner'];
            }
            elseif($jsn['section_slug'] == 'homepage-header')
            {
                $jsn['section_enable'] = $request['homepage-header'];
            }
            elseif($jsn['section_slug'] == 'homepage-featured-course')
            {
                $jsn['section_enable'] = $request['homepage-featured-course'];
            }
            elseif($jsn['section_slug'] == 'homepage-categories')
            {
                $jsn['section_enable'] = $request['homepage-categories'];
            }
            elseif($jsn['section_slug'] == 'homepage-on-sale')
            {
                $jsn['section_enable'] = $request['homepage-on-sale'];
            }
            elseif($jsn['section_slug'] == 'homepage-email-subscriber')
            {
                $jsn['section_enable'] = $request['homepage-email-subscriber'];
            }
            $json[] = $jsn;
        }
        $storethemesetting->value       = json_encode($json);
        $storethemesetting->save();
        return redirect()->back()->with('success', __('Blocks Order Successfully Updated.'));
    }

    public function SeoSetting($slug, $theme)
    {
        $store = Store::where('slug', $slug)->first();
        $PixelFields = LmsPixelFields::where('store_id', $store->id)->where('created_by',creatorId())->get();
        return view('lms::company.settings.seo', compact('store', 'theme','PixelFields'));
    }

    public function SeoSettingStore(Request $request,$slug,$theme)
    {
        $store = Store::where('slug', $slug)->first();

        if(!empty($request->meta_image))
        {
            if(!empty($store->meta_image))
            {
                delete_file($store->meta_image);
            }
            $filenameWithExt  = $request->File('meta_image')->getClientOriginalName();
            $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension        = $request->file('meta_image')->getClientOriginalExtension();
            $fileNameToStoresmetaimage =  'meta_image'.'_' . $store->id. '.' . $extension;

            $path = upload_file($request,'meta_image',$fileNameToStoresmetaimage,'lms_meta_image');
            if($path['flag'] == 1){
                $url = $path['url'];
                $store->meta_image = $url;
            }else{
                return redirect()->back()->with('error', __($path['msg']));
            }
        }
        if(!empty($request->meta_image)){
            $store->meta_image = $url;
        }
        $store->meta_keyword     = $request->meta_keyword;
        $store->meta_description = $request->meta_description;
        $store->google_analytic  = $request->google_analytic;
        $store->fbpixel_code     = $request->fbpixel_code;
        $store->save();
        return redirect()->back()->with('success', __('SEO Successfully Updated.'));
    }

    public function PixelCreate($slug)
    {
        if(Auth::user()->isAbleTo('lms pixel fields create'))
        {
            $store = Store::where('slug', $slug)->first();
            $pixals_platforms = Store::pixel_plateforms();
            return view('lms::company.settings.create_pixel',compact('store','pixals_platforms'));
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function PixelStore(Request $request, $slug)
    {
        if(Auth::user()->isAbleTo('lms pixel fields create'))
        {
            $store = Store::where('slug', $slug)->first();

            $request->validate([
                'platform'=>'required',
                'pixel_id'=>'required'
            ]);
            $pixel_fields             = new LmsPixelFields();
            $pixel_fields->platform   = $request->platform;
            $pixel_fields->pixel_id   = $request->pixel_id;
            $pixel_fields->store_id   = $store->id;
            $pixel_fields->created_by = creatorId();
            $pixel_fields->save();

            return redirect()->back()->with('success', __('Fields Saves Successfully.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function PixelDelete($id)
    {
        if(Auth::user()->isAbleTo('lms pixel fields create'))
        {
            $pixelfield= LmsPixelFields::find($id);
            $pixelfield->delete();
            return redirect()->back()->with('success', __('Pixel Deleted Successfully!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function PWASetting($slug,$theme)
    {
            $store = Store::where('slug', $slug)->first();
            try {
                $pwa_data = \File::get('uploads/theme_app/lms_' . $store->id . '/manifest.json');
                $pwa_data = json_decode($pwa_data);


            } catch (\Throwable $th) {
                $pwa_data = '';
            }
            return view('lms::company.settings.pwa',compact('store','pwa_data'));
    }

    public function PWASettingStore(Request $request, $id)
    {
        if (\Auth::user()->isAbleTo('lms pwa settings')) {
            $store_id = $id;
            $store = Store::find($id);
            $store['enable_pwa'] = $request->enable_pwa ?? 'off';

            if ($request->enable_pwa == 'on') {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'pwa_app_title' => 'required|max:100',
                        'pwa_app_name' => 'required|max:50',
                        'pwa_app_background_color' => 'required|max:15',
                        'pwa_app_theme_color' => 'required|max:15',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $company_favicon = get_module_img('LMS');
                $lang = \Auth::user()->lang;
                if ($store['enable_storelink'] == 'on') {
                    $app_url               = trim(env('APP_URL'), '/');
                    $start_url = $app_url . '/store-lms/' . $store['slug'];
                } else if ($store['enable_domain'] == 'on') {
                    $start_url = 'https://' . $store['domains'] . '/';
                } else {
                    $start_url = 'https://' . $store['subdomain'] . '/';
                }


                $mainfest = '{
                                "lang": "' . $lang . '",
                                "name": "' . $request->pwa_app_title . '",
                                "short_name": "' . $request->pwa_app_name . '",
                                "start_url": "' . $start_url . '",
                                "display": "standalone",
                                "background_color": "' . $request->pwa_app_background_color . '",
                                "theme_color": "' . $request->pwa_app_theme_color . '",
                                "orientation": "portrait",
                                "categories": [
                                    "shopping"
                                ],
                                "icons": [
                                    {
                                        "src": "' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png').'?'.time() . '",
                                        "sizes": "80x80",
                                        "type": "image/png",
                                        "purpose": "any"
                                    },
                                    {
                                        "src": "' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png').'?'.time() . '",
                                        "sizes": "128x128",
                                        "type": "image/png",
                                        "purpose": "any"
                                    },
                                    {
                                        "src": "' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png').'?'.time() . '",
                                        "sizes": "144x144",
                                        "type": "image/png",
                                        "purpose": "any"
                                    },
                                    {
                                        "src": "' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png').'?'.time() . '",
                                        "sizes": "152x152",
                                        "type": "image/png",
                                        "purpose": "any"
                                    },
                                    {
                                        "src": "' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png').'?'.time() . '",
                                        "sizes": "192x192",
                                        "type": "image/png",
                                        "purpose": "any"
                                    },
                                    {
                                        "src": "' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png').'?'.time() . '",
                                        "sizes": "256x256",
                                        "type": "image/png",
                                        "purpose": "any"
                                    },
                                    {
                                        "src": "' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png').'?'.time() . '",
                                        "sizes": "512x512",
                                        "type": "image/png",
                                        "purpose": "any"
                                    },
                                    {
                                        "src": "' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png').'?'.time() . '",
                                        "sizes": "1024x1024",
                                        "type": "image/png",
                                        "purpose": "any"
                                    }
                                ]
                            }';


                if (!file_exists('uploads/theme_app/lms_' . $store_id)) {
                    mkdir('uploads/theme_app/lms_' . $store_id, 0777, true);
                }
                if (!file_exists('uploads/theme_app/lms_' . $store_id . '/manifest.json')) {
                    fopen('uploads/theme_app/lms_' . $store_id . "/manifest.json", "w");
                }
                \File::put('uploads/theme_app/lms_' . $store_id . '/manifest.json', $mainfest);
            }

            $store->save();
            $tab = 6;
            return redirect()->back()->with('success', __('PWA Successfully Updated.'))->with('tab', $tab);
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function QrCodeSetting($slug,$theme)
    {
        $store = Store::where('slug',$slug)->first();
        $qr_code = Store::$qr_type;
        $qr_detail = LmsQr::where('store_id',$store->id)->where('created_by',creatorId())->first();

        return view('lms::company.settings.qrcode',compact('store','qr_detail','qr_code'));
    }

    public function QrCodeSettingStore(Request $request, $id)
    {
        $tab = 8;
        $lmsqr = LmsQr::where('store_id', $id)->where('created_by',creatorId())->first();
        if ($request->hasFile('image')) {
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = $filename . '_' . time() . '.' . $extension;

            $upload_qr = upload_file($request, 'image', $fileName, 'qrcode');
            if ($upload_qr['flag'] == 1) {
                $url = $upload_qr['url'];
            } else {
                return redirect()->back()->with('error', $upload_qr['msg']);
            }
            $qrImage = $url;
        }
        if (empty($lmsqr)) {
            $lmsqr = new LmsQr();

        }

        if (!isset($fileName)) {
            $qrImage = isset($lmsqr->image) ? $lmsqr->image : null;
        }

        $lmsqr->foreground_color = isset($request->foreground_color) ? $request->foreground_color : '#000000';
        $lmsqr->background_color = isset($request->background_color) ? $request->background_color : '#ffffff';
        $lmsqr->radius = isset($request->radius) ? $request->radius : 26;
        $lmsqr->qr_type = isset($request->qr_type) ? $request->qr_type : 0;
        $lmsqr->qr_text = isset($request->qr_text) ? $request->qr_text : "Vcard";
        $lmsqr->qr_text_color = isset($request->qr_text_color) ? $request->qr_text_color : '#f50a0a';
        $lmsqr->size = isset($request->size) ? $request->size : 9;
        $lmsqr->image = isset($qrImage) ? $qrImage : null;
        $lmsqr->store_id = $id;
        $lmsqr->created_by = creatorId();
        $lmsqr->save();
        $tab = 8;
        return redirect()->back()->with('success', 'QrCode generated successfully')->with('tab', $tab);

    }
}
