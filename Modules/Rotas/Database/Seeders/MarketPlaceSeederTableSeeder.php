<?php

namespace Modules\Rotas\Database\Seeders;

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
        $data['product_main_heading'] = 'Rotas';
        $data['product_main_description'] = '<p>Managing employees, their availability, shifts, leaves, and rates could become quite overwhelming, especially when you have multiple locations to cater to. Well no more.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'Easily Manage Work Shifts… With ASimple,Efficient, And AffordableTool';
        $data['dedicated_theme_description'] = '<p>Manage your employees and their work shifts, availability, leaves, rates, and more - from anywhere, with one simple tool.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Organize Employees And Maximize Productivity","dedicated_theme_section_description":"#","dedicated_theme_section_cards":{"1":{"title":"Mobile Scheduling.","description":"Schedule your employees’ shifts and assign tasks at any time. Easily manage availability preferences, view the details of all your employees, generate reports, and improve productivity - all inside one tool."},"2":{"title":"Flexible Management","description":"Easily edit every aspect of your business. Add or remove employees, days off, leaves, and more from the scheduled shifts. Set up face-to-face meetings at any time and easily reassign tasks to new employees."},"3":{"title":"Stay On Top Of Everything","description":"Let nothing escape you by receiving notifications of every important activity. Grant or reject employee leave requests, edit availability patterns, and manage every aspect of your business from a single tab."}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"A Smart And Reliable Way To Schedule Shifts","dedicated_theme_section_description":"#","dedicated_theme_section_cards":{"1":{"title":"Easy Payment Management","description":"Safeguard your clients’ payment by using Stripe, PayPal, Flutterwave, and many more. Get paid for work done with a stress-free and secure payment process. Integrate up to 10 unique payment gateways."},"2":{"title":"Manage Employee Details","description":"Manage every single information about your employees with ease. Get minute details like employment type, allowance, personal details and more in a single secure database. Also assign custom wages and salaries from a single tab."},"3":{"title":"Manage Multiple Locations","description":"Effectively manage several locations at a time. Assign employees to specific locations and manage their schedules. Easily switch locations and access a wide range of scheduling features - all under one roof."}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"EMPLOYEE DATABANK","dedicated_theme_section_description":"<p>Manage every minute detail of an employee through this tab. From personal details to employee details, manage each aspect of basic information here. Employee details include employment type, holiday allowance based on days and hours, weekly hours, and working duration. Additionally, you can assign the roles and locations of that particular employee from here. Further, assign the wages and salary of any particular employee by setting their default and role-wise custom rates. Lastly, determine their working and off days through this tab.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Comprehensive Dashboard","dedicated_theme_section_description":"<p>The dashboard would offer a monthly calendar view with general details about assigned users and costs incurred on any given day at a specific location. You can filter the details by selecting specific roles or all, based on your needs.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"Rotas"},{"screenshots":"","screenshots_heading":"Rotas"},{"screenshots":"","screenshots_heading":"Rotas"},{"screenshots":"","screenshots_heading":"Rotas"},{"screenshots":"","screenshots_heading":"Rotas"}]';
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
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'rotas')->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => 'rotas'

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
