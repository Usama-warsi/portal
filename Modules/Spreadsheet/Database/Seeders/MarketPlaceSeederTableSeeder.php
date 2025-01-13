<?php

namespace Modules\Spreadsheet\Database\Seeders;

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
        $data['product_main_heading'] = 'Spreadsheet';
        $data['product_main_description'] = '<p>A spreadsheet is a powerful tool that simplifies data management, enabling you to effortlessly create, organize, and analyze information. From basic calculations to automated data analysis, spreadsheets streamline a wide range of everyday tasks.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = '<h2>Simplify Data Management with Spreadsheets</h2>';
        $data['dedicated_theme_description'] = '<p>Spreadsheets are versatile digital tools for efficiently organizing, analyzing, and visualizing data in a grid format, ideal for calculations, data management, and chart creation.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"How is a spreadsheet useful","dedicated_theme_section_description":"<p>You can create subfolders within folders and share them with users. Also associate various modules like projects, leads, deals, contracts, etc., with these folders to organize related data. Additionally, you can share the folder directly within these modules for easy access, streamlining data management and collaboration.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Some Benefits Of Using Spreadsheet","dedicated_theme_section_description":"<p>To use spreadsheet content , you can manipulate, analyze, and visualize the data as needed. Spreadsheets are powerful tools for tasks such as financial analysis, data tracking, and reporting, providing a structured and organized way to work with data.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"Spreadsheet"},{"screenshots":"","screenshots_heading":"Spreadsheet"},{"screenshots":"","screenshots_heading":"Spreadsheet"}]';
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
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'Spreadsheet')->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => 'Spreadsheet'
                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
