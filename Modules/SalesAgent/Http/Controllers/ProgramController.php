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
use Modules\SalesAgent\Entities\SalesAgentUtility;
use Modules\SalesAgent\Entities\Program;
use Modules\SalesAgent\Entities\ProgramItems;
use Illuminate\Support\Facades\Validator;
use Rawilk\Settings\Support\Context;
use Illuminate\Support\Facades\Crypt;
use Modules\ProductService\Entities\ProductService;
use Modules\SalesAgent\Events\SalesAgentProgramCreate;
use Modules\SalesAgent\Events\SalesAgentProgramDelete;
use Modules\SalesAgent\Events\SalesAgentProgramUpdate;
use Modules\SalesAgent\Events\SalesAgentRequestAccept;
use Modules\SalesAgent\Events\SalesAgentRequestReject;
use Modules\SalesAgent\Events\SalesAgentRequestSent;


class ProgramController extends Controller
{
    public function index()
    {
        if ( (Auth::user()) && Auth::user()->isAbleTo('programs show') && Auth::user()->type == 'company')
        {
            $programs = Program::where('workspace',getActiveWorkSpace())->get();

        }else{

            $userId     = \Auth::user()->id;
            $programs = Program::where('workspace',getActiveWorkSpace())->where(function($query) use ($userId) {
                $query->whereRaw('FIND_IN_SET(?, sales_agents_applicable)', [$userId])
                      ->orWhereRaw('FIND_IN_SET(?, sales_agents_view)', [$userId]);
            })->get();
        }   

        return view('salesagent::programs.index',compact('programs'));
    }

    public function create()
    {
        $salesAgents = User::where('workspace_id',getActiveWorkSpace())
                        ->leftjoin('customers', 'users.id', '=', 'customers.user_id')
                        ->leftjoin('sales_agents', 'users.id', '=', 'sales_agents.user_id')
                        ->where('users.type', 'salesagent')
                        ->where('users.is_disable','1')
                        ->where('sales_agents.is_agent_active','1')
                        ->select('users.name as name', 'users.email as email', 'users.id as id')
                        ->get();
                        
        $product_services = ProductService::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');

        if(module_is_active('CustomField')){
            $customFields =  \Modules\CustomField\Entities\CustomField::where('workspace_id',getActiveWorkSpace())->where('module', '=', 'SalesAgent')->where('sub_module','Bill')->get();
        }else{
            $customFields = null;
        }
        
        $product_type   = ProductService::$product_type;   

        return view('salesagent::programs.create', compact('salesAgents','product_services','product_type','customFields'));

    }

    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('programs create'))
        {

            $rules = [
                'name' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
                'description' => 'required',
                'discount_type' => 'required',
            ];
            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->route('programs.index')->with('error', $messages->first());
            }
            $program_date = explode('to' ,$request->program_date );
            $program                            = new Program();
            $program->name                      = $request->name;
            $program->from_date                 = $request->from_date;
            $program->to_date                   = $request->to_date;
            $program->description               = $request->description;
            $program->discount_type	            = $request->discount_type;
            $program->sales_agents_applicable   = (!isset($request->sales_agents_applicable)) ? '' : implode(',',$request->sales_agents_applicable) ; 
            $program->sales_agents_view         = (!isset($request->sales_agents_view)) ? '' :  implode(',',$request->sales_agents_view) ; 
            $program->workspace                 = getActiveWorkSpace();
            $program->created_by                = \Auth::user()->id;
            $program->save();

            $products = $request->program_details;
            for ($i = 0; $i < count($products); $i++)
            {
                $ProgramItem                      = new ProgramItems();
                $ProgramItem->program_id          = $program->id;
                $ProgramItem->product_type        = $products[$i]['product_type'];
                $ProgramItem->from_amount         = $products[$i]['from_amount'];
                $ProgramItem->to_amount           = $products[$i]['to_amount'];
                $ProgramItem->discount            = $products[$i]['discount'];
                $ProgramItem->items               = implode(',', $products[$i]['items']) ;
                $ProgramItem->save();
                $ProgramItems[$i] = $ProgramItem ;
            }

            event(new SalesAgentProgramCreate($request, $program , $ProgramItems));

            return redirect()->route('programs.index')->with('success', __('Program successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show($e_id)
    {
        try {
            $id       = Crypt::decrypt($e_id);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Program Not Found.'));
        }

        $program                    = Program::find($id);
        $program->program_details   = ProgramItems::where('program_id','=',$id)->get();
        $productServices            = Program::getProductServices([$program->id]);
        $totalJoinRequests          = ($program->requests_to_join != '') ? count(explode(',', ltrim($program->requests_to_join, ','))) : 0;

        return view('salesagent::programs.show' , compact('productServices','program','totalJoinRequests'));
    }

    public function edit($id)
    {

        $program    = Program::find($id);
        $productServicesItems = Program::getProductServicesItems($program->id);
        $salesAgents = User::where('workspace_id',getActiveWorkSpace())
                        ->leftjoin('sales_agents', 'users.id', '=', 'sales_agents.user_id')
                        ->leftjoin('customers', 'users.id', '=', 'customers.user_id')
                        ->where('users.type', 'salesagent')
                        ->where('users.is_disable','1')
                        // ->where('sales_agents.is_agent_active','1')
                        ->select('users.name as name', 'users.email as email', 'users.id as id')
                        ->get();

        $product_services = Program::getProductServices([$program->id]);
        $program->program_details = ProgramItems::where('program_id' , $id)->get();

        $product_type =\Modules\ProductService\Entities\ProductService::$product_type;   
        if(module_is_active('CustomField')){
            $customFields =  \Modules\CustomField\Entities\CustomField::where('workspace_id',getActiveWorkSpace())->where('module', '=', 'SalesAgent')->where('sub_module','Bill')->get();
        }else{
            $customFields = null;
        }

        $program->from_date  = date('Y-m-d', strtotime($program->from_date));
        $program->to_date  = date('Y-m-d', strtotime($program->to_date));

        return view('salesagent::programs.edit' , compact('customFields','program','product_services','product_type','salesAgents','productServicesItems'));
    }

    public function update(Request $request, $id)
    {

        if (Auth::user()->isAbleTo('programs edit'))
        {

            $rules = [
                'name' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
                'description' => 'required',
                'discount_type' => 'required',
            ];

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->route('programs.index')->with('error', $messages->first());
            }
            $program_date                       = explode('to' ,$request->program_date );
            $program                            = Program::find($id);
            $program->name                      = $request->name;
            $program->from_date                 = $request->from_date;
            $program->to_date                   = $request->to_date;
            $program->description               = $request->description;
            $program->discount_type	            = $request->discount_type;
            $program->sales_agents_applicable   = (!isset($request->sales_agents_applicable)) ? '' : implode(',',$request->sales_agents_applicable) ; 
            $program->sales_agents_view         = (!isset($request->sales_agents_view)) ? '' :  implode(',',$request->sales_agents_view) ; 
            $program->workspace                 = getActiveWorkSpace();
            $program->created_by                = \Auth::user()->id;
            $program->save();

            $products = $request->program_details;

            foreach ($products as $product )
            {
                if(isset($product['id']))
                {
                    $ProgramItem                  =  ProgramItems::find($product['id']);

                }else{

                    $ProgramItem                  =  New ProgramItems() ;
                    $ProgramItem->program_id      = $program->id;

                }
                
                $ProgramItem->product_type        = $product['product_type'];
                $ProgramItem->from_amount         = $product['from_amount'];
                $ProgramItem->to_amount           = $product['to_amount'];
                $ProgramItem->discount            = $product['discount'];
                $ProgramItem->items               = implode(',', $product['items']);
                $ProgramItem->save();
                $ProgramItems[] =  $ProgramItem;
 
            }

            event(new SalesAgentProgramUpdate($request, $program , $ProgramItems));

            return redirect()->route('programs.index')->with('success', __('Program successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {   
        if (Auth::user()->isAbleTo('programs create'))
        {
            $program    = Program::find($id);
            $ProgramItems  =  ProgramItems::find($program->id);

            if($program)
            {
                $program->delete();
            }
            if($ProgramItems)
            {
                $ProgramItems->delete();
            }

            event(new SalesAgentProgramDelete($program , $ProgramItems));

            return redirect()->route('programs.index')->with('success', __('Program successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function requestList($id)
    {
        $program    = Program::find($id);
        $totalRequests = count( explode(',', $program->requests_to_join) );

        $salesagents = User::where('workspace_id',getActiveWorkSpace())
                        ->whereIn('users.id', explode(',', $program->requests_to_join))
                        ->leftjoin('customers', 'users.id', '=', 'customers.user_id')
                        ->leftjoin('sales_agents', 'users.id', '=', 'sales_agents.user_id')
                        ->where('users.type', 'salesagent')
                        ->select('users.*','customers.*', 'users.name as name', 'users.email as email', 'users.id as id' , 'sales_agents.is_agent_active as is_agent_active')
                        ->get();
                        
        return view('salesagent::programs.requestList', compact('salesagents','totalRequests','program'));

    }

    public function sendRequest($program_id , $user_id='')
    {
        $program    = Program::find($program_id);
        $program->requests_to_join = SalesAgentUtility::addNumberToString($program->requests_to_join , \Auth::user()->id );
        $program->save();

        event(new SalesAgentRequestSent($program , $user_id));

        return redirect()->back()->with('success', __('Request Sent successfully.'));
    }

    public function acceptRequest($program_id , $user_id='')
    {
        $program = Program::find($program_id); 
        $program->requests_to_join = SalesAgentUtility::removeNumberFromString($program->requests_to_join , $user_id);
        $program->sales_agents_applicable = SalesAgentUtility::addNumberToString($program->sales_agents_applicable , $user_id);
        $program->save();

        event(new SalesAgentRequestAccept($program , $user_id));

        return redirect()->back()->with('success', __('Request Accepted successfully.'));
    }

    public function rejectRequest($program_id , $user_id='')
    {

        $program = Program::find($program_id); 
        $program->requests_to_join = SalesAgentUtility::removeNumberFromString($program->requests_to_join , $user_id);
        $program->save();

        event(new SalesAgentRequestReject($program , $user_id));

        return redirect()->back()->with('success', __('Request Rejected successfully.'));
    }
}
