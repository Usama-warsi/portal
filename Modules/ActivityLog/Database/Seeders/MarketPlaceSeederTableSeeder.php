<?php

namespace Modules\ActivityLog\Database\Seeders;

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
        $module = 'ActivityLog';

        // $data['product_main_banner'] = '';
        // $data['product_main_status'] = 'on';
        // $data['product_main_heading'] = 'ActivityLog';
        // $data['product_main_description'] = '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>';
        // $data['product_main_demo_link'] = '#';
        // $data['product_main_demo_button_text'] = 'View Live Demo';
        // $data['dedicated_theme_heading'] = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.';
        // $data['dedicated_theme_description'] = '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>';
        // $data['dedicated_theme_sections'] = '[
        //                                         {
        //                                             "dedicated_theme_section_image": "",
        //                                             "dedicated_theme_section_heading": "Lorem Ipsum",
        //                                             "dedicated_theme_section_description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. ",
        //                                             "dedicated_theme_section_cards": {
        //                                             "1": {
        //                                                 "title": "Lorem Ipsum",
        //                                                 "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. ."
        //                                             },
        //                                             "2": {
        //                                             "title": "Lorem Ipsum",
        //                                                 "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. ."
        //                                             },
        //                                             "3": {
        //                                             "title": "Lorem Ipsum",
        //                                                 "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. ."
        //                                             }
        //                                             }
        //                                         }
        //                                     ]';
        // $data['dedicated_theme_sections_heading'] = '';
        // $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"ActivityLog"},{"screenshots":"","screenshots_heading":"ActivityLog"},{"screenshots":"","screenshots_heading":"ActivityLog"},{"screenshots":"","screenshots_heading":"ActivityLog"},{"screenshots":"","screenshots_heading":"ActivityLog"}]';
        // $data['addon_heading'] = 'What is Lorem Ipsum?';
        // $data['addon_description'] = '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>';
        // $data['addon_section_status'] = 'on';
        // $data['whychoose_heading'] = 'What is Lorem Ipsum?';
        // $data['whychoose_description'] = '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>';
        // $data['pricing_plan_heading'] = 'What is Lorem Ipsum?';
        // $data['pricing_plan_description'] = '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>';
        // $data['pricing_plan_demo_link'] = '#';
        // $data['pricing_plan_demo_button_text'] = 'View Live Demo';
        // $data['pricing_plan_text'] = '{"1":{"title":"Pay-as-you-go"},"2":{"title":"Unlimited installation"},"3":{"title":"Secure cloud storage"}}';
        // $data['whychoose_sections_status'] = 'on';
        // $data['dedicated_theme_section_status'] = 'on';
        $data['product_main_banner'] = '';
        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'Activity Log';
        $data['product_main_description'] = '<p>An Activity Log (also known as an Activity Diary or a Job Activity Log) is&nbsp;<strong>a written record of how you spend your time</strong>. By keeping an Activity Log for a few days, you can build up an accurate picture of what you do during the day, and how you invest your time.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'Activity Log Is Useful';
        $data['dedicated_theme_description'] = '<p>An activity log helps you see which part of the day is your most productive time.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"uploads\/marketplace_image\/ActivityLog\/1692847416-dedicated_theme_section_image.png","dedicated_theme_section_status":"on","dedicated_theme_section_heading":"Why Use Activity Log?","dedicated_theme_section_description":"<p>Activity Logs are&nbsp;<strong>useful tools for analyzing how you use your time<\/strong>. They help you track changes in your energy, alertness and effectiveness throughout the day, and they help you eliminate time wasting activities so that you can be more productive.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"uploads\/marketplace_image\/ActivityLog\/1692847446-dedicated_theme_section_image.png","dedicated_theme_section_status":"on","dedicated_theme_section_heading":"How Does The Activity Log Work In Dash?","dedicated_theme_section_description":"<p>The activity log shows you the activity happening within the modules along with time and according to the appropriate formate. For example, when you create a new object inside any module and edit it, the activity log will show you that activity.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"uploads\/marketplace_image\/ActivityLog\/1692847682-screenshots.png","screenshots_heading":"Activity Log"},{"screenshots":"uploads\/marketplace_image\/ActivityLog\/1692847702-screenshots.png","screenshots_heading":"Activity Log"},{"screenshots":"uploads\/marketplace_image\/ActivityLog\/1692847714-screenshots.png","screenshots_heading":"Activity Log"},{"screenshots":"uploads\/marketplace_image\/ActivityLog\/1692847728-screenshots.png","screenshots_heading":"Activity Log"},{"screenshots":"uploads\/marketplace_image\/ActivityLog\/1692847740-screenshots.png","screenshots_heading":"Activity Log"}]';
        $data['addon_heading'] = 'Why choose dedicated modules for Your Business?';
        $data['addon_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['addon_section_status'] = 'on';
        $data['whychoose_heading'] = 'Why choose dedicated modules for Your Business?';
        $data['whychoose_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['pricing_plan_heading'] = 'Empower Your Workforce with DASH';
        $data['pricing_plan_description'] = '<p>Access over Premium Add-ons for Accounting, HR, Payments, Leads, Communication, Management, and more, all in one place!</p>';
        $data['pricing_plan_demo_link'] = '#';
        $data['pricing_plan_demo_button_text'] = 'View Live Demo';
        $data['pricing_plan_text'] = '{"1":{"title":"Pay-as-you-go"},"2":{"title":"Unlimited installation"},"3":{"title":"Secure cloud storage"}}';
        $data['whychoose_sections_status'] = 'on';
        $data['dedicated_theme_section_status'] = 'on';
        
        foreach($data as $key => $value){
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', $module)->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => $module

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
