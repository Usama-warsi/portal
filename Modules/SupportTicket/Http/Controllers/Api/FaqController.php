<?php

namespace Modules\SupportTicket\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\SupportTicket\Entities\Faq;

class FaqController extends Controller
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
            $faqs = Faq::where('created_by', creatorId())
                    ->where('workspace_id', $currentWorkspace)
                    ->get()
                    ->map(function($faq){
                        return [
                            'id' => $faq->id,
                            'title' => $faq->title,
                            'description' => $faq->description,
                        ];
                    });

            return response()->json(['status'=>1, 'data' => $faqs]);
            
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
                    'title' => 'required|string|max:255',
                    'description' => 'required',
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
                'workspace_id' => $currentWorkspace,
                'created_by' => creatorId()
            ];

            Faq::create($post);

            return response()->json(['status'=>1,'message'=>'Faq Created Successfully!']);
            
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
                ]
            );
    
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
    
                return response()->json(['status' => 0, 'message' => $messages->first()] , 403);
            }

            $currentWorkspace = $request->workspace_id;
            $faq = Faq::where('id',$id)->where('workspace_id',$currentWorkspace)->where('created_by',creatorId())->first();
            if(!$faq){
                return response()->json(['status'=>0,'message'=>'Faq Updated Successfully!']);
            }

            $post = [
                'title' => $request->title,
                'description' => $request->description
            ];

            $faq->update($post);

            return response()->json(['status'=>1,'message'=>'Faq Updated Successfully!']);
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
            $faq = Faq::where('id',$id)->where('workspace_id',$currentWorkspace)->where('created_by',creatorId())->first();
            if(!$faq){
                return response()->json(['status'=>0,'message'=>'Faq Updated Successfully!']);
            }

            $faq->delete();

            return response()->json(['status'=>1,'message'=>'Faq Deleted Successfully!']);

        } catch (\Exception $e) {
            return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
        } 
    }
}
