<?php

namespace Modules\LMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;
use Modules\LMS\Entities\Store;
use Modules\LMS\Entities\Student;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('lms::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if(Auth::user()->isAbleTo('student create'))
        {
            return view('lms::student.create');
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if(Auth::user()->isAbleTo('student create'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'email' => 'required',
                                   'password' => 'required',
                                   'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('student.index')->with('error', $messages->first());
            }
            $company_settings = getCompanyAllSetting();

            $store = Store::where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->first();
            $student               = new Student();
            $student->name         = $request->name;
            $student->email        = $request->email;
            $student->phone_number = $request->phone_number;
            $student->password     = Hash::make($request->password);
            $student->lang         = !empty($store['lang']) ? $store['lang'] : 'en';
            $student->avatar       = '';
            $student->store_id     = $store->id;
            $student->save();

            return redirect()->back()->with('success', __('Student successfully created!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('lms::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if(Auth::user()->isAbleTo('student edit'))
        {
            $student = Student::find($id);
            return view('lms::student.edit', compact('student'));
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
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
        if(Auth::user()->isAbleTo('student edit'))
        {
            $student = Student::find($id);
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'email' => 'required',
                                   'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('student.index')->with('error', $messages->first());
            }
            $company_settings = getCompanyAllSetting();

            $store = Store::where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->first();
            $student->name         = $request->name;
            $student->email        = $request->email;
            $student->phone_number = $request->phone_number;
            $student->lang         = !empty($company_settings['defult_language']) ? $company_settings['defult_language'] : 'en';
            $student->avatar       = '';
            $student->store_id     = $store->id;

            $student->save();

            return redirect()->route('student.index')->with('success', __('Student successfully updated!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if(Auth::user()->isAbleTo('student delete'))
        {
            $student = Student::find($id);
            $student->delete();

            return redirect()->route('student.index')->with('success', __('Student successfully deleted!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
