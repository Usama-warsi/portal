<?php

namespace Modules\LMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LMS\Entities\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\LMS\Entities\Store;
use Modules\LMS\Entities\StudentLoginDetail;

class StudentlogController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if(Auth::user()->isAbleTo('student logs manage')){
            $objUser = Auth::user();
            $time = date_create($request->month);
            $firstDayofMOnth = (date_format($time, 'Y-m-d'));
            $lastDayofMonth =    \Carbon\Carbon::parse($request->month)->endOfMonth()->toDateString();
            $store = Store::where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->first();
            $studentsList = Student::where('store_id', '=', $store->id)->get()->pluck('name', 'id');
            $studentsList->prepend('All', '');

            if ($request->month == null) {
                $students = DB::table('student_login_details')
                    ->join('students', 'student_login_details.student_id', '=', 'students.id')
                    ->select(DB::raw('student_login_details.*, students.name as student_name , students.email as student_email'))
                    ->where(['student_login_details.created_by' => creatorId()])->where(['student_login_details.workspace_id' => getActiveWorkSpace()]);

            } else {
                $students = DB::table('student_login_details')
                    ->join('students', 'student_login_details.student_id', '=', 'students.id')
                    ->select(DB::raw('student_login_details.*, students.name as student_name , students.email as student_email'))
                    ->where(['student_login_details.created_by' => creatorId()])->where(['student_login_details.workspace_id' => getActiveWorkSpace()]);
            }
            if (!empty($request->month)) {
                $students->where('date', '>=', $firstDayofMOnth);
                $students->where('date', '<=', $lastDayofMonth);
            }
            if (!empty($request->student)) {
                $students->where(['student_id'  => $request->student]);
            }

            $students = $students->get();
            return view('lms::student_log.index', compact('students', 'studentsList'));
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function show($id)
    {
        if(\Auth::user()->isAbleTo('student logs show')){
            $students = StudentLoginDetail::find($id);
            return view('lms::student_log.view', compact('students'));
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
    public function destroy($id)
    {
        if(\Auth::user()->isAbleTo('student logs delete')){
            $student = StudentLoginDetail::find($id);
            if ($student) {
                $student->delete();
                return redirect()->back()->with('success', __('Student Logs successfully deleted .'));
            } else {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
