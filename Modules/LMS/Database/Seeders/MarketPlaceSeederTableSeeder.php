<?php

namespace Modules\LMS\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\LandingPage\Entities\MarketplacePageSetting;

class MarketPlaceSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $data['product_main_banner'] = '';
        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'Feedback';
        $data['product_main_description'] = '<p>LMS  is The finest course builder. Right from managing the courses of various categories to fulfilling chapters, each aspect of your courses could be settled through LMS under one tab. Enjoy manageable learning with an easy user interface.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = ' <h2>LEARNING <b>MANAGEMENT </b>SYSTEM </h2>';
        $data['dedicated_theme_description'] = '<p>LMS is a one-stop solution for effective learning management.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Teach your students better, and get quicker results.","dedicated_theme_section_description":"<p>LMS is the ideal learners’ paradise. Expose your students to an immersive learning experience. Group your learners into groups, and deliver tailored content to them without stress.<\/p>","dedicated_theme_section_cards":{"1":{"title":"Manage Your Courses With Ease","description":"Simplify your accounting and make it easy to keep an eye on your money. Set financial goals and let the system monitor them for you, automate taxes, and more! - without lifting a finger."},"2":{"title":"Get Valuable Insights In A Matter Of Seconds","description":"Monitor and analyze data from each course and easily improve them. Get information about students’ progress, time logs, assessment data, and more. Know what learners are most enthusiastic about and generate tailored initiatives from one single tab."},"3":{"title":"Monitor Learning Progress","description":"Access detailed reports about how your learners are faring. Determine what areas they are struggling with and make suggestions easily. Get regular, up-to-date reports and many more in one easily accessible tab!"}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Course Certificate Module to generate a certificate","dedicated_theme_section_description":"<p><\/p>","dedicated_theme_section_cards":{"1":{"title":"Course Certificate Management","description":"Easily generate certificates for completed courses in 4 easy steps. Just provide your store name, student name, course time, and course name. Choose from two templates and adjust the colors."},"2":{"title":"Handle Inventory Tasks Without Stress","description":"Easily manage inventory by creating categories and adding products to them. Modify product prices whenever you want, assign SKUs, create different tax rates, and do so much more!"},"3":{"title":"Management of Email subscribers list","description":"Get a list of interested students or customers and easily manage them. "}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Easy to manage learning by listing courses and courses’ categories","dedicated_theme_section_description":"<p>Easily organize all your courses by dividing them into distinct course categories. Easily manage all the courses in your store. Keep your inventories in a single tab and manage every aspect of your courses. View course details like names, prices, categories, qualities etc.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Create A Personalized Teaching And Learning Process","dedicated_theme_section_description":"<p>Update courses and add new study materials when needed. Adjust course difficulty and make the courses flow smoother. Easily edit lecture presentations and many more. Make the learning experience personal by exposing your clients to a process they won’t find anywhere else. Use LMSGo’s multilingual interface to create courses in your native tongue, and get them translated to each students’ favorite language.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"LMS"},{"screenshots":"","screenshots_heading":"LMS"},{"screenshots":"","screenshots_heading":"LMS"},{"screenshots":"","screenshots_heading":"LMS"}]';
        $data['addon_heading'] = '<h2>Why choose dedicated modules<b> for Your Business?</b></h2>';
        $data['addon_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['addon_section_status'] = 'on';
        $data['whychoose_heading'] = 'Why choose dedicated modulesfor Your Business?';
        $data['whychoose_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['pricing_plan_heading'] = 'Empower Your Workforce with DASH';
        $data['pricing_plan_description'] = '<p>Access over Premium Add-ons for Accounting, HR, Payments, Leads, Communication, Management, and more, all in one place!</p>';
        $data['pricing_plan_demo_link'] = '#';
        $data['pricing_plan_demo_button_text'] = 'View Live Demo';
        $data['pricing_plan_text'] = '{"1":{"title":"Pay-as-you-go"},"2":{"title":"Unlimited installation"},"3":{"title":"Secure cloud storage"}}';
        $data['whychoose_sections_status'] = 'on';
        $data['dedicated_theme_section_status'] = 'on';

        foreach($data as $key => $value){
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'LMS')->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => 'LMS'

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
