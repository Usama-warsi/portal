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
use Illuminate\Support\Facades\Validator;
use Rawilk\Settings\Support\Context;
use Modules\SalesAgent\Entities\Program;
use Modules\SalesAgent\Entities\Customer;
use Modules\SalesAgent\Entities\SalesAgentPurchase;
use Modules\SalesAgent\Events\SalesAgentCreate;
use Modules\SalesAgent\Events\SalesAgentDelete;
use Modules\SalesAgent\Events\SalesAgentUpdate;
use Modules\SalesAgent\Entities\SalesAgentUtility;


class SalesAgentController extends Controller
{

    public function dashboard(Request $request)
    {
        $salesAgents = User::where('workspace_id', getActiveWorkSpace())
            ->leftjoin('customers', 'users.id', '=', 'customers.user_id')
            ->leftjoin('sales_agents', 'users.id', '=', 'sales_agents.user_id')
            ->where('users.type', 'salesagent')
            ->select('users.*', 'customers.*', 'users.name as name', 'users.email as email', 'users.id as id');

        $totalAgents    = $salesAgents->count();
        $activeAgents   = $salesAgents->where('sales_agents.is_agent_active', '1')->count();
        $inactiveAgents = $totalAgents - $activeAgents;
        $salesAgents    = $salesAgents->get();


        $totalPrograms   = Program::where('workspace', getActiveWorkSpace())->count();
        $totalSalesOrders   = SalesAgentPurchase::where('workspace', getActiveWorkSpace())->count();

        $PurchaseOrderData = [];
        foreach (SalesAgentPurchase::$purchaseOrder as $key => $order) {
            $PurchaseOrder          = SalesAgentPurchase::where('workspace', '=', getActiveWorkSpace())->where('order_status', $key)->orderBy('order', 'ASC')->count();
            $PurchaseOrderData[]    = $PurchaseOrder;
        }

        return view('salesagent::dashboard.dashboard', compact('totalAgents', 'activeAgents', 'inactiveAgents', 'totalPrograms', 'totalSalesOrders', 'salesAgents', 'PurchaseOrderData'));
    }

    public function index(Request $request)
    {

        $salesagents = User::where('workspace_id', getActiveWorkSpace())
            ->leftjoin('customers', 'users.id', '=', 'customers.user_id')
            ->leftjoin('sales_agents', 'users.id', '=', 'sales_agents.user_id')
            ->where('users.type', 'salesagent')
            ->select('users.*', 'customers.*', 'users.name as name', 'users.email as email', 'users.id as id', 'sales_agents.is_agent_active as is_agent_active')
            ->get();

        return view('salesagent::salesagent.index', compact('salesagents'));
    }

    public function create()
    {
        if (\Auth::user()->isAbleTo('salesagent create')) {
            $customFields = null;
            if (module_is_active('CustomField')) {
                $customFields =  \Modules\CustomField\Entities\CustomField::where('workspace_id', getActiveWorkSpace())->where('module', '=', 'SalesAgent')->where('sub_module', 'Sales Agent')->get();
            } else {
                $customFields = null;
            }
            return view('salesagent::salesagent.create', compact('customFields'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        return view('salesagent::salesagent.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('salesagent create')) {
            $canUse =  PlanCheck('User', Auth::user()->id);
            if ($canUse == false) {
                return redirect()->back()->with('error', 'You have maxed out the total number of Agents allowed on your current plan');
            }

            $rules = [
                'name' => 'required',
                'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
                'billing_name' => 'required',
                'billing_phone' => 'required',
                'billing_address' => 'required',
                'billing_city' => 'required',
                'billing_state' => 'required',
                'billing_country' => 'required',
                'billing_zip' => 'required',
            ];

            $validator = \Validator::make($request->all(), $rules);
            if (empty($request->user_id)) {
                $rules = [
                    'email'     => 'required|email|unique:users',
                    'password'  => 'required',
                    'contact'   => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',

                ];
                $validator = \Validator::make($request->all(), $rules);
            }

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->route('salesagents.index')->with('error', $messages->first());
            }

            $roles = Role::where('name', 'salesagent')->where('guard_name', 'web')->where('created_by', creatorId())->first();
            if (empty($roles)) {
                return redirect()->back()->with('error', __('Agent Role Not found !'));
            }

            if (!empty($request->user_id)) {
                $user = User::find($request->user_id);

                if (empty($user)) {
                    return redirect()->back()->with('error', __('Something went wrong please try again.'));
                }
                if ($user->name != $request->name || $user->email != $request->email) {
                    $user->name = $request->name;
                    $user->email = $request->input('email');
                    $user->save();
                }
            } else {
                $userpassword               = $request->input('password');
                $user['name']               = $request->input('name');
                $user['email']              = $request->input('email');
                $user['password']           = \Hash::make($userpassword);
                $user['email_verified_at']  = date('Y-m-d h:i:s');
                $user['lang']               = 'en';
                $user['type']               = $roles->name;
                $user['created_by']         = \Auth::user()->id;
                $user['workspace_id']       = getActiveWorkSpace();
                $user['active_workspace']   = getActiveWorkSpace();
                $user                       = User::create($user);
                $user->addRole($roles);
            }

            $check    = Customer::where('user_id', '=', $user->id)->exists();
            if ($check) {
                $customer      = Customer::where('user_id', '=', $user->id)->first();
            } else {
                $customer      = new Customer();
            }

            $customer->user_id         = $user->id;
            $customer->customer_id     = $this->customerNumber();
            $customer->name            = !empty($request->name) ? $request->name : null;
            $customer->contact         = !empty($request->contact) ? $request->contact : null;
            $customer->email           = !empty($user->email) ? $user->email : null;
            $customer->tax_number      = !empty($request->tax_number) ? $request->tax_number : null;
            $customer->password        = null;
            $customer->billing_name    = !empty($request->billing_name) ? $request->billing_name : null;
            $customer->billing_country = !empty($request->billing_country) ? $request->billing_country : null;
            $customer->billing_state   = !empty($request->billing_state) ? $request->billing_state : null;
            $customer->billing_city    = !empty($request->billing_city) ? $request->billing_city : null;
            $customer->billing_phone   = !empty($request->billing_phone) ? $request->billing_phone : null;
            $customer->billing_zip     = !empty($request->billing_zip) ? $request->billing_zip : null;
            $customer->billing_address = !empty($request->billing_address) ? $request->billing_address : null;

            $customer->shipping_name    = !empty($request->shipping_name) ? $request->shipping_name : null;
            $customer->shipping_country = !empty($request->shipping_country) ? $request->shipping_country : null;
            $customer->shipping_state   = !empty($request->shipping_state) ? $request->shipping_state : null;
            $customer->shipping_city    = !empty($request->shipping_city) ? $request->shipping_city : null;
            $customer->shipping_phone   = !empty($request->shipping_phone) ? $request->shipping_phone : null;
            $customer->shipping_zip     = !empty($request->shipping_zip) ? $request->shipping_zip : null;
            $customer->shipping_address = !empty($request->shipping_address) ? $request->shipping_address : null;
            $customer->lang             = !empty($user->lang) ? $user->lang : '';

            $customer->workspace        = getActiveWorkSpace();
            $customer->created_by      = \Auth::user()->id;
            $customer->save();


            $check    = SalesAgent::where('user_id', '=', $user->id)->exists();
            if ($check) {
                $SalesAgent      = SalesAgent::where('user_id', '=', $user->id)->first();
            } else {
                $SalesAgent    = new SalesAgent();
            }

            $SalesAgent->user_id         = $user->id;
            $SalesAgent->agent_id        = $this->agentNumber();
            $SalesAgent->customer_id     = $customer->customer_id;
            $SalesAgent->workspace       = getActiveWorkSpace();
            $SalesAgent->created_by      = \Auth::user()->id;
            $SalesAgent->save();
            if (module_is_active('CustomField')) {
                \Modules\CustomField\Entities\CustomField::saveData($SalesAgent, $request->customField);
            }
            event(new SalesAgentCreate($request, $SalesAgent));

            return redirect()->back()->with('success', __('SalesAgent successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show($id)
    {
        try {
            $id       = \Crypt::decrypt($id);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Purchase Order Not Found.'));
        }

        $userId     = \Auth::user()->id;
        $salesAgent = User::where('workspace_id', getActiveWorkSpace())
            ->leftjoin('customers', 'users.id', '=', 'customers.user_id')
            ->leftjoin('sales_agents', 'users.id', '=', 'sales_agents.user_id')
            ->where('users.type', 'salesagent')
            ->where('users.id', $id)
            ->select('users.*', 'customers.*', 'users.name as name', 'users.email as email', 'users.id as id')
            ->first();

        $programs       = Program::where(function ($query) use ($id) {
            $query->whereRaw('FIND_IN_SET(?, sales_agents_applicable)', [$id])
                ->orWhereRaw('FIND_IN_SET(?, sales_agents_view)', [$id]);
        })->get();

        $purchaseOrders = SalesAgentPurchase::where('workspace', getActiveWorkSpace())
            ->where('created_by', '=', $id);

        $totalPurchaseOrders    = $purchaseOrders->get();
        $totalPrograms          = $programs->count();
        $totalSalesOrders       = $purchaseOrders->count();


        $totalInvoiceCreated = $purchaseOrders->where('invoice_id', '!=', null)->count();
        $totalDeliveredOrders = $purchaseOrders->where('order_status', '=', 3)->count();

        $totalSalesOrdersValue = [];
        foreach ($totalPurchaseOrders as $order) {
            $totalSalesOrdersValue[]  = $order->getTotal();
        }

        $totalSalesOrdersValue = array_sum($totalSalesOrdersValue);
        if (module_is_active('CustomField')) {
            $salesAgent->customField = \Modules\CustomField\Entities\CustomField::getData($salesAgent, 'SalesAgent', 'Sales Agent');
            $customFields      = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'SalesAgent')->where('sub_module', 'Sales Agent')->get();
        } else {
            $customFields = null;
        }

        return view('salesagent::salesagent.show', compact('salesAgent', 'programs', 'totalPurchaseOrders', 'totalPrograms', 'totalSalesOrders', 'totalSalesOrdersValue', 'totalInvoiceCreated', 'totalDeliveredOrders', 'customFields'));
    }

    public function edit($id)
    {
        if (Auth::user()->isAbleTo('salesagent edit')) {
            $agent = SalesAgent::find($id);
            $user         = User::where('id', $id)->where('workspace_id', getActiveWorkSpace())->first();
            $salesAgent   = Customer::where('user_id', $id)->where('workspace', getActiveWorkSpace())->first();
            $customFields = null;
            if (module_is_active('CustomField')) {
                $salesAgent->customField = \Modules\CustomField\Entities\CustomField::getData($salesAgent, 'SalesAgent', 'Sales Agent');
                $customFields             = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'SalesAgent')->where('sub_module', 'Sales Agent')->get();
            } else {
                $customFields = null;
            }
            return view('salesagent::salesagent.edit', compact('salesAgent', 'user', 'customFields'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('salesagent create')) {
            // $canUse =  PlanCheck('User', Auth::user()->id);
            // if ($canUse == false) {
            //     return redirect()->back()->with('error', 'You have maxed out the total number of Agents allowed on your current plan');
            // }

            $rules = [
                'name' => 'required',
                'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
                'billing_name' => 'required',
                'billing_phone' => 'required',
                'billing_address' => 'required',
                'billing_city' => 'required',
                'billing_state' => 'required',
                'billing_country' => 'required',
                'billing_zip' => 'required',
            ];

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->route('salesagents.index')->with('error', $messages->first());
            }

            $roles = Role::where('name', 'salesagent')->where('guard_name', 'web')->where('created_by', creatorId())->first();
            if (empty($roles)) {
                return redirect()->back()->with('error', __('Agent Role Not found !'));
            }


            $user = User::find($id);

            if (empty($user)) {
                return redirect()->back()->with('error', __('Something went wrong please try again.'));
            }
            if ($user->name != $request->name || $user->email != $request->email) {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->save();
            }

            $customer                   = Customer::where('user_id', $id)->first();
            $customer->name             = $request->name;
            $customer->email            = $request->email;
            $customer->contact          = $request->contact;
            $customer->tax_number       = $request->tax_number;
            $customer->billing_name     = $request->billing_name;
            $customer->billing_country  = $request->billing_country;
            $customer->billing_state    = $request->billing_state;
            $customer->billing_city     = $request->billing_city;
            $customer->billing_phone    = $request->billing_phone;
            $customer->billing_zip      = $request->billing_zip;
            $customer->billing_address  = $request->billing_address;
            $customer->shipping_name    = $request->shipping_name;
            $customer->shipping_country = $request->shipping_country;
            $customer->shipping_state   = $request->shipping_state;
            $customer->shipping_city    = $request->shipping_city;
            $customer->shipping_phone   = $request->shipping_phone;
            $customer->shipping_zip     = $request->shipping_zip;
            $customer->shipping_address = $request->shipping_address;
            $customer->save();

            $check                      = SalesAgent::where('user_id', $id)->exists();
            if (!$check) {
                $SalesAgent                 = new SalesAgent();
                $SalesAgent->user_id        = $user->id;
                $SalesAgent->customer_id    = $customer->customer_id;
                $SalesAgent->workspace      = getActiveWorkSpace();
                $SalesAgent->created_by     = \Auth::user()->id;
                $SalesAgent->save();
            } else {

                $SalesAgent                      = SalesAgent::where('user_id', $id)->first();
            }
            if (module_is_active('CustomField')) {
                \Modules\CustomField\Entities\CustomField::saveData($SalesAgent, $request->customField);
            }
            event(new SalesAgentUpdate($request, $SalesAgent));

            return redirect()->back()->with('success', __('Sales Agent Updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {
        $Customer     = Customer::where('user_id', $id)->where('workspace', getActiveWorkSpace())->first();
        $SalesAgent   = SalesAgent::where('user_id', '=', $id)->first();

        if (Auth::user()->isAbleTo('salesagent delete')) {
            if ($SalesAgent->workspace == getActiveWorkSpace()) {
                if (module_is_active('CustomField')) {
                    $customFields = \Modules\CustomField\Entities\CustomField::where('module', 'SalesAgent')->where('sub_module', 'Sales Agent')->get();
                    foreach ($customFields as $customField) {
                        $value = \Modules\CustomField\Entities\CustomFieldValue::where('record_id', '=', $SalesAgent->id)->where('field_id', $customField->id)->first();
                        if (!empty($value)) {
                            $value->delete();
                        }
                    }
                }
                $Customer->delete();
                $SalesAgent->delete();

                event(new SalesAgentDelete($SalesAgent));

                return redirect()->route('salesagents.index')->with('success', __('Sales Agents successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    function customerNumber()
    {
        $latest = Customer::where('workspace', getActiveWorkSpace())->latest()->first();
        if (!$latest) {
            return 1;
        }

        return $latest->customer_id + 1;
    }

    function agentNumber()
    {
        $latest = SalesAgent::where('workspace', getActiveWorkSpace())->latest()->first();
        if (!$latest) {
            return 1;
        }
        return $latest->agent_id + 1;
    }

    public function setting(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'salesagent_prefix' => 'required',
                // 'vendor_prefix' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        } else {
            // $userContext = new Context(['user_id' => creatorId(),'workspace_id'=>getActiveWorkSpace()]);
            // \Settings::context($userContext)->set('salesagent_prefix', $request->salesagent_prefix);
            // \Settings::context($userContext)->set('vendor_prefix', $request->vendor_prefix);
            $post['salesagent_prefix'] = $request->salesagent_prefix;
            SalesAgentUtility::saveSettings($post);
            return redirect()->back()->with('success', 'Sales Agent setting save sucessfully.');
        }
    }

    public function changeSalesAgentStatus(Request $request)
    {
        if (isset($request->is_agent_active) && isset($request->sales_agent_id)) {
            $salesAgent = SalesAgent::where('user_id', $request->sales_agent_id)->first();
            $salesAgent->is_agent_active = $request->is_agent_active;
            $salesAgent->save();

            $data['message'] =  __('Sales Agent Status Changed Successfully');
            $data['status'] = 200;
            return $data;
        }

        $data['message'] = __('Something Went Wrong!!');
        $data['status'] = 201;
        return $data;
    }
}
