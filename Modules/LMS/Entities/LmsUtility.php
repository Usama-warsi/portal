<?php

namespace Modules\LMS\Entities;

use App\Models\User;
use App\Models\WorkSpace;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\LMS\Entities\Store;
use Modules\LMS\Entities\Student;

class LmsUtility extends Model
{
    use HasFactory;
    private static $fetchthemesetting = null;
    public static function course_level()
    {

        $level = [
            'Beginner' => 'Beginner',
            'Intermediate' => '	Intermediate',
            'Expert' => 'Expert',
        ];

        return $level;
    }

    public static function status()
    {

        $status = [
            'Active' => 'Active',
            'Inactive' => 'Inactive',
        ];

        return $status;
    }
    public static function themeOne()
    {
        $arr = [];
        $arr = [
            'theme1' => [
                'yellow-style.css' => [
                    'img_path' => asset('Modules/LMS/Resources/assets/image/store_theme/theme1/Home.png'),
                    'color' => 'fbd593',
                ],
                'blue-style.css' => [
                    'img_path' => asset('Modules/LMS/Resources/assets/image/store_theme/theme1/Home-1.png'),
                    'color' => 'aac8e3',
                ],
                'green-style.css' => [
                    'img_path' => asset('Modules/LMS/Resources/assets/image/store_theme/theme1/Home-2.png'),
                    'color' => 'bdd683',
                ],
            ],

            'theme2' => [
                'dark-blue-color.css' => [
                    'img_path' => asset('Modules/LMS/Resources/assets/image/store_theme/theme2/Home.png'),
                    'color' => '1E56E7',
                ],
                'dark-green-color.css' => [
                    'img_path' => asset('Modules/LMS/Resources/assets/image/store_theme/theme2/Home-1.png'),
                    'color' => '34e89e',
                ],
                'dark-pink-color.css' => [
                    'img_path' => asset('Modules/LMS/Resources/assets/image/store_theme/theme2/Home-2.png'),
                    'color' => '8C366C',
                ],
            ],

            'theme3' => [
                'light-blue-style.css' => [
                    'img_path' => asset('Modules/LMS/Resources/assets/image/store_theme/theme3/Home.png'),
                    'color' => '1DB2F8',
                ],
                'light-pink-style.css' => [
                    'img_path' => asset('Modules/LMS/Resources/assets/image/store_theme/theme3/Home-1.png'),
                    'color' => '39065A',
                ],
                'light-green-style.css' => [
                    'img_path' => asset('Modules/LMS/Resources/assets/image/store_theme/theme3/Home-2.png'),
                    'color' => '50C0C7',
                ],
            ],

            'theme4' => [
                'green-blue-style.css' => [
                    'img_path' => asset('Modules/LMS/Resources/assets/image/store_theme/theme4/Home.png'),
                    'color' => '06D4AE',
                ],
                'green-black-style.css' => [
                    'img_path' => asset('Modules/LMS/Resources/assets/image/store_theme/theme4/Home-1.png'),
                    'color' => '00727a',
                ],
                'blue-black-style.css' => [
                    'img_path' => asset('Modules/LMS/Resources/assets/image/store_theme/theme4/Home-2.png'),
                    'color' => '8FE3CF',
                ],
            ],

        ];

        return $arr;
    }

    public static function chapter_type()
    {

        $type = [
            'Video Url' => 'Video Url',
            'Video File' => 'Video File',
            'iFrame' => 'iFrame',
            'Text Content' => 'Text Content',
        ];

        return $type;
    }

    public function createSlug($table, $title, $id = 0)
    {
        // Normalize the title
        $slug = Str::slug($title, '-');
        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = $this->getRelatedSlugs($table, $slug, $id);
        // If we haven't used it before then we are all good.
        if(!$allSlugs->contains('slug', $slug))
        {
            return $slug;
        }
        // Just append numbers like a savage until we find not used.
        for($i = 1; $i <= 100; $i++)
        {
            $newSlug = $slug . '-' . $i;
            if(!$allSlugs->contains('slug', $newSlug))
            {
                return $newSlug;
            }
        }
        throw new \Exception('Can not create a unique slug');
    }

    protected function getRelatedSlugs($table, $slug, $id = 0)
    {
        return DB::table($table)->select()->where('slug', 'like', $slug . '%')->where('id', '<>', $id)->get();
    }

    public static function getStoreThemeSetting($store_id = null, $theme = null)
    {
        if(self::$fetchthemesetting === null)
        {
            $data     = DB::table('store_theme_settings');
            $settings = [];

            if(Auth::check() && Auth::user()->type != 'super admin')
            {
                $workspace_id = getActiveWorkSpace();
                $store_id = Store::where('workspace_id',$workspace_id)->first();
                $data     = $data->where('store_id', '=', $store_id->id)->where('theme_name', '=', $theme);
            }
            else
            {
                $data = $data->where('store_id', '=', $store_id)->where('theme_name', '=', $theme);
            }
            $data = $data->get();

            self::$fetchthemesetting = $data;
        }

        foreach(self::$fetchthemesetting as $row)
        {
            $settings[$row->name] = $row->value;
        }

        return $settings;
    }

    public static function templateData()
    {
        $arr              = [];
        $arr['colors']    =[
            [
                'hex'=>'b10d0d',
                'gradiant'=>'color-one'
            ],
            [
                'hex'=>'554360',
                'gradiant'=>'color-two'
            ],
            [
                'hex'=>'2a475b',
                'gradiant'=>'color-three'
            ],
            [
                'hex'=>'6f0000',
                'gradiant'=>'color-four'
            ],
            [
                'hex'=>'1d7280',
                'gradiant'=>'color-five'
            ],
            [
                'hex'=>'365476',
                'gradiant'=>'color-six'
            ],
            [
                'hex'=>'414345',
                'gradiant'=>'color-seven'
            ],
            [
                'hex'=>'237a57',
                'gradiant'=>'color-eight'
            ],
            [
                'hex'=>'734b6d',
                'gradiant'=>'color-nine'
            ],
            [
                'hex'=>'aa076b',
                'gradiant'=>'color-ten'
            ],
        ];

        $arr['templates'] = [
            "template1" => "Certificate 1",
            "template2" => "Certificate 2",
        ];

        return $arr;
    }

    public static function hex2rgb($hex)
    {
        $hex = str_replace("#", "", $hex);

        if(strlen($hex) == 3)
        {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        }
        else
        {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array(
            $r,
            $g,
            $b,
        );

        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values

    }

    public static function getFontColor($color_code)
    {
        $rgb = self::hex2rgb($color_code);
        $R   = $G = $B = $C = $L = $color = '';

        $R = (floor($rgb[0]));
        $G = (floor($rgb[1]));
        $B = (floor($rgb[2]));

        $C = [
            $R / 255,
            $G / 255,
            $B / 255,
        ];

        for($i = 0; $i < count($C); ++$i)
        {
            if($C[$i] <= 0.03928)
            {
                $C[$i] = $C[$i] / 12.92;
            }
            else
            {
                $C[$i] = pow(($C[$i] + 0.055) / 1.055, 2.4);
            }
        }

        $L = 0.2126 * $C[0] + 0.7152 * $C[1] + 0.0722 * $C[2];

        if($L > 0.179)
        {
            $color = 'black';
        }
        else
        {
            $color = 'white';
        }

        return $color;
    }

    public static function demoStoreThemeSetting($store_id = null)
    {
        if(!empty($store_id))
        {
            $data = StoreThemeSetting::where('store_id', $store_id)->get();
        }
        else
        {
            $data = [];
        }

        $settings = [
            /*HEADER*/
            "enable_header_img" => "on",
            "header_title" => "Knowledge",
            "header_desc" => "The only true wisdom is in knowing you know nothing.",
            "button_text" => "Explore Courses",
            "header_img" => "default_header_img.jpg",

            /*HEADER SECTION*/
            "enable_header_section_img" => "on",
            "header_section_title" => "Knowledge",
            "header_section_desc" => "The only true wisdom is in knowing you know nothing.",
            "button_section_text" => "Contact me",
            "button_section_url" => "#button",
            "header_section_img" => "default_section_img.jpg",

            /*FOOTER 1*/
            "enable_footer_note" => "on",
            "enable_quick_link1" => "on",
            "enable_quick_link2" => "on",
            "enable_quick_link3" => "on",
            "enable_footer_desc" => "on",

            "quick_link_header_name1" => "Account",
            "quick_link_header_name2" => "About",
            "quick_link_header_name3" => "Company",
            "footer_desc" => "Purpose is a unique and beautiful collection of UI elements that are all flexible and modular. A complete and customizable solution to building the website of your dreams.",

            "quick_link_name11" => "Profile",
            "quick_link_name12" => "Settings",
            "quick_link_name13" => "Notifications",
            "quick_link_name14" => "Notifications",


            "quick_link_name21" => "Services",
            "quick_link_name22" => "Contact",
            "quick_link_name23" => "Careers",
            "quick_link_name24" => "Careers",

            "quick_link_name31" => "Terms",
            "quick_link_name32" => "Privacy",
            "quick_link_name33" => "Support",
            "quick_link_name34" => "Support",

            "quick_link_url11" => "#Profile",
            "quick_link_url12" => "#Settings",
            "quick_link_url13" => "#Notifications",
            "quick_link_url14" => "#Notifications",

            "quick_link_url21" => "#Services",
            "quick_link_url22" => "#Contact",
            "quick_link_url23" => "#Careers",
            "quick_link_url24" => "#Careers",

            "quick_link_url31" => "#Terms",
            "quick_link_url32" => "#Privacy",
            "quick_link_url33" => "#Support",
            "quick_link_url34" => "#Support",


            /*FOOTER LOGO*/
            "footer_logo" => "default_footer_logo.png",

            /*FOOTER 2*/
            "enable_footer" => "on",
            "email" => "test@test.com",
            "whatsapp" => "https://api.whatsapp.com/",
            "facebook" => "https://www.facebook.com/",
            "instagram" => "https://www.instagram.com/",
            "twitter" => "https://twitter.com/",
            "youtube" => "https://www.youtube.com/",
            "footer_note" => "© 2021 My Store. All rights reserved",
            "storejs" => "<script></script>",

            "enable_brand_logo" => "on",
            "brand_logo" => implode(
                ',', [
                       'brand_logo.png',
                       'brand_logo.png',
                       'brand_logo.png',
                       'brand_logo.png',
                       'brand_logo.png',
                       'brand_logo.png',
                   ]
            ),

            "enable_categories" => "on",
            "categories" => "Categories",
            "categories_title" => "There is only that moment and the incredible certainty that everything under the sun has been written by one hand only.",

            "enable_featuerd_course" => "on",
            "featured_title" => "Featured Course",

        ];

        foreach($data as $row)
        {
            $settings[$row->name] = $row->value;
        }

        return $settings;
    }

    public static function StudentAuthCheck($slug = null)
    {
        if($slug == null)
        {
            $slug = \Request::segment(1);
        }
        $auth_student = Auth::guard('students')->user();
        if(!empty($auth_student))
        {
            $store = Store::getStore($slug);
            $student  = Student::studentAuth($store->id);
            if($student > 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    public static function sum_time($times)
    {
        $m_h = 0;
        foreach ($times as $time) {
            $time=!empty($time->duration)?$time->duration:'00:00';
            sscanf($time, '%d:%d', $hour, $min);
            $m_h += $hour * 60 + $min;

        }
        if ($h = floor($m_h / 60)) {
            $m_h %= 60;
        }
        return sprintf('%02d:%02d', $h, $m_h);

    }

    public static function DirectAssignCourse($store,$type)
    {
        $cart     = session()->get($store->slug);
        $products = $cart['products'];

        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $student = Auth::guard('students')->user();
        $company_settings = getCompanyAllSetting($store->created_by,$store->workspace_id);
        if(!empty($products))
        {
            $course_order                 = new \Modules\LMS\Entities\CourseOrder();
            $course_order->order_id       = $orderID;
            $course_order->name           = $student->name;
            $course_order->card_number    = '';
            $course_order->card_exp_month = '';
            $course_order->card_exp_year  = '';
            $course_order->student_id     = $student->id;
            $course_order->course         = json_encode($products);
            $course_order->price          = 0;
            $course_order->coupon         = !empty($cart['coupon']['coupon']['id']) ? $cart['coupon']['coupon']['id'] : '';
            $course_order->coupon_json    = json_encode(!empty($coupon) ? $coupon : '');
            $course_order->discount_price = !empty($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
            $course_order->price_currency = !empty($company_settings['defult_currancy']) ? $company_settings['defult_currancy'] : 'USD';
            $course_order->txn_id         = '';
            $course_order->payment_type   = $type;
            $course_order->payment_status = 'succeeded';
            $course_order->receipt        = '';
            $course_order->store_id       = $store['id'];
            $course_order->save();

            foreach ($products as $course_id) {
                $purchased_course = new \Modules\LMS\Entities\PurchasedCourse();
                $purchased_course->course_id  = $course_id['product_id'];
                $purchased_course->student_id = $student->id;
                $purchased_course->order_id   = $course_order->id;
                $purchased_course->save();

                $student = \Modules\LMS\Entities\Student::where('id', $purchased_course->student_id)->first();
                $student->courses_id = $purchased_course->course_id;
                $student->save();
            }
            session()->forget($store->slug);
            return ['is_success' => true,'courseorder_id' =>$course_order->id];
        }
        else
        {
            return ['is_success' => false];
        }
    }

    public static function defaultdata($company_id = null, $workspace_id = null)
    {
        if($company_id == Null)
        {
            $companys = User::where('type','company')->get();
            foreach($companys as $company)
            {
                $WorkSpaces = WorkSpace::where('created_by',$company->id)->get();
                foreach($WorkSpaces as $WorkSpace)
                {
                    $check = Store::where('workspace_id',$WorkSpace->id)->where('created_by',$company->id)->first();
                    if(!$check)
                    {
                        $company_setting = getCompanyAllSetting($company->id,$WorkSpace->id);
                        $store   = Store::create(
                            [
                                'created_by' => $company->id,
                                'name' => $WorkSpace->name,
                                'lang' => !empty($company_setting['default_language']) ? $company_setting['default_language'] : 'en',
                                'currency' => !empty($company_setting['currency_symbol']) ? $company_setting['currency_symbol'] : '$',
                                'currency_code' => !empty($company_setting['currency']) ? $company_setting['currency'] : 'USD',
                            ]
                        );
                        $store->name                 = $WorkSpace->name;
                        $store->enable_storelink     = 'on';
                        $store->theme_dir            = 'theme1';
                        $store->store_theme          = 'yellow-style.css';
                        $store->header_name          = 'Course Certificate';
                        $store->certificate_template = 'template1';
                        $store->certificate_color    = 'b10d0d';
                        $store->certificate_gradiant = 'color-one';
                        $store->workspace_id         = $WorkSpace->id;
                        $store->save();
                    }
                }
            }
        }elseif($workspace_id == Null){
            $company = User::where('type','company')->where('id',$company_id)->first();
            $WorkSpaces = WorkSpace::where('created_by',$company->id)->get();
            foreach($WorkSpaces as $WorkSpace)
            {
                $check = Store::where('workspace_id',$WorkSpace->id)->where('created_by',$company->id)->first();
                if(!$check)
                {
                    $company_setting = getCompanyAllSetting($company->id,$WorkSpace->id);
                    $store   = Store::create(
                        [
                            'created_by' => $company->id,
                            'name' => $WorkSpace->name,
                            'lang' => !empty($company_setting['default_language']) ? $company_setting['default_language'] : 'en',
                            'currency' => !empty($company_setting['currency_symbol']) ? $company_setting['currency_symbol'] : '$',
                            'currency_code' => !empty($company_setting['currency']) ? $company_setting['currency'] : 'USD',
                        ]
                    );
                    $store->name                 = $WorkSpace->name;
                    $store->enable_storelink     = 'on';
                    $store->theme_dir            = 'theme1';
                    $store->store_theme          = 'yellow-style.css';
                    $store->header_name          = 'Course Certificate';
                    $store->certificate_template = 'template1';
                    $store->certificate_color    = 'b10d0d';
                    $store->certificate_gradiant = 'color-one';
                    $store->workspace_id         = $WorkSpace->id;
                    $store->save();
                }
            }
        }else{
            $company = User::where('type','company')->where('id',$company_id)->first();
            $WorkSpace = WorkSpace::where('created_by',$company->id)->where('id',$workspace_id)->first();
            $check = Store::where('workspace_id',$WorkSpace->id)->where('created_by',$company->id)->first();
            if(!$check)
            {
                $company_setting = getCompanyAllSetting($company->id,$WorkSpace->id);
                $store   = Store::create(
                    [
                        'created_by' => $company->id,
                        'name' => $WorkSpace->name,
                        'lang' => !empty($company_setting['default_language']) ? $company_setting['default_language'] : 'en',
                        'currency' => !empty($company_setting['currency_symbol']) ? $company_setting['currency_symbol'] : '$',
                        'currency_code' => !empty($company_setting['currency']) ? $company_setting['currency'] : 'USD',
                    ]
                );
                $store->name                 = $WorkSpace->name;
                $store->enable_storelink     = 'on';
                $store->theme_dir            = 'theme1';
                $store->store_theme          = 'yellow-style.css';
                $store->header_name          = 'Course Certificate';
                $store->certificate_template = 'template1';
                $store->certificate_color    = 'b10d0d';
                $store->certificate_gradiant = 'color-one';
                $store->workspace_id         = $WorkSpace->id;
                $store->save();
            }
        }
    }
}
