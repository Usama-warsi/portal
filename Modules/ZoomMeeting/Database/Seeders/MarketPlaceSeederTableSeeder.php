<?php

namespace Modules\ZoomMeeting\Database\Seeders;

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
        $data['product_main_heading'] = 'Zoom Meeting';
        $data['product_main_description'] = '<p>Eliminate the need to use multiple platforms for virtual meetings. Easily schedule Zoom meetings and sync meeting details and attendees with your calendar - For a smooth conferencing experience.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'Zoom Integration';
        $data['dedicated_theme_description'] = '<p>Virtual meetings are no new to us. The Zoom Integration provides a platform where you can create a zoom meeting after giving inputs of asked details like; Client, User(s), Meeting Time, and Duration, and it generates a link through which people can join the meeting. You can create, View, Start, and Delete meetings with ease.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"How to Use Zoom Management","dedicated_theme_section_description":"<p>Zoom is a communications platform that allows users to connect with video, audio, phone, and chat. Using Zoom requires an internet connection and a supported device. Most new users will want to start by creating an account and downloading the Zoom Client for Meetings.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null},"2":{"title":"null","description":null},"3":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"What is the use of zoom meeting?","dedicated_theme_section_description":" <p>Zoom is a communications platform that allows users to connect with video, audio, phone, and chat. Using Zoom requires an internet connection and a supported device. Most new users will want to start by creating an account and downloading the Zoom Client for Meetings.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null},"2":{"title":null,"description":null},"3":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"Project"},{"screenshots":"","screenshots_heading":"Project"},{"screenshots":"","screenshots_heading":"Project"},{"screenshots":"","screenshots_heading":"Project"},{"screenshots":"","screenshots_heading":"Project"}]';
        $data['addon_heading'] = 'Why choose dedicated modulesfor Your Business?';
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
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'ZoomMeeting')->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => 'ZoomMeeting'

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
