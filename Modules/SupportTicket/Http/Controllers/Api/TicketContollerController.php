<?php

namespace Modules\SupportTicket\Http\Controllers\Api;

use App\Models\EmailTemplate;
use App\Models\User;
use App\Models\WorkSpace;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\SupportTicket\Entities\Conversion;
use Modules\SupportTicket\Entities\Ticket;
use Modules\SupportTicket\Entities\TicketCategory;
use Modules\SupportTicket\Entities\TicketField;
use Modules\SupportTicket\Events\CreateTicket;
use Modules\SupportTicket\Events\DestroyTicket;
use Modules\SupportTicket\Events\ReplyTicket;
use Modules\SupportTicket\Events\UpdateTicket;

class TicketContollerController extends Controller
{

    public function home(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(), [
                    'workspace_id'  => 'required|exists:work_spaces,id',
                    'page' => 'nullable|integer|min:1',
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                
                return response()->json(['status' => 0, 'message' => $messages->first()] , 403);
            }

            $categories   = TicketCategory::where('created_by', creatorId())->where('workspace_id', $request->workspace_id)->count();
            $open_ticket  = Ticket::whereIn('status', ['On Hold', 'In Progress'])->where('workspace_id', $request->workspace_id)->count();
            $close_ticket = Ticket::where('status', '=', 'Closed')->where('workspace_id', $request->workspace_id)->count();

            $workspace       = WorkSpace::where('id', $request->workspace_id)->first();
            $categoriesChart = Ticket::select(
                [
                    'ticket_categories.name',
                    'ticket_categories.color',
                    \DB::raw('count(*) as total'),
                ]
            )->join('ticket_categories', 'ticket_categories.id', '=', 'tickets.category')->where('tickets.workspace_id', $request->workspace_id)->groupBy('ticket_categories.id')->get();
            $chartData = [];
            
            if (count($categoriesChart) > 0) {
                foreach ($categoriesChart as $key => $category) {
                    $chartData[$key]['name']  = $category->name;
                    $chartData[$key]['value'] = $category->total;
                    $chartData[$key]['color'] = $category->color;
                }
            } else {
                $chartData['color'] = ['#5ce600'];
                $chartData['name'] = ['Category'];
                $chartData['value'] = [100];
            }

            $monthData = [];
            $barChart  = Ticket::select(
                [
                    \DB::raw('MONTH(created_at) as month'),
                    \DB::raw('YEAR(created_at) as year'),
                    \DB::raw('count(*) as total'),
                ]
            )->where('created_at', '>', \DB::raw('DATE_SUB(NOW(),INTERVAL 1 YEAR)'))->where('workspace_id', $request->workspace_id)->groupBy(
                [
                    \DB::raw('MONTH(created_at)'),
                    \DB::raw('YEAR(created_at)'),
                ]
            )->get();

            $start = \Carbon\Carbon::now()->startOfYear();

            for ($i = 0; $i <= 11; $i++) {

                foreach ($barChart as $chart) {
                    if (intval($chart->month) == intval($start->format('m'))) {
                        $monthData[$i]['name'] = $start->format('M');
                        $monthData[$i]['value'] = $chart->total;
                    }
                    else{
                        $monthData[$i]['name'] = $start->format('M');
                        $monthData[$i]['value'] = 0;
                    }
                }
                $start->addMonth();
            }

            return response()->json([
                'status' => 1,
                'data'   => [
                    'YearWiseChart' => $monthData,
                    'chartDatas' => $chartData,
                    'total_categories' => $categories,
                    'open_ticket' => $open_ticket,
                    'close_ticket' => $close_ticket,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
        } 
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(), [
                    'workspace_id'  => 'required|exists:work_spaces,id',
                    'page' => 'nullable|integer|min:1',
                ]
            );
    
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                
                return response()->json(['status' => 0, 'message' => $messages->first()] , 403);
            }

            $currentWorkspace = $request->workspace_id;

            $tickets = Ticket::select(
                [
                    'tickets.id',
                    'tickets.ticket_id',
                    'tickets.name',
                    'tickets.email',
                    'tickets.account_type',
                    'tickets.subject',
                    'tickets.status',
                    'tickets.description',
                    'tickets.note',
                    'ticket_categories.name as category_name',
                    'ticket_categories.color',
                ]
            )
            ->join('ticket_categories', 'ticket_categories.id', '=', 'tickets.category')
            ->where('tickets.workspace_id', $currentWorkspace)
            ->orderBy('id')
            ->paginate(10);
            
            return response()->json([
                'status' => 1,
                'data'   => $tickets,
                
            ]);

        } catch (\Exception $e) {
            return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
        } 
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(), [
                    'workspace_id'  => 'required|exists:work_spaces,id',
                ]
            );
    
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
    
                return response()->json(['status' => 0, 'message' => $messages->first()] , 403);
            }
            $currentWorkspace = $request->workspace_id;

            $categories = TicketCategory::select('id','name')->where('created_by', creatorId())
                                        ->where('workspace_id', $currentWorkspace)
                                        ->get();

            $staff = User::where('created_by', creatorId())
                            ->where('workspace_id', $currentWorkspace)
                            ->where('type', 'staff')
                            ->get()
                            ->map(function($user){
                                return [
                                    'id'    => $user->id,
                                    'name'  => $user->name,
                                    'email' => $user->email,
                                ];
                            });
            $client = User::where('created_by', creatorId())
                        ->where('workspace_id', $currentWorkspace)
                        ->where('type', 'client')
                        ->get()
                        ->map(function($user){
                            return [
                                'id'    => $user->id,
                                'name'  => $user->name,
                                'email' => $user->email,
                            ];
                        });
            $vendor = User::where('created_by', creatorId())
                            ->where('workspace_id', $currentWorkspace)
                            ->where('type', 'vendor')
                            ->get()
                            ->map(function($user){
                                return [
                                    'id'    => $user->id,
                                    'name'  => $user->name,
                                    'email' => $user->email,
                                ];
                            });

            $data = [];
            $data['category']   = $categories;
            $data['staff']      = $staff;
            $data['client']     = $client;
            $data['vendor']     = $vendor;

            return response()->json([
                'status' => 1,
                'data'   => $data,
                
            ]);

        } catch (\Exception $e) {
            return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
        } 
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {

            $validator = Validator::make(
                $request->all(), [
                    'workspace_id'  => 'required|exists:work_spaces,id',
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255',
                    'category' => 'required|exists:ticket_categories,id',
                    'subject' => 'required|string|max:255',
                    'status' => 'required|in:In Progress,On Hold,Closed',
                    'account_type' => 'required_if:account_type,!=,null|in:staff,client,vendor,custom'
                ]
            );
            
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
    
                return response()->json(['status' => 0, 'message' => $messages->first()] , 403);
            }
            $currentWorkspace = $request->workspace_id;

            $post = $request->all();
            $post['ticket_id'] = time();
            $post['created_by'] = creatorId();
            $post['workspace_id'] = $currentWorkspace;
            if ($post['account_type'] == 'staff') {
                $post['user_id'] = $post['name'];
                $post['name'] = User::find($post['name'])->name;
            } elseif ($post['account_type'] == 'client') {
                $post['user_id'] = $post['name'];
                $post['name'] = User::find($post['name'])->name;
            } elseif ($post['account_type'] == 'vendor') {
                $post['user_id'] = $post['name'];
                $post['name'] = User::find($post['name'])->name;
            } elseif ($post['account_type'] == 'custom') {
                $post['name'] = $request->input('name');
            }   
            $data = [];
            if ($request->hasfile('attachments')) {
                foreach ($request->file('attachments') as $file) {

                    $name = $file->getClientOriginalName();
                    $data[] = [
                        'name' => $name,
                        'path' => 'uploads/tickets/' . $post['ticket_id'] . '/' . $name,
                    ];
                    multi_upload_file($file, 'attachments', $name, 'tickets/' . $post['ticket_id']);
                }
            }
            $post['attachments'] = json_encode($data);
            $ticket = Ticket::create($post);

            event(new CreateTicket($request, $ticket));
            TicketField::saveData($ticket, $request->fields);


            if (!empty(company_setting('New Ticket')) && company_setting('New Ticket') == true) {
                $user = User::where('id', $ticket->created_by)->where('workspace_id', '=', $currentWorkspace)->first();

                $uArr = [
                    'ticket_name' => $request->name,
                    'email' => $request->email,
                    'ticket_id' => $ticket->ticket_id,
                    'ticket_url' => route('dashboard.support-tickets', \Illuminate\Support\Facades\Crypt::encrypt($ticket->ticket_id)),
                ];

                try {
                    $resp = EmailTemplate::sendEmailTemplate('New Ticket', [$request->email], $uArr);
                } catch (\Exception $e) {
                    $resp['error'] = $e->getMessage();
                }

                // Send Email to
                if (isset($resp['error'])) {
                    session('smtp_error', '<span class="text-danger ml-2">' . $resp['error'] . '</span>');
                }
            }

            return response()->json(['status' => 1,'message'=>'Ticket Created Successfully!'],200);

        } catch (\Exception $e) {
            return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
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
        try {
            $validator = Validator::make(
                $request->all(), [
                    'workspace_id'  => 'required|exists:work_spaces,id',
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255',
                    'category' => 'required|exists:ticket_categories,id',
                    'subject' => 'required|string|max:255',
                    'status' => 'required|in:In Progress,On Hold,Closed',
                    'account_type' => 'required_if:account_type,!=,null|in:staff,client,vendor,custom'
                ]
            );
            
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
    
                return response()->json(['status' => 0, 'message' => $messages->first()] , 403);
            }

            $currentWorkspace = $request->workspace_id;

            $ticket = Ticket::where('id',$id)->where('workspace_id',$currentWorkspace)->where('created_by',creatorId())->first();
            if(!$ticket){
                return response()->json(['status'=>0,'message' => 'Ticket Not Found!']);
            }

            if ($request->hasfile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $name = $file->getClientOriginalName();
                    $data[] = [
                        'name' => $name,
                        'path' => 'uploads/tickets/' . $ticket->ticket_id . '/' . $name,
                    ];
                    multi_upload_file($file, 'attachments', $name, 'tickets/' . $ticket->ticket_id);
                }
                if ($request->hasfile('attachments')) {
                    $json_decode = json_decode($ticket->attachments);
                    $attachments = json_encode(array_merge($json_decode, $data));
                } else {
                    $attachments = json_encode($data);
                }
                $ticket->attachments = isset($attachments) ? $attachments : null;
            }

            TicketField::saveData($ticket, $request->fields);

            event(new UpdateTicket($request, $ticket));

            if ($request->account_type == 'custom') {
                $ticket->name = !empty($request->name) ? $request->name : '';
            } elseif ($request->account_type == 'staff') {
                $ticket->user_id = $request->name;
                $ticket->name = User::find($request->name)->name;
            } elseif ($request->account_type == 'client') {
                $ticket->user_id = $request->name;
                $ticket->name = User::find($request->name)->name;
            } elseif ($request->account_type == 'vendor') {
                $ticket->user_id = $request->name;
                $ticket->name = User::find($request->name)->name;

            }

            $ticket->account_type = !empty($request->account_type) ? $request->account_type : '' ;
            $ticket->email = !empty($request->email) ? $request->email : '';
            $ticket->category = !empty($request->category) ? $request->category : '';
            $ticket->subject = !empty($request->subject) ? $request->subject : '';
            $ticket->status = !empty($request->status) ? $request->status : '';
            $ticket->description = !empty($request->description) ? $request->description : '';
            $ticket->save();

            return response()->json(['status'=>1, 'message' => 'Ticket Update successfully']);

        } catch (\Exception $e) {
            return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
        } 
        
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request,$id)
    {
        try {
            $validator = Validator::make(
                $request->all(), [
                    'workspace_id'  => 'required|exists:work_spaces,id',
                ]
            );
            
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
    
                return response()->json(['status' => 0, 'message' => $messages->first()] , 403);
            }

            $currentWorkspace = $request->workspace_id;

            $ticket = Ticket::where('id',$id)->where('workspace_id',$currentWorkspace)->where('created_by',creatorId())->first();
            if(!$ticket){
                return response()->json(['status'=>0,'message' => 'Ticket Not Found!']);
            }

            $conversions = Conversion::where('ticket_id', $ticket->id)->get();

            if (count($conversions) > 0) {
                $conversions = Conversion::where('ticket_id', $ticket->id)->delete();
            }

            delete_folder('tickets/' . $ticket->ticket_id);

            event(new DestroyTicket($ticket));

            $ticket->delete();
            return response()->json(['status'=>1,'message'=> 'Ticket Deleted Successfully!']);

        } catch (\Exception $e) {
            return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
        } 
    }

    public function storeNote(Request $request, $id)
    {
        try {
            $validator = Validator::make(
                $request->all(), [
                    'workspace_id'  => 'required|exists:work_spaces,id',
                    'note'  => 'required'
                ]
            );
            
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
    
                return response()->json(['status' => 0, 'message' => $messages->first()] , 403);
            }

            $currentWorkspace = $request->workspace_id;
            $ticket = Ticket::where('id',$id)->where('workspace_id',$currentWorkspace)->where('created_by',creatorId())->first();
            if(!$ticket){
                return response()->json(['status'=>0,'message' => 'Ticket Not Found!']);
            }

            $ticket->note = $request->note;
            $ticket->save();

            return response()->json(['status'=>1,'message'=>'Ticket note saved successfully!']);

        } catch (\Exception $e) {
            return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
        } 
    }

    public function addReply(Request $request, $id)
    {
        try {
            $validator = Validator::make(
                $request->all(), [
                    'workspace_id'  => 'required|exists:work_spaces,id',
                    'reply_description'  => 'required'
                ]
            );
            
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
    
                return response()->json(['status' => 0, 'message' => $messages->first()] , 403);
            }

            $user = Auth::user();
            $currentWorkspace = $request->workspace_id;
            $ticket = Ticket::where('id',$id)->where('workspace_id',$currentWorkspace)->where('created_by',creatorId())->first();
            if(!$ticket){
                return response()->json(['status'=>0,'message' => 'Ticket Not Found!']);
            }

            $post = [];
            $post['sender'] = ($user) ? $user->id : 'user';
            $post['ticket_id'] = $ticket->id;
            $post['description'] = $request->reply_description;

            $data = [];
            if ($request->hasfile('reply_attachments')) {
                foreach ($request->file('reply_attachments') as $file) {
                    $name = $file->getClientOriginalName();
                    $data[] = [
                        'name' => $name,
                        'path' => 'uploads/tickets/' . $ticket->ticket_id . '/' . $name
                    ];
                    multi_upload_file($file, 'reply_attachments', $name, 'tickets/' . $ticket->ticket_id);
                }
            }
            $post['attachments'] = json_encode($data);
            $conversion = Conversion::create($post);

            event(new ReplyTicket($request,$conversion, $ticket));

            if (!empty(company_setting('New Ticket Reply')) && company_setting('New Ticket Reply')  == true) {
                $user        = User::where('id', $ticket->created_by)->where('workspace_id', '=',  $currentWorkspace)->first();

                $uArr = [
                    'ticket_name' => $ticket->name,
                    'ticket_id' => $ticket->ticket_id,
                    'email' => $ticket->email,
                    'reply_description' => $request->reply_description,
                ];

                $resp = EmailTemplate::sendEmailTemplate('New Ticket Reply', [$ticket->email], $uArr);
            }

            return response()->json(['status'=>1,'message'=>'Reply Added Successfully!']);

        } catch (\Exception $e) {
            return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
        } 
    }
}
