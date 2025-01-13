<?php

namespace Modules\GoogleMeet\Database\Seeders;

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
        $data['product_main_heading'] = 'Google Meet';
        $data['product_main_description'] = '<p>Discover the seamless collaboration of Workdo-Dash, where productivity meets innovation. Integrated with Google Meet, our platform transforms teamwork, allowing real-time collaboration and efficient meetings. Elevate your productivity and streamline your work processes with Workdo-Dash – where work meets innovation effortlessly.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = '<h2>Google Meet  <b>Integration</b></h2>';
        $data['dedicated_theme_description'] = '<p>Elevating teamwork with Google Meet integration for seamless collaboration and productive meetings.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Effortless Google Meet Integration for Seamless Collaboration","dedicated_theme_section_description":"<p>Empower your team with the ability to create and schedule Google Meet sessions instantly from within Workdo-Dash. Simplify your virtual meeting experience by seamlessly generating Google Meet links, enhancing team collaboration and communication.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null},"2":{"title":"null","description":null},"3":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Streamlined Meeting Management for Enhanced Productivity","dedicated_theme_section_description":" <p>Gain a holistic view of all your meetings right within Workdo-Dash. Effortlessly access and manage your meeting schedule, ensuring optimal organization and enabling you to prioritize your time effectively. Stay on top of your commitments and boost productivity with our intuitive meeting listing feature.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null},"2":{"title":null,"description":null},"3":{"title":null,"description":null}}}]';
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
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'GoogleMeet')->exists()){
                MarketplacePageSetting::updateOrCreate([
                    'name' => $key,
                    'module' => 'GoogleMeet'
                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
