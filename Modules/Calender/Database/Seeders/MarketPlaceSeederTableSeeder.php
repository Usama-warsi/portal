<?php

namespace Modules\Calender\Database\Seeders;

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
        $data['product_main_heading'] = 'Calendar';
        $data['product_main_description'] = '<p>Calendar is a unique approach to the classic event calendar concept. Fully responsive with modern design and  display your events in easy to read and navigate way. The plugin is packed with rich arsenal of features and options to help you create fully detailed and informative events quick and easy!</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'You can useCalendarShow all Events';
        $data['dedicated_theme_description'] = '<p>The calendar with an event view makes it easier to keep a tab on important meetings.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Calender Helps You Simplify Your Work","dedicated_theme_section_description":"","dedicated_theme_section_cards":{"1":{"title":"Calendar syncing feature helps to schedule meetings","description":"The zoom meeting created will be synced with the calendar which will show the meeting details, as well as which are people joining the meeting and at what time. This calendar syncing feature helps to schedule meetings accordingly."},"2":{"title":"Leads Management.","description":"Get a calendar view for every lead detail. In short, managing lead has never been easier with the calender of lead visibility in the system."}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Calender Helps You Simplify Your Work","dedicated_theme_section_description":"","dedicated_theme_section_cards":{"1":{"title":"What are Event Calendars?","description":"An event calendar is a design pattern that allows the user to select a date. The dates are not presented as a traditional list that a user scrolls through, or as input fields where the user enters numbers, but rather as a visual representation of a monthly calendar."},"2":{"title":"Speed up the User’s Process by Adding an Event Calendar","description":"Users are impatient creatures: anything that may seem like an inefficient course of action to achieve the goals they have they will regard as suboptimal and take a dim view of."},"3":{"title":"Why Choose an Event Calendar Design Pattern?","description":"Allowing users to select a date from a list—rather than input the information manually— saves them time and effort. A user might be trying to submit a date, track an order, arrange content according to a specific range of dates, or filter results."}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Event Calendar","dedicated_theme_section_description":"<p>Event calendars are at their best when the user is entering dates in the near future or past; when dealing with information far in the past or future, users have to flick through all the different pages in the event calendar before they reach the desired date or date range. For example, if the users are trying to enter birthdates, they would have to make many movements through the calendar and the process would take longer as the age increased, which is in no way ideal for older users (not to mention the added downer for many of them in getting a protracted visual showing just how old they are!). <\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"The Take Away","dedicated_theme_section_description":"<p>Event calendars are a great visual way of letting the user enter a date or date range. When implementing event calendars, you need to take into consideration that date formats differ across cultures. Also, you should help the user out by presenting dates that are closest to the present.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"Calender"},{"screenshots":"","screenshots_heading":"Calender"},{"screenshots":"","screenshots_heading":"Calender"},{"screenshots":"","screenshots_heading":"Calender"},{"screenshots":"","screenshots_heading":"Calender"}]';
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
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'Calender')->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => 'Calender'

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
