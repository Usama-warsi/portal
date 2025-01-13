<?php

namespace Modules\Newsletter\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use App\Models\Invoice;
use App\Models\User;
use Modules\Lead\Entities\Lead;
use App\Models\Proposal;
use Modules\Newsletter\Entities\NewsletterModule;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Account\Entities\Bill;
use Modules\Account\Entities\Customer;
use Modules\Hrm\Entities\Transfer;
use Modules\Account\Entities\Vender;
use Modules\Contract\Entities\Contract;
use Modules\Contract\Entities\ContractType;
use Modules\Hrm\Entities\Award;
use Modules\Hrm\Entities\AwardType;
use Modules\Hrm\Entities\Branch;
use Modules\Hrm\Entities\Department;
use Modules\Hrm\Entities\Designation;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Entities\Leave;
use Modules\Hrm\Entities\LeaveType;
use Modules\Hrm\Entities\Promotion;
use Modules\Hrm\Entities\Resignation;
use Modules\Hrm\Entities\Termination;
use Modules\Hrm\Entities\TerminationType;
use Modules\Lead\Entities\DealStage;
use Modules\Lead\Entities\LeadStage;
use Modules\Lead\Entities\Pipeline;
use Modules\Newsletter\Entities\Newsletters;
use App\Models\Purchase;
use App\Models\Warehouse;
use Modules\BeautySpaManagement\Entities\BeautyService;
use Modules\CarDealership\Entities\CarPurchase;
use Modules\CarDealership\Entities\CarSale;
use Modules\CleaningManagement\Entities\CleaningTeam;
use Modules\Commission\Entities\CommissionPlan;
use Modules\Fleet\Entities\Driver;
use Modules\Fleet\Entities\FuelType;
use Modules\Fleet\Entities\License;
use Modules\Fleet\Entities\VehicleType;
use Modules\ParkingManagement\Entities\Parking;
use Modules\PharmacyManagement\Entities\PharmacyBill;
use Modules\PharmacyManagement\Entities\PharmacyInvoice;
use Modules\Recruitment\Entities\InterviewSchedule;
use Modules\Recruitment\Entities\Job;
use Modules\Recruitment\Entities\JobApplication;
use Modules\Retainer\Entities\Retainer;
use Modules\Sales\Entities\Call;
use Modules\Sales\Entities\Contact;
use Modules\Sales\Entities\Meeting;
use Modules\Sales\Entities\SalesAccount;
use Modules\Sales\Entities\SalesInvoice;
use Modules\Sales\Entities\SalesOrder;
use Modules\School\Entities\Classroom;
use Modules\Taskly\Entities\Project;
use Modules\TourTravelManagement\Entities\Tour;
use Modules\TourTravelManagement\Entities\TourBooking;
use Modules\WasteManagement\Entities\WasteCategory;
use Modules\WasteManagement\Entities\WastePickupPoints;
use SebastianBergmann\Template\Template;

class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $modules = NewsletterModule::groupBy('module')->pluck('module');
        $notify = NewsletterModule::get();


        return view('newsletter::index', compact('modules', 'notify'));
    }


    public function filter(Request $request)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'content' => 'required',
            ]
        );

        if ($validator->fails()) {

            return response()->json(
                [
                    'is_success' => false,
                    'response' => __('The Message is required.'),
                ]
            );
        }

        if ($request->ajax()) {

            $news_module = NewsletterModule::find($request->additionalField);
            if ($news_module) {
                $module = $news_module->module;
                $moduleName = $news_module->submodule;
                $news = new Newsletters();
                $news->module = $module;
                $news->sub_module = $moduleName;
                $news->from = \Auth::user()->name;
                $news->subject = $request->subject;
                $news->content = $request->content;
                $news->workspace_id = getActiveWorkSpace();
                $news->created_by = creatorId();
                $news->save();


                if ($module == 'general') {
                    if ($moduleName == 'Invoice') {
                        $users = \DB::table('invoices')
                            ->join('users', 'users.id', '=', 'invoices.user_id')
                            ->select(['users.id', 'users.email', 'invoices.user_id'])
                            ->pluck('users.email', 'invoices.user_id');
                        $invoices = Invoice::where('workspace', '=', getActiveWorkSpace())->where('status', $request->status)->get();
                        $uArr = [];
                        foreach ($users as $user_id => $email) {
                            foreach ($invoices as $invoice) {
                                $invoice->dueAmount = currency_format_with_sym($invoice->getDue());
                                if ($invoice->getDue() >= $request->amount && $invoice->user_id == $user_id) {
                                    $use = User::where('id', $invoice->user_id)->first();
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    } elseif ($moduleName == 'Proposal') {
                        $users = \DB::table('proposals')->join('users', 'users.id', '=', 'proposals.customer_id')->select(['users.id', 'users.email', 'proposals.customer_id'])->pluck('proposals.customer_id');
                        $proposals = Proposal::where('workspace', '=', getActiveWorkSpace())->where('status', $request->status)->get();

                        $uArr = [];
                        foreach ($users as $key => $user) {
                            foreach ($proposals as  $proposal) {
                                if ($proposal->getTotal() >= $request->amount && $proposal->customer_id == $user) {
                                    $proposal = Proposal::where('customer_id', $user)->first();
                                    $use = User::where('id', $proposal->customer_id)->first();
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    } elseif ($moduleName == 'Purchase') {
                        $users = \DB::table('purchases')->join('users', 'users.id', '=', 'purchases.user_id')->select(['users.id', 'users.email', 'purchases.user_id'])->pluck('purchases.user_id');
                        $purchases = Purchase::where('workspace', '=', getActiveWorkSpace())
                            ->where('warehouse_id', $request->warehouse_id)
                            ->where('category_id', $request->category_id)
                            ->get();

                        foreach ($users as $key => $user) {
                            foreach ($purchases as  $purchase) {
                                if ($purchase->user_id == $user) {
                                    $use = User::where('id', $purchase->user_id)->first();
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    }
                } elseif ($module == 'Account') {
                    if ($moduleName == 'Customer') {
                        $users = \DB::table('customers')->join('users', 'users.id', '=', 'customers.user_id')->select(['users.id', 'users.email', 'customers.user_id'])->pluck('customers.user_id');

                        $customers = Customer::where('workspace', '=', getActiveWorkSpace())
                            ->where('billing_country', '=', $request->country)
                            ->where('billing_state', '=', $request->state)
                            ->where('billing_city', '=', $request->city)
                            ->get();

                        $uArr = [];
                        foreach ($users as $key => $user) {
                            foreach ($customers as $customer) {
                                if ($user == $customer->user_id) {
                                    $uArr[] = $customer->email;
                                }
                            }
                        }
                    } elseif ($moduleName == 'Vendor') {
                        $users = \DB::table('vendors')->join('users', 'users.id', '=', 'vendors.user_id')->select(['users.id', 'users.email', 'vendors.user_id'])->pluck('vendors.user_id');
                        $vendors = Vender::where('workspace', '=', getActiveWorkSpace())
                            ->where('billing_country', '=', $request->country)
                            ->where('billing_state', '=', $request->state)
                            ->where('billing_city', '=', $request->city)
                            ->get();

                        $uArr = [];
                        foreach ($users as $key => $user) {
                            foreach ($vendors as $vendor) {
                                if ($user == $vendor->user_id) {
                                    $uArr[] = $vendor->email;
                                }
                            }
                        }
                    } elseif ($moduleName == 'Bill') {
                        $users = \DB::table('bills')->join('users', 'users.id', '=', 'bills.user_id')->select(['users.id', 'users.email', 'bills.user_id'])->pluck('bills.user_id');
                        $bills = Bill::where('workspace', '=', getActiveWorkSpace())->where('status', $request->status)->get();

                        $uArr = [];
                        foreach ($users as $key => $user) {
                            foreach ($bills as  $bill) {
                                if ($bill->getTotal() >= $request->amount && $bill->user_id == $user) {
                                    $bill = Bill::where('user_id', $user)->first();
                                    $use = User::where('id', $bill->user_id)->first();
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    }
                } elseif ($module == 'Contract') {
                    if ($moduleName == 'Contract') {
                        $users = \DB::table('contracts')->join('users', 'users.id', '=', 'contracts.user_id')->select(['users.id', 'users.email', 'contracts.user_id'])->pluck('contracts.user_id');
                        $contracts = Contract::where('workspace', '=', getActiveWorkSpace())
                            ->where('project_id', $request->project_id)
                            ->where('type', $request->type)
                            ->get();

                        $uArr = [];

                        foreach ($users as $key => $user) {
                            foreach ($contracts as  $contract) {
                                if ($contract->user_id == $user) {
                                    $use = User::where('id', $contract->user_id)->first();
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    }
                } elseif ($module == 'Hrm') {
                    if ($moduleName == 'Employee') {
                        $users = \DB::table('employees')->join('users', 'users.id', '=', 'employees.user_id')->select(['users.id', 'users.email', 'employees.user_id'])->pluck('employees.user_id');
                        $employees = Employee::where('workspace', '=', getActiveWorkSpace())
                            ->where('branch_id', $request->branch_id)
                            ->where('department_id', $request->department_id)
                            ->where('designation_id', $request->designation_id)
                            ->get();
                        $uArr = [];

                        foreach ($users as $key => $user) {
                            foreach ($employees as  $employee) {
                                if ($employee->user_id == $user) {
                                    $use = User::where('id', $employee->user_id)->first();
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    } elseif ($moduleName == 'Leave') {
                        $users = \DB::table('leaves')->join('users', 'users.id', '=', 'leaves.user_id')->select(['users.id', 'users.email', 'leaves.user_id'])->pluck('leads.user_id');
                        $leaves = Leave::where('workspace', '=', getActiveWorkSpace())->where('leave_type_id', $request->type)->get();
                        $uArr = [];

                        foreach ($users as $key => $user) {
                            foreach ($leaves as  $leave) {
                                if ($leave->user_id == $user) {
                                    $use = User::where('id', $leave->user_id)->first();
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    } elseif ($moduleName == 'Award') {
                        $users = \DB::table('awards')->join('users', 'users.id', '=', 'awards.user_id')->select(['users.id', 'users.email', 'awards.user_id'])->pluck('awards.user_id');
                        $awards = Award::where('workspace', '=', getActiveWorkSpace())->where('award_type', $request->type)->get();
                        $uArr = [];

                        foreach ($users as $key => $user) {
                            foreach ($awards as  $award) {
                                if ($award->user_id == $user) {
                                    $use = User::where('id', $award->user_id)->first();
                                    if (!in_array($use->email, $uArr)) {
                                        $uArr[] = $use->email;
                                    }
                                }
                            }
                        }
                    } elseif ($moduleName == 'Transfer') {
                        $users = \DB::table('transfers')->join('users', 'users.id', '=', 'transfers.user_id')->select(['users.id', 'users.email', 'transfers.user_id'])->pluck('transfers.user_id');
                        $transfers = Transfer::where('workspace', '=', getActiveWorkSpace())
                            ->where('branch_id', $request->branch_id)
                            ->where('department_id', $request->department_id)
                            ->get();
                        $uArr = [];

                        foreach ($users as $key => $user) {
                            foreach ($transfers as  $transfer) {
                                if ($transfer->user_id == $user) {
                                    $use = User::where('id', $transfer->user_id)->first();
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    } elseif ($moduleName == 'Termination') {
                        $users = \DB::table('terminations')->join('users', 'users.id', '=', 'terminations.user_id')->select(['users.id', 'users.email', 'terminations.user_id'])->pluck('terminations.user_id');
                        $terminations = Termination::where('workspace', '=', getActiveWorkSpace())
                            ->where('termination_type', $request->type)
                            ->where('notice_date', $request->noticedate)
                            ->where('termination_date', $request->terminationdate)
                            ->get();
                        $uArr = [];

                        foreach ($users as $key => $user) {
                            foreach ($terminations as  $termination) {
                                if ($termination->user_id == $user) {
                                    $use = User::where('id', $termination->user_id)->first();
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    } elseif ($moduleName == 'Promotion') {
                        $users = \DB::table('promotions')->join('users', 'users.id', '=', 'promotions.user_id')->select(['users.id', 'users.email', 'promotions.user_id'])->pluck('promotions.user_id');
                        $promotions = Promotion::where('workspace', '=', getActiveWorkSpace())
                            ->where('designation_id', $request->designation)
                            ->where('promotion_date', $request->promotiondate)
                            ->get();
                        $uArr = [];

                        foreach ($users as $key => $user) {
                            foreach ($promotions as  $promotion) {
                                if ($promotion->user_id == $user) {
                                    $use = User::where('id', $promotion->user_id)->first();
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    } elseif ($moduleName == 'Resignation') {
                        $users = \DB::table('resignations')->join('users', 'users.id', '=', 'resignations.user_id')->select(['users.id', 'users.email', 'resignations.user_id'])->pluck('resignations.user_id');
                        $resignations = Resignation::where('workspace', '=', getActiveWorkSpace())
                            ->where('resignation_date', $request->resignation_date)
                            ->where('last_working_date', $request->last_working_date)
                            ->get();
                        $uArr = [];

                        foreach ($users as $key => $user) {
                            foreach ($resignations as  $resignation) {
                                if ($resignation->user_id == $user) {
                                    $use = User::where('id', $resignation->user_id)->first();
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    } elseif ($moduleName == 'Announcement') {
                        $uArr = \DB::table('employees')
                            ->join('announcement_employees', 'employees.id', '=', 'announcement_employees.employee_id')
                            ->join('announcements', 'announcement_employees.employee_id', '=', 'announcements.id')
                            ->where('announcements.branch_id', $request->branch_id)
                            ->where('announcements.department_id', $request->department_id)
                            ->pluck('employees.email')
                            ->toArray();
                    }
                } elseif ($module == 'Lead') {
                    if ($moduleName == 'Deal') {
                        $uArr = \DB::table('users')
                            ->join('client_deals', 'users.id', '=', 'client_deals.client_id')
                            ->join('deals', 'client_deals.deal_id', '=', 'deals.id')
                            ->where('deals.stage_id', $request->stage)
                            ->where('deals.pipeline_id', $request->pipeline)
                            ->pluck('users.email')
                            ->toArray();
                    } elseif ($moduleName == 'Lead') {
                        $users = \DB::table('leads')->join('users', 'users.id', '=', 'leads.user_id')->select(['users.id', 'users.email', 'leads.user_id'])->pluck('leads.user_id');
                        $leads = Lead::where('workspace_id', '=', getActiveWorkSpace())
                            ->where('stage_id', $request->stage)
                            ->where('pipeline_id', $request->pipeline)
                            ->get();
                        $uArr = [];

                        foreach ($users as $key => $user) {
                            foreach ($leads as  $lead) {
                                if ($lead->user_id == $user) {
                                    $use = User::where('id', $lead->user_id)->first();
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    }
                } elseif ($module == 'Recruitment') {
                    if ($moduleName == 'Interview Schedule') {
                        $users = \DB::table('interview_schedules')->join('users', 'users.id', '=', 'interview_schedules.user_id')->select(['users.id', 'users.email', 'interview_schedules.user_id'])->pluck('interview_schedules.user_id');
                        $interviews = InterviewSchedule::where('workspace', '=', getActiveWorkSpace())
                            ->where('date', $request->date)
                            ->where('time', $request->time)
                            ->get();

                        $uArr = [];

                        foreach ($users as $key => $user) {
                            foreach ($interviews as  $interview) {
                                if ($interview->user_id == $user) {
                                    $use = User::where('id', $interview->user_id)->first();
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    } elseif ($moduleName == 'Job Application') {
                        $uArr = JobApplication::where('workspace', '=', getActiveWorkSpace())
                            ->where('job', '=', $request->job)
                            ->where('country', $request->country)
                            ->where('state', $request->state)
                            ->where('city', $request->city)
                            ->pluck('email')
                            ->toArray();
                    }
                } elseif ($module == 'Retainer') {
                    if ($moduleName == 'Retainer') {
                        $users = \DB::table('retainers')
                            ->join('users', 'users.id', '=', 'retainers.user_id')
                            ->select(['users.id', 'users.email', 'retainers.user_id'])
                            ->pluck('users.email', 'retainers.user_id');

                        $retainers = Retainer::where('workspace', '=', getActiveWorkSpace())->where('status', $request->status)->get();
                        $uArr = [];
                        foreach ($users as $user_id => $email) {
                            foreach ($retainers as $retainer) {
                                $retainer->dueAmount = currency_format_with_sym($retainer->getDue());
                                if ($retainer->getDue() >= $request->amount && $retainer->user_id == $user_id) {
                                    $use = User::where('id', $retainer->user_id)->first();
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    }
                } elseif ($module == 'Sales') {
                    if ($moduleName == 'Account') {
                        $users = \DB::table('sales_accounts')->join('users', 'users.id', '=', 'sales_accounts.user_id')->select(['users.id', 'users.email', 'sales_accounts.user_id'])->pluck('sales_accounts.user_id');

                        $accounts = SalesAccount::where('workspace', '=', getActiveWorkSpace())
                            ->where('billing_country', '=', $request->country)
                            ->where('billing_state', '=', $request->state)
                            ->where('billing_city', '=', $request->city)
                            ->get();

                        $uArr = [];
                        foreach ($users as $key => $user) {
                            foreach ($accounts as $account) {
                                if ($user == $account->user_id) {
                                    $emailaddress = User::find($user);
                                    $emails = $emailaddress->email;
                                    $uArr[] = $emails;
                                }
                            }
                        }
                    } elseif ($moduleName == 'Contact') {

                        $users = \DB::table('contacts')->join('users', 'users.id', '=', 'contacts.user_id')->select(['users.id', 'users.email', 'contacts.user_id'])->pluck('contacts.user_id');

                        $contacts = Contact::where('workspace', '=', getActiveWorkSpace())
                            ->where('contact_country', '=', $request->country)
                            ->where('contact_state', '=', $request->state)
                            ->where('contact_city', '=', $request->city)
                            ->get();

                        $uArr = [];
                        foreach ($users as $key => $user) {
                            foreach ($contacts as $contact) {
                                if ($user == $contact->user_id) {
                                    $emailaddress = User::find($user);
                                    $emails = $emailaddress->email;
                                    $uArr[] = $emails;
                                }
                            }
                        }
                    } elseif ($moduleName == 'Sales Invoice') {
                        $users = \DB::table('sales_invoices')
                            ->join('users', 'users.id', '=', 'sales_invoices.user_id')
                            ->select(['users.id', 'users.email', 'sales_invoices.user_id'])
                            ->pluck('users.email', 'sales_invoices.user_id');
                        $invoices = SalesInvoice::where('workspace', '=', getActiveWorkSpace())->where('status', $request->status)->get();
                        $uArr = [];
                        foreach ($users as $user_id => $email) {
                            foreach ($invoices as $invoice) {
                                $invoice->dueAmount = currency_format_with_sym($invoice->getdue());
                                if ($invoice->getTotal() >= $request->amount && $invoice->user_id == $user_id) {
                                    $use = User::where('id', $invoice->user_id)->first();
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    } elseif ($moduleName == 'Sales Order') {
                        $users = \DB::table('sales_orders')->join('users', 'users.id', '=', 'sales_orders.user_id')->select(['users.id', 'users.email', 'sales_orders.user_id'])->pluck('sales_orders.user_id');
                        $orders = SalesOrder::where('workspace', '=', getActiveWorkSpace())->where('status', $request->status)->get();

                        $uArr = [];
                        foreach ($users as $key => $user) {
                            foreach ($orders as  $order) {
                                if ($order->getTotal() >= $request->amount && $order->user_id == $user) {
                                    $order = SalesOrder::where('user_id', $user)->first();
                                    $use = User::where('id', $order->user_id)->first();
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    } elseif ($moduleName == 'Meeting') {

                        $users = \DB::table('meetings')->join('users', 'users.id', '=', 'meetings.user_id')->select(['users.id', 'users.email', 'meetings.user_id'])->pluck('meetings.user_id');
                        $meetings = Meeting::where('workspace', '=', getActiveWorkSpace())
                            ->where('parent', $request->parent)
                            ->where('attendees_lead', $request->attendees_lead)
                            ->where('start_date', $request->start_date)
                            ->where('end_date', $request->end_date)
                            ->get();
                        $uArr = [];

                        foreach ($users as $key => $user) {
                            foreach ($meetings as  $meeting) {
                                if ($meeting->user_id == $user) {
                                    $use = User::where('id', $meeting->user_id)->first();
                                    if (!in_array($use->email, $uArr)) {
                                        $uArr[] = $use->email;
                                    }
                                }
                            }
                        }
                    } elseif ($moduleName == 'Call') {
                        $users = \DB::table('calls')->join('users', 'users.id', '=', 'calls.user_id')->select(['users.id', 'users.email', 'calls.user_id'])->pluck('calls.user_id');
                        $calls = Call::where('workspace', '=', getActiveWorkSpace())
                            ->where('parent', $request->parent)
                            ->where('attendees_lead', $request->attendees_lead)
                            ->where('start_date', $request->start_date)
                            ->where('end_date', $request->end_date)
                            ->get();
                        $uArr = [];

                        foreach ($users as $key => $user) {
                            foreach ($calls as  $call) {
                                if ($call->user_id == $user) {
                                    $use = User::where('id', $call->user_id)->first();
                                    if (!in_array($use->email, $uArr)) {
                                        $uArr[] = $use->email;
                                    }
                                }
                            }
                        }
                    }
                } elseif ($module == 'ZoomMeeting') {
                    if ($moduleName == 'Zoom Meeting') {
                        $uArr = \DB::table('users')
                            ->join('general_meeting', 'users.id', '=', 'general_meeting.user_id')
                            ->join('zoom_meeting', 'general_meeting.m_id', '=', 'zoom_meeting.id')
                            ->where('zoom_meeting.start_date', $request->start_date)
                            ->pluck('users.email')
                            ->toArray(); // Convert the collection to an array


                    }
                } elseif ($module == 'Assets') {
                    if ($moduleName == 'Assets') {
                        $uArr = User::join('assets', 'users.id', '=', 'assets.user_id')
                            ->where('assets.amount', '>=', $request->amount)
                            ->where('assets.workspace_id', '=', getActiveWorkSpace())
                            ->pluck('users.email')
                            ->toArray();
                    }
                } elseif ($module == 'Commission') {
                    $users = \DB::table('commission_plans')->join('users', 'users.id', '=', 'commission_plans.user_id')->select(['users.id', 'users.email', 'commission_plans.user_id'])->pluck('commission_plans.user_id');
                    $commission_plans = CommissionPlan::where('workspace', '=', getActiveWorkSpace())
                        ->where('start_date', $request->start_date)
                        ->where('end_date', $request->end_date)
                        ->get();
                    $uArr = [];

                    foreach ($users as $key => $user) {
                        foreach ($commission_plans as  $commission_plan) {
                            if ($commission_plan->user_id == $user) {
                                $userIds = explode(',', $commission_plan->user_id);
                                foreach ($userIds as $key => $userId) {
                                    $use = User::where('id', $userId)->first();
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    }
                } elseif ($module == 'Fleet') {

                    if ($moduleName == 'Driver') {
                        $users = \DB::table('drivers')->join('users', 'users.id', '=', 'drivers.user_id')->select(['users.id', 'users.email', 'drivers.user_id'])->pluck('drivers.user_id');
                        $drivers = Driver::where('workspace', '=', getActiveWorkSpace())
                            ->where('lincese_number', $request->lincese_number)
                            ->where('lincese_type', $request->lincese_type)
                            ->get();
                        $uArr = [];

                        foreach ($users as $key => $user) {
                            foreach ($drivers as  $driver) {
                                if ($driver->user_id == $user) {
                                    $use = User::where('id', $driver->user_id)->first();
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    } elseif ($moduleName == 'Vehicle') {
                        $uArr = \DB::table('vehicles')
                            ->join('drivers', 'vehicles.driver_name', '=', 'drivers.id')
                            ->where('workspace', '=', getActiveWorkSpace())
                            ->where('vehicles.name', $request->vehicle_name)
                            ->where('vehicles.vehicle_type', $request->vehicle_type)
                            ->where('vehicles.fuel_type', $request->fuel_type)
                            ->pluck('drivers.email')
                            ->toArray();
                    } elseif ($moduleName == 'Vehicle Booking') {

                        $uArr = \DB::table('bookings')
                            ->join('users', 'bookings.customer_name', '=', 'users.id')
                            ->where('workspace', '=', getActiveWorkSpace())
                            ->where('bookings.start_date', $request->start_date)
                            ->where('bookings.end_date', $request->end_date)
                            ->pluck('users.email')
                            ->toArray();
                    }
                } elseif ($module == 'CarDealership') {
                    if ($moduleName == 'Car purchase') {
                        $uArr = \DB::table('car_purchases')
                            ->join('users', 'car_purchases.user_id', '=', 'users.id')
                            ->where('workspace', '=', getActiveWorkSpace())
                            ->where('car_purchases.purchase_date', $request->purchase_date)
                            ->where('car_purchases.due_date', $request->due_date)
                            ->where('car_purchases.status', $request->status)
                            ->pluck('users.email')
                            ->toArray();
                    } elseif ($moduleName == 'Car sale') {
                        $uArr = \DB::table('car_sales')
                            ->join('users', 'car_sales.user_id', '=', 'users.id')
                            ->where('workspace', '=', getActiveWorkSpace())
                            ->where('car_sales.issue_date', $request->issue_date)
                            ->where('car_sales.due_date', $request->due_date)
                            ->where('car_sales.status', $request->status)
                            ->pluck('users.email')
                            ->toArray();
                    }
                } elseif ($module == 'ChildcareManagement') {
                    $uArr = \DB::table('inquiries')
                        ->where('workspace', '=', getActiveWorkSpace())
                        ->where('date', $request->inquiry_date)
                        ->where('child_gender', $request->gender)
                        ->pluck('email')
                        ->toArray();
                } elseif ($module == 'ParkingManagement') {
                    $uArr = \DB::table('parkings')
                        ->where('workspace', '=', getActiveWorkSpace())
                        ->where('vehicle_number', $request->vehicle_number)
                        ->where('vehicle', $request->vehicle)
                        ->pluck('email')
                        ->toArray();
                } elseif ($module == 'School') {
                    if ($moduleName == 'Student') {
                        $uArr = \DB::table('school_students')
                            ->where('workspace', '=', getActiveWorkSpace())
                            ->where('roll_number', $request->student_number)
                            ->where('class_name', $request->class)
                            ->pluck('email')
                            ->toArray();
                    } elseif ($moduleName == 'Admission') {
                        $uArr = \DB::table('admissions')
                            ->where('workspace', '=', getActiveWorkSpace())
                            ->where('date', $request->admission_date)
                            ->where('gender', $request->gender)
                            ->pluck('email')
                            ->toArray();
                    }
                } elseif ($module == 'TourTravelManagement') {
                    if ($moduleName == 'Tourist Inquiry') {

                        $uArr = \DB::table('tour_inquiries')
                            ->where('workspace', '=', getActiveWorkSpace())
                            ->where('tour_id', $request->tour_name)
                            ->where('tour_start_date', $request->tour_start_date)
                            ->pluck('email_id')
                            ->toArray();
                    } elseif ($moduleName == 'Tourist Booking') {

                        $uArr = \DB::table('tour_bookings')
                            ->join('tour_inquiries', 'tour_bookings.inquiry_id', '=', 'tour_inquiries.id')
                            ->where('tour_bookings.workspace', '=', getActiveWorkSpace())
                            ->where('tour_inquiries.tour_id', $request->tour_name)
                            ->where('tour_inquiries.tour_start_date', $request->tour_start_date)
                            ->where('tour_inquiries.payment_status', $request->payment_status)
                            ->pluck('email_id')
                            ->toArray();
                    }
                } elseif ($module == 'BeautySpaManagement') {
                    $uArr = \DB::table('beauty_bookings')
                        ->where('workspace', '=', getActiveWorkSpace())
                        ->where('service', $request->service)
                        ->where('date', $request->date)
                        ->pluck('email')
                        ->toArray();
                } elseif ($module == 'WasteManagement') {
                    if ($moduleName == 'Collection Requests') {
                        $uArr = \DB::table('waste_collections')
                            ->where('workspace_id', '=', getActiveWorkSpace())
                            ->where('category_id', $request->category)
                            ->where('pickup_point_id', $request->pickup_point)
                            ->pluck('email')
                            ->toArray();
                    }
                } elseif ($module == 'CleaningManagement') {
                    if ($moduleName == 'Cleaning Team') {
                        $users = \DB::table('cleaning_teams')->join('users', 'users.id', '=', 'cleaning_teams.user_id')->select(['users.id', 'users.email', 'cleaning_teams.user_id'])->pluck('cleaning_teams.user_id');
                        $cleaning_teams = CleaningTeam::where('workspace', '=', getActiveWorkSpace())
                            ->where('name', $request->team_name)
                            ->where('status', $request->status)
                            ->get();
                        $uArr = [];

                        foreach ($users as $key => $user) {
                            foreach ($cleaning_teams as  $cleaning_team) {
                                if ($cleaning_team->user_id == $user) {
                                    $userIds = explode(',', $cleaning_team->user_id);
                                    foreach ($userIds as $key => $userId) {
                                        $use = User::where('id', $userId)->first();
                                        $uArr[] = $use->email;
                                    }
                                }
                            }
                        }
                    }
                } elseif ($module == 'SalesAgent') {
                    $users = \DB::table('sales_agents')->join('users', 'users.id', '=', 'sales_agents.user_id')->select(['users.id', 'users.email', 'sales_agents.user_id'])->pluck('sales_agents.user_id');
                    $sales_agents = \DB::table('sales_agents')
                        ->join('customers', 'sales_agents.user_id', '=', 'customers.user_id')
                        ->where('sales_agents.workspace', '=', getActiveWorkSpace())
                        ->where('customers.billing_country', '=', $request->country)
                        ->where('customers.billing_state', '=', $request->state)
                        ->where('customers.billing_city', '=', $request->city)
                        ->get();
                    $uArr = [];
                    foreach ($users as $key => $user) {
                        foreach ($sales_agents as  $sales_agent) {
                            if ($sales_agent->user_id == $user) {
                                $use = User::where('id', $sales_agent->user_id)->first();
                                $uArr[] = $use->email;
                            }
                        }
                    }
                }  elseif ($module == 'PharmacyManagement') {
                    if( $moduleName == 'Pharmacy Bill') {
                        $users = \DB::table('pharmacy_bills')->join('users', 'users.id', '=', 'pharmacy_bills.user_id')->select(['users.id', 'users.email', 'pharmacy_bills.user_id'])->pluck('pharmacy_bills.user_id');
                        $pharmacy_bills = PharmacyBill::where('workspace_id', '=', getActiveWorkSpace())
                            ->where('issue_date', $request->issue_date)
                            ->where('due_date', $request->due_date)
                            ->get();
                        $uArr = [];
                        foreach ($users as $key => $user) {
                            foreach ($pharmacy_bills as  $pharmacy_bill) {
                                if ($pharmacy_bill->user_id == $user) {
                                    $use = User::where('id', $pharmacy_bill->user_id)->first();
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    }  elseif( $moduleName == 'Pharmacy Invoice') {
                        $users = \DB::table('pharmacy_invoices')->join('users', 'users.id', '=', 'pharmacy_invoices.user_id')->select(['users.id', 'users.email', 'pharmacy_invoices.user_id'])->pluck('pharmacy_invoices.user_id');
                        $pharmacy_invoices = PharmacyInvoice::where('workspace_id', '=', getActiveWorkSpace())
                            ->where('issue_date', $request->issue_date)
                            ->where('due_date', $request->due_date)
                            ->get();
                        $uArr = [];
                        foreach ($users as $key => $user) {
                            foreach ($pharmacy_invoices as  $pharmacy_invoice) {
                                if ($pharmacy_invoice->user_id == $user) {
                                    $use = User::where('id', $pharmacy_invoice->user_id)->first();
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    }
                }


                $templates = [
                    'subject' => $request->subject,
                    'content' => $request->content,
                    'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                ];

                if (!empty($uArr)) {
                    $user_id = creatorId();
                    $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                    $status = $resp['is_success'] ? 1 : 0;
                    if (!empty($moduleName)) {
                        Newsletters::where('sub_module', $moduleName)->delete();
                        $news = new Newsletters();
                        $news->module = $module;
                        $news->sub_module = $moduleName;
                        $news->from = \Auth::user()->name;
                        $news->subject = $request->subject;
                        $news->emails_list = json_encode($uArr);
                        $news->content = $request->content;
                        $news->workspace_id = getActiveWorkSpace();
                        $news->created_by = creatorId();
                        $news->status = $status;
                        $news->save();
                    }
                } else {
                    $message = response()->json([
                        'html' => false,
                        'response' => __('Users not found'),
                    ]);
                    return $message;
                }
                $message =  response()->json([
                    'status' => 'success',
                    'response' => __('Newsletter Created Successfully!') . ((isset($resp) && $resp != 1) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''),
                ]);

                return $message;
            } else {

                $message =  response()->json([
                    'status' => 'error',
                    'response' => __('Something went wrong, Please try again,') . ((isset($resp) && $resp != 1) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''),
                ]);

                return $message;
            }
        }
    }

    public function getcondition(Request $request)
    {
        $news_module = NewsletterModule::find($request->workmodule_id);
        if ($news_module != null) {
            $field_data = json_decode($news_module->field_json);
            $data = null;
            foreach ($field_data->field as $value) {

                if ($value->field_type == "select") {
                    if ($value->model_name == 'LeadStage') {
                        $data['LeadStage'] = LeadStage::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'DealStage') {
                        $data['DealStage'] = DealStage::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Pipeline') {
                        $data['Pipeline'] = Pipeline::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Invoice') {
                        $data['Invoice'] = \App\Models\Invoice::$statues;
                    } elseif ($value->model_name == 'Proposal') {
                        $data['Proposal'] = \App\Models\Proposal::$statues;
                    } elseif ($value->model_name == 'Bill') {
                        $data['Bill'] = Bill::$statues;
                    } elseif ($value->model_name == 'Leave') {
                        $data['Leave'] = LeaveType::where('workspace', getActiveWorkSpace())->get()->pluck('title', 'id');
                    } elseif ($value->model_name == 'Award') {
                        $data['Award'] = AwardType::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Termination') {
                        $data['Termination'] = TerminationType::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Promotion') {
                        $data['Promotion'] = Designation::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'SalesInvoice') {
                        $data['SalesInvoice'] = SalesInvoice::$status;
                    } elseif ($value->model_name == 'SalesOrder') {
                        $data['SalesOrder']  = SalesOrder::$status;
                    } elseif ($value->model_name == 'Project') {
                        $data['Project'] = Project::where('workspace', getActiveWorkSpace())->projectonly()->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Type') {
                        $data['Type'] = ContractType::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Meeting') {
                        $data['Meeting'] = Meeting::$parent;
                    } elseif ($value->model_name == 'Call') {
                        $data['Call'] = Call::$parent;
                    } elseif ($value->model_name == 'Lead') {
                        $data['Lead'] = Lead::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Warehouse') {
                        $data['Warehouse'] = Warehouse::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Category') {
                        $data['Category']     = \Modules\ProductService\Entities\Category::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 2)->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Department') {
                        $data['Department'] = Department::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Branch') {
                        $data['Branch'] = Branch::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Designation') {
                        $data['Designation'] = Designation::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Retainer') {
                        $data['Retainer']  = Retainer::$statues;
                    } elseif ($value->model_name == 'Job') {
                        $data['Job'] = Job::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('title', 'id');
                    } elseif ($value->model_name == 'License') {
                        $data['License'] = License::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'VehicleType') {
                        $data['VehicleType'] = VehicleType::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'FuelType') {
                        $data['FuelType'] = FuelType::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'CarPurchase') {
                        $data['CarPurchase'] = CarPurchase::$statues;
                    } elseif ($value->model_name == 'CarSale') {
                        $data['CarSale'] = CarSale::$statues;
                    } elseif ($value->model_name == 'Parking') {
                        $data['Parking'] = Parking::$statues;
                    } elseif ($value->model_name == 'Classroom') {
                        $data['Classroom'] = Classroom::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('class_name', 'id');
                    } elseif ($value->model_name == 'Tour') {
                        $data['Tour'] = Tour::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('tour_name', 'id');
                    } elseif ($value->model_name == 'BeautyService') {
                        $data['BeautyService'] = BeautyService::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'WasteCategory') {
                        $data['WasteCategory'] = WasteCategory::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'WastePickupPoints') {
                        $data['WastePickupPoints'] = WastePickupPoints::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'CleaningTeam') {
                        $data['CleaningTeam'] = CleaningTeam::$statues;
                    } elseif ($value->model_name == 'TourBooking') {
                        $data['TourBooking'] = TourBooking::$statues;
                    } else {
                        return redirect()->back()->with('error', __('Permission denied.'));
                    }
                }
            }
            $returnHTML = view('newsletter::input', compact('news_module', 'data', 'request', 'field_data'))->render();
            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
            ];
            return response()->json($response);
        }
    }

    public function show($id)
    {
        // return view('newsletter::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        // return view('newsletter::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
