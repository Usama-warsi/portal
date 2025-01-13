<?php

namespace Modules\SalesAgent\Database\Seeders;

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
        $data['product_main_heading'] = 'Sales Agent';
        $data['product_main_description'] = '<p>Utilize a comprehensive Sales Agent Add-on to enhance your sales team\'s management of sales activities, customer relationships, and achieve performance goals</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = '<h2>Boost Sales Efficiency.</h2>';
        $data['dedicated_theme_description'] = '<p>Elevate your sales operations with the SalesAgent Module, a one-stop solution for efficient program management, sales agent performance tracking, and order-to-invoice processes.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Program Management","dedicated_theme_section_description":"<p>Take control of your sales programs effortlessly. Create, assign, and monitor programs to drive revenue and enhance collaboration.<\/p>","dedicated_theme_section_cards":{"1":{"title":"Create Custom Programs","description":"Tailor programs to your unique needs and objectives, ensuring that they align with your strategic vision."},"2":{"title":"Real-time Progress Tracking","description":"Stay in the loop with real-time tracking of program progress, empowering you to make timely adjustments."},"3":{"title":"Streamlined Assignment and Notifications","description":"Collaborate effectively with your sales team by easily assigning programs and sending notifications for updates and deadlines."}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Sales Agent Dashboard","dedicated_theme_section_description":"<p>Empower your sales agents with a dedicated, insightful dashboard. Provide them with the tools and insights they need to excel in their roles and contribute to your company\'s success.<\/p>","dedicated_theme_section_cards":{"1":{"title":"Track Sales Performance and KPIs","description":"Stay on top of key performance indicators and sales performance data, enabling data-driven decision-making."},"2":{"title":"View Program Details and Progress","description":"Keep tabs on program-related data, including details and progress, making it easier to manage initiatives effectively."},"3":{"title":"Manage Orders and Invoices Seamlessly","description":"Simplify the order-to-invoice processes, making it effortless to manage sales transactions, invoices, and payments."}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Purchase Orders and Invoices","dedicated_theme_section_description":"<p>Streamline order creation, generate invoices with ease, and maintain control over your financial transactions. Simplify the entire process from order initiation to invoicing.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"Sales Agent"},{"screenshots":"","screenshots_heading":"Sales Agent"},{"screenshots":"","screenshots_heading":"Sales Agent"},{"screenshots":"","screenshots_heading":"Sales Agent"},{"screenshots":"","screenshots_heading":"Sales Agent"}]';
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
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'SalesAgent')->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => 'SalesAgent'
                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
