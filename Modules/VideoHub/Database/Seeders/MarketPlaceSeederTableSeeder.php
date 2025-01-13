<?php

namespace Modules\VideoHub\Database\Seeders;

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
        $module = 'VideoHub';

        $data['product_main_banner']                = '';
        $data['product_main_status']                = 'on';
        $data['product_main_heading']               = 'Video Hub';
        $data['product_main_description']           = '<p>Video Hub makes work quick and easy. If you use video hub you can complete your work on time and save your time.&nbsp;Video Hub saves your videos so you can watch them whenever you need.</p>';
        $data['product_main_demo_link']             = '#';
        $data['product_main_demo_button_text']      = 'View Live Demo';
        $data['dedicated_theme_heading']            = 'Video Hub Is Useful';
        $data['dedicated_theme_description']        = '<p>Video Hub helps you explain the workflow to anyone like clients, users, employees etc.</p>';
        $data['dedicated_theme_sections']           = '[{"dedicated_theme_section_image":"uploads\/marketplace_image\/VideoHub\/1697016389-dedicated_theme_section_image.png","dedicated_theme_section_status":"on","dedicated_theme_section_heading":"Why Use Video Hub?","dedicated_theme_section_description":"<p>You can easily explain to the client how your product works, how admin can work, how user can work, how employee can work. When the client is new and it is difficult to explain the complete product flow to him, you can easily and quickly explain the complete product flow to him through Video Hub.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"uploads\/marketplace_image\/VideoHub\/1697016468-dedicated_theme_section_image.png","dedicated_theme_section_status":"on","dedicated_theme_section_heading":"How To View Video Details And Add Comments?","dedicated_theme_section_description":"<p>Click on the thumbnail or video title. Then you can see a page similar to the given photo. And you can view video, video description, comment box and easily add comments and files in comment box.<\/p><p>You can watch all types of videos on this page.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading']   = '';
        $data['screenshots']                        = '[{"screenshots":"uploads\/marketplace_image\/VideoHub\/1697016545-screenshots.png","screenshots_heading":"Video Hub"},{"screenshots":"uploads\/marketplace_image\/VideoHub\/1697016558-screenshots.png","screenshots_heading":"Video Hub"},{"screenshots":"uploads\/marketplace_image\/VideoHub\/1697016566-screenshots.png","screenshots_heading":"Video Hub"},{"screenshots":"uploads\/marketplace_image\/VideoHub\/1697016581-screenshots.png","screenshots_heading":"Video Hub"},{"screenshots":"uploads\/marketplace_image\/VideoHub\/1697016574-screenshots.png","screenshots_heading":"Video Hub"}]';
        $data['addon_heading']                      = 'Why choose dedicated modules for Your Business?';
        $data['addon_description']                  = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['addon_section_status']               = 'on';
        $data['whychoose_heading']                  = 'Why choose dedicated modules for Your Business?';
        $data['whychoose_description']              = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['pricing_plan_heading']               = 'Empower Your Workforce with DASH';
        $data['pricing_plan_description']           = '<p>Access over Premium Add-ons for Accounting, HR, Payments, Leads, Communication, Management, and more, all in one place!</p>';
        $data['pricing_plan_demo_link']             = '#';
        $data['pricing_plan_demo_button_text']      = 'View Live Demo';
        $data['pricing_plan_text']                  = '{"1":{"title":"Pay-as-you-go"},"2":{"title":"Unlimited installation"},"3":{"title":"Secure cloud storage"}}';
        $data['whychoose_sections_status']          = 'on';
        $data['dedicated_theme_section_status']     = 'on';

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
