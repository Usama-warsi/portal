<?php

namespace Modules\SalesAgent\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Modules\SalesAgent\Entities\SalesAgent;
use Modules\SalesAgent\Entities\Program;
use Modules\SalesAgent\Entities\SalesAgentPurchase;
use Modules\SalesAgent\Entities\PurchaseOrderItems;
use Modules\SalesAgent\Entities\ProgramItems;
use Illuminate\Support\Facades\Validator;
use Rawilk\Settings\Support\Context;
use Illuminate\Support\Facades\Crypt;
use Modules\ProductService\Entities\ProductService;
use App\Models\Invoice;
use App\Models\BankTransferPayment;
use App\Models\InvoicePayment;
use Carbon\Carbon;
use Modules\SalesAgent\Events\SalesAgentOrderCreate;
use Modules\SalesAgent\Events\SalesAgentOrderDelete;
use Modules\SalesAgent\Events\SalesAgentOrderStatusUpdated;
use Modules\SalesAgent\Entities\SalesAgentUtility;


class SalesAgentPurchaseController extends Controller
{

    public function index()
    {
        $purchaseOrders = SalesAgentPurchase::where('workspace',getActiveWorkSpace());
        if(\Auth::user()->type == 'salesagent')
        {
            $purchaseOrders->where('created_by','=',\Auth::user()->id);
        }
        $purchaseOrders =  $purchaseOrders->with('user')->get();

        return view('salesagent::purchase.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $currentDate = Carbon::now()->format('Y-m-d');

        $programs = Program::where('workspace', getActiveWorkSpace())
                                ->where('from_date', '<=', $currentDate)
                                ->where('to_date', '>=', $currentDate)
                                ->whereRaw('FIND_IN_SET(?, sales_agents_applicable)', [\Auth::user()->id])->get()->pluck('name', 'id');

        // $programs = Program::getProgramsBySalesAgentId();
        $salesAgents = User::where('workspace_id',getActiveWorkSpace())
                        ->leftjoin('customers', 'users.id', '=', 'customers.user_id')
                        ->leftjoin('sales_agents', 'users.id', '=', 'sales_agents.user_id')
                        ->where('users.type', 'salesagent')
                        ->where('users.is_disable','1')
                        ->where('sales_agents.is_agent_active','1')
                        ->select('users.name as name', 'users.email as email', 'users.id as id')
                        ->get();

        $purchaseOrderNumber = SalesAgent::purchaseOrderNumberFormat($this->purchaseOrderNumber());

        return view('salesagent::purchase.create' , compact('programs','salesAgents','purchaseOrderNumber'));
    }

    public function store(Request $request)
    {
       if (Auth::user()->isAbleTo('salesagent purchase create'))
       {
           $validator = \Validator::make(
               $request->all(),
               [
                   'order_date' => 'required',
                   'delivery_date' => 'required',
                   'order_details' => 'required',
               ]
           );

           if ($validator->fails()) {
               $messages = $validator->getMessageBag();

               return redirect()->back()->with('error', $messages->first());
           }

           $order                       = new SalesAgentPurchase();
           $order->user_id              = \Auth::user()->id;
           $order->purchaseOrder_id     = $this->purchaseOrderNumber();
           $order->order_number         = $request->order_number;
           $order->order_date           = $request->order_date;
           $order->delivery_date        = $request->delivery_date;
           $order->delivery_status      = 0;
           $order->order_status         = 0;
           $order->created_by           = \Auth::user()->id;
           $order->workspace            = getActiveWorkSpace();

           $order->save();

           $products = $request->order_details;

           for ($i = 0; $i < count($products); $i++)
           {
               $OrderItem                       = new PurchaseOrderItems();
               $OrderItem->purchase_order_id    = $order->id;
               $OrderItem->program_id           = $products[$i]['program_id'];
               $OrderItem->item_id              = $products[$i]['item'];
               $OrderItem->quantity             = $products[$i]['quantity'];
               $OrderItem->tax                  = $products[$i]['tax'];
               $OrderItem->discount             = $products[$i]['discountHidden'];
               $OrderItem->price                = $products[$i]['price'];
               $OrderItem->description          = $products[$i]['description'];
               $OrderItem->save();
               $OrderItems[$i] = $OrderItem;
           }

           event(new SalesAgentOrderCreate($request , $order , $OrderItems));

           return redirect()->route('salesagent.purchase.order.index')->with('success', __('Purchase Order successfully created.'));
       } else {
           return redirect()->back()->with('error', __('Permission denied.'));
       }

    }

    public function show($id)
    {
        try {
            $id       = Crypt::decrypt($id);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Purchase Order Not Found.'));
        }
        $purchaseOrder    = SalesAgentPurchase::with('items')->find($id);
        if ($purchaseOrder->workspace == getActiveWorkSpace())
        {
            $salesagent       = $purchaseOrder->salesagent;

            return view('salesagent::purchase.show', compact('salesagent','purchaseOrder'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {
        $order     = SalesAgentPurchase::find($id);
        $OrderItems     =  PurchaseOrderItems::find($order->id);

        if($order)
        {
            $order->delete();
        }

        if($OrderItems)
        {
            $OrderItems->delete();
        }

        event(new SalesAgentOrderDelete($order , $OrderItems));

        return redirect()->back()->with('success', __('Purchase Order successfully deleted.'));

    }

    function purchaseOrderNumber()
    {
        $latest = SalesAgentPurchase::where('workspace',getActiveWorkSpace())->latest()->first();
        if (!$latest)
        {
            return 1;
        }
        return $latest->purchaseOrder_id + 1;
    }

    public function settingsCreate()
    {

        return view('salesagent::purchase.purchaseSetting');
    }

    public function settings(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'sales_agent_purchase_order_prefix' => 'required',
        ]);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        else
        {
            // $userContext = new Context(['user_id' => creatorId(),'workspace_id'=>getActiveWorkSpace()]);
            // \Settings::context($userContext)->set('sales_agent_purchase_order_prefix', $request->sales_agent_purchase_order_prefix);
            $post['sales_agent_purchase_order_prefix'] = $request->sales_agent_purchase_order_prefix;
            SalesAgentUtility::saveSettings($post);

            return redirect()->back()->with('success','Sales Agent setting save sucessfully.');
        }
    }

    public function getProgramItems(Request $request)
    {
        if($request->program_id !== null){
            $program    = Program::find($request->program_id);
            $program_details = ProgramItems::where('program_id' , $request->program_id)->get();
            // $program_details = json_decode($program->program_details);
            $productServices = ProductService::where('workspace_id', getActiveWorkSpace());

            foreach($program_details as $key => $program_detail){
               $items[$key]         = $program_detail->items;
            }
            $flattenedArray = [];

            foreach ($items as $subArray) {
                $flattenedArray = array_merge($flattenedArray, explode(',',$subArray));
            }

            $productServices->whereIn('id', $flattenedArray);

            $data['productServices'] =  $productServices->get()->pluck('name', 'id');
            $data['program_discount_type'] = $program->discount_type;
            return response()->json($data);
        }

    }

    public function product(Request $request)
    {
        $data['product']     = $product = \Modules\ProductService\Entities\ProductService::find($request->product_id);
        $data['unit']        = !empty($product) ? ((!empty($product->unit())) ? $product->unit()->name : '') : '';
        $data['taxRate']     = $taxRate = !empty($product) ? (!empty($product->tax_id) ? $product->taxRate($product->tax_id) : 0 ): 0;
        $data['taxes']       =  !empty($product) ? ( !empty($product->tax_id) ? $product->tax($product->tax_id) : 0) : 0;
        $salePrice           = !empty($product) ?  $product->purchase_price : 0;
        $quantity            = 1;
        $taxPrice            = !empty($product) ? (($taxRate / 100) * ($salePrice * $quantity)) : 0;
        $data['totalAmount'] = !empty($product) ?  ($salePrice * $quantity) : 0;
        $data['discount']    = SalesAgentPurchase::getdiscount($request->program_id, $request->product_id, $salePrice);
        $data['program_discount_type']     = Program::where('id','=', $request->program_id)->pluck('discount_type')->first();
        $data['discount_range']            = SalesAgentPurchase::getDiscountRange($request->program_id, $request->product_id);
        return json_encode($data);
    }

    public function productList(Request $request , $program_id = '')
    {
        $programs           = Program::getProgramsBySalesAgentId();

        if($request['program'])
        {
            $productServices    = Program::getProductServices([$request['program']]);

        }else{
            $productServices    = Program::getProductServices($programs->keys()->all());
        }

        $keysArray = $programs->keys()->all();

        return view('salesagent::programs.productList' , compact('productServices','programs'));
    }

    public function updateOrderStatus($order_id , $key='')
    {
        if($order_id != '' && $key != '')
        {
            $check  = SalesAgentPurchase::find($order_id)->exists();
            if($check)
            {
                $purchaseOrder                  = SalesAgentPurchase::find($order_id);
                $purchaseOrder->order_status    = $key;
                $purchaseOrder->save();

                event(new SalesAgentOrderStatusUpdated($purchaseOrder));

                return redirect()->back()->with('success', __('purchaseOrder Status Updated successfully.'));

            }else{

                return redirect()->back()->with('error', __('Order Not Found!'));
            }
        }else{

            return redirect()->back()->with('error', __('Something Went Wrong!'));
        }
    }

    public function invoiceCreate($order_id)
    {
        $category = \Modules\ProductService\Entities\Category::where('created_by', '=', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 1)->get()->pluck('name', 'id');
        $purchaseOrder    = SalesAgentPurchase::find($order_id);
        return view('salesagent::purchase.invoiceCreate', compact('category','purchaseOrder'));
    }

    public function invoiceIndex(Request $request)
    {
        if(Auth::user()->type == 'salesagent')
        {
            $customer   = \Modules\SalesAgent\Entities\Customer::where('user_id',\Auth::user()->id)->where('workspace', getActiveWorkSpace())->first();
            $status     = Invoice::$statues;
            $query      = Invoice::where('user_id',\Auth::user()->id)->where('customer_id', '=', $customer->id)->where('workspace', getActiveWorkSpace());
            if(!empty($request->issue_date))
            {
                $date_range = explode('to', $request->issue_date);
                if(count($date_range) == 2)
                {
                    $query->whereBetween('issue_date',$date_range);
                }
                else
                {
                    $query->where('issue_date',$date_range[0]);
                }
            }
            if(!empty($request->status))
            {
                $query->where('status', $request->status);
            }
            $invoices = $query->get();

            return view('salesagent::purchase.invoiceIndex', compact('invoices', 'status'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function invoiceShow($e_id)
    {
        if(Auth::user()->type == 'salesagent')
        {
            try {
                $id       = Crypt::decrypt($e_id);
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', __('Invoice Not Found.'));
            }
            $invoice = Invoice::find($id);
            if($invoice)
            {
                $bank_transfer_payments = BankTransferPayment::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->where('type','invoice')->where('request',$invoice->id)->get();
                if($invoice->workspace == getActiveWorkSpace())
                {
                    $invoicePayment = InvoicePayment::where('invoice_id', $invoice->id)->first();
                    if(module_is_active('SalesAgent'))
                    {
                        $customer = \Modules\SalesAgent\Entities\Customer::where('user_id',$invoice->user_id)->where('workspace',getActiveWorkSpace())->first();
                    }
                    else
                    {
                        $customer = $invoice->customer;
                    }
                    if(module_is_active('CustomField')){
                        $invoice->customField = \Modules\CustomField\Entities\CustomField::getData($invoice, 'Base','Invoice');
                        $customFields      = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'Base')->where('sub_module','Invoice')->get();
                    }else{
                        $customFields = null;
                    }
                    $iteams   = $invoice->items;

                    return view('salesagent::purchase.invoiceShow', compact('invoice', 'customer', 'iteams', 'invoicePayment','customFields','bank_transfer_payments'));
                }
                else
                {
                    return redirect()->back()->with('error', __('Permission denied.'));
                }
            }
            else
            {
                return redirect()->back()->with('error', __('This invoice is deleted.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
