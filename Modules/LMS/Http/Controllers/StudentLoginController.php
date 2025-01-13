<?php

namespace Modules\LMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LMS\Entities\LmsUtility;
use Modules\LMS\Entities\PageOption;
use Modules\LMS\Entities\Blog;
use Modules\LMS\Entities\Student;
use Modules\LMS\Entities\Store;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Modules\LMS\Entities\StudentLoginDetail;

class StudentLoginController extends Controller
{
    public function showLoginForm($slug)
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
        $page_slug_urls        = PageOption::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();
        $blog                  = Blog::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by);
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
            $path = asset( 'Modules/LMS/Resources/assets/image/' . $store->theme_dir . "/" . $store->theme_dir . ".json") ;
            $getStoreThemeSetting = json_decode(file_get_contents($path), true);
        }

        return view('lms::storefront.' . $store->theme_dir . '.user.login', compact('blog', 'demoStoreThemeSetting', 'store', 'slug', 'page_slug_urls','getStoreThemeSetting','getStoreThemeSetting1'));
    }

    public function login(Request $request, $slug, $cart = 0)
    {
        if($this->validator($request, $slug) == true)
        {
            if(Auth::guard('students')->attempt($request->only('email', 'password'), $request->filled('remember')))
            {
                $ip = $_SERVER['REMOTE_ADDR']; // your ip address here


                // $ip = '49.36.83.154'; // This is static ip address

                $query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));
                if (isset($query['status']) &&  $query['status'] != 'fail') {
                    $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
                    if ($whichbrowser->device->type == 'bot') {
                        return;
                    }
                    $referrer = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER']) : null;
                    /* Detect extra details about the user */
                    $query['browser_name'] = $whichbrowser->browser->name ?? null;
                    $query['os_name'] = $whichbrowser->os->name ?? null;
                    $query['browser_language'] = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
                    $query['device_type'] = GetDeviceType($_SERVER['HTTP_USER_AGENT']);
                    $query['referrer_host'] = !empty($referrer['host']);
                    $query['referrer_path'] = !empty($referrer['path']);

                    isset($query['timezone'])?date_default_timezone_set($query['timezone']):'';
                    $student =  \Auth::guard('students')->user();
                    $store   = Store::where('slug', $slug)->first();
                    $json = json_encode($query);
                    $login_detail = StudentLoginDetail::create([
                        'student_id' => $student->id,
                        'workspace_id' => $store->workspace_id,
                        'ip' => $ip,
                        'date' => date('Y-m-d H:i:s'),
                        'details' => $json,
                        'created_by' => $store->created_by,
                    ]);
                }

                //Authentication passed...
                if($cart == 1)
                {
                    return redirect()->route('store.cart', $slug)->with('success', __('You can checkout now.'));
                }
                else
                {

                    return redirect()->route('student.home', $slug);
                }

            }
        }
        else
        {
            return redirect()->back()->with('error', 'These credentials do not match our records.');
        }


        //Authentication failed...
        return $this->loginFailed();
    }

    private function validator(Request $request, $slug)
    {
        //custom validation error messages.
        $messages = [
            'email.exists' => 'These credentials do not match our records.',
        ];
        $validate = Validator::make(
            $request->all(), [
                               'email' => [
                                   'required',
                                   'string',
                                   'email',
                                   'min:5',
                                   'max:191',
                               ],
                               'password' => [
                                   'required',
                                   'string',
                                   'min:4',
                                   'max:255',
                               ],
                           ]
        );
        $store    = Store::where('slug', $slug)->first();
        $vali     = Student::where('email', $request->email)->where('store_id', $store->id)->count();
        if($validate->fails())
        {
            $message = $validate->getMessageBag();

            return redirect()->back()->with('error', $message->first());
        }
        elseif($vali > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function profile($slug, $id)
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
        $userDetail = Student::find(Crypt::decrypt($id));

        return view('lms::storefront.' . $store->theme_dir . '.student.profile', compact('blog', 'demoStoreThemeSetting', 'slug', 'store', 'page_slug_urls', 'userDetail'));
    }

    public function profileUpdate($slug, Request $request)
    {
        $store          = Store::where('slug', $slug)->first();
        $blog           = Blog::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by);
        $page_slug_urls = PageOption::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();
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
                               'email' => [
                                   'required',
                                   'string',
                                   'email',
                                   'max:255',
                               ],
                           ]
        );
        $vali     = Student::where('email', $request->email)->where('store_id', $store->id)->count();
        if($validate->fails())
        {
            $message = $validate->getMessageBag();

            return redirect()->back()->with('error', $message->first());
        }
        $student = Student::find($request->id);

        if($request->hasFile('profile'))
        {
            $filenameWithExt = $request->file('profile')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('profile')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            $path = upload_file($request,'profile',$fileNameToStore,'profile');
            if($path['flag'] == 1){
                $url = $path['url'];
            }else{
                return redirect()->back()->with('error', __($path['msg']));
            }

        }

        if(!empty($request->profile))
        {
            $student['avatar'] = $url;
        }
        if($request->email != $student->email)
        {
            if($vali > 0)
            {
                return redirect()->back()->with('error', __('Email already exists'));
            }
            else
            {
                $student->email = $request->email;
            }
        }


        $student->name = $request->name;
        $student->update();
        if(!empty($request->current_password) && !empty($request->new_password) && !empty($request->confirm_password))
        {
            if(Auth::guard('students')->check())
            {
                $request->validate(
                    [
                        'current_password' => 'required',
                        'new_password' => 'required|min:6',
                        'confirm_password' => 'required|same:new_password',
                    ]
                );
                $objUser          = Auth::guard('students')->user();
                $request_data     = $request->All();
                $current_password = $objUser->password;
                if(Hash::check($request_data['current_password'], $current_password))
                {
                    $user_id            = Auth::guard('students')->user()->id;
                    $obj_user           = Student::find($user_id);
                    $obj_user->password = Hash::make($request_data['new_password']);;
                    $obj_user->update();

                    return redirect()->back()->with('success', __('Password successfully updated.'));
                }
                else
                {
                    return redirect()->back()->with('error', __('Please enter correct current password.'));
                }
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        return redirect()->back()->with('success', __('Profile successfully updated.'));
    }

    private function loginFailed()
    {
        return redirect()->back()->withInput()->with('error', 'These credentials do not match our records!');
    }

    public function logout($slug)
    {
        $store          = Store::where('slug', $slug)->first();
        $blog           = Blog::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by);
        $page_slug_urls = PageOption::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();
        if(empty($store))
        {
            return redirect()->back()->with('error', __('Store not available'));
        }
        Auth::guard('students')->logout();

        return redirect()->route('store.slug', $slug);
    }
}
