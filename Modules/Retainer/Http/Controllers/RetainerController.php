<?php

namespace Modules\Retainer\Http\Controllers;

use App\Models\BankTransferPayment;
use App\Models\EmailTemplate;
use App\Models\InvoicePayment;
use App\Models\InvoiceProduct;
use App\Models\Proposal;
use App\Models\ProposalProduct;
use App\Models\User;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Account\Entities\Customer;
use Modules\Retainer\Entities\Retainer;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Retainer\Entities\RetainerPayment;
use Modules\Retainer\Entities\RetainerProduct;
use Illuminate\Support\Facades\Crypt;
use Modules\ProductService\Entities\ProductService;
use Modules\Retainer\Entities\RetainerUtility;
use Modules\Retainer\Events\CreatePaymentRetainer;
use Modules\Retainer\Events\CreateRetainer;
use Modules\Retainer\Events\DestroyRetainer;
use Modules\Retainer\Events\PaymentDestroyRetainer;
use Modules\Retainer\Events\ResentRetainer;
use Modules\Retainer\Events\SentRetainer;
use Modules\Retainer\Events\UpdateRetainer;
use Modules\Retainer\Events\RetainerConvertToInvoice;
use Modules\Retainer\Events\RetainerDuplicate;
use Modules\Retainer\Entities\RetainerAttechment;
use App\Models\Setting;



class RetainerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {

        if (\Auth::user()->isAbleTo('retainer manage')) {

            $customer = User::where('workspace_id', '=', getActiveWorkSpace())->where('type', 'Client')->get()->pluck('name', 'id');

            $status = Retainer::$statues;

            $query = Retainer::where('workspace', getActiveWorkSpace());

            if (!empty($request->customer)) {
                $query->where('user_id', '=', $request->customer);
            }

            if (!empty($request->issue_date)) {
                $date_range = explode('to', $request->issue_date);
                if (count($date_range) == 2) {
                    $query->whereBetween('issue_date', $date_range);
                } else {
                    $query->where('issue_date', $date_range[0]);
                }
            }
            if (!empty($request->status)) {
                $query->where('status', $request->status);
            }
            $retainers = $query->get();
            return view('retainer::retainer.index', compact('retainers', 'customer', 'status'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($customerId)
    {
        if (module_is_active('ProductService')) {
            if (\Auth::user()->isAbleTo('retainer create')) {
                $retainer_number = Retainer::retainerNumberFormat($this->retainerNumber());
                $customers = User::where('workspace_id', '=', getActiveWorkSpace())->where('type', 'Client')->get()->pluck('name', 'id');
                $category = [];
                $product_services = [];
                $projects = [];
                $taxs = [];
                if (module_is_active('Account')) {
                    if ($customerId > 0) {
                        $customerId = \Modules\Account\Entities\Customer::where('customer_id', $customerId)->first()->user_id;
                    }
                    $category = \Modules\ProductService\Entities\Category::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 1)->get()->pluck('name', 'id');
                    $product_services = \Modules\ProductService\Entities\ProductService::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
                }
                if (module_is_active('Taskly')) {
                    if (module_is_active('ProductService')) {
                        $taxs = \Modules\ProductService\Entities\Tax::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
                    }
                    $projects = \Modules\Taskly\Entities\Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', Auth::user()->id)->where('workspace', getActiveWorkSpace())->projectonly()->get()->pluck('name', 'id');
                }
                if (module_is_active('CustomField')) {

                    $customFields =  \Modules\CustomField\Entities\CustomField::where('workspace_id', getActiveWorkSpace())->where('module', '=', 'Retainer')->where('sub_module', 'Retainer')->get();
                } else {
                    $customFields = null;
                }

                return view('retainer::retainer.create', compact('customers', 'retainer_number', 'category', 'customerId', 'projects', 'taxs', 'product_services', 'customFields'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return redirect()->route('retainer.index')->with('error', __('Please Enable Product & Service Module'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('retainer create')) {
            if ($request->retainer_type == "product") {
                $validator = \Validator::make(
                    $request->all(),
                    [

                        'issue_date' => 'required',
                        'category_id' => 'required',
                        'items' => 'required',
                        'account_type' => 'required',

                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                $status = Retainer::$statues;
                $retainer                 = new Retainer();
                if (module_is_active('Account')) {
                    $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
                    $retainer->customer_id    = !empty($customer) ?  $customer->id : 0;
                }
                $retainer->retainer_id     = $this->retainerNumber();
                $retainer->user_id        = $request->customer_id;
                $retainer->status         = 0;
                $retainer->retainer_module = 'account';
                $retainer->issue_date     = $request->issue_date;
                $retainer->category_id    = $request->category_id;
                $retainer->retainer_template    = $request->retainer_template;
                $retainer->account_type     = $request->account_type;
                $retainer->workspace      = getActiveWorkSpace();
                $retainer->created_by     = Auth::user()->id;

                $retainer->save();
                $products = $request->items;

                Invoice::starting_number($retainer->retainer_id + 1, 'retainer');

                for ($i = 0; $i < count($products); $i++) {
                    $retainerProduct                 = new RetainerProduct();
                    $retainerProduct->retainer_id    = $retainer->id;
                    $retainerProduct->product_type   = $products[$i]['product_type'];
                    $retainerProduct->product_id     = $products[$i]['item'];
                    $retainerProduct->quantity       = $products[$i]['quantity'];
                    $retainerProduct->tax            = $products[$i]['tax'];
                    $retainerProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                    $retainerProduct->price          = $products[$i]['price'];
                    $retainerProduct->description    = str_replace( array( '\'', '"', '`','{',"\n"), ' ', $products[$i]['description']);
                    $retainerProduct->save();

                    if (module_is_active('CustomField')) {
                        \Modules\CustomField\Entities\CustomField::saveData($retainer, $request->customField);
                    }
                    event(new CreateRetainer($request, $retainer));
                }

                return redirect()->route('retainer.index', $retainer->id)->with('success', __('Retainer successfully created.'));
            } else if ($request->retainer_type == "project") {
                $validator = \Validator::make(
                    $request->all(),
                    [

                        'issue_date' => 'required',
                        'project' => 'required',
                        'tax_project' => 'required',
                        'items' => 'required',

                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $retainer                 = new Retainer();
                if (module_is_active('Account')) {
                    $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
                    $retainer->customer_id    = !empty($customer) ?  $customer->id : null;
                }

                $status = Retainer::$statues;
                $retainer->retainer_id     = $this->retainerNumber();
                $retainer->user_id        = $request->customer_id;
                $retainer->status         = 0;
                $retainer->retainer_module = 'taskly';
                $retainer->issue_date     = $request->issue_date;
                $retainer->due_date       = $request->due_date;
                $retainer->account_type     = $request->account_type;
                $retainer->category_id    = $request->project;
                $retainer->retainer_template    = $request->retainer_template;
                $retainer->workspace      = getActiveWorkSpace();
                $retainer->created_by     = Auth::user()->id;

                $retainer->save();

                $products = $request->items;

                Invoice::starting_number($retainer->retainer_id + 1, 'retainer');

                $project_tax = implode(',', $request->tax_project);

                for ($i = 0; $i < count($products); $i++) {
                    $retainerProduct              = new RetainerProduct();
                    $retainerProduct->retainer_id  = $retainer->id;
                    $retainerProduct->product_id  = $products[$i]['item'];
                    $retainerProduct->quantity    = 1;
                    $retainerProduct->tax         = $project_tax;
                    $retainerProduct->discount    = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                    $retainerProduct->price       = $products[$i]['price'];
                    $retainerProduct->description = $products[$i]['description'];
                    $retainerProduct->save();
                }

                if (module_is_active('CustomField')) {
                    \Modules\CustomField\Entities\CustomField::saveData($retainer, $request->customField);
                }

                event(new CreateRetainer($request, $retainer));

                return redirect()->route('retainer.index', $retainer->id)->with('success', __('Retainer successfully created.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */

    public function show($e_id)
    {

        if (Auth::user()->isAbleTo('retainer show')) {

            try {
                $id       = Crypt::decrypt($e_id);
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', __('Rerainer Not Found.'));
            }
            $retainer = Retainer::find($id);
            $bank_transfer_payments = BankTransferPayment::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->where('type', 'retainer')->where('request', $retainer->id)->get();
            if ($retainer->workspace == getActiveWorkSpace()) {

                $retainerPayment = RetainerPayment::where('retainer_id', $retainer->id)->first();
                $retainer_attachment = RetainerAttechment::where('retainer_id', $retainer->id)->get();
                if (module_is_active('Account')) {
                    $customer = \Modules\Account\Entities\Customer::where('user_id', $retainer->user_id)->where('workspace', getActiveWorkSpace())->first();
                } else {
                    $customer = $retainer->customer;
                }
                if (module_is_active('CustomField')) {
                    $retainer->customField = \Modules\CustomField\Entities\CustomField::getData($retainer, 'Retainer', 'Retainer');
                    $customFields      = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'Retainer')->where('sub_module', 'Retainer')->get();
                } else {
                    $customFields = null;
                }
                $iteams   = $retainer->items;


                return view('retainer::retainer.view', compact('retainer', 'customer', 'iteams', 'retainerPayment', 'customFields', 'bank_transfer_payments', 'retainer_attachment'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }



    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($e_id)
    {
        if (module_is_active('ProductService')) {
            if (Auth::user()->isAbleTo('retainer edit')) {
                try {
                    $id = \Illuminate\Support\Facades\Crypt::decrypt($e_id);
                } catch (\Throwable $th) {
                    return redirect()->back()->with('error', __('Retainer not available! please try again later'));
                }

                $retainer = Retainer::find($id);

                $retainer_number = Retainer::retainerNumberFormat($retainer->retainer_id);

                $customers = User::where('workspace_id', '=', getActiveWorkSpace())->where('type', 'Client')->get()->pluck('name', 'id');


                $category = [];
                $projects = [];
                $taxs = [];
                if (module_is_active('Account')) {
                    $category = \Modules\ProductService\Entities\Category::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 1)->get()->pluck('name', 'id');
                }
                if (module_is_active('Taskly')) {
                    if (module_is_active('ProductService')) {
                        $taxs = \Modules\ProductService\Entities\Tax::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
                    }
                    $projects = \Modules\Taskly\Entities\Project::where('workspace', getActiveWorkSpace())->projectonly()->get()->pluck('name', 'id');
                }
                if (module_is_active('CustomField')) {
                    $retainer->customField = \Modules\CustomField\Entities\CustomField::getData($retainer, 'Retainer', 'Retainer');
                    $customFields             = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'Retainer')->where('sub_module', 'Retainer')->get();
                } else {
                    $customFields = null;
                }


                return view('retainer::retainer.edit', compact('customers', 'projects', 'taxs', 'retainer', 'retainer_number', 'category', 'customFields'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return redirect()->route('retainer.index')->with('error', __('Please Enable Product & Service Module'));
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Retainer $retainer)
    {
        if (Auth::user()->isAbleTo('retainer edit')) {
            if ($retainer->workspace == getActiveWorkSpace()) {
                if ($request->retainer_type == "product") {
                    $validator = \Validator::make(
                        $request->all(),
                        [
                            'customer_id' => 'required',
                            'issue_date' => 'required',
                            'category_id' => 'required',
                            'items' => 'required',
                        ]
                    );
                    if ($validator->fails()) {
                        $messages = $validator->getMessageBag();

                        return redirect()->route('retainer.index')->with('error', $messages->first());
                    }
                    if (module_is_active('Account')) {
                        $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
                        $retainer->customer_id    = !empty($customer) ?  $customer->id : null;
                    }
                    if ($request->retainer_type != $retainer->retainer_module) {
                        RetainerProduct::where('retainer_id', '=', $retainer->id)->delete();
                    }
                    $retainer->user_id        = $request->customer_id;
                    $retainer->issue_date     = $request->issue_date;
                    $retainer->due_date       = $request->due_date;
                    $retainer->account_type     = $request->account_type;
                    $retainer->retainer_module = 'account';
                    $retainer->category_id    = $request->category_id;
                    $retainer->retainer_template    = $request->retainer_template;
                    $retainer->save();
                    if (module_is_active('CustomField')) {
                        \Modules\CustomField\Entities\CustomField::saveData($retainer, $request->customField);
                    }
                    $products = $request->items;

                    for ($i = 0; $i < count($products); $i++) {
                        $retainerProduct = RetainerProduct::find($products[$i]['id']);
                        if ($retainerProduct == null) {
                            $retainerProduct              = new RetainerProduct();
                            $retainerProduct->retainer_id = $retainer->id;

                            $updatePrice = ($products[$i]['price'] * $products[$i]['quantity']) + ($products[$i]['itemTaxPrice']) - ($products[$i]['discount']);
                            RetainerUtility::updateUserBalance('customer', $retainer->customer_id, $updatePrice, 'credit');
                        }

                        if (isset($products[$i]['item'])) {
                            $retainerProduct->product_id = $products[$i]['item'];
                        }
                        $retainerProduct->product_type   = $products[$i]['product_type'];
                        $retainerProduct->quantity       = $products[$i]['quantity'];
                        $retainerProduct->tax            = $products[$i]['tax'];
                        $retainerProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                        $retainerProduct->price          = $products[$i]['price'];
                        $retainerProduct->description    = str_replace( array( '\'', '"', '`','{',"\n"), ' ', $products[$i]['description']);
                        $retainerProduct->save();
                    }
                    event(new UpdateRetainer($request, $retainer));
                    return redirect()->route('retainer.index')->with('success', __('Retainer successfully updated.'));
                } else if ($request->retainer_type == "project") {
                    $validator = \Validator::make(
                        $request->all(),
                        [
                            'customer_id' => 'required',
                            'issue_date' => 'required',
                            'project' => 'required',
                            'tax_project' => 'required',
                            'items' => 'required',

                        ]
                    );
                    if ($validator->fails()) {
                        $messages = $validator->getMessageBag();

                        return redirect()->back()->with('error', $messages->first());
                    }

                    if (module_is_active('Account')) {
                        $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
                        $retainer->customer_id    = !empty($customer) ?  $customer->id : null;
                    }
                    if ($request->retainer_type != $retainer->retainer_module) {
                        RetainerProduct::where('retainer_id', '=', $retainer->id)->delete();
                    }

                    $status = Retainer::$statues;
                    $retainer->retainer_id     = $this->retainerNumber();
                    $retainer->user_id        = $request->customer_id;
                    $retainer->issue_date     = $request->issue_date;
                    $retainer->account_type     = $request->account_type;
                    $retainer->category_id    = $request->project;
                    $retainer->retainer_module = 'taskly';
                    $retainer->discount_apply = 1;
                    $retainer->retainer_template    = $request->retainer_template;
                    $retainer->save();


                    $products = $request->items;

                    if (module_is_active('CustomField')) {
                        \Modules\CustomField\Entities\CustomField::saveData($retainer, $request->customField);
                    }
                    $project_tax = implode(',', $request->tax_project);
                    for ($i = 0; $i < count($products); $i++) {
                        $retainerProduct = RetainerProduct::find($products[$i]['id']);
                        if ($retainerProduct == null) {
                            $retainerProduct             = new RetainerProduct();
                            $retainerProduct->retainer_id = $retainer->id;
                        }
                        $retainerProduct->product_id  = $products[$i]['item'];
                        $retainerProduct->quantity    = 1;
                        $retainerProduct->tax         = $project_tax;
                        $retainerProduct->discount    = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                        $retainerProduct->price       = $products[$i]['price'];
                        $retainerProduct->description = $products[$i]['description'];
                        $retainerProduct->save();
                    }

                    if (module_is_active('CustomField')) {
                        \Modules\CustomField\Entities\CustomField::saveData($retainer, $request->customField);
                    }
                }
                return redirect()->route('retainer.index')->with('success', __('Retainer successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     *
     */


    public function destroy(Retainer $retainer)
    {
        if (Auth::user()->isAbleTo('retainer delete')) {
            if ($retainer->workspace == getActiveWorkSpace()) {
                if (module_is_active('Account')) {
                    foreach ($retainer->payments as $retainers) {
                        if (!empty($retainers->add_receipt)) {
                            try {
                                delete_file($retainers->add_receipt);
                            } catch (\Exception $e) {
                            }
                        }
                        event(new DestroyRetainer($retainer));
                        $retainers->delete();
                    }
                    if (!empty($retainer->user_id) && $retainer->user_id != 0) {
                        $customer = \Modules\Account\Entities\Customer::where('user_id', $retainer->user_id)->where('workspace', getActiveWorkSpace())->first();
                        if (!empty($customer)) {
                            RetainerUtility::updateUserBalance('customer', $customer->id, $retainer->getTotal(), 'debit');
                        }
                    }
                }
                $convertedRetainer = Retainer::where('converted_invoice_id', $retainer->id)->first();

                if (!empty($convertedRetainer)) {
                    $retainer->converted_invoice_id = Null;
                    $retainer->is_convert           = 0;
                    $retainer->save();
                }
                RetainerProduct::where('retainer_id', '=', $retainer->id)->delete();
                $retainer->delete();

                if (module_is_active('CustomField')) {
                    $customFields = \Modules\CustomField\Entities\CustomField::where('module', 'Retainer')->where('sub_module', 'Retainer')->get();
                    foreach ($customFields as $customField) {
                        $value = \Modules\CustomField\Entities\CustomFieldValue::where('record_id', '=', $retainer->id)->where('field_id', $customField->id)->first();
                        if (!empty($value)) {
                            $value->delete();
                        }
                    }
                }
                return redirect()->route('retainer.index')->with('success', __('Retainer successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    function retainerNumber()
    {
        $latest = company_setting('retainer_starting_number');
        if ($latest == null) {
            return 1;
        } else {
            return $latest;
        }
    }

    public function customer(Request $request)
    {
        if (module_is_active('Account')) {
            $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->id)->first();
            if (empty($customer)) {
                $user = User::find($request->id);
                $customer['name'] = !empty($user->name) ? $user->name : '';
                $customer['email'] = !empty($user->email) ? $user->email : '';
            }
        } else {
            $user = User::find($request->id);
            $customer['name'] = !empty($user->name) ? $user->name : '';
            $customer['email'] = !empty($user->email) ? $user->email : '';
        }

        return view('retainer::retainer.customer_detail', compact('customer'));
    }

    public function RetainerSectionGet(Request $request)
    {
        $type = $request->type;
        $acction = $request->acction;
        $retainer = [];
        if ($acction == 'edit') {
            $retainer = Retainer::find($request->retainer_id);
        }

        if ($request->type == "product" && module_is_active('Account')) {
            $product_services = \Modules\ProductService\Entities\ProductService::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
            $product_services_count = $product_services->count();
            $product_type = ProductService::$product_type;

            $returnHTML = view('retainer::retainer.section', compact('product_services', 'type', 'acction', 'retainer', 'product_services_count', 'product_type'))->render();
            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
            ];
            return response()->json($response);
        } elseif ($request->type == "project" && module_is_active('Taskly')) {
            $projects = \Modules\Taskly\Entities\Project::where('workspace', getActiveWorkSpace())->projectonly();
            if ($request->project_id != 0) {
                $projects = $projects->where('id', $request->project_id);
            }
            $projects = $projects->first();
            $tasks = [];
            if (!empty($projects)) {
                $tasks = \Modules\Taskly\Entities\Task::where('project_id', $projects->id)->get()->pluck('title', 'id');
                if ($acction != 'edit') {
                    $tasks->prepend('--', '');
                }
            }
            $returnHTML = view('retainer::retainer.section', compact('tasks', 'type', 'acction', 'retainer'))->render();
            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
            ];
            return response()->json($response);
        } else {
            return [];
        }
    }

    public function TaxDetailGet(Request $request)
    {

        $taxs_data = [];
        if (module_is_active('ProductService')) {
            $taxs_data = \Modules\ProductService\Entities\Tax::whereIn('id', !empty($request->Taxid) ? $request->Taxid : [])->where('workspace_id', getActiveWorkSpace())->get();
        }
        return $taxs_data;
    }

    public function product(Request $request)
    {
        $data['product']     = $product = \Modules\ProductService\Entities\ProductService::find($request->product_id);
        $data['unit']        = !empty($product) ? ((!empty($product->unit())) ? $product->unit()->name : '') : '';
        $data['taxRate']     = $taxRate = !empty($product) ? (!empty($product->tax_id) ? $product->taxRate($product->tax_id) : 0) : 0;
        $data['taxes']       = !empty($product) ? (!empty($product->tax_id) ? $product->tax($product->tax_id) : 0) : 0;
        $salePrice           = !empty($product) ?  $product->sale_price : 0;
        $quantity            = 1;
        $taxPrice            = !empty($product) ? (($taxRate / 100) * ($salePrice * $quantity)) : 0;
        $data['totalAmount'] = !empty($product) ?  ($salePrice * $quantity) : 0;
        return json_encode($data);
    }

    public function sent($id)
    {
        if (Auth::user()->isAbleTo('retainer send')) {
            $retainer            = Retainer::where('id', $id)->first();
            $retainer->send_date = date('Y-m-d');
            $retainer->status    = 1;
            $retainer->save();
            if (module_is_active('Account')) {
                $customer         = \Modules\Account\Entities\Customer::where('user_id', $retainer->user_id)->first();
                if (empty($customer)) {
                    $customer         = User::where('id', $retainer->user_id)->first();
                }
                RetainerUtility::updateUserBalance('customer', $customer->id, $retainer->getTotal(), 'credit');
            } else {
                $customer         = User::where('id', $retainer->user_id)->first();
            }
            $retainer->name    = !empty($customer) ? $customer->name : '';
            $retainer->invoice = Invoice::invoiceNumberFormat($retainer->invoice_id);

            $retainerId    = Crypt::encrypt($retainer->id);
            $retainer->url = route('retainer.pdf', $retainerId);
            event(new SentRetainer($retainer));
            //Email notification
            if (!empty(company_setting('Retainer Send')) && company_setting('Retainer Send')  == true) {
                $uArr = [
                    'retainer_name' => $retainer->name,
                    'retainer_number' => $retainer->retainer,
                    'retainer_url' => $retainer->url,
                ];

                try {
                    $resp = EmailTemplate::sendEmailTemplate('Retainer Send', [$customer->id => $customer->email], $uArr);
                } catch (\Exception $e) {
                    $resp['error'] = $e->getMessage();
                }

                return redirect()->back()->with('success', __('Retainer successfully sent.') . ((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }
            return redirect()->back()->with('success', 'Retainer sent email notification is off.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function payment($retainer_id)
    {

        if (Auth::user()->isAbleTo('retainer payment create')) {
            $retainer = Retainer::where('id', $retainer_id)->first();
            if ($retainer) {
                if (module_is_active('Account')) {
                    $accounts   = \Modules\Account\Entities\BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                } else {
                    $accounts = [];
                }

                return view('retainer::retainer.payment', compact('accounts', 'retainer'));
            } else {
                return response()->json(['error' => __('oops, something went wrong.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function createPayment(Request $request, $retainer_id)
    {
        if (Auth::user()->isAbleTo('retainer payment create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'date' => 'required',
                    'amount' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $retainerPayment                 = new RetainerPayment();

            if (module_is_active('Account')) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'account_id' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                $retainerPayment->account_id     = $request->account_id;
            }
            $retainerPayment->retainer_id     = $retainer_id;
            $retainerPayment->date           = $request->date;
            $retainerPayment->amount         = $request->amount;
            $retainerPayment->payment_method = 0;
            $retainerPayment->reference      = $request->reference;
            $retainerPayment->description    = $request->description;
            if (!empty($request->add_receipt)) {
                $fileName = time() . "_" . $request->add_receipt->getClientOriginalName();
                $uplaod = upload_file($request, 'add_receipt', $fileName, 'payment');
                if ($uplaod['flag'] == 1) {
                    $url = $uplaod['url'];
                } else {
                    return redirect()->back()->with('error', $uplaod['msg']);
                }
                $retainerPayment->add_receipt = $url;
            }
            $retainerPayment->save();

            $retainer = Retainer::where('id', $retainer_id)->first();
            $due     = $retainer->getDue();
            $total   = $retainer->getTotal();
            if ($retainer->status == 0) {
                $retainer->send_date = date('Y-m-d');
                $retainer->save();
            }
            if ($due <= 0) {
                $retainer->status = 4;
                $retainer->save();
            } else {
                $retainer->status = 3;
                $retainer->save();
            }
            $retainerPayment->user_id    = $retainer->user_id;
            $retainerPayment->user_type  = 'Customer';
            $retainerPayment->type       = 'Partial';
            $retainerPayment->created_by = \Auth::user()->id;
            $retainerPayment->payment_id = $retainerPayment->id;
            $retainerPayment->category   = 'Retainer';
            $retainerPayment->account    = $request->account_id;


            $customer = User::where('id', $retainer->user_id)->first();



            if (module_is_active('Account')) {
                \Modules\Account\Entities\Transaction::addTransaction($retainerPayment);
                $customer_acc =  \Modules\Account\Entities\Customer::where('id', $retainer->customer_id)->first();

                if (empty($customer_acc)) {
                    $customer = $customer_acc;
                }
                RetainerUtility::updateUserBalance('customer', $retainer->customer_id, $request->amount, 'debit');

                \Modules\Account\Entities\Transfer::bankAccountBalance($request->account_id, $request->amount, 'credit');
            }
            $payment            = new RetainerPayment();
            $payment->name      = $customer['name'];
            $payment->date      = company_date_formate($request->date);
            $payment->amount    = currency_format_with_sym($request->amount);
            $payment->retainer   = 'retainer ' . Retainer::retainerNumberFormat($retainer->retainer_id);
            $payment->dueAmount = currency_format_with_sym($retainer->getDue());

            event(new CreatePaymentRetainer($request, $retainer));


            //Email notification
            if (!empty(company_setting('Retainer Payment Create')) && company_setting('Retainer Payment Create')  == true) {
                $uArr = [
                    'payment_name' => $payment->name,
                    'payment_amount' => $payment->amount,
                    'retainer_number' => $payment->retainer,
                    'payment_date' => $payment->date,
                    'payment_dueAmount' => $payment->dueAmount
                ];


                try {
                    $resp = EmailTemplate::sendEmailTemplate('Retainer Payment Create', [$customer->id => $customer->email], $uArr);
                } catch (\Exception $e) {
                    $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                }
            }
            return redirect()->back()->with('success', __('Payment successfully addedxfdsf.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        }
    }

    public function paymentDestroy($retainer_id, $payment_id)
    {
        if (Auth::user()->isAbleTo('retainer payment delete')) {
            $payment = RetainerPayment::find($payment_id);
            if (!empty($payment->add_receipt)) {
                try {
                    delete_file($payment->add_receipt);
                } catch (\Exception $e) {
                }
            }
            $retainer = Retainer::where('id', $retainer_id)->first();
            $due     = $retainer->getDue();
            $total   = $retainer->getTotal();

            if ($due > 0 && $total != $due) {
                $retainer->status = 3;
            } else {
                $retainer->status = 2;
            }

            $retainer->save();

            if (module_is_active('Account')) {
                $type = 'Partial';
                $user = 'Customer';

                \Modules\Account\Entities\Transaction::destroyTransaction($payment_id, $type, $user);

                RetainerUtility::updateUserBalance('customer', $retainer->customer_id, $payment->amount, 'credit');

                \Modules\Account\Entities\Transfer::bankAccountBalance($payment->account_id, $payment->amount, 'debit');
            }
            event(new PaymentDestroyRetainer($payment));

            $payment->delete();
            return redirect()->back()->with('success', __('Payment successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }



    public function productDestroy(Request $request)
    {
        if (Auth::user()->isAbleTo('retainer product delete')) {
            RetainerProduct::where('id', '=', $request->id)->delete();

            return response()->json(['success' => __('Retainer product successfully deleted.')]);
        } else {
            return response()->json(['error' => __('Permission denied.')]);
        }
    }

    public function getTax(Request $request)
    {

        if (module_is_active('ProductService')) {
            $taxs_data = \Modules\ProductService\Entities\Tax::whereIn('id', $request->tax_id)->where('workspace_id', getActiveWorkSpace())->get();
            return json_encode($taxs_data);
        } else {
            $taxs_data = [];
            return json_encode($taxs_data);
        }
    }

    public function convert($retainer_id)
    {

        if (\Auth::user()->isAbleTo('retainer convert invoice')) {
            $retainer             = Retainer::where('id', $retainer_id)->first();
            $retainer->is_convert = 1;
            $convertInvoice                      = new Invoice();

            if($retainer->account_type == 'Projects')
            {
                $account_type = 'Taskly';
            }
            else
            {
                $account_type = 'Account';
            }
            if (module_is_active('Account')) {
                $customer = \Modules\Account\Entities\Customer::where('user_id', $retainer['customer_id'])->first();
                $convertInvoice->customer_id    = !empty($customer) ?  $customer->id : null;
            }
            $convertInvoice->invoice_id          = $this->invoiceNumber();
            $convertInvoice->user_id             = $retainer['user_id'];
            $convertInvoice->account_type        = $account_type;
            $convertInvoice->issue_date          = date('Y-m-d');
            $convertInvoice->due_date            = date('Y-m-d');
            $convertInvoice->send_date           = null;
            $convertInvoice->category_id         = $retainer['category_id'];
            $convertInvoice->status              = 0;
            $convertInvoice->invoice_module      = $retainer['retainer_module'];
            $convertInvoice->invoice_template    = $retainer['retainer_template'];
            $convertInvoice->workspace           = $retainer['workspace'];
            $convertInvoice->created_by          = $retainer['created_by'];
            $convertInvoice->save();
            Invoice::starting_number($convertInvoice->invoice_id + 1, 'invoice');
            $retainer->converted_invoice_id = $convertInvoice->id;
            $retainer->save();

            if ($convertInvoice) {
                $retainerProduct = RetainerProduct::where('retainer_id', $retainer_id)->get();
                foreach ($retainerProduct as $product) {
                    $duplicateProduct                   = new InvoiceProduct();
                    $duplicateProduct->invoice_id       = $convertInvoice->id;
                    $duplicateProduct->product_type     = $product->product_type;
                    $duplicateProduct->product_id       = $product->product_id;
                    $duplicateProduct->quantity         = $product->quantity;
                    $duplicateProduct->tax              = $product->tax;
                    $duplicateProduct->discount         = $product->discount;
                    $duplicateProduct->price            = $product->price;
                    $duplicateProduct->save();

                    //inventory management (Quantity)
                    if ($retainer['retainer_module'] == 'account') {
                        Invoice::total_quantity('minus', $duplicateProduct->quantity, $duplicateProduct->product_id);
                    }

                    //Product Stock Report
                    if (module_is_active('Account')) {
                        $type = 'invoice';
                        $type_id = $convertInvoice->id;
                        \Modules\Account\Entities\StockReport::where('type', '=', 'invoice')->where('type_id', '=', $convertInvoice->id)->delete();
                        $description = $duplicateProduct->quantity . '' . __(' quantity sold in') . ' ' . Retainer::retainerNumberFormat($retainer->retainer_id) . ' ' . __('Retainer convert to invoice') . ' ' . Invoice::invoiceNumberFormat($convertInvoice->invoice_id);
                        \Modules\Account\Entities\AccountUtility::addProductStock($duplicateProduct->product_id, $duplicateProduct->quantity, $type, $description, $type_id);
                    }
                }
            }
            if ($convertInvoice) {
                $retainerPayment = RetainerPayment::where('retainer_id', $retainer_id)->get();
                foreach ($retainerPayment as $payment) {
                    $duplicatePayment                   = new InvoicePayment();
                    $duplicatePayment->invoice_id       = $convertInvoice->id;
                    $duplicatePayment->date             = $payment->date;
                    $duplicatePayment->amount           = $payment->amount;
                    $duplicatePayment->account_id       = $payment->account_id;
                    $duplicatePayment->payment_method   = $payment->payment_method;
                    $duplicatePayment->receipt          = $payment->receipt;
                    $duplicatePayment->payment_type     = $payment->payment_type;
                    $duplicatePayment->reference        = $payment->reference;
                    $duplicatePayment->description      = 'Payment by Retainer' . Retainer::retainerNumberFormat($retainer->retainer_id);
                    $duplicatePayment->save();
                }
            }

            event(new RetainerConvertToInvoice($convertInvoice));

            return redirect()->back()->with('success', __('Retainer to invoice convert successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function proposal_convert($proposal_id)
    {
        if (\Auth::user()->isAbleTo('retainer convert invoice')) {
            $proposal             = Proposal::where('id', $proposal_id)->first();
            $proposal->is_convert_retainer = 1;
            $proposal->save();

            $convertRetainer              = new Retainer();
            $convertRetainer->retainer_id  = $this->retainerNumber();
            $convertRetainer->user_id = $proposal['customer_id'];


            $convertRetainer->issue_date  = date('Y-m-d');
            $convertRetainer->due_date    = date('Y-m-d');
            $convertRetainer->send_date   = null;
            $convertRetainer->category_id = $proposal['category_id'];
            $convertRetainer->status      = 0;
            $convertRetainer->retainer_module      = $proposal['proposal_module'];
            $convertRetainer->workspace           = $proposal['workspace'];
            $convertRetainer->created_by  = $proposal['created_by'];
            $convertRetainer->save();

            Invoice::starting_number($convertRetainer->retainer_id + 1, 'retainer');
            $proposal->converted_retainer_id = $convertRetainer->id;
            $proposal->save();

            if ($convertRetainer) {
                $proposalProduct = ProposalProduct::where('proposal_id', $proposal_id)->get();
                foreach ($proposalProduct as $product) {
                    $duplicateProduct                   = new RetainerProduct();
                    $duplicateProduct->product_type     = $product->product_type;
                    $duplicateProduct->retainer_id      = $convertRetainer->id;
                    $duplicateProduct->product_id       = $product->product_id;
                    $duplicateProduct->quantity         = $product->quantity;
                    $duplicateProduct->tax              = $product->tax;
                    $duplicateProduct->discount         = $product->discount;
                    $duplicateProduct->price            = $product->price;
                    $duplicateProduct->save();


                    if ($convertRetainer['retainer_module'] == 'account') {
                        Invoice::total_quantity('minus', $duplicateProduct->quantity, $duplicateProduct->product_id);
                    }




                    //Product Stock Report
                    if (module_is_active('Account')) {
                        $type = 'Retainer';
                        $type_id = $convertRetainer->id;
                        $description = $duplicateProduct->quantity . '  ' . __(' quantity sold in invoice') . ' ' . Retainer::retainerNumberFormat($convertRetainer->retainer_id);
                        \Modules\Account\Entities\AccountUtility::addProductStock($duplicateProduct->product_id, $duplicateProduct->quantity, $type, $description, $type_id);
                    }
                }
            }

            return redirect()->back()->with('success', __('Proposal to Retainer convert successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    function invoiceNumber()
    {
        $latest = company_setting('invoice_starting_number');
        if ($latest == null) {
            return 1;
        } else {
            return $latest;
        }
    }

    public function duplicate($retainer_id)
    {
        if (Auth::user()->isAbleTo('retainer duplicate')) {
            $retainer                            = Retainer::where('id', $retainer_id)->first();
            $duplicateRetainer                   = new Retainer();
            $duplicateRetainer->retainer_id       = $this->retainerNumber();
            $duplicateRetainer->customer_id      = $retainer['customer_id'];
            $duplicateRetainer->user_id          = $retainer['user_id'];
            $duplicateRetainer->issue_date       = date('Y-m-d');
            $duplicateRetainer->due_date         = $retainer['due_date'];
            $duplicateRetainer->send_date        = null;
            $duplicateRetainer->category_id      = $retainer['category_id'];
            $duplicateRetainer->status           = 0;
            $duplicateRetainer->retainer_module   = $retainer['retainer_module'];
            $duplicateRetainer->retainer_template = $retainer['retainer_template'];
            $duplicateRetainer->account_type     = $retainer['account_type'];
            $duplicateRetainer->workspace        = $retainer['workspace'];
            $duplicateRetainer->created_by       = $retainer['created_by'];
            $duplicateRetainer->save();
            Invoice::starting_number($duplicateRetainer->retainer_id + 1, 'retainer');

            if ($duplicateRetainer) {
                $retainerProduct = RetainerProduct::where('retainer_id', $retainer_id)->get();
                foreach ($retainerProduct as $product) {
                    $duplicateProduct                   = new RetainerProduct();
                    $duplicateProduct->retainer_id      = $duplicateRetainer->id;
                    $duplicateProduct->product_type     = $product->product_type;
                    $duplicateProduct->product_id       = $product->product_id;
                    $duplicateProduct->quantity         = $product->quantity;
                    $duplicateProduct->tax              = $product->tax;
                    $duplicateProduct->discount         = $product->discount;
                    $duplicateProduct->price            = $product->price;
                    $duplicateProduct->description      = $product->description;
                    $duplicateProduct->save();
                }
            }
            event(new RetainerDuplicate($duplicateRetainer));
            return redirect()->back()->with('success', __('Retainer duplicate successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }




    public function resent($id)
    {
        if (Auth::user()->isAbleTo('retainer send')) {
            $retainer = Retainer::where('id', $id)->first();

            if (module_is_active('Account')) {
                $customer         = \Modules\Account\Entities\Customer::where('user_id', $retainer->user_id)->first();
                if (empty($customer)) {
                    $customer         = User::where('id', $retainer->user_id)->first();
                }
            } else {
                $customer         = User::where('id', $retainer->user_id)->first();
            }

            $retainer->name     = !empty($customer) ? $customer->name : '';
            $retainer->retainer = Retainer::retainerNumberFormat($retainer->retainer_id);

            $retainerId    = Crypt::encrypt($retainer->id);
            $retainer->url = route('retainer.pdf', $retainerId);

            if (!empty(company_setting('Retainer Send')) && company_setting('Retainer Send')  == true) {
                $uArr = [
                    'retainer_name' => $retainer->name,
                    'retainer_number' => $retainer->retainer,
                    'retainer_url' => $retainer->url,
                ];
                try {
                    $resp = EmailTemplate::sendEmailTemplate('Retainer Send', [$customer->id => $customer->email], $uArr);
                } catch (\Exception $e) {
                    $resp['error'] = $e->getMessage();
                }
                return redirect()->back()->with('success', __('Retainer successfully sent.') . ((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }
            event(new ResentRetainer($retainer));

            return redirect()->back()->with('success', 'Retainer sent email notification is off.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function retainer($retainer_id)
    {
        try {
            $retainerId = Crypt::decrypt($retainer_id);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Retainer Not Found.'));
        }

        $retainer   = Retainer::where('id', $retainerId)->first();
        if (module_is_active('Account')) {
            $customer         = \Modules\Account\Entities\Customer::where('user_id', $retainer->user_id)->first();
        } else {
            $customer         = User::where('id', $retainer->user_id)->first();
        }
        $items         = [];
        $totalTaxPrice = 0;
        $totalQuantity = 0;
        $totalRate     = 0;
        $totalDiscount = 0;
        $taxesData     = [];
        foreach ($retainer->items as $product) {
            $item              = new \stdClass();
            $item->name        = !empty($product->product()) ? $product->product()->name : '';

            if ($retainer->retainer_module == "taskly") {
                $item->name        = !empty($product->product()) ? $product->product()->title : '';
            } elseif ($retainer->retainer_module == "account") {
                $item->name        = !empty($product->product()) ? $product->product()->name : '';
                $item->product_type   = !empty($product->product_type) ? $product->product_type : '';
            }

            $item->quantity    = $product->quantity;
            $item->tax         = $product->tax;
            $item->discount    = $product->discount;
            $item->price       = $product->price;
            $item->description = $product->description;
            $totalQuantity += $item->quantity;
            $totalRate     += $item->price;
            $totalDiscount += $item->discount;
            if (module_is_active('ProductService')) {
                $taxes = \Modules\ProductService\Entities\Tax::tax($product->tax);
                $itemTaxes = [];
                if (!empty($item->tax)) {
                    $tax_price = 0;
                    foreach ($taxes as $tax) {
                        $taxPrice      = Retainer::taxRate($tax->rate, $item->price, $item->quantity, $item->discount);
                        $tax_price  += $taxPrice;
                        $totalTaxPrice += $taxPrice;

                        $itemTax['name']  = $tax->name;
                        $itemTax['rate']  = $tax->rate . '%';
                        $itemTax['price'] = currency_format_with_sym($taxPrice, $retainer->created_by);
                        $itemTaxes[]      = $itemTax;


                        if (array_key_exists($tax->name, $taxesData)) {
                            $taxesData[$tax->name] = $taxesData[$tax->name] + $taxPrice;
                        } else {
                            $taxesData[$tax->name] = $taxPrice;
                        }
                    }
                    $item->itemTax = $itemTaxes;
                    $item->tax_price = $tax_price;
                } else {
                    $item->itemTax = [];
                }
                $items[] = $item;
            }
        }
        $retainer->itemData      = $items;
        $retainer->totalTaxPrice = $totalTaxPrice;
        $retainer->totalQuantity = $totalQuantity;
        $retainer->totalRate     = $totalRate;
        $retainer->totalDiscount = $totalDiscount;
        $retainer->taxesData     = $taxesData;
        if (module_is_active('CustomField')) {
            $retainer->customField = \Modules\CustomField\Entities\CustomField::getData($retainer, 'Retainer', 'Retainer');
            $customFields             = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', $retainer->workspace)->where('module', '=', 'Retainer')->where('sub_module', 'Retainer')->get();
        } else {
            $customFields = null;
        }

        //Set your logo
        $company_logo = get_file(sidebar_logo());
        $company_settings = getCompanyAllSetting($retainer->created_by,$retainer->workspace);
        $retainer_logo = isset($company_settings['retainer_logo']) ? $company_settings['retainer_logo'] : '';
        if (isset($retainer_logo) && !empty($retainer_logo)) {
            $img  = get_file($retainer_logo);
        } else {
            $img  = $company_logo;
        }

        if ($retainer) {
            $color      = '#'.(isset($company_settings['retainer_color']) ? $company_settings['retainer_color'] : 'ffffff');

            $font_color = User::getFontColor($color);


            if(!empty($retainer->retainer_template))
            {
                $retainer_template = $retainer->retainer_template;
            }
            else{
                $retainer_template  = isset($company_settings['retainer_template']) ? $company_settings['retainer_template'] : 'template1';
            }
            $settings['site_rtl'] = isset($company_settings['site_rtl']) ? $company_settings['site_rtl'] : '';
            $settings['company_name'] = isset($company_settings['company_name']) ? $company_settings['company_name'] : '';
            $settings['company_email'] = isset($company_settings['company_email']) ? $company_settings['company_email'] : '';
            $settings['company_telephone'] = isset($company_settings['company_telephone']) ? $company_settings['company_telephone'] : '';
            $settings['company_address'] = isset($company_settings['company_address']) ? $company_settings['company_address'] : '';
            $settings['company_city'] = isset($company_settings['company_city']) ? $company_settings['company_city'] : '';
            $settings['company_state'] = isset($company_settings['company_state']) ? $company_settings['company_state'] : '';
            $settings['company_zipcode'] = isset($company_settings['company_zipcode']) ? $company_settings['company_zipcode'] : '';
            $settings['company_country'] = isset($company_settings['company_country']) ? $company_settings['company_country'] : '';
            $settings['registration_number'] = isset($company_settings['registration_number']) ? $company_settings['registration_number'] : '';
            $settings['tax_type'] = isset($company_settings['tax_type']) ? $company_settings['tax_type'] : '';
            $settings['vat_number'] = isset($company_settings['vat_number']) ? $company_settings['vat_number'] : '';
            $settings['footer_title'] = isset($company_settings['retainer_footer_title']) ? $company_settings['retainer_footer_title'] : '';
            $settings['footer_notes'] = isset($company_settings['retainer_footer_notes']) ? $company_settings['retainer_footer_notes'] : '';
            $settings['shipping_display'] = isset($company_settings['retainer_shipping_display']) ? $company_settings['retainer_shipping_display'] : '';
            $settings['retainer_template'] = isset($company_settings['retainer_template']) ? $company_settings['retainer_template'] : '';
            $settings['retainer_color'] = isset($company_settings['retainer_color']) ? $company_settings['retainer_color'] : '';

            return view('retainer::retainer.templates.' . $retainer_template, compact('retainer', 'color', 'settings', 'customer', 'img', 'font_color', 'customFields'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function previewRetainer($template, $color)
    {

        $objUser  = \Auth::user();
        $retainer     = new Retainer();

        $customer                   = new \stdClass();
        $customer->email            = '<Email>';
        $customer->shipping_name    = '<Customer Name>';
        $customer->shipping_country = '<Country>';
        $customer->shipping_state   = '<State>';
        $customer->shipping_city    = '<City>';
        $customer->shipping_phone   = '<Customer Phone Number>';
        $customer->shipping_zip     = '<Zip>';
        $customer->shipping_address = '<Address>';
        $customer->billing_name     = '<Customer Name>';
        $customer->billing_country  = '<Country>';
        $customer->billing_state    = '<State>';
        $customer->billing_city     = '<City>';
        $customer->billing_phone    = '<Customer Phone Number>';
        $customer->billing_zip      = '<Zip>';
        $customer->billing_address  = '<Address>';
        $customer->sku              = 'Test123';

        $totalTaxPrice = 0;
        $taxesData     = [];
        $items         = [];
        for ($i = 1; $i <= 3; $i++) {
            $item           = new \stdClass();
            $item->name     = 'Item ' . $i;
            $item->quantity = 1;
            $item->tax      = 5;
            $item->discount = 50;
            $item->price    = 100;
            $item->description    = 'In publishing and graphic design, Lorem ipsum is a placeholder';


            $taxes = [
                'Tax 1',
                'Tax 2',
            ];

            $itemTaxes = [];
            foreach ($taxes as $k => $tax) {
                $taxPrice         = 10;
                $totalTaxPrice    += $taxPrice;
                $itemTax['name']  = 'Tax ' . $k;
                $itemTax['rate']  = '10 %';
                $itemTax['price'] = '$10';

                $itemTaxes[]      = $itemTax;
                if (array_key_exists('Tax ' . $k, $taxesData)) {
                    $taxesData['Tax ' . $k] = $taxesData['Tax 1'] + $taxPrice;
                } else {
                    $taxesData['Tax ' . $k] = $taxPrice;
                }
            }
            $item->itemTax = $itemTaxes;
            $item->tax_price = 10;
            $items[]       = $item;
        }

        $retainer->retainer_id    = 1;
        $retainer->issue_date = date('Y-m-d H:i:s');
        $retainer->due_date   = date('Y-m-d H:i:s');
        $retainer->itemData   = $items;

        $retainer->totalTaxPrice = 60;
        $retainer->totalQuantity = 3;
        $retainer->totalRate     = 300;
        $retainer->totalDiscount = 10;
        $retainer->taxesData     = $taxesData;
        $retainer->customField   = [];
        $customFields        = [];

        $preview      = 1;
        $color        = '#' . $color;

        $font_color   = RetainerUtility::getFontColor($color);

        $company_logo = get_file(sidebar_logo());
        $company_settings = getCompanyAllSetting($retainer->created_by,$retainer->workspace);

        $retainer_logo = isset($company_settings['retainer_logo']) ? $company_settings['retainer_logo'] : '';


        if (isset($retainer_logo) && !empty($retainer_logo)) {
            $img = get_file($retainer_logo);
        } else {
            $img          =  $company_logo;
        }


        $settings['company_name'] = isset($company_settings['company_name']) ? $company_settings['company_name'] : '';
        $settings['company_email'] = isset($company_settings['company_email']) ? $company_settings['company_email'] : '';
        $settings['company_telephone'] = isset($company_settings['company_telephone'])? $company_settings['company_telephone'] : '';
        $settings['company_address'] = isset($company_settings['company_address'])? $company_settings['company_address'] : '';
        $settings['company_city'] = isset($company_settings['company_city'])? $company_settings['company_city'] : '';
        $settings['company_state'] = isset($company_settings['company_state'])? $company_settings['company_state'] : '';
        $settings['company_zipcode'] = isset($company_settings['company_zipcode'])? $company_settings['company_zipcode'] : '';
        $settings['company_country'] = isset($company_settings['company_country'])? $company_settings['company_country'] : '';
        $settings['registration_number'] = isset($company_settings['registration_number'])? $company_settings['registration_number'] : '';
        $settings['tax_type'] = isset($company_settings['tax_type'])? $company_settings['tax_type'] : '';
        $settings['vat_number'] = isset($company_settings['vat_number'])? $company_settings['vat_number'] : '';
        $settings['retainer_footer_title'] = isset($company_settings['retainer_footer_title'])? $company_settings['retainer_footer_title'] : '';
        $settings['retainer_footer_notes'] = isset($company_settings['retainer_footer_notes'])? $company_settings['retainer_footer_notes'] : '';
        $settings['retainer_shipping_display'] = isset($company_settings['retainer_shipping_display'])? $company_settings['retainer_shipping_display'] : '';
        return view('retainer::retainer.templates.' . $template, compact('retainer', 'preview', 'color', 'settings', 'img', 'customer', 'font_color', 'customFields'));
    }

    public function saveRetainerTemplateSettings(Request $request)
    {
        $user = \Auth::user();
        $validator = \Validator::make(
            $request->all(),
            [
                'retainer_template' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }


        if ($request->hasFile('retainer_logo')) {
            $retainer_logo         = $user->id . '_retainer_logo' . time() . '.png';

            $uplaod = upload_file($request, 'retainer_logo', $retainer_logo, 'retainer_logo');
            if ($uplaod['flag'] == 1) {
                $url = $uplaod['url'];
                $old_retainer_logo = company_setting('retainer_logo');
                if (!empty($old_retainer_logo) && check_file($old_retainer_logo)) {
                    delete_file($old_retainer_logo);
                }
            } else {
                return redirect()->back()->with('error', $uplaod['msg']);
            }
        }
        $post = $request->all();
        unset($post['_token']);

        if (isset($post['retainer_template']) && (!isset($post['retainer_color']) || empty($post['retainer_color']))) {
            $post['retainer_color'] = "ffffff";
        }

        foreach ($post as $key => $value) {
            // Define the data to be updated or inserted
            $data = [
                'key' => $key,
                'workspace' => getActiveWorkSpace(),
                'created_by' => Auth::user()->id,
            ];
            // Check if the record exists, and update or insert accordingly
            Setting::updateOrInsert($data, ['value' => $value]);
        }
        // Settings Cache forget
        comapnySettingCacheForget();

        return redirect()->back()->with('success', 'Retainer Print setting save sucessfully.');
    }

    public function grid(Request $request)
    {
        if (Auth::user()->isAbleTo('retainer manage')) {
            $customer = User::where('workspace_id', '=', getActiveWorkSpace())->where('type', 'Client')->get()->pluck('name', 'id');
            $status = Retainer::$statues;

            $query = Retainer::where('workspace', '=', getActiveWorkSpace());
            if (!empty($request->customer)) {

                $query->where('user_id', '=', $request->customer);
            }
            if (!empty($request->issue_date)) {
                $date_range = explode('to', $request->issue_date);
                if (count($date_range) == 2) {
                    $query->whereBetween('issue_date', $date_range);
                } else {
                    $query->where('issue_date', $date_range[0]);
                }
            }
            if (!empty($request->status)) {
                $query->where('status', $request->status);
            }
            $retainers = $query->get();


            return view('retainer::retainer.grid', compact('retainers', 'customer', 'status'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function items(Request $request)
    {
        $items = RetainerProduct::where('retainer_id', $request->retainer_id)->where('product_id', $request->product_id)->first();
        return json_encode($items);
    }

    public function payretainer($retainer_id)
    {
        if (!empty($retainer_id)) {
            try {
                $id = \Illuminate\Support\Facades\Crypt::decrypt($retainer_id);
            } catch (\Throwable $th) {
                return redirect('login');
            }

            $retainer = Retainer::where('id', $id)->first();

            if (!is_null($retainer)) {

                $items         = [];
                $totalTaxPrice = 0;
                $totalQuantity = 0;
                $totalRate     = 0;
                $totalDiscount = 0;
                $taxesData     = [];

                foreach ($retainer->items as $item) {
                    $totalQuantity += $item->quantity;
                    $totalRate     += $item->price;
                    $totalDiscount += $item->discount;
                    $taxes         = Retainer::tax($item->tax);

                    $itemTaxes = [];
                    foreach ($taxes as $tax) {
                        if (!empty($tax)) {
                            $taxPrice            = Retainer::taxRate($tax->rate, $item->price, $item->quantity, $item->discount);
                            $totalTaxPrice       += $taxPrice;
                            $itemTax['tax_name'] = $tax->tax_name;
                            $itemTax['tax']      = $tax->rate . '%';
                            $itemTax['price']    = currency_format_with_sym($taxPrice, $retainer->created_by);
                            $itemTaxes[]         = $itemTax;

                            if (array_key_exists($tax->name, $taxesData)) {
                                $taxesData[$itemTax['tax_name']] = $taxesData[$tax->tax_name] + $taxPrice;
                            } else {
                                $taxesData[$tax->tax_name] = $taxPrice;
                            }
                        } else {
                            $taxPrice            = Retainer::taxRate(0, $item->price, $item->quantity, $item->discount);
                            $totalTaxPrice       += $taxPrice;
                            $itemTax['tax_name'] = 'No Tax';
                            $itemTax['tax']      = '';
                            $itemTax['price']    = currency_format_with_sym($taxPrice, $retainer->created_by);
                            $itemTaxes[]         = $itemTax;

                            if (array_key_exists('No Tax', $taxesData)) {
                                $taxesData[$tax->tax_name] = $taxesData['No Tax'] + $taxPrice;
                            } else {
                                $taxesData['No Tax'] = $taxPrice;
                            }
                        }
                    }

                    $item->itemTax = $itemTaxes;
                    $items[]       = $item;
                }
                $retainer->items         = $items;
                $retainer->totalTaxPrice = $totalTaxPrice;
                $retainer->totalQuantity = $totalQuantity;
                $retainer->totalRate     = $totalRate;
                $retainer->totalDiscount = $totalDiscount;
                $retainer->taxesData     = $taxesData;
                $ownerId = $retainer->created_by;

                $users = User::where('id', $retainer->created_by)->first();

                if (!is_null($users)) {
                    \App::setLocale($users->lang);
                } else {
                    \App::setLocale('en');
                }

                $retainer    = Retainer::where('id', $id)->first();
                $customer = $retainer->customer;
                $iteams   = $retainer->items;

                $company_payment_setting = [];

                if (module_is_active('Account')) {
                    $customer = \Modules\Account\Entities\Customer::where('user_id', $retainer->user_id)->first();
                } else {
                    $customer = $retainer->customer;
                }
                if (module_is_active('CustomField')) {

                    $retainer->customField = \Modules\CustomField\Entities\CustomField::getData($retainer, 'Retainer', 'Retainer');
                    $customFields             = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', $retainer->workspace)->where('module', '=', 'Retainer')->where('sub_module', 'Retainer')->get();
                } else {
                    $customFields = null;
                }
                $company_id = $retainer->created_by;
                $workspace_id = $retainer->workspace;
                return view('retainer::retainer.retainerpay', compact('retainer', 'iteams', 'customer', 'users', 'company_payment_setting', 'company_id', 'workspace_id', 'customFields'));
            } else {
                return abort('404', 'The Link You Followed Has Expired');
            }
        } else {
            return abort('404', 'The Link You Followed Has Expired');
        }
    }

    public function retainerAttechment(Request $request, $id)
    {
        $retainer = Retainer::find($id);
        $file_name = time() . "_" . $request->file->getClientOriginalName();

        $upload = upload_file($request, 'file', $file_name, 'retainer_attachment', []);

        $fileSizeInBytes = \File::size($upload['url']);
        $fileSizeInKB = round($fileSizeInBytes / 1024, 2);

        if ($fileSizeInKB < 1024) {
            $fileSizeFormatted = $fileSizeInKB . " KB";
        } else {
            $fileSizeInMB = round($fileSizeInKB / 1024, 2);
            $fileSizeFormatted = $fileSizeInMB . " MB";
        }

        if ($upload['flag'] == 1) {
            $file                 = RetainerAttechment::create(
                [
                    'retainer_id' => $retainer->id,
                    'file_name'   => $file_name,
                    'file_path'   => $upload['url'],
                    'file_size'   => $fileSizeFormatted,
                ]
            );
            $return               = [];
            $return['is_success'] = true;
            $return['download']   = get_file($file->file_path);
            $return['delete']     = route(
                'retainer.attachment.destroy',
                [
                    $retainer->id,
                ]
            );
            return response()->json(
                [
                    'is_success' => true,
                    'success' => __('Status successfully updated!'),
                ],
                200
            );
            // event(new PurchaseUploadFiles($request , $upload , $purchase));    // this event is not created

        } else {

            return response()->json(
                [
                    'is_success' => false,
                    'error' => $upload['msg'],
                ],
                401
            );
        }
    }

    public function retainerAttechmentDestroy($id)
    {
        $file = RetainerAttechment::find($id);

        if (!empty($file->file_path)) {
            delete_file($file->file_path);
        }
        $file->delete();
        return redirect()->back()->with('success', __('File successfully deleted.'));
    }
}
