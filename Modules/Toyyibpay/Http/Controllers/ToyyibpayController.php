<?php

namespace Modules\Toyyibpay\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rawilk\Settings\Support\Context;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Modules\Toyyibpay\Events\ToyyibpayPaymentStatus;
use Illuminate\Support\Facades\Cookie;
use Modules\Holidayz\Entities\Hotels;
use Modules\Holidayz\Entities\RoomBookingCart;
use Modules\Holidayz\Entities\BookingCoupons;
use Modules\Holidayz\Entities\HotelCustomer;
use Modules\Holidayz\Entities\RoomBooking;
use Modules\Holidayz\Entities\RoomBookingOrder;
use Modules\Holidayz\Entities\UsedBookingCoupons;
use Modules\Holidayz\Events\CreateRoomBooking;

class ToyyibpayController extends Controller
{
    public $currancy, $secrect_key, $category_code, $is_enabled, $callBackUrl, $returnUrl;

    public function setting(Request $request)
    {
        if (Auth::user()->isAbleTo('toyyibpay payment manage')) {
            if ($request->has('toyyibpay_payment_is_on')) {
                $validator = Validator::make($request->all(), [
                    'company_toyyibpay_secrect_key' => 'required|string',
                    'company_toyyibpay_category_code' => 'required|string'
                ]);
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
            }
            $getActiveWorkSpace = getActiveWorkSpace();
            $creatorId = creatorId();
            if ($request->has('toyyibpay_payment_is_on')) {
                $post = $request->all();
                unset($post['_token']);
                foreach ($post as $key => $value) {
                    // Define the data to be updated or inserted
                    $data = [
                        'key' => $key,
                        'workspace' => $getActiveWorkSpace,
                        'created_by' => $creatorId,
                    ];

                    // Check if the record exists, and update or insert accordingly
                    Setting::updateOrInsert($data, ['value' => $value]);
                }
            } else {
                $data = [
                    'key' => 'toyyibpay_payment_is_on',
                    'workspace' => $getActiveWorkSpace,
                    'created_by' => $creatorId,
                ];
                // Check if the record exists, and update or insert accordingly
                Setting::updateOrInsert($data, ['value' => 'off']);
            }
            // Settings Cache forget
            AdminSettingCacheForget();
            comapnySettingCacheForget();
            return redirect()->back()->with('success', 'Toyyibpay setting save sucessfully.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function payment_setting($id = Null, $wokspace = Null)
    {

        if (!empty($id) && empty($wokspace)) {
            $company_settings = getCompanyAllSetting($id);
        } elseif (!empty($id) && !empty($wokspace)) {
            $company_settings = getCompanyAllSetting($id, $wokspace);
        } else {
            $company_settings = getCompanyAllSetting();
        }

        $this->currancy      = !empty($company_settings['defult_currancy']) ? $company_settings['defult_currancy'] : '$';
        $this->secrect_key    = !empty($company_settings['company_toyyibpay_secrect_key']) ? $company_settings['company_toyyibpay_secrect_key'] : '';
        $this->category_code    = !empty($company_settings['company_toyyibpay_category_code']) ? $company_settings['company_toyyibpay_category_code'] : '';
        $this->is_enabled    = !empty($company_settings['toyyibpay_payment_is_on']) ? $company_settings['toyyibpay_payment_is_on'] : 'off';
    }

    public function planPayWithToyyibpay(Request $request)
    {
        $this->payment_setting();

        try {
            $plan = Plan::find($request->plan_id);
            $user_counter = !empty($request->user_counter_input) ? $request->user_counter_input : 0;
            $workspace_counter = !empty($request->workspace_counter_input) ? $request->workspace_counter_input : 0;
            $user_module = !empty($request->user_module_input) ? $request->user_module_input : '';
            $duration = !empty($request->time_period) ? $request->time_period : 'Month';
            $user_module_price = 0;
            if (!empty($user_module) && $plan->custom_plan == 1) {
                $user_module_array =    explode(',', $user_module);
                foreach ($user_module_array as $key => $value) {
                    $temp = ($duration == 'Year') ? ModulePriceByName($value)['yearly_price'] : ModulePriceByName($value)['monthly_price'];
                    $user_module_price = $user_module_price + $temp;
                }
            }
            $user_price = 0;
            if ($user_counter > 0) {
                $temp = ($duration == 'Year') ? $plan->price_per_user_yearly : $plan->price_per_user_monthly;
                $user_price = $user_counter * $temp;
            }
            $workspace_price = 0;
            if ($workspace_counter > 0) {
                $temp = ($duration == 'Year') ? $plan->price_per_workspace_yearly : $plan->price_per_workspace_monthly;
                $workspace_price = $workspace_counter * $temp;
            }
            $plan_price = ($duration == 'Year') ? $plan->package_price_yearly : $plan->package_price_monthly;
            $counter = [
                'user_counter' => $user_counter,
                'workspace_counter' => $workspace_counter,
            ];
            if ($plan) {
                $price                  = $plan_price + $user_module_price + $user_price + $workspace_price;
                if ($price <= 0) {
                    $assignPlan = DirectAssignPlan($plan->id, $duration, $user_module, $counter, 'MOLLIE');
                    if ($assignPlan['is_success']) {
                        return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
                    } else {
                        return redirect()->route('plans.index')->with('error', __('Something went wrong, Please try again,'));
                    }
                }
                if ($plan) {
                    $admin_settings = getAdminAllSetting();
                    $this->callBackUrl = route('plan.get.toyyibpay.status', [$plan->id, 'user_module' => $user_module, 'duration' => $duration, 'counter' => $counter, 'price' => $price]);
                    $this->returnUrl = route('plan.get.toyyibpay.status', [$plan->id, 'user_module' => $user_module, 'duration' => $duration, 'counter' => $counter, 'price' => $price]);

                    $Date = date('d-m-Y');
                    $product = !empty($plan->name) ? $plan->name : 'Basic Package';
                    $billExpiryDays = 3;
                    $billExpiryDate = date('d-m-Y', strtotime($Date . ' + 3 days'));
                    $billContentEmail = "Thank you for purchasing our product!";

                    $some_data = array(
                        'userSecretKey' => !empty($admin_settings['company_toyyibpay_secrect_key']) ? $admin_settings['company_toyyibpay_secrect_key'] : '',
                        'categoryCode' => !empty($admin_settings['company_toyyibpay_category_code']) ? $admin_settings['company_toyyibpay_category_code'] : '',
                        'billName' => $product,
                        'billDescription' => $product,
                        'billPriceSetting' => 1,
                        'billPayorInfo' => 1,
                        'billAmount' => 100 * $price,
                        'billReturnUrl' => $this->returnUrl,
                        'billCallbackUrl' => $this->callBackUrl,
                        'billExternalReferenceNo' => 'AFR341DFI',
                        'billTo' => Auth::user()->name,
                        'billEmail' => Auth::user()->email,
                        'billPhone' => '0000000000',
                        'billSplitPayment' => 0,
                        'billSplitPaymentArgs' => '',
                        'billPaymentChannel' => '0',
                        'billContentEmail' => $billContentEmail,
                        'billChargeToCustomer' => 1,
                        'billExpiryDate' => $billExpiryDate,
                        'billExpiryDays' => $billExpiryDays
                    );
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_POST, 1);
                    curl_setopt($curl, CURLOPT_URL, 'https://toyyibpay.com/index.php/api/createBill');
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);
                    $result = curl_exec($curl);
                    $info = curl_getinfo($curl);
                    curl_close($curl);
                    $obj = json_decode($result);
                    return redirect('https://toyyibpay.com/' . $obj[0]->BillCode);
                } else {
                    return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
                }
            }
        } catch (\Exception $e) {
            return redirect()->route('plans.index')->with('error', __($e->getMessage()));
        }
    }

    public function planGetToyyibpayStatus(Request $request, $planId)
    {
        $plan = Plan::find($planId);
        if ($plan) {
            try {
                $user = User::find(Auth::user()->id);

                if ($request->status_id == 3) {
                    $statuses = 'Fail';
                    $oder = $this->oder($plan->id, $request->all(), $statuses, $user->id);
                    if ($oder['is_success']) {
                        return redirect()->route('plans.index')->with('error', __('Your Transaction is failed, please try again'));
                    }
                } else if ($request->status_id == 2) {
                    $statuses = 'pending';
                    $oder = $this->oder($plan->id, $request->all(), $statuses, $user->id);
                    if ($oder['is_success']) {
                        return redirect()->route('plans.index')->with('error', __('Your transaction is pending'));
                    }
                } else if ($request->status_id == 1) {
                    $statuses = 'succeeded';
                    $assignPlan = $user->assignPlan($plan->id, $request->duration, $request->user_module, $request->counter);
                    if ($assignPlan['is_success']) {
                        $oder = $this->oder($plan->id, $request->all(), $statuses, $user->id);
                        $value = Session::get('user-module-selection');
                        if (!empty($value)) {
                            Session::forget('user-module-selection');
                        }
                        if ($oder['is_success']) {
                            return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
                        }
                    } else {
                        return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                    }
                } else {
                    return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
                }
            } catch (Exception $e) {
                return redirect()->route('plans.index')->with('error', __($e->getMessage()));
            }
        }
    }

    public function oder($plan, $post, $statuses, $user)
    {
        $admin_settings = getAdminAllSetting();
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        try {
            $plan = Plan::find($plan);
            $product = !empty($plan->name) ? $plan->name : 'Basic Package';
            $order = Order::create(
                [
                    'order_id' => $orderID,
                    'name' => null,
                    'email' => null,
                    'card_number' => null,
                    'card_exp_month' => null,
                    'card_exp_year' => null,
                    'plan_name' => $product,
                    'plan_id' => $plan,
                    'price' => !empty($post['price']) ? $post['price'] : 0,
                    'price_currency' => $admin_settings['defult_currancy'],
                    'txn_id' => '',
                    'payment_type' => __('Toyyibpay'),
                    'payment_status' => $statuses,
                    'receipt' => null,
                    'user_id' => $user,
                ]
            );

            $type = 'Subscription';
            event(new ToyyibpayPaymentStatus($plan, $type, $order));

            return ['is_success' => true];
        } catch (Exception $e) {
            return ['is_success' => false];
        }
    }

    public function invoicePayWithtoyyibpay(Request $request)
    {
        try {
            if ($request->type == "invoice") {
                $invoice      = \App\Models\Invoice::find($request->invoice_id);
                $user_id      = $invoice->created_by;
                $wokspace     = $invoice->workspace;
                $invoice_no   = \App\Models\Invoice::invoiceNumberFormat($invoice->invoice_id, $user_id, $wokspace);
                $customer     = User::where('id', $invoice->user_id)->first();
            } elseif ($request->type == "retainer") {
                $invoice      = \Modules\Retainer\Entities\Retainer::find($request->invoice_id);
                $user_id      = $invoice->created_by;
                $wokspace     = $invoice->workspace;
                $invoice_no   = \Modules\Retainer\Entities\Retainer::retainerNumberFormat($invoice->retainer_id, $user_id, $wokspace);
                $customer     = User::where('id', $invoice->user_id)->first();
            }
            $user = User::where('id', $user_id)->first();
            self::payment_setting($user_id, $wokspace);
            $get_amount = $request->amount;
            if ($invoice && $get_amount != 0) {

                $this->callBackUrl = route('invoice.toyyibpay', [
                    $invoice->id,
                    $get_amount,
                    $request->type
                ]);

                $this->returnUrl = route('invoice.toyyibpay', [
                    $invoice->id,
                    $get_amount,
                    $request->type
                ]);

                $Date = date('d-m-Y');
                $ammount = $get_amount;
                $billExpiryDays = 3;
                $billExpiryDate = date('d-m-Y', strtotime($Date . ' + 3 days'));
                $billContentEmail = "Thank you for purchasing our product!";

                $some_data = array(
                    'userSecretKey' => $this->secrect_key,
                    'categoryCode' => $this->category_code,
                    'billPriceSetting' => 1,
                    'billPayorInfo' => 1,
                    'billName' => $invoice_no,
                    'billDescription' => ucfirst($request->type) . ' Payment',
                    'billAmount' => 100 * $ammount,
                    'billReturnUrl' => $this->returnUrl,
                    'billCallbackUrl' => $this->callBackUrl,
                    'billExternalReferenceNo' => 'AFR341DFI',
                    'billTo' => $customer->name,
                    'billEmail' => $customer->email,
                    'billPhone' => isset($customer->mobile_no) ? $customer->mobile_no : '0000000000',
                    'billSplitPayment' => 0,
                    'billSplitPaymentArgs' => '',
                    'billPaymentChannel' => '0',
                    'billContentEmail' => $billContentEmail,
                    'billChargeToCustomer' => 1,
                    'billExpiryDate' => $billExpiryDate,
                    'billExpiryDays' => $billExpiryDays
                );
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_URL, 'https://toyyibpay.com/index.php/api/createBill');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);
                $result = curl_exec($curl);
                $info = curl_getinfo($curl);
                curl_close($curl);
                $obj = json_decode($result);

                if ($obj != null) {
                    try {
                        return redirect('https://toyyibpay.com/' . $obj[0]->BillCode);
                    } catch (Exception $e) {
                        return redirect()->back()->with('error', __($e->getMessage()));
                    }
                }

                return redirect()->back()->with('error', __('Unknown error occurred'));
            } else {
                return redirect()->back()->with('error', __('Please enter valid amount.'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', __($e->getMessage()));
        }
    }

    public function getInvoicePaymentStatus(Request $request, $invoice_id, $amount, $type)
    {
        if (!empty($invoice_id) && !empty($amount) && !empty($type)) {
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

            if ($type == "invoice") {
                $invoice    =  \App\Models\Invoice::find($invoice_id);
                $user_id = $invoice->created_by;
                $wokspace        = $invoice->workspace;
                self::payment_setting($user_id, $wokspace);
                if ($request->status_id == 3) {

                    return redirect()->route('pay.invoice', encrypt($invoice_id))->with('error', __('Your Transaction is failed, please try again.'));
                } else if ($request->status_id == 2) {
                    return redirect()->route('pay.invoice', encrypt($invoice_id))->with('error', __('Your transaction is pending.'));
                } else if ($request->status_id == 1) {

                    if ($invoice) {
                        $invoice_payment                       = new \App\Models\InvoicePayment();
                        $invoice_payment->invoice_id           = $invoice_id;
                        $invoice_payment->date                 = date('Y-m-d');
                        $invoice_payment->amount               = isset($amount) ? $amount : 0;
                        $invoice_payment->account_id           = 0;
                        $invoice_payment->payment_method       = 0;
                        $invoice_payment->order_id             = $orderID;
                        $invoice_payment->currency             = isset($this->currancy) ? $this->currancy : 'USD';
                        $invoice_payment->payment_type         = __('Toyyibpay');
                        $invoice_payment->save();
                        $due     = $invoice->getDue();
                        if ($due <= 0) {
                            $invoice->status = 4;
                            $invoice->save();
                        } else {
                            $invoice->status = 3;
                            $invoice->save();
                        }
                        if (($invoice->getDue() - $invoice_payment->amount) == 0) {
                            $invoice->status = 3;
                            $invoice->save();
                        }
                        if (module_is_active('Account')) {
                            //for customer balance update
                            \Modules\Account\Entities\AccountUtility::updateUserBalance('customer', $invoice->customer_id, $invoice_payment->amount, 'debit');
                        }
                        event(new ToyyibpayPaymentStatus($invoice, $type, $invoice_payment));

                        return redirect()->route('pay.invoice', encrypt($invoice_id))->with('success', __('Payment added Successfully'));
                    } else {
                        return redirect()->route('pay.invoice', encrypt($invoice_id))->with('error', __('Invoice not found.'));
                    }
                }
            } elseif ($type == "retainer") {
                $invoice    =  \Modules\Retainer\Entities\Retainer::find($invoice_id);
                $user_id = $invoice->created_by;
                $wokspace        = $invoice->workspace;
                self::payment_setting($user_id, $wokspace);
                if ($request->status_id == 3) {
                    return redirect()->route('pay.retainer', encrypt($invoice_id))->with('error', __('Your Transaction is failed, please try again.'));
                } else if ($request->status_id == 2) {
                    return redirect()->route('pay.retainer', encrypt($invoice_id))->with('error', __('Your transaction is pending.'));
                } else if ($request->status_id == 1) {

                    if ($invoice) {
                        $retainer_payment                       = new \Modules\Retainer\Entities\RetainerPayment();
                        $retainer_payment->retainer_id           = $invoice_id;
                        $retainer_payment->date                 = date('Y-m-d');
                        $retainer_payment->amount               = isset($amount) ? $amount : 0;
                        $retainer_payment->account_id           = 0;
                        $retainer_payment->payment_method       = 0;
                        $retainer_payment->order_id             = $orderID;
                        $retainer_payment->currency             = isset($this->currancy) ? $this->currancy : 'USD';
                        $retainer_payment->payment_type         = __('Toyyibpay');
                        $retainer_payment->save();

                        $due     = $invoice->getDue();
                        if ($due <= 0) {
                            $invoice->status = 4;
                            $invoice->save();
                        } else {
                            $invoice->status = 3;
                            $invoice->save();
                        }
                        if (($invoice->getDue() - $retainer_payment->amount) == 0) {
                            $invoice->status = 3;
                            $invoice->save();
                        }
                        //for customer balance update
                        \Modules\Retainer\Entities\RetainerUtility::updateUserBalance('customer', $invoice->customer_id, $retainer_payment->amount, 'debit');

                        event(new ToyyibpayPaymentStatus($invoice, $type, $retainer_payment));


                        return redirect()->route('pay.retainer', encrypt($invoice_id))->with('success', __('Payment added Successfully'));
                    } else {
                        return redirect()->route('pay.retainer', encrypt($invoice_id))->with('error', __('Retainer not found.'));
                    }
                }
            }
        } else {

            return redirect()->back()->with('error', __('Oops something went wrong.'));
        }
    }

    public function coursePayWithtoyyibpay(Request $request, $slug)
    {
        try {
            $cart     = session()->get($slug);
            $products = $cart['products'];

            $store = \Modules\LMS\Entities\Store::where('slug', $slug)->first();
            self::payment_setting($store->created_by, $store->workspace_id);

            $get_amount    = 0;
            $sub_totalprice = 0;
            $product_name   = [];
            $product_id     = [];

            foreach ($products as $key => $product) {
                $product_name[] = $product['product_name'];
                $product_id[]   = $product['id'];
                $sub_totalprice += $product['price'];
                $get_amount    += $product['price'];
            }

            if ($products) {
                $coupon_id = 0;
                if (isset($cart['coupon']) && isset($cart['coupon'])) {
                    if ($cart['coupon']['coupon']['enable_flat'] == 'off') {
                        $discount_value = ($sub_totalprice / 100) * $cart['coupon']['coupon']['discount'];
                        $get_amount    = $sub_totalprice - $discount_value;
                    } else {
                        $discount_value = $cart['coupon']['coupon']['flat_discount'];
                        $get_amount    = $sub_totalprice - $discount_value;
                    }
                }
                if ($get_amount <= 0) {
                    $assignCourse = \Modules\LMS\Entities\LmsUtility::DirectAssignCourse($store, 'Coingate');
                    if ($assignCourse['is_success']) {
                        return redirect()->route(
                            'store-complete.complete',
                            [
                                $store->slug,
                                \Illuminate\Support\Facades\Crypt::encrypt($assignCourse['courseorder_id']),
                            ]
                        )->with('success', __('Transaction has been success'));
                    } else {
                        return redirect()->route('store.cart', $store->slug)->with('error', __('Something went wrong, Please try again,'));
                    }
                }
                $this->callBackUrl = route('course.toyyibpay', [$store->slug, $get_amount, $coupon_id]);
                $this->returnUrl = route('course.toyyibpay', [$store->slug, $get_amount, $coupon_id]);

                $product_name = implode(",", $product_name);
                $student            = Auth::guard('students')->user();
                $Date = date('d-m-Y');
                $ammount = $get_amount;
                $billName = $student->name;
                $description = $product_name;
                $billExpiryDays = 3;
                $billExpiryDate = date('d-m-Y', strtotime($Date . ' + 3 days'));
                $billContentEmail = "Thank you for purchasing our product!";

                $some_data = array(
                    'userSecretKey' => $this->secrect_key,
                    'categoryCode' => $this->category_code,
                    'billName' => $billName,
                    'billDescription' => $description,
                    'billPriceSetting' => 1,
                    'billPayorInfo' => 1,
                    'billAmount' => 100 * $ammount,
                    'billReturnUrl' => $this->returnUrl,
                    'billCallbackUrl' => $this->callBackUrl,
                    'billExternalReferenceNo' => 'AFR341DFI',
                    'billTo' => $student->name,
                    'billEmail' => $student->email,
                    'billPhone' => '0000000000',
                    'billSplitPayment' => 0,
                    'billSplitPaymentArgs' => '',
                    'billPaymentChannel' => '0',
                    'billContentEmail' => $billContentEmail,
                    'billChargeToCustomer' => 1,
                    'billExpiryDate' => $billExpiryDate,
                    'billExpiryDays' => $billExpiryDays
                );
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_URL, 'https://toyyibpay.com/index.php/api/createBill');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);
                $result = curl_exec($curl);
                $info = curl_getinfo($curl);
                curl_close($curl);
                $obj = json_decode($result);
                return redirect('https://toyyibpay.com/' . $obj[0]->BillCode);
            } else {
                return redirect()->back()->with('error', __('is deleted.'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', __($e->getMessage()));
        }
    }

    public function getCoursePaymentStatus(Request $request, $slug, $getAmount, $couponCode)
    {
        $store = \Modules\LMS\Entities\Store::where('slug', $slug)->first();
        $cart = session()->get($slug);
        $products       = $cart['products'];
        $sub_totalprice = 0;
        $product_name   = [];
        $product_id     = [];
        foreach ($products as $key => $product) {
            $product_name[] = $product['product_name'];
            $product_id[]   = $product['id'];
            $sub_totalprice += $product['price'];
        }
        $company_settings = getCompanyAllSetting($store->created_by, $store->workspace_id);
        self::payment_setting($store->created_by, $store->workspace_id);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        // $request['status_id'] = 1;
        // 1=success, 2=pending, 3=fail
        try {
            $student            = Auth::guard('students')->user();
            if ($request->status_id == 3) {
                $statuses = 'Fail';
                $course_order                 = new \Modules\LMS\Entities\CourseOrder();
                $course_order->order_id       = $orderID;
                $course_order->name           = $student->name;
                $course_order->card_number    = '';
                $course_order->card_exp_month = '';
                $course_order->card_exp_year  = '';
                $course_order->student_id     = $student->id;
                $course_order->course         = json_encode($products);
                $course_order->price          = $getAmount;
                $course_order->coupon         = isset($cart['coupon']['data_id']) ? $cart['coupon']['data_id'] : '';
                $course_order->coupon_json    = json_encode($couponCode);
                $course_order->discount_price = isset($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
                $course_order->price_currency = $this->currancy;
                $course_order->txn_id         = '-';
                $course_order->payment_type   = 'ToyyibPay';
                $course_order->payment_status = $statuses;
                $course_order->receipt        = '';
                $course_order->store_id       = $store['id'];
                $course_order->save();
                return redirect()->route(
                    'store-complete.complete',
                    [
                        $store->slug,
                        \Illuminate\Support\Facades\Crypt::encrypt($course_order->id),
                    ]
                )->with('success', __('Transaction has been') . ' ' . $statuses);
            } else if ($request->status_id == 2) {
                $statuses = 'pandding';
                $course_order                 = new \Modules\LMS\Entities\CourseOrder();
                $course_order->order_id       = $orderID;
                $course_order->name           = $student->name;
                $course_order->card_number    = '';
                $course_order->card_exp_month = '';
                $course_order->card_exp_year  = '';
                $course_order->student_id     = $student->id;
                $course_order->course         = json_encode($products);
                $course_order->price          = $getAmount;
                $course_order->coupon         = isset($cart['coupon']['data_id']) ? $cart['coupon']['data_id'] : '';
                $course_order->coupon_json    = json_encode($couponCode);
                $course_order->discount_price = isset($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
                $course_order->price_currency = $this->currancy;
                $course_order->txn_id         = '-';
                $course_order->payment_type   = 'ToyyibPay';
                $course_order->payment_status = $statuses;
                $course_order->receipt        = '';
                $course_order->store_id       = $store['id'];
                $course_order->save();

                return redirect()->route(
                    'store-complete.complete',
                    [
                        $store->slug,
                        \Illuminate\Support\Facades\Crypt::encrypt($course_order->id),
                    ]
                )->with('success', __('Transaction has been') . ' ' . $statuses);
            } else if ($request->status_id == 1) {
                $statuses = 'success';
                $course_order                 = new \Modules\LMS\Entities\CourseOrder();
                $course_order->order_id       = $orderID;
                $course_order->name           = $student->name;
                $course_order->card_number    = '';
                $course_order->card_exp_month = '';
                $course_order->card_exp_year  = '';
                $course_order->student_id     = $student->id;
                $course_order->course         = json_encode($products);
                $course_order->price          = $getAmount;
                $course_order->coupon         = isset($cart['coupon']['data_id']) ? $cart['coupon']['data_id'] : '';
                $course_order->coupon_json    = json_encode($couponCode);
                $course_order->discount_price = isset($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
                $course_order->price_currency = $this->currancy;
                $course_order->txn_id         = '-';
                $course_order->payment_type   = 'ToyyibPay';
                $course_order->payment_status = $statuses;
                $course_order->receipt        = '';
                $course_order->store_id       = $store['id'];
                $course_order->save();

                foreach ($products as $course_id) {
                    $purchased_course = new \Modules\LMS\Entities\PurchasedCourse();
                    $purchased_course->course_id  = $course_id['product_id'];
                    $purchased_course->student_id = $student->id;
                    $purchased_course->order_id   = $course_order->id;
                    $purchased_course->save();

                    $student = \Modules\LMS\Entities\Student::where('id', $purchased_course->student_id)->first();
                    $student->courses_id = $purchased_course->course_id;
                    $student->save();
                }
                if (!empty($company_settings['New Course Order']) && $company_settings['New Course Order']  == true) {
                    $user = User::where('id', $store->created_by)->where('workspace_id', $store->workspace_id)->first();
                    $course = \Modules\LMS\Entities\Course::whereIn('id', $product_id)->get()->pluck('title');
                    $course_name = implode(', ', $course->toArray());
                    $uArr    = [
                        'student_name' => $student->name,
                        'course_name' => $course_name,
                        'store_name' => $store->name,
                        'order_url' => route('user.order', [$store->slug, \Illuminate\Support\Facades\Crypt::encrypt($course_order->id),]),
                    ];
                    try {
                        // Send Email
                        $resp = EmailTemplate::sendEmailTemplate('New Course Order', [$user->id => $user->email], $uArr, $store->created_by);
                    } catch (\Exception $e) {
                        $resp['error'] = $e->getMessage();
                    }
                }
                $type = 'coursepayment';
                event(new ToyyibpayPaymentStatus($store, $type, $course_order));

                session()->forget($slug);

                return redirect()->route(
                    'store-complete.complete',
                    [
                        $store->slug,
                        \Illuminate\Support\Facades\Crypt::encrypt($course_order->id),
                    ]
                )->with('success', __('Transaction has been') . ' ' . $statuses);
            } else {
                return redirect()->back()->with('error', __('Transaction has been'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('Transaction has been failed.'));
        }
    }


    public function bookingPayWithToyyibpay(Request $request, $slug)
    {
        $hotel = Hotels::where('slug', $slug)->first();
        if ($hotel) {
            $company_settings = getCompanyAllSetting($hotel->created_by, $hotel->workspace);
            $payment    = self::payment_setting($hotel->created_by, $hotel->workspace);
            $grandTotal = $couponsId = 0;
            if (!auth()->guard('holiday')->user()) {
                $Carts = Cookie::get('cart');
                $Carts = json_decode($Carts, true);
                foreach ($Carts as $key => $value) {
                    //
                    $toDate = \Carbon\Carbon::parse($value['check_in']);
                    $fromDate = \Carbon\Carbon::parse($value['check_out']);

                    $days = $toDate->diffInDays($fromDate);
                    //
                    $grandTotal += $value['price'] * $value['room'] * $days;
                    $grandTotal += ($value['serviceCharge']) ? $value['serviceCharge'] : 0;
                }
            } else {
                $Carts = RoomBookingCart::where(['customer_id' => auth()->guard('holiday')->user()->id])->get();
                foreach ($Carts as $key => $value) {
                    $grandTotal += $value->price;   // * $value->room
                    $grandTotal += ($value->service_charge) ? $value->service_charge : 0;
                }
            }

            $price = $grandTotal;
            $coupons_id = 0;
            if (!empty($request->coupon)) {
                $coupons = BookingCoupons::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun     = $coupons->used_coupon();
                    $discount_value = ($price / 100) * $coupons->discount;
                    $price          = $price - $discount_value;
                    $coupons_id = $coupons->id;
                    if ($coupons->limit == $usedCoupun) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }

            if ($price <= 0) {

                if ($coupons_id != 0) {
                    $coupons = BookingCoupons::find($coupons_id);
                    if (!empty($coupons)) {
                        $userCoupon         = new UsedBookingCoupons();
                        $userCoupon->customer_id   = isset(auth()->guard('holiday')->user()->id) ? auth()->guard('holiday')->user()->id : 0;
                        $userCoupon->coupon_id = $coupons->id;
                        $userCoupon->save();
                        $usedCoupun = $coupons->used_coupon();
                        if ($coupons->limit <= $usedCoupun) {
                            $coupons->is_active = 0;
                            $coupons->save();
                        }
                    }
                }

                if (!auth()->guard('holiday')->user()) {
                    $booking_number = \Modules\Holidayz\Entities\Utility::getLastId('room_booking', 'booking_number');
                    $booking = RoomBooking::create([
                        'booking_number' => $booking_number,
                        'user_id' => isset(auth()->guard('holiday')->user()->id) ? auth()->guard('holiday')->user()->id : 0,
                        'payment_method' => __('Toyyibpay'),
                        'payment_status' => 1,
                        'invoice' => null,
                        'workspace' => $hotel->workspace,
                        'created_by' => $hotel->created_by,
                        'total' => $price,
                        'coupon_id' => $coupons_id,
                        'first_name' => $request->firstname,
                        'last_name' => $request->lastname,
                        'email' =>  $request->email,
                        'phone' => $request->phone,
                        'address' => $request->address,
                        'city' => $request->city,
                        'country' => ($request->country) ? $request->country : 'india',
                        'zipcode' => $request->zipcode,
                    ]);
                    foreach ($Carts as $key => $value) {
                        //
                        $toDate = \Carbon\Carbon::parse($value['check_in']);
                        $fromDate = \Carbon\Carbon::parse($value['check_out']);

                        $days = $toDate->diffInDays($fromDate);
                        //
                        $bookingOrder = RoomBookingOrder::create([
                            'booking_id' => $booking->id,
                            'customer_id' => isset(auth()->guard('holiday')->user()->id) ? auth()->guard('holiday')->user()->id : 0,
                            'room_id' => $value['room_id'],
                            'workspace' => $value['workspace'],
                            'check_in' => $value['check_in'],
                            'check_out' => $value['check_out'],
                            'price' => $value['price'] * $value['room'] * $days,
                            'room' => $value['room'],
                            'service_charge' => $value['serviceCharge'],
                            'services' => $value['serviceIds'],
                        ]);
                        unset($Carts[$key]);
                    }
                    $cart_json = json_encode($Carts);
                    Cookie::queue('cart', $cart_json, 1440);
                } else {
                    $booking_number = \Modules\Holidayz\Entities\Utility::getLastId('room_booking', 'booking_number');
                    $booking = RoomBooking::create([
                        'booking_number' => $booking_number,
                        'user_id' => auth()->guard('holiday')->user()->id,
                        'payment_method' => __('Toyyibpay'),
                        'payment_status' => 1,
                        'total' => $price,
                        'coupon_id' => $coupons_id,
                        'invoice' => null,
                        'workspace' => $hotel->workspace,
                        'created_by' => $hotel->created_by,
                    ]);
                    foreach ($Carts as $key => $value) {
                        $bookingOrder = RoomBookingOrder::create([
                            'booking_id' => $booking->id,
                            'customer_id' => auth()->guard('holiday')->user()->id,
                            'room_id' => $value->room_id,
                            'workspace' => $value->workspace,
                            'check_in' => $value->check_in,
                            'check_out' => $value->check_out,
                            'price' => $value->price,   // * $value->room
                            'room' => $value->room,
                            'service_charge' => $value->service_charge,
                            'services' => $value->services,
                        ]);
                    }
                    RoomBookingCart::where(['customer_id' => auth()->guard('holiday')->user()->id])->delete();
                }

                event(new CreateRoomBooking($request, $booking));
                $type = 'roombookinginvoice';
                event(new ToyyibpayPaymentStatus($hotel, $type, $booking));

                //Email notification
                if (!empty($company_settings['New Room Booking By Hotel Customer']) && $company_settings['New Room Booking By Hotel Customer']  == true) {
                    $user = User::where('id', $hotel->created_by)->first();
                    $customer = HotelCustomer::find($booking->user_id);
                    $room = \Modules\Holidayz\Entities\Rooms::find($bookingOrder->room_id);
                    $uArr = [
                        'hotel_customer_name' => isset($customer->name) ? $customer->name : $booking->first_name,
                        'invoice_number' => $booking->booking_number,
                        'check_in_date' => $bookingOrder->check_in,
                        'check_out_date' => $bookingOrder->check_out,
                        'room_type' => $room->type,
                        'hotel_name' => $hotel->name,
                    ];

                    try {
                        $resp = EmailTemplate::sendEmailTemplate('New Room Booking By Hotel Customer', [$user->email], $uArr);
                    } catch (\Exception $e) {
                        $resp['error'] = $e->getMessage();
                    }

                    return redirect()->route('hotel.home', $slug)->with('success', __('Booking Successfully.') . ((isset($resp['error'])) ? '<br> <span class="text-danger" style="color:red">' . $resp['error'] . '</span>' : ''));
                }
                return redirect()->route('hotel.home', $slug)->with('success', 'Booking Successfully. email notification is off.');
                return redirect()->route('hotel.home', $slug)->with('success', __('Booking successfully.'));
            }

            $this->callBackUrl = route('booking.status', [$slug, $price, $coupons_id]);
            $this->returnUrl = route('booking.status', [$slug, $price, $coupons_id]);

            $Date = date('d-m-Y');
            $ammount = $price;
            $billName = 'Booking';
            $description = 'Booking';
            $billExpiryDays = 3;
            $billExpiryDate = date('d-m-Y', strtotime($Date . ' + 3 days'));
            $billContentEmail = "Thank you for purchasing our product!";

            session()->put('guestInfo', $request->only(['firstname', 'email', 'address', 'country', 'lastname', 'phone', 'city', 'zipcode']));

            $some_data = array(
                'userSecretKey' => $this->secrect_key,
                'categoryCode' => $this->category_code,
                'billName' => $billName,
                'billDescription' => $description,
                'billPriceSetting' => 1,
                'billPayorInfo' => 1,
                'billAmount' => 100 * $ammount,
                'billReturnUrl' => $this->returnUrl,
                'billCallbackUrl' => $this->callBackUrl,
                'billExternalReferenceNo' => 'AFR341DFI',
                'billTo' => 'John Doe',
                'billEmail' => 'jd@gmail.com',
                'billPhone' => '0194342411',
                'billSplitPayment' => 0,
                'billSplitPaymentArgs' => '',
                'billPaymentChannel' => '0',
                'billContentEmail' => $billContentEmail,
                'billChargeToCustomer' => 1,
                'billExpiryDate' => $billExpiryDate,
                'billExpiryDays' => $billExpiryDays
            );
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_URL, 'https://toyyibpay.com/index.php/api/createBill');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);
            $result = curl_exec($curl);
            $info = curl_getinfo($curl);
            curl_close($curl);
            $obj = json_decode($result);
            return redirect('https://toyyibpay.com/' . $obj[0]->BillCode);
        } else {
            return redirect()->back()->with('error', __('Hotel Not found'));
        }
    }

    public function getBookingPaymentStatus(Request $request, $slug, $price, $coupon_id = 0)
    {
        $hotel = Hotels::where('slug', $slug)->first();
        if ($hotel) {
            $company_settings = getCompanyAllSetting($hotel->created_by, $hotel->workspace);
            $request['status_id'] = 1;   // if you want to test payment gateway so uncomment
            if ($request->status_id == 3) {
                return redirect()->route('hotel.home', $slug)->with('error', __('Your Transaction is failed, please try again.'));
            } else if ($request->status_id == 2) {
                return redirect()->route('hotel.home', $slug)->with('error', __('Your transaction is pending.'));
            } else if ($request->status_id == 1) {
                $guestDetails = session()->get('guestInfo');

                if ($coupon_id != 0) {
                    $coupons = BookingCoupons::find($coupon_id);
                    if (!empty($coupons)) {
                        $userCoupon         = new UsedBookingCoupons();
                        $userCoupon->customer_id   = isset(auth()->guard('holiday')->user()->id) ? auth()->guard('holiday')->user()->id : 0;
                        $userCoupon->coupon_id = $coupons->id;
                        $userCoupon->save();
                        $usedCoupun = $coupons->used_coupon();
                        if ($coupons->limit <= $usedCoupun) {
                            $coupons->is_active = 0;
                            $coupons->save();
                        }
                    }
                }

                if (!auth()->guard('holiday')->user()) {
                    $Carts = Cookie::get('cart');
                    $Carts = json_decode($Carts, true);
                    $booking_number = \Modules\Holidayz\Entities\Utility::getLastId('room_booking', 'booking_number');
                    $booking = RoomBooking::create([
                        'booking_number' => $booking_number,
                        'user_id' => isset(auth()->guard('holiday')->user()->id) ? auth()->guard('holiday')->user()->id : 0,
                        'payment_method' => __('Toyyibpay'),
                        'payment_status' => 1,
                        'invoice' => null,
                        'workspace' => $hotel->workspace,
                        'created_by' => $hotel->created_by,
                        'total' => isset($price) ? $price : 0,
                        'coupon_id' => ($coupon_id) ? $coupon_id : 0,
                        'first_name' => $guestDetails['firstname'],
                        'last_name' => $guestDetails['lastname'],
                        'email' =>  $guestDetails['email'],
                        'phone' => $guestDetails['phone'],
                        'address' => $guestDetails['address'],
                        'city' => $guestDetails['city'],
                        'country' => ($guestDetails['country']) ? $guestDetails['country'] : 'india',
                        'zipcode' => $guestDetails['zipcode'],
                    ]);
                    foreach ($Carts as $key => $value) {
                        //
                        $toDate = \Carbon\Carbon::parse($value['check_in']);
                        $fromDate = \Carbon\Carbon::parse($value['check_out']);

                        $days = $toDate->diffInDays($fromDate);
                        //
                        $bookingOrder = RoomBookingOrder::create([
                            'booking_id' => $booking->id,
                            'customer_id' => isset(auth()->guard('holiday')->user()->id) ? auth()->guard('holiday')->user()->id : 0,
                            'room_id' => $value['room_id'],
                            'workspace' => $value['workspace'],
                            'check_in' => $value['check_in'],
                            'check_out' => $value['check_out'],
                            'price' => $value['price'] * $value['room'] * $days,
                            'room' => $value['room'],
                            'service_charge' => $value['serviceCharge'],
                            'services' => $value['serviceIds'],
                        ]);
                        unset($Carts[$key]);
                    }
                    $cart_json = json_encode($Carts);
                    Cookie::queue('cart', $cart_json, 1440);
                } else {
                    $Carts = RoomBookingCart::where(['customer_id' => auth()->guard('holiday')->user()->id])->get();
                    $booking_number = \Modules\Holidayz\Entities\Utility::getLastId('room_booking', 'booking_number');
                    $booking = RoomBooking::create([
                        'booking_number' => $booking_number,
                        'user_id' => auth()->guard('holiday')->user()->id,
                        'payment_method' => __('Toyyibpay'),
                        'payment_status' => 1,
                        'invoice' => null,
                        'workspace' => $hotel->workspace,
                        'created_by' => $hotel->created_by,
                        'total' => isset($price) ? $price : 0,
                        'coupon_id' => ($coupon_id) ? $coupon_id : 0,
                    ]);
                    foreach ($Carts as $key => $value) {
                        $bookingOrder = RoomBookingOrder::create([
                            'booking_id' => $booking->id,
                            'customer_id' => auth()->guard('holiday')->user()->id,
                            'room_id' => $value->room_id,
                            'workspace' => $value->workspace,
                            'check_in' => $value->check_in,
                            'check_out' => $value->check_out,
                            'price' => $value->price,   // * $value->room
                            'room' => $value->room,
                            'service_charge' => $value->service_charge,
                            'services' => $value->services,
                        ]);
                    }
                    RoomBookingCart::where(['customer_id' => auth()->guard('holiday')->user()->id])->delete();
                }

                event(new CreateRoomBooking($request, $booking));
                $type = 'roombookinginvoice';
                event(new ToyyibpayPaymentStatus($hotel, $type, $booking));

                //Email notification
                if (!empty($company_settings['New Room Booking By Hotel Customer']) && $company_settings['New Room Booking By Hotel Customer']  == true) {
                    $user = User::where('id', $hotel->created_by)->first();
                    $customer = HotelCustomer::find($booking->user_id);
                    $room = \Modules\Holidayz\Entities\Rooms::find($bookingOrder->room_id);
                    $uArr = [
                        'hotel_customer_name' => isset($customer->name) ? $customer->name : $booking->first_name,
                        'invoice_number' => $booking->booking_number,
                        'check_in_date' => $bookingOrder->check_in,
                        'check_out_date' => $bookingOrder->check_out,
                        'room_type' => $room->type,
                        'hotel_name' => $hotel->name,
                    ];

                    try {
                        $resp = EmailTemplate::sendEmailTemplate('New Room Booking By Hotel Customer', [$user->email], $uArr);
                    } catch (\Exception $e) {
                        $resp['error'] = $e->getMessage();
                    }

                    return redirect()->route('hotel.home', $slug)->with('success', __('Booking Successfully.') . ((isset($resp['error'])) ? '<br> <span class="text-danger" style="color:red">' . $resp['error'] . '</span>' : ''));
                }
                return redirect()->route('hotel.home', $slug)->with('success', 'Booking Successfully. email notification is off.');
                return redirect()->route('hotel.home', $slug)->with('success', __('Booking successfully'));
            } else {
                return redirect()->back()->with('error', __('Payment fail please try again!'));
            }
        } else {
            return redirect()->back()->with('error', __('Hotel Not found'));
        }
    }
}
