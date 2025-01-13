<?php

namespace Modules\LMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LMS\Entities\Blog;
use Modules\LMS\Entities\LmsUtility;
use Modules\LMS\Entities\PageOption;
use Modules\LMS\Entities\Store;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Modules\LMS\Entities\Student;

class StudentForgotPasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function showLinkRequestForm($slug)
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
        $page_slug_urls = PageOption::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by)->get();
        $blog           = Blog::where('workspace_id', $store->workspace_id)->where('created_by',$store->created_by);
        $demoStoreThemeSetting = LmsUtility::demoStoreThemeSetting($store->id);
        if(empty($store))
        {
            return redirect()->back()->with('error', __('Store not available'));
        }

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
        return view('lms::storefront.' . $store->theme_dir . '.student.password',compact('store','page_slug_urls','slug','blog','demoStoreThemeSetting','getStoreThemeSetting'));
    }

    public function postStudentEmail(Request $request,$slug)
    {
        $request->validate(
            [
                'email' => 'required|email|exists:students',
            ]
        );

        $token = \Str::random(60);

        DB::table('password_resets')->insert(
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]
        );

        try{
            $store = Store::where('slug', $slug)->first();
            $company_settings = getCompanyAllSetting($store->created_by, $store->workspace_id);
            config(
                [
                    'mail.mailers.smtp.host' => $company_settings['mail_host'],
                    'mail.mailers.smtp.port' => $company_settings['mail_port'],
                    'mail.mailers.smtp.encryption' => $company_settings['mail_encryption'],
                    'mail.mailers.smtp.password' => $company_settings['mail_password'],
                    'mail.mailers.smtp.username' => $company_settings['mail_username'],
                ]
            );
            Mail::send(
                'lms::storefront.'.$store->theme_dir.'.student.resetmail', ['token' => $token,'slug'=>$slug], function ($message) use ($request , $company_settings){
                    $message->from($company_settings['mail_username'],$company_settings['mail_from_name']);
                    $message->to($request->email);
                    $message->subject('Reset Password Notification');

                    // return back()->with('success', 'We have e-mailed your password reset link!');
                }
            );
        }catch(\Exception $e)
        {
            $smtp_error['status'] = false;
            $smtp_error['msg'] = $e->getMessage();

        }
        return redirect()->back()->with('error', __('We have e-mailed your password reset link!') . ((isset($smtp_error['status'])) ? $smtp_error['msg']  : ''));
    }

    public function getStudentPassword($slug,$token)
    {
        $store          = Store::where('slug', $slug)->first();
        $page_slug_urls = PageOption::where('workspace_id', $store->workspace_id)->get();
        $blog           = Blog::where('workspace_id', $store->workspace_id);
        $demoStoreThemeSetting = LmsUtility::demoStoreThemeSetting($store->id);
        if(empty($store))
        {
            return redirect()->back()->with('error', __('Store not available'));
        }

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

        return view('lms::storefront.' . $store->theme_dir . '.student.newpassword', compact('token','slug','store','page_slug_urls','blog','demoStoreThemeSetting','getStoreThemeSetting'));
    }

    public function updateStudentPassword(Request $request,$slug)
    {
        $request->validate(
            [
                'email' => 'required|email|exists:students',
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required',

            ]
        );

        $updatePassword = DB::table('password_resets')->where(
            [
                'email' => $request->email,
                'token' => $request->token,
            ]
        )->first();

        if(!$updatePassword)
        {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = Student::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return redirect()->route('student.loginform',$slug)->with('success', 'Your password has been changed.');

    }
}
