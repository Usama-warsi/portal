<?php

namespace Modules\Training\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Modules\Hrm\Entities\Branch;
use Modules\Hrm\Entities\Employee;
use Modules\Training\Entities\Trainer;
use Modules\Training\Entities\Training;
use Modules\Training\Entities\TrainingType;
use Modules\Training\Events\CreateTraining;
use Modules\Training\Events\DestroyTraining;
use Modules\Training\Events\UpdateStatus;
use Modules\Training\Events\UpdateTraining;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('training manage')) {
            $trainings = Training::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->with(['branches', 'types', 'employees', 'trainers'])->get();
            $status    = Training::$Status;

            return view('training::training.index', compact('trainings', 'status'));
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
        if (Auth::user()->isAbleTo('training create')) {
            $branches      = Branch::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $trainingTypes = TrainingType::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $trainers      = Trainer::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('firstname', 'id');
            $employees     = Employee::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $options       = Training::$options;

            return view('training::training.create', compact('branches', 'trainingTypes', 'trainers', 'employees', 'options'));
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
        if (Auth::user()->isAbleTo('training create')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'branch' => 'required',
                    'training_type' => 'required',
                    'training_cost' => 'required|numeric|min:0',
                    'employee' => 'required',
                    'start_date' => 'required|after:yesterday',
                    'end_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $training                 = new Training();
            $employee = Employee::where('id', '=', $request->employee)->first();
            if (!empty($employee)) {
                $training->user_id = $employee->user_id;
            }
            $training->branch         = $request->branch;
            $training->trainer_option = $request->trainer_option;
            $training->training_type  = $request->training_type;
            $training->trainer        = $request->trainer;
            $training->training_cost  = $request->training_cost;
            $training->employee       = $request->employee;
            $training->start_date     = $request->start_date;
            $training->end_date       = $request->end_date;
            $training->description    = $request->description;
            $training->workspace       = getActiveWorkSpace();
            $training->created_by     = creatorId();
            $training->save();

            event(new CreateTraining($request, $training));

            return redirect()->route('training.index')->with('success', __('Training successfully created.'));
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
        if (Auth::user()->isAbleTo('training show')) {
            $traId       = Crypt::decrypt($id);
            $training    = Training::find($traId);
            $performance = Training::$performance;
            $status      = Training::$Status;

            return view('training::training.show', compact('training', 'performance', 'status'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Training $training)
    {
        if (Auth::user()->isAbleTo('training edit')) {
            $branches      = Branch::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $trainingTypes = TrainingType::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $trainers      = Trainer::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('firstname', 'id');
            $employees     = Employee::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $options       = Training::$options;

            return view('training::training.edit', compact('branches', 'trainingTypes', 'trainers', 'employees', 'options', 'training'));
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
    public function update(Request $request, Training $training)
    {
        if (Auth::user()->isAbleTo('training edit')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'branch' => 'required',
                    'training_type' => 'required',
                    'training_cost' => 'required|numeric|min:0',
                    'employee' => 'required',
                    'start_date' => 'required|after:yesterday',
                    'end_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $training->branch         = $request->branch;
            $training->trainer_option = $request->trainer_option;
            $training->training_type  = $request->training_type;
            $training->trainer        = $request->trainer;
            $training->training_cost  = $request->training_cost;
            $training->employee       = $request->employee;
            $training->start_date     = $request->start_date;
            $training->end_date       = $request->end_date;
            $training->description    = $request->description;
            $training->save();

            event(new UpdateTraining($request, $training));

            return redirect()->route('training.index')->with('success', __('Training successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Training $training)
    {
        if (Auth::user()->isAbleTo('training delete')) {
            if ($training->created_by == creatorId() &&  $training->workspace  == getActiveWorkSpace()) {
                event(new DestroyTraining($training));
                $training->delete();

                return redirect()->route('training.index')->with('success', __('Training successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function updateStatus(Request $request)
    {
        if (Auth::user()->isAbleTo('training update status')) {
            $training              = Training::find($request->id);
            $training->performance = $request->performance;
            $training->status      = $request->status;
            $training->remarks     = $request->remarks;
            $training->save();

            event(new UpdateStatus($request, $training));

            return redirect()->route('training.index')->with('success', __('Training status successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
