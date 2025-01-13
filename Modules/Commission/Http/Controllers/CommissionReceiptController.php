<?php

namespace Modules\Commission\Http\Controllers;

use App\Models\BankTransferPayment;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\User;
use Google\Service\ServiceControl\Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Commission\Entities\CommissionModule;
use Modules\Commission\Entities\CommissionPlan;
use Modules\Commission\Entities\CommissionReceipt;
use Carbon\Carbon;
use Modules\Commission\Events\CreateCommissionReceipt;
use Modules\Commission\Events\DestroyCommissionReceipt;
use PhpParser\Node\Stmt\Foreach_;

class CommissionReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (\Auth::user()->isAbleTo('commission receipt manage')) {
            $commissionReceipts = CommissionReceipt::where('created_by', '=', creatorId())->where('workspace', '=', getActiveWorkSpace())->get();

            return view('commission::commissionReceipt.index', compact('commissionReceipts'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (\Auth::user()->isAbleTo('commission receipt create')) {

            $modules = CommissionModule::select('module', 'submodule')->get();
            $commissionModule = [];
            foreach ($modules as $module) {
                $sub_modules = CommissionModule::select('id', 'module', 'submodule')->where('module', $module->module)->get();
                $temp = [];
                foreach ($sub_modules as $sub_module) {
                    $temp[$sub_module->id] = $sub_module->submodule;
                }
                $commissionModule[Module_Alias_Name($module->module)] = $temp;
            }
            $commissionReceipt = CommissionReceipt::where('created_by', '=', creatorId())->where('workspace', '=', getActiveWorkSpace())->first();
            for ($i = 0; $i <= 10; $i++) {
                $data = date("Y", strtotime('-1 years' . " +$i years"));
                $year[$data] = $data;
            }


            for ($i = 0; $i <= 15; $i++) {
                $data = date('Y', strtotime('-5 years' . " +$i years"));
                $years[$data] = $data;
            }
            $month = [
                '01' => 'JAN',
                '02' => 'FEB',
                '03' => 'MAR',
                '04' => 'APR',
                '05' => 'MAY',
                '06' => 'JUN',
                '07' => 'JUL',
                '08' => 'AUG',
                '09' => 'SEP',
                '10' => 'OCT',
                '11' => 'NOV',
                '12' => 'DEC',
            ];
            return view('commission::commissionReceipt.create', compact('commissionModule', 'commissionReceipt', 'month', 'years', 'year'));
        } else {
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
        if (\Auth::user()->isAbleTo('commission receipt create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'commission_str' => 'required',
                    'agent' => 'required',
                    'amount' => 'required',
                    'month' => 'required',
                    'year' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $month = $request->month;
            $year  = $request->year;

            $formate_month_year = $year . '-' . $month;

            $commissionReceipt = new CommissionReceipt();
            $commissionReceipt->commission_str    = $request->commission_str;
            $commissionReceipt->commissionplan_id = $request->comissionPlanId;
            $commissionReceipt->agent             = $request->agent;
            $commissionReceipt->amount            = $request->amount;
            $commissionReceipt->status            = '0';
            $commissionReceipt->commission_date   = $formate_month_year;
            $commissionReceipt->workspace         = getActiveWorkSpace();
            $commissionReceipt->created_by        = creatorId();
            $commissionReceipt->save();
            event(new CreateCommissionReceipt($request, $commissionReceipt));


            return redirect()->route('commission-receipt.index')->with('success', __('Commission Receipt successfully created.'));
        } else {
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
        return redirect()->back();
        return view('commission::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(CommissionReceipt $commissionReceipt)
    {
        if (\Auth::user()->isAbleTo('commission receipt delete')) {
            event(new DestroyCommissionReceipt($commissionReceipt));
            $commissionReceipt->delete();
            return redirect()->back()->with('success', __('Commission Receipt successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function getcommissionagent(Request $request)
    {

        if (!empty($request->commission_str)) {
            $commissionStr    = CommissionModule::where('id', $request->commission_str)->get()->pluck('submodule')->toArray();
            $html = '';
            $str = '';

            if (in_array('Project', $commissionStr)) {
                $str = 'Project';
                $html .= '<div class="form-group">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="project_type" value="project" id="project_wise" checked>
                            <label class="form-check-label pointer" for="project_wise">
                                Project Wise
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="project_type" value="task" id="task_wise">
                            <label class="form-check-label pointer" for="task_wise">
                                Task Wise
                            </label>
                        </div>
                    </div>';

                $agentData = Invoice::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->where('invoice_module', '=', 'taskly')->get();
            } elseif (in_array('Invoice', $commissionStr)) {
                $agentData = Invoice::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->where('invoice_module', '=', 'account')->get();
            } else {
                $agentData = \Modules\Sales\Entities\SalesInvoiceItem::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();
            }
            $users = []; // Initialize the $users array

            foreach ($agentData as $invoice) {
                $userId = explode(',', $invoice->agent);

                foreach ($userId as $id) {
                    if (!array_key_exists($id, $users)) {
                        $user = User::find($id);

                        if ($user) {
                            $users[$id] = $user->name;
                        }
                    }
                }
            }


            return response()->json([
                'html' => $html,
                'str' => $str,
                'users' => $users,
            ]);
        }
    }

    public static function calc(Request $request)
    {
        $agentId = $request->selectAgent;
        if ($request->selectStr == 1) {
            $invoices = Invoice::whereRaw("FIND_IN_SET(?, agent)", [$agentId])->where('invoice_module', '=', 'account')->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->toArray();

            foreach ($invoices as $invoice) {
                $invoiceProduct = InvoiceProduct::where('invoice_id', $invoice['id'])->get();
                if ($invoice) {
                    $commissionPlan = CommissionPlan::where('id', $invoice['commission_plan'])->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();
                }
            }
            $total = 0;
            if (count($invoiceProduct) > 0) {
                foreach ($invoiceProduct as $product) {
                    $total += ($product->price * $product->quantity) - $product->discount;
                }
            }
        } elseif ($request->selectStr == 2) {
            $projectInvoices = Invoice::whereRaw("FIND_IN_SET(?, agent)", [$agentId])->where('invoice_module', '=', 'taskly')->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->toArray();

            foreach($projectInvoices as $projectInvoice){
                $invoiceProduct = InvoiceProduct::where('invoice_id', $projectInvoice['id'])->get();
                if ($projectInvoice) {
                    $commissionPlan = CommissionPlan::where('id', $projectInvoice['commission_plan'])->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();
                }
            }

            $total = 0;
            if (count($invoiceProduct) > 0) {
                foreach ($invoiceProduct as $product) {
                    $total += ($product->price * $product->quantity) - $product->discount;
                }
            }
        } elseif ($request->selectStr == 3) {
            $salesInvoices = \Modules\Sales\Entities\SalesInvoiceItem::whereRaw("FIND_IN_SET(?, agent)", [$agentId])->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->toArray();

            foreach($salesInvoices as $salesInvoice){
                if ($salesInvoice) {
                    $commissionPlan = CommissionPlan::where('id', $salesInvoice['commission_plan'])->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();

                    $total = ($salesInvoice['price'] * $salesInvoice['quantity']) - $salesInvoice['discount'];
                }
            }

        }
        foreach ($commissionPlan as $commissionPlan) {
            $commission_str = $commissionPlan['commission_str'];
            $commission_data = json_decode($commission_str, true);
            $items = $commission_data['checklist_items'];
            $comission = 0;
        }


        if (isset($commission_data['conditional']) && $commission_data['conditional'] == 'ladder_of_invoice') {
            foreach ($items as $checklist_item) {
                if ($total >= $checklist_item['from_amount'] && $total <= $checklist_item['to_amount']) {
                    if ($commissionPlan->commission_type == 'fixed') {
                        $comission += $checklist_item['commission'];
                    } elseif ($commissionPlan->commission_type == 'percentage') {
                        $calc = $total * $checklist_item['commission'] / 100;
                        $comission += $calc;
                    }
                }
            }
        } elseif ($commission_data['conditional'] == 'non_conditional') {
            foreach ($items as $checklist_item) {
                if (isset($checklist_item['commission_total'])) {
                    if ($commissionPlan->commission_type == 'fixed') {
                        $comission += $checklist_item['commission_total'];
                    } elseif ($commissionPlan->commission_type == 'percentage') {
                        $calc = $total * $checklist_item['commission_total'] / 100;
                        $comission += $calc;
                    }
                }
            }
        }

        return response()->json([
            'comission' => $comission,
            'comissionPlanId' => $commissionPlan->id,
        ]);
    }

    public function receipt($id)
    {

        $commissionReceipt = CommissionReceipt::where('id', $id)->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->first();

        $invoice = Invoice::where('commission_plan', $commissionReceipt->commissionplan_id)->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('id')->toArray();
        $invoiceProducts = InvoiceProduct::whereIn('invoice_id', $invoice)->get();

        $total = 0;
        foreach ($invoiceProducts as $product) {
            $total += ($product->price * $product->quantity) - $product->discount;
        }

        return view('commission::commissionReceipt.receipt', compact('commissionReceipt', 'invoiceProducts'));
    }
    public function receiptPayment($id)
    {

        $commissionReceipt = CommissionReceipt::where('id', decrypt($id))->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->first();
        $invoice = Invoice::where('commission_plan', $commissionReceipt->commissionplan_id)->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('id')->toArray();
        $invoiceProducts = InvoiceProduct::whereIn('invoice_id', $invoice)->get();

        $total = 0;
        foreach ($invoiceProducts as $product) {
            $total += ($product->price * $product->quantity) - $product->discount;
        }
        return view('commission::commissionReceipt.payment', compact('commissionReceipt', 'invoiceProducts'));
    }
    public function banktransfer(Request $request)
    {
        $bank_transfer_payment  = new  BankTransferPayment();

        if (!empty($request->payment_receipt)) {
            $filenameWithExt = $request->file('payment_receipt')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('payment_receipt')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            $uplaod = upload_file($request, 'payment_receipt', $fileNameToStore, 'bank_transfer');
            if ($uplaod['flag'] == 1) {
                $bank_transfer_payment->attachment = $uplaod['url'];
            } else {
                return response()->json(
                    [
                        'status' => 'error',
                        'msg' => $uplaod['msg']
                    ]
                );
            }
        }

        $commissionReceipt = CommissionReceipt::where('id', $request->commissionReceiptId)->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->first();

        $post = $request->all();
        unset($post['_token']);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

        $bank_transfer_payment->order_id = $orderID;
        $bank_transfer_payment->user_id = $commissionReceipt->agent;
        $bank_transfer_payment->request = json_encode($post);
        $bank_transfer_payment->status = 'Pending';
        $bank_transfer_payment->type = 'commission';
        $bank_transfer_payment->price = $commissionReceipt->amount;
        $bank_transfer_payment->price_currency  = admin_setting('defult_currancy');
        $bank_transfer_payment->created_by = creatorId();
        $bank_transfer_payment->workspace = getActiveWorkSpace();
        $bank_transfer_payment->save();


        return response()->json(
            [
                'status' => 'success',
                'msg' =>  __('Commission payment request send successfully') . '<br> <span class="text-danger">' . __("Go to  Bank Transfer Request to view payment entries.") . '</span>'
            ]
        );
    }
    public function payment(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'commission_str' => 'required',
                'agent' => 'required',
                'amount' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $commissionReceipt = new CommissionReceipt();
        $commissionReceipt->commission_str    = $request->commission_str;
        $commissionReceipt->commissionplan_id = $request->comissionPlanId;
        $commissionReceipt->agent             = $request->agent;
        $commissionReceipt->amount            = $request->amount;
        $commissionReceipt->workspace         = getActiveWorkSpace();
        $commissionReceipt->created_by        = creatorId();
        $commissionReceipt->save();

        $invoice = Invoice::where('commission_plan', $commissionReceipt->commissionplan_id)->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('id')->toArray();
        $invoiceProducts = InvoiceProduct::whereIn('invoice_id', $invoice)->get();

        $total = 0;
        foreach ($invoiceProducts as $product) {
            $total += ($product->price * $product->quantity) - $product->discount;
        }
        return view('commission::commissionReceipt.payment', compact('commissionReceipt', 'invoiceProducts'))->with('success', __('Commission Receipt successfully created.'));
    }
    public function commissionPlans(Request $request)
    {
        $commissionstr = $request->selected == 'Account' ? 1 : ($request->selected == 'Taskly' ? 2 : ($request->selected == 'Sales' ? 3 : 0));
        $currentDate = Carbon::now()->format('Y-m-d');

        $commissions = CommissionPlan::where('created_by', \Auth::user()->id)->where('commission_module', '=', $commissionstr)->where('start_date', '<=', $currentDate)->where('end_date', '>=', $currentDate)->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('name', 'id');

        return response()->json([
            'commissions' => $commissions,
            'commissionstr' => $commissions ?? 0,
        ]);
    }
}
