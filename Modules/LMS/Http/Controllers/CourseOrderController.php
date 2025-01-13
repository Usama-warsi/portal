<?php

namespace Modules\LMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\LMS\Entities\CourseOrder;
use Modules\LMS\Entities\Store;
use Modules\LMS\Entities\Student;
use Illuminate\Support\Facades\Crypt;
use Modules\LMS\Entities\Course;
use Modules\LMS\Entities\CourseOrderSummary;
use Modules\LMS\Entities\PurchasedCourse;
use Modules\LMS\Events\CreateCourseOrder;

class CourseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->isAbleTo('course order manage'))
        {
            $user  = Auth::user();
            $store = Store::where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->first();

            $Course_orders = CourseOrder::orderBy('id', 'DESC')->where('store_id', $store->id)->get();
            $Course_orders_summarys = CourseOrderSummary::orderBy('id', 'DESC')->where('status','Unpaid')->where('workspace', getActiveWorkSpace())->where('created_by',creatorId())->get();

            return view('lms::course_orders.index', compact('Course_orders','Course_orders_summarys'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {
        if(Auth::user()->isAbleTo('course coupon create'))
        {
            $store = Store::where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->first();
            if($request->student_id)
            {
                $students = Student::where('id',$request->student_id)->where('store_id',$store->id)->get()->pluck('name','id');
            }
            else
            {

                $students = Student::where('store_id',$store->id)->get()->pluck('name','id');
            }
            return view('lms::course_orders.create',compact('students'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if(\Auth::user()->isAbleTo('course order create'))
        {
            $course = json_encode($request->items);
            $courseOrderSummary                 = new CourseOrderSummary();
            $courseOrderSummary->order_id       = '#' . time();
            $courseOrderSummary->student_id     = $request->student_id;
            $courseOrderSummary->issue_date     = $request->issue_date;
            $courseOrderSummary->course_number  = $request->course_number;
            $courseOrderSummary->price          = $request->total_amount;
            $courseOrderSummary->status         = 'Unpaid';
            $courseOrderSummary->course         = $course;
            $courseOrderSummary->workspace      = getActiveWorkSpace();
            $courseOrderSummary->created_by     = creatorId();
            $courseOrderSummary->save();

            event(new CreateCourseOrder($request, $courseOrderSummary));

            return redirect()->route('course_orders.index')->with('success',__('Course Order Successfully Created.'));
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
        if(\Auth::user()->isAbleTo('course order show'))
        {
            $courseorder = CourseOrder::find($id);
            $store = Store::where('id', $courseorder->store_id)->first();

            $order_products = json_decode($courseorder->course);
            $sub_total = 0;
            if(!empty($order_products))
            {
                foreach($order_products as $product)
                {
                    $totalprice = $product->price;
                    $sub_total  += $totalprice;
                }
            }
            if(!empty($store->currency)){
                $currency = $store->currency;
            }else{
                $currency = '$';
            }

            if($courseorder->discount_price == 'undefined'){
                $discount_price = 0;
            }else{
                $discount_price = str_replace('-' . $currency, '', $courseorder->discount_price);
            }

            if(!empty($discount_price))
            {
                $grand_total = $sub_total - $discount_price;
            }
            else
            {
                $discount_price = 0;
                $grand_total    = $sub_total;
            }
            $student_data = Student::where('id', $courseorder->student_id)->first();
            $order_id     = Crypt::encrypt($courseorder->id);


            return view('lms::course_orders.view', compact('student_data', 'discount_price', 'courseorder', 'store', 'grand_total', 'order_products', 'sub_total', 'order_id'));
        }
        else{
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
        if(\Auth::user()->isAbleTo('course order edit'))
        {
            $Course_orders_summary = CourseOrderSummary::find($id);
            $store = Store::where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->first();
            $students = Student::where('store_id',$store->id)->get()->pluck('name','id');
            return view('lms::course_orders.edit',compact('Course_orders_summary','students'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if(\Auth::user()->isAbleTo('course order edit'))
        {
            $course = json_encode($request->items);
            $courseOrderSummary = CourseOrderSummary::find($id);
            $courseOrderSummary->student_id     = $request->student_id;
            $courseOrderSummary->issue_date     = $request->issue_date;
            $courseOrderSummary->course_number  = $request->course_number;
            $courseOrderSummary->price          = $request->total_amount;
            $courseOrderSummary->status         = 'Unpaid';
            $courseOrderSummary->course         = $course;
            $courseOrderSummary->workspace      = getActiveWorkSpace();
            $courseOrderSummary->created_by     = creatorId();
            $courseOrderSummary->save();

            return redirect()->route('course_orders.index')->with('success',__('Course Order Successfully Updated.'));
        }
        else{
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
        if(\Auth::user()->isAbleTo('course order delete'))
        {
            $course_order = CourseOrder::find($id);
            $purchased_courses = PurchasedCourse::where('order_id',$course_order->id)->get();
            foreach($purchased_courses as $purchased_course)
            {
                $purchased_course->delete();
            }
            $course_order->delete();
            return redirect()->back()->with('success', __('Course Order Deleted!'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function courseOrderSummarydestroy($id)
    {
        if(\Auth::user()->isAbleTo('course order delete'))
        {
            $Course_orders_summary = CourseOrderSummary::find($id);
            $Course_orders_summary->delete();
            return redirect()->back()->with('success', __('Course Order Deleted!'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getCourse(Request $request)
    {
        $purchasedcourse_id = PurchasedCourse::where('student_id',$request->student_id)->get()->pluck('course_id');
        $course = Course::whereNotIn('id',$purchasedcourse_id)->where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->get()->pluck('title','id');
        return response()->json($course);
    }

    public function CourseSectionGet(Request $request)
    {
        $action = $request->action;
        $purchasedcourse_id = PurchasedCourse::where('student_id',$request->student_id)->get()->pluck('course_id');
        $course = Course::whereNotIn('id',$purchasedcourse_id)->where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->get()->pluck('title','id');
        $course_orders_summary =[];
        if($action == 'edit')
        {
            $course_orders_summary = CourseOrderSummary::find($request->order_id);
        }
        $returnHTML = view('lms::course_orders.section',compact('course','action','course_orders_summary'))->render();
            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
            ];
        return response()->json($response);
    }

    public function getCoursePrice(Request $request)
    {
        $totalprice = 0;
        if(!empty($request->course))
        {
            $courses = Course::where('id',$request->course)->where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->first();
            $totalprice = $courses->price;
        }
        return response()->json($totalprice);
    }

    public function orderCourse(Request $request, $id)
    {
        $course_orders_summary = CourseOrderSummary::find($id);
        $course_orders = json_decode($course_orders_summary->course);

        foreach($course_orders as $course_orders)
        {
            if($request->course_id == $course_orders->product_id)
            {
                return response()->json($course_orders);
            }
        }

    }
}
