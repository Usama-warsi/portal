<?php

namespace Modules\LMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\LMS\Entities\CourseCoupon;
use Modules\LMS\Entities\CourseOrder;
use Modules\LMS\Entities\Store;
use Modules\LMS\Events\CreateCourseCoupon;
use Modules\LMS\Events\DestroyCourseCoupon;
use Modules\LMS\Events\UpdateCourseCoupon;

class CourseCouponController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(Auth::user()->isAbleTo('course coupon manage')){
            $productcoupons = CourseCoupon::where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->get();

            return view('lms::course-coupon.index', compact('productcoupons'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if(Auth::user()->isAbleTo('course coupon create'))
        {
            return view('lms::course-coupon.create');
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if(Auth::user()->isAbleTo('course coupon create')){
            $arrValidate = [
                'name' => 'required',
                'limit' => 'required|numeric',
                'code' => 'unique:course_coupons',
            ];

            if($request->enable_flat == 'on')
            {
                $arrValidate['pro_flat_discount'] = 'required';
            }
            else
            {
                $arrValidate['discount'] = 'required';
            }
            $validator = \Validator::make(
                $request->all(), $arrValidate
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $coursecoupon              = new CourseCoupon();
            $coursecoupon->name        = $request->name;
            $coursecoupon->enable_flat = !empty($request->enable_flat) ? $request->enable_flat : 'off';
            if($request->enable_flat == 'on')
            {
                $coursecoupon->flat_discount = $request->pro_flat_discount;
            }
            if(empty($request->enable_flat))
            {
                $coursecoupon->discount = $request->discount;
            }
            $coursecoupon->limit        = $request->limit;
            $coursecoupon->code         = $request->code;
            $coursecoupon->workspace_id = getActiveWorkSpace();
            $coursecoupon->created_by   = creatorId();
            $coursecoupon->save();

            event(new CreateCourseCoupon($request, $coursecoupon));

            return redirect()->route('course-coupon.index')->with('success', __('Coupon successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if(Auth::user()->isAbleTo('course coupon show'))
        {
            $coursecoupon = CourseCoupon::find($id);
            $productCoupons = CourseOrder::where('coupon', $id)->get();

            return view('lms::course-coupon.view', compact('coursecoupon','productCoupons'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if(Auth::user()->isAbleTo('course coupon edit'))
        {
            $coursecoupon = CourseCoupon::find($id);
            return view('lms::course-coupon.edit', compact('coursecoupon'));
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request,$id)
    {
        if(Auth::user()->isAbleTo('course coupon edit')){
            $arrValidate = [
                'name' => 'required',
                'limit' => 'required|numeric',
                'code' => 'required',
            ];

            if($request->enable_flat == 'on')
            {
                $arrValidate['pro_flat_discount'] = 'required';
            }
            else
            {
                $arrValidate['discount'] = 'required';
            }
            $validator = \Validator::make(
                $request->all(), $arrValidate
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $coursecoupon = CourseCoupon::find($id);
            $coursecoupon->name        = $request->name;
            $coursecoupon->enable_flat = !empty($request->enable_flat) ? $request->enable_flat : 'off';
            if($request->enable_flat == 'on')
            {
                $coursecoupon->flat_discount = $request->pro_flat_discount;
            }
            if(empty($request->enable_flat))
            {
                $coursecoupon->discount = $request->discount;
            }
            $coursecoupon->limit      = $request->limit;
            $coursecoupon->code       = $request->code;
            $coursecoupon->workspace_id   = getActiveWorkSpace();
            $coursecoupon->created_by = creatorId();
            $coursecoupon->update();

            event(new UpdateCourseCoupon($request, $coursecoupon));

            return redirect()->route('course-coupon.index')->with('success', __('Coupon successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if(Auth::user()->isAbleTo('course coupon delete'))
        {
            $coursecoupon = CourseCoupon::find($id);

            event(new DestroyCourseCoupon($coursecoupon));

            $coursecoupon->delete();

            return redirect()->route('course-coupon.index')->with('success', __('Coupon successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function applyCourseCoupon(Request $request)
    {
        if($request->price != '' && $request->coupon != '')
        {
            $original_price = $request->price;
            $store          = Store::where('id', $request->store_id)->first();
            $cart           = session()->get($store->slug);
            $coupons = CourseCoupon::where('code', strtoupper($request->coupon))->first();
            $company_settings = getCompanyAllSetting($store->created_by,$store->workspace_id);
            if(!empty($coupons))
            {
                $usedCoupun = $coupons->product_coupon();
                if($coupons->limit == $usedCoupun)
                {
                    return response()->json(
                        [
                            'is_success' => false,
                            'final_price' => $original_price,
                            'price' => number_format($request->price, $company_settings['currency_format']),
                            'message' => __('This coupon code has expired.'),
                        ]
                    );
                }
                else
                {
                    $requestprice = str_replace('$', '', $request->price);
                    if($coupons->enable_flat == 'on')
                    {
                        $discount_value = $coupons->flat_discount;
                    }
                    else
                    {
                        $discount_value = ($requestprice / 100) * $coupons->discount;
                    }

                    $plan_price = $requestprice - $discount_value;
                    if($plan_price < 0)
                    {
                        return response()->json(
                            [
                                'is_success' => false,
                                'final_price' => $original_price,
                                'price' => number_format($request->price),
                                'message' => __('This coupon is in valid.'),
                            ]
                        );
                    }
                    if(!empty($request->shipping_price))
                    {
                        $price = self::formatPrice($requestprice - $discount_value + $request->shipping_price, $request->store_id);
                        $data_value_price = $requestprice - $discount_value + $request->shipping_price;
                    }
                    else
                    {
                        $price = self::formatPrice($requestprice - $discount_value, $request->store_id);
                        $data_value_price = $requestprice - $discount_value;
                    }
                    $discount_value = '-' . self::formatPrice($discount_value, $request->store_id);
                    $cart['coupon'] = [
                        'coupon' => $coupons,
                        'discount_price' => $discount_value,
                        'final_price' => $price,
                        'final_price_data_value' => number_format($data_value_price,2),
                        'data_id' => $coupons->id,
                    ];
                    session()->put($store->slug, $cart);
                    return response()->json(
                        [
                            'is_success' => true,
                            'discount_price' => $discount_value,
                            'final_price' => $price,
                            'final_price_data_value' => number_format($data_value_price,2),
                            'data_id' => $coupons->id,
                            'price' => number_format($plan_price, !empty($company_settings['currency_format'])) ? $company_settings['currency_format'] : '',
                            'message' => __('Coupon code has applied successfully.'),
                        ]
                    );
                }
            }
            else
            {
                return response()->json(
                    [
                        'is_success' => false,
                        'final_price' => $original_price,
                        'price' => $request->price,
                        !empty($company_settings['currency_format']) ? $company_settings['currency_format'] : '',
                        'message' => __('This coupon code is invalid or has expired.'),
                    ]
                );
            }
        }
    }

    public function formatPrice($price, $store_id)
    {
        $store = Store::where('id', $store_id)->first();

        return $store->currency . number_format((float)$price, 2, '.', '');
    }
}
