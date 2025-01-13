<?php

namespace Modules\Training\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Training\Entities\Training;
use Modules\Training\Entities\TrainingType;
use Modules\Training\Events\CreateTrainingType;
use Modules\Training\Events\DestroyTrainingType;
use Modules\Training\Events\UpdateTrainingType;

class TrainingTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('trainingtype manage')) {
            $trainingtypes = TrainingType::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();

            return view('training::trainingtype.index', compact('trainingtypes'));
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
        if (Auth::user()->isAbleTo('trainingtype create')) {
            return view('training::trainingtype.create');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('trainingtype create')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $trainingtype                   = new TrainingType();
            $trainingtype->name             = $request->name;
            $trainingtype->workspace        = getActiveWorkSpace();
            $trainingtype->created_by       = creatorId();
            $trainingtype->save();

            event(new CreateTrainingType($request, $trainingtype));

            return redirect()->route('trainingtype.index')->with('success', __('TrainingType  successfully created.'));
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
        return view('training::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('trainingtype edit')) {
            $trainingType = TrainingType::find($id);
            if ($trainingType->created_by == creatorId() &&  $trainingType->workspace  == getActiveWorkSpace()) {

                return view('training::trainingtype.edit', compact('trainingType'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
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
        if (Auth::user()->isAbleTo('trainingtype edit')) {
            $trainingType = TrainingType::find($id);
            if ($trainingType->created_by == creatorId() &&  $trainingType->workspace  == getActiveWorkSpace()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required',
                    ]
                );

                $trainingType->name = $request->name;
                $trainingType->save();

                event(new UpdateTrainingType($request, $trainingType));

                return redirect()->route('trainingtype.index')->with('success', __('TrainingType successfully updated.'));
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
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('trainingtype delete')) {

            $trainingType = TrainingType::find($id);
            if ($trainingType->created_by == creatorId() &&  $trainingType->workspace  == getActiveWorkSpace()) {
                $trainings = Training::where('training_type', $trainingType->id)->where('workspace', getActiveWorkSpace())->get();
                if (count($trainings) == 0) {

                    event(new DestroyTrainingType($trainingType));

                    $trainingType->delete();
                } else {
                    return redirect()->route('trainingtype.index')->with('error', __('This TrainingType has Training List. Please remove the Training List from this TrainingType.'));
                }

                return redirect()->route('trainingtype.index')->with('success', __('TrainingType successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
