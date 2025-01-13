<?php

namespace Modules\SupportTicket\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\SupportTicket\Entities\KnowledgeBase;
use Modules\SupportTicket\Entities\KnowledgeBaseCategory;
use Modules\SupportTicket\Events\CreateKnowledgeBase;
use Modules\SupportTicket\Events\DestroyKnowledgeBase;

class KnowledgeController extends Controller
{
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
                ]
            );
    
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
    
                return response()->json(['status' => 0, 'message' => $messages->first()] , 403);
            }
            $currentWorkspace = $request->workspace_id;
            $knowledges = KnowledgeBase::where('created_by', creatorId())
                                    ->where('workspace_id', $currentWorkspace)
                                    ->get()
                                    ->map(function($knowledge){
                                        return [
                                            'id'    => $knowledge->id,
                                            'title'    => $knowledge->title,
                                            'description'    => $knowledge->description,
                                            'category'    => $knowledge->getCategoryInfo->title ?? null,
                                        ];
                                    });

            return response()->json([
                'status' => 1,
                'data'   => $knowledges
            ]);

        } catch (\Exception $e) {
            return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
        } 
    }

    public function knowledgeCategories(Request $request){
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
            $knowledges_categories = KnowledgeBaseCategory::where('created_by', creatorId())
                                    ->where('workspace_id', $currentWorkspace)
                                    ->get()
                                    ->pluck('title','id');

            return response()->json(['status'=>1,'data'=>$knowledges_categories]);

        } catch (\Exception $e) {
            return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('supportticket::create');
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
                    'title' => 'required|string|max:255',
                    'description' => 'required',
                    'category' => 'required|string|max:255|exists:knowledge_base_categories,id'
                ]
            );
    
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
    
                return response()->json(['status' => 0, 'message' => $messages->first()] , 403);
            }

            $currentWorkspace = $request->workspace_id;
            $post = [
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'workspace_id' => $currentWorkspace,
                'created_by' => creatorId(),
            ];

            $KnowledgeBase = KnowledgeBase::create($post);

            event(new CreateKnowledgeBase($request, $KnowledgeBase));

            return response()->json(['status'=>1,  'message'=> 'Knowledge Created Successfully!']);

        } catch (\Exception $e) {
            return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('supportticket::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('supportticket::edit');
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
                    'title' => 'required|string|max:255',
                    'description' => 'required',
                    'category' => 'required|string|max:255|exists:knowledge_base_categories,id'
                ]
            );
    
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
    
                return response()->json(['status' => 0, 'message' => $messages->first()] , 403);
            }

            $currentWorkspace = $request->workspace_id;
            $knowledge = KnowledgeBase::where('id',$id)->where('workspace_id',$currentWorkspace)->where('created_by',creatorId())->first();
            if(!$knowledge){
                return response()->json(['status'=>1,'message'=>'Knowledge Not Found!']);
            }

            $knowledge->title = $request->title;
            $knowledge->description = $request->description;
            $knowledge->category = $request->category;
            $knowledge->save();

            event(new CreateKnowledgeBase($request, $knowledge));

            return response()->json(['status'=>1,  'message'=> 'Knowledge Updated Successfully!']);

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
                    'workspace_id'  => 'required|exists:work_spaces,id'  
                ]
            );
    
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
    
                return response()->json(['status' => 0, 'message' => $messages->first()] , 403);
            }

            $currentWorkspace = $request->workspace_id;
            $knowledge = KnowledgeBase::where('id',$id)->where('workspace_id',$currentWorkspace)->where('created_by',creatorId())->first();
            if(!$knowledge){
                return response()->json(['status'=>1,'message'=>'Knowledge Not Found!']);
            }

            $knowledge->delete();
            event(new DestroyKnowledgeBase($knowledge));

            return response()->json(['status'=>1,  'message'=> 'Knowledge Deleted Successfully!']);

        } catch (\Exception $e) {
            return response()->json(['status'=>0,'message'=>'something went wrong!!!']);     
        }
    }
}
