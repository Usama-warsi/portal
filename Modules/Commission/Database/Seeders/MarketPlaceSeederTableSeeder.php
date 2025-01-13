<?php

namespace Modules\Commission\Database\Seeders;

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
        $module = 'Commission';

        $data['product_main_banner'] = '';
        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'Commission';
        $data['product_main_description'] = '<p>A Commission Module typically refers to a software or system component that helps organizations manage and calculate commissions or incentives for their salespeople, partners, or employees. The content of a commission module may vary depending on the specific needs of the organization and the complexity of its commission structure.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'Commission Plan';
        $data['dedicated_theme_description'] = '<p>Setting up various Commission Plans is a critical aspect of a Commission Module within a Sales or Compensation management system. These plans determine how individuals or teams are rewarded for their efforts, motivating and aligning them with organizational goals. Three common types of commission plans are percentage-based and flat-rate commissions, each with its own merits and use cases.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Why use Commission Plan?","dedicated_theme_section_description":"<p>Commission plans play a pivotal role in the operations of organizations, particularly in the realms of sales and compensation management. These plans provide a structured framework through which employees, partners, or sales teams are compensated based on their performance and contributions to the organization. In this comprehensive exploration, we delve into the multifaceted reasons why commission plans are a cornerstone of modern businesses, how they drive success, and their profound impact on motivation, alignment, and revenue growth.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"How the Commission Module work?","dedicated_theme_section_description":"<p>In Commission Module, Commission calculation is about Invoice, Sales Invoice, Project , project and task, agent will get his Commission when payment is made, bank transfer payment in Commission module. When the payment is made, the bank shows its entry in the transfer request so that it knows how much commission is paid.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"Commission"},{"screenshots":"","screenshots_heading":"Commission"},{"screenshots":"","screenshots_heading":"Commission"},{"screenshots":"","screenshots_heading":"Commission"}]';
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
