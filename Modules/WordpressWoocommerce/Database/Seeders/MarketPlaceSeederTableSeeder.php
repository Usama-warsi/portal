<?php

namespace Modules\WordpressWoocommerce\Database\Seeders;

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
        $data['product_main_heading'] = 'Wordpress (Woocommerce)';
        $data['product_main_description'] = '<p>Integrate and manage your store`s products and inventory with WooCommerce using WooCommerce. Simplify product and coupon management while enhancing customer interactions for an optimized WooCommerce experience.You can also add your woocommerce products to dash saas by clicking on the button given beside the product.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = '<h2>Simplify <b> WooCommerce</b> integration</h2>';
        $data['dedicated_theme_description'] = '<p>Seamlessly Integrate and manage your products, customer interactions and inventory with WooCommerce.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Effortless Product Management and Streamlined Coupon Control","dedicated_theme_section_description":"<p>Simplify your WooCommerce operations with easy-to-use tools that help you manage your products and inventory seamlessly. Spend less time on logistics and more time growing your business. Take charge of your discount strategy with our intuitive coupon management system. Create, track, and optimize promotions effortlessly to boost sales and customer loyalty.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Enhanced Customer Interaction","dedicated_theme_section_description":"<p>Elevate your customer service game by building stronger relationships through personalized interactions. Improve the overall shopping experience, driving repeat business and brand loyalty.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"Wordpress (Woocommerce)"},{"screenshots":"","screenshots_heading":"Wordpress (Woocommerce)"},{"screenshots":"","screenshots_heading":"Wordpress (Woocommerce)"},{"screenshots":"","screenshots_heading":"Wordpress (Woocommerce)"},{"screenshots":"","screenshots_heading":"Wordpress (Woocommerce)"}]';
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
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'WordpressWoocommerce')->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => 'WordpressWoocommerce'

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
