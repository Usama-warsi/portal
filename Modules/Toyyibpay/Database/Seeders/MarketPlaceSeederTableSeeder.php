<?php

namespace Modules\Toyyibpay\Database\Seeders;

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

        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'Toyyib Pay';
        $data['product_main_description'] = '<p>ToyyibPay is a payment gateway solution that allows businesses to accept online payment. With toyyibPay, customers can purchase your products and make payment via any local online banking through FPX. You don’t need to go through the hassle of checking your account to verify the transaction as toyyibPay will do that for you via special encryption and verification technology.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'ToyyibpayPaymentGateway';
        $data['dedicated_theme_description'] = '<p>ToyyibPay can be integrated with a variety of platforms, including websites, mobile apps, and point-of-sale systems. It supports a wide range of payment methods and FPX.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Why use Toyyibpay payment?","dedicated_theme_section_description":"<p>toyyibPay is better , Fast onboarding, low cost per transaction and very good customer service! Very close to perfection, looking forward to instant settlement!<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null},"2":{"title":"null","description":null},"3":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"ToyyibPay payment gateway","dedicated_theme_section_description":" <p>toyyibPay is a payment gateway solution that allows businesses to accept online payment. With toyyibPay, customers can purchase your products and make payment via any local online banking through FPX.You don’t need to go through the hassle of checking your account to verify the transaction as toyyibPay will do that for you via special encryption and verification technology.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null},"2":{"title":null,"description":null},"3":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"Toyyibpay"},{"screenshots":"","screenshots_heading":"Toyyibpay"},{"screenshots":"","screenshots_heading":"Toyyibpay"},{"screenshots":"","screenshots_heading":"Toyyibpay"},{"screenshots":"","screenshots_heading":"Toyyibpay"}]';
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

        foreach ($data as $key => $value) {
            if (!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'toyyibpay')->exists()) {
                MarketplacePageSetting::updateOrCreate(
                    [
                        'name' => $key,
                        'module' => 'toyyibpay'

                    ],
                    [
                        'value' => $value
                    ]
                );
            }
        }
    }
}
