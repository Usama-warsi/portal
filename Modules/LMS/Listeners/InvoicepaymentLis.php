<?php

namespace Modules\LMS\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\LMS\Entities\Course;
use Modules\LMS\Entities\CourseOrder;
use Modules\LMS\Entities\CourseOrderSummary;
use Modules\LMS\Entities\PurchasedCourse;
use Modules\LMS\Entities\Store;
use Modules\LMS\Entities\Student;

class InvoicepaymentLis
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if (!empty($event->data)) {

            $invoice = $event->data;
        } else {
            $invoice = $event->invoice;
        }
        if($invoice->invoice_module == 'lms'){

            $items = $invoice->items;
            if (!empty($event->payment)) {

                $payment = $event->payment;;
            } else {
                $payment = $event->invoicePayment;
            }
            $store = Store::where('workspace_id', getActiveWorkSpace())->where('created_by', creatorId())->first();
            $company_settings = getCompanyAllSetting($store->created_by, $store->workspace_id);
            if (empty($event->type)) {
                $event->type = 'invoice';
            }
            if ($invoice->status == '4' && $event->type == 'invoice') {
                $cart['products'][time()] = [];
                foreach ($items as $item) {
                    $product = Course::find($item['product_id']);
                    $student = Student::find($invoice->user_id);

                    $totalprice = 0;
                    $product_name = [];
                    $product_id = [];
                    $product_name[] = $product['title'];
                    $product_id[] = $product['id'];
                    $totalprice += $item['price'];

                    if (!empty($product->thumbnail)) {
                        $pro_img = get_file($product->thumbnail);
                    } else {
                        $pro_img = '';
                    }

                    $cart['products'][] = [
                        "product_id" => $product->id,
                        "product_name" => $product->title,
                        "image" => $pro_img,
                        "price" => $product->price != 0 ? $product->price : 0,
                        "id" => $product->id,
                        'variant_id' => 0,
                    ];
                    $cart['products'] = array_filter($cart['products']);
                }
                $products = $cart['products'];

                $course_order = new CourseOrder();
                $course_order->order_id = '#' . time();
                $course_order->name = $student->name;
                $course_order->card_number = '';
                $course_order->card_exp_month = '';
                $course_order->card_exp_year = '';
                $course_order->student_id = $student->id;
                $course_order->course = json_encode($products);
                $course_order->price = $totalprice;
                $course_order->coupon = '';
                $course_order->coupon_json = json_encode(!empty($coupon) ? $coupon : '');
                $course_order->discount_price = '';
                $course_order->price_currency = !empty($company_settings['defult_currancy']) ? $company_settings['defult_currancy'] : 'USD';
                $course_order->txn_id = '';
                $course_order->payment_type = !empty($payment->payment_type) ? $payment->payment_type : 'Manually';
                $course_order->payment_status = 'success';
                $course_order->receipt = !empty($payment->receipt) ? $payment->receipt : '';
                $course_order->store_id = $store['id'];
                $course_order->save();

                foreach ($products as $course_id) {
                    $purchased_course = new PurchasedCourse();
                    $purchased_course->course_id = $course_id['product_id'];
                    $purchased_course->student_id = $student->id;
                    $purchased_course->order_id = $course_order->id;
                    $purchased_course->save();

                    $student = \Modules\LMS\Entities\Student::where('id', $purchased_course->student_id)->first();
                    $student->courses_id = $purchased_course->course_id;
                    $student->save();
                }

                $courseOrderSummary = CourseOrderSummary::find($invoice->category_id);
                $courseOrderSummary->status =  'success';
                $courseOrderSummary->save();
            }
        }
    }
}
