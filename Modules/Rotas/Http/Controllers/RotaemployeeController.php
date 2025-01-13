<?php

namespace Modules\Rotas\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Rotas\Entities\Branch;
use Modules\Rotas\Entities\Department;
use Modules\Rotas\Entities\Designation;
use Modules\Rotas\Entities\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class RotaemployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('rotaemployee manage'))
        {

            $employees = User::where('workspace_id', getActiveWorkSpace())
                ->leftJoin('employees', 'users.id', '=', 'employees.user_id')
                ->leftJoin('branches', 'employees.branch_id', '=', 'branches.id')
                ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
                ->leftJoin('designations', 'employees.designation_id', '=', 'designations.id')
                ->where('users.created_by', creatorId())
                ->select('users.*', 'users.id as ID', 'employees.*', 'users.name as name', 'users.email as email', 'users.id as id', 'branches.name as branches_name', 'departments.name as departments_name', 'designations.name as designations_name')
                ->get();
            return view('rotas::employee.index', compact('employees'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if(Auth::user()->isAbleTo('rotaemployee create'))
        {
            $role             = Role::where('created_by', creatorId())->whereNotIn('name',Auth::user()->not_emp_type)->get()->pluck('name', 'id');
            $branches         = Branch::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $departments      = Department::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $designations     = Designation::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $employees        = User::where('created_by', creatorId())->where('workspace_id',getActiveWorkSpace())->get();
            $employeesId      = Employee::employeeIdFormat($this->employeeNumber());

            if(module_is_active('CustomField')){
                $customFields =  \Modules\CustomField\Entities\CustomField::where('workspace_id',getActiveWorkSpace())->where('module', '=', 'rotas')->where('sub_module','RotaEmployee')->get();
            }else{
                $customFields = null;
            }
            return view('rotas::employee.create', compact('employees', 'employeesId', 'departments', 'designations', 'branches','role','customFields' ));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    function employeeNumber()
    {
        $latest = Employee::where('workspace',getActiveWorkSpace())->where('created_by', creatorId())->latest()->first();
        if (!$latest) {
            return 1;
        }
        return $latest->employee_id + 1;
    }

    public function getdepartment(Request $request)
    {
        if($request->branch_id == 0)
        {
            $departments = Department::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id')->toArray();
        }
        else
        {
            $departments = Department::where('branch_id', $request->branch_id)->where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id')->toArray();
        }
        return response()->json($departments);
    }
    public function getdDesignation(Request $request)
    {
        if($request->department_id == 0)
        {
            $designations = Designation::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id')->toArray();
        }
        else
        {
            $designations = Designation::where('department_id', $request->department_id)->where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id')->toArray();
        }
        return response()->json($designations);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        $roles            = Role::where('created_by',creatorId())->where('id',$request->role)->first();
        if ( Auth::user()->isAbleTo('rotaemployee create'))
        {
            $validator = \Validator::make(
                $request->all(),
                [
                    'dob' => 'before:' . date('Y-m-d'),
                    'gender' => 'required',
                    'phone' => 'required',
                    'address' => 'required',
                    'branch_id' => 'required',
                    'department_id' => 'required',
                    'designation_id' => 'required'
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->withInput()->with('error', $messages->first());
            }
            if(isset($request->user_id))
            {
                $user = User::where('id',$request->user_id)->first();
            }
            else
            {
                $user = User::create(
                    [
                        'name' => $request['name'],
                        'email' => $request['email'],
                        'password' => Hash::make($request['password']),
                        'email_verified_at' => date('Y-m-d h:i:s'),
                        'type' => $roles->name,
                        'lang' => 'en',
                        'workspace_id' => getActiveWorkSpace(),
                        'active_workspace' =>getActiveWorkSpace(),
                        'created_by' => creatorId(),
                        ]
                    );
                    $user->save();

                    $user->addRole($roles->id);

            }
            if(empty($user))
            {
                return redirect()->back()->with('error', __('Something went wrong please try again.'));
            }
            if($user->name != $request->name)
            {
                $user->name = $request->name;
                $user->save();
            }
            if (!empty($request->document) && !is_null($request->document)) {
                $document_implode = implode(',', array_keys($request->document));
            } else {
                $document_implode = null;
            }

            $employee = Employee::create(
                [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'dob' => $request['dob'],
                    'gender' => $request['gender'],
                    'phone' => $request['phone'],
                    'address' => $request['address'],
                    'email' => $user->email,
                    'employee_id' => $this->employeeNumber(),
                    'branch_id' => $request['branch_id'],
                    'department_id' => $request['department_id'],
                    'designation_id' => $request['designation_id'],
                    'company_doj' => $request['company_doj'],
                    'documents' => $document_implode,
                    'account_holder_name' => $request['account_holder_name'],
                    'account_number' => $request['account_number'],
                    'bank_name' => $request['bank_name'],
                    'bank_identifier_code' => $request['bank_identifier_code'],
                    'branch_location' => $request['branch_location'],
                    'tax_payer_id' => $request['tax_payer_id'],
                    'workspace' => $user->workspace_id,
                    'created_by' => $user->created_by,
                ]
            );
            if(module_is_active('CustomField'))
            {
                \Modules\CustomField\Entities\CustomField::saveData($employee, $request->customField);
            }
        return redirect()->route('rotaemployee.index')->with('success', __('Employee successfully created.'));
        }
        else
        {
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
        if ( Auth::user()->isAbleTo('rotaemployee show'))
        {
            $empId        = Crypt::decrypt($id);
            $employee     = Employee::where('user_id',$empId)->where('workspace',getActiveWorkSpace())->first();
            if(!empty($employee))
            {
                $user         = User::where('id',$empId)->where('workspace_id',getActiveWorkSpace())->first();
                $employeesId  = Employee::employeeIdFormat($employee->employee_id);

                if(module_is_active('CustomField')){
                    $employee->customField = \Modules\CustomField\Entities\CustomField::getData($employee, 'rotas','RotaEmployee');
                    $customFields             = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'rotas')->where('sub_module','RotaEmployee')->get();
                }else{
                    $customFields = null;
                }
                return view('rotas::employee.show',compact('employee','user','employeesId','customFields'));
            }
            else
            {
                return redirect()->back()->with('error', __('Something went wrong please try again.'));
            }

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Employee Not Found.'));
        }

        if (Auth::user()->isAbleTo('rotaemployee edit'))
        {
            $branches     = Branch::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $departments  = Department::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $designations = Designation::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $employee     = Employee::where('user_id',$id)->where('workspace',getActiveWorkSpace())->first();
            $user         = User::where('id',$id)->where('workspace_id',getActiveWorkSpace())->first();
            if(!empty($employee))
            {
                if(module_is_active('CustomField')){
                    $employee->customField = \Modules\CustomField\Entities\CustomField::getData($employee, 'rotas','RotaEmployee');
                    $customFields             = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'rotas')->where('sub_module','RotaEmployee')->get();
                }else{
                    $customFields = null;
                }
                 $employeesId  = Employee::employeeIdFormat($employee->employee_id);
            }
            else
            {
                if(module_is_active('CustomField')){
                    $customFields =  \Modules\CustomField\Entities\CustomField::where('workspace_id',getActiveWorkSpace())->where('module', '=', 'rotas')->where('sub_module','RotaEmployee')->get();
                }else{
                    $customFields = null;
                }
                $employeesId  = Employee::employeeIdFormat($this->employeeNumber());
            }

            return view('rotas::employee.edit', compact('employee','user','employeesId', 'branches', 'departments', 'designations','customFields'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
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
        if (Auth::user()->isAbleTo('rotaemployee edit'))
        {
            $validator = \Validator::make(
                $request->all(),
                [
                    'dob' => 'required',
                    'gender' => 'required',
                    'phone' => 'required',
                    'address' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $user = User::where('id',$request->user_id)->first();
            if(empty($user))
            {
                return redirect()->back()->with('error', __('Something went wrong please try again.'));
            }
            if($user->name != $request->name)
            {
                $user->name = $request->name;
                $user->save();
            }
            $employee = Employee::findOrFail($id);

            $input    = $request->all();
            $employee->fill($input)->save();
            if(module_is_active('CustomField'))
            {
                \Modules\CustomField\Entities\CustomField::saveData($employee, $request->customField);
            }
            return redirect()->route('rotaemployee.index')->with('success', 'Employee successfully updated.');
        }
        else
        {
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

        if (Auth::user()->isAbleTo('rotaemployee delete'))
        {
            $employee     = Employee::where('user_id',$id)->where('workspace',getActiveWorkSpace())->first();
            if(!empty($employee)){

                if(module_is_active('CustomField')){
                    $customFields = \Modules\CustomField\Entities\CustomField::where('module','rotas')->where('sub_module','RotaEmployee')->get();
                    foreach($customFields as $customField)
                    {
                        $value = \Modules\CustomField\Entities\CustomFieldValue::where('record_id', '=', $employee->id)->where('field_id',$customField->id)->first();
                        if(!empty($value)){
                            $value->delete();
                        }
                    }
                }
                $employee->delete();
            }else{
                       return redirect()->back()->with('error', __('employee already delete.'));
            }

            return redirect()->back()->with('success', 'Employee successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function grid()
    {
        if(\Auth::user()->isAbleTo('rotaemployee manage'))
        {
            $employees = User::where('workspace_id',getActiveWorkSpace())
            ->leftjoin('employees', 'users.id', '=', 'employees.user_id')
            ->where('users.created_by', creatorId())->emp()
            ->select('users.*','users.id as ID','employees.*', 'users.name as name', 'users.email as email', 'users.id as id')
            ->get();

            return view('rotas::employee.grid', compact('employees'));
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    public function fileImportExport()
    {
        if(Auth::user()->isAbleTo('rotaemployee import'))
        {
            return view('rotas::employee.import');
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function fileImport(Request $request)
    {
        if(Auth::user()->isAbleTo('rotaemployee import'))
        {
            session_start();

            $error = '';

            $html = '';

            if ($request->file->getClientOriginalName() != '') {
                $file_array = explode(".", $request->file->getClientOriginalName());

                $extension = end($file_array);
                if ($extension == 'csv') {
                    $file_data = fopen($request->file->getRealPath(), 'r');

                    $file_header = fgetcsv($file_data);
                    $html .= '<table class="table table-bordered"><tr>';

                    for ($count = 0; $count < count($file_header); $count++) {
                        $html .= '
                                <th>
                                    <select name="set_column_data" class="form-control set_column_data" data-column_number="' . $count . '">
                                    <option value="">Set Count Data</option>
                                    <option value="name">Name</option>
                                    <option value="dob">DOB</option>
                                    <option value="gender">Gender</option>
                                    <option value="phone">Phone</option>
                                    <option value="address">Address</option>
                                    <option value="email">Email</option>
                                    <option value="password">Password</option>
                                    <option value="company_doj">Company Doj</option>
                                    <option value="account_holder_name">Account Holder Name</option>
                                    <option value="account_number">Account Number</option>
                                    <option value="bank_name">Bank Name</option>
                                    <option value="bank_identifier_code">Bank Identifier Code</option>
                                    <option value="branch_location">Branch Location</option>
                                    <option value="tax_payer_id">Tax Payer Id</option>
                                    </select>
                                </th>
                                ';

                    }
                    $html .= '</tr>';
                    $limit = 0;
                    while (($row = fgetcsv($file_data)) !== false) {
                        $limit++;

                        $html .= '<tr>';

                        for ($count = 0; $count < count($row); $count++) {
                            $html .= '<td>' . $row[$count] . '</td>';
                        }

                        $html .= '</tr>';

                        $temp_data[] = $row;

                    }
                    $_SESSION['file_data'] = $temp_data;
                } else {
                    $error = 'Only <b>.csv</b> file allowed';
                }
            } else {

                $error = 'Please Select CSV File';
            }
            $output = array(
                'error' => $error,
                'output' => $html,
            );

            echo json_encode($output);
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }

    }

    public function fileImportModal()
    {
        if(Auth::user()->isAbleTo('rotaemployee import'))
        {
            return view('rotas::employee.import_modal');
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function rotaemployeeImportdata(Request $request)
    {
        if(Auth::user()->isAbleTo('rotaemployee import'))
        {
            session_start();
            $html = '<h3 class="text-danger text-center">Below data is not inserted</h3></br>';
            $flag = 0;
            $html .= '<table class="table table-bordered"><tr>';
            $file_data = $_SESSION['file_data'];

            unset($_SESSION['file_data']);

            $user = \Auth::user();

            $roles = Role::where('created_by',creatorId())->where('name','staff')->first();
            foreach ($file_data as $row) {
                    $employees = Employee::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->Where('email', 'like',$row[$request->email])->get();
                    if($employees->isEmpty()){

                    try {
                        Employee::create([
                            'name' => $row[$request->name],
                            'dob' => $row[$request->dob],
                            'gender' => $row[$request->gender],
                            'phone' => $row[$request->phone],
                            'address' => $row[$request->address],
                            'email' => $row[$request->email],
                            'password' => $row[$request->password],
                            'employee_id' => $this->employeeNumber(),
                            'company_doj' => $row[$request->company_doj],
                            'account_holder_name' => $row[$request->account_holder_name],
                            'account_number' => $row[$request->account_number],
                            'bank_name' => $row[$request->bank_name],
                            'bank_identifier_code' => $row[$request->bank_identifier_code],
                            'branch_location' => $row[$request->branch_location],
                            'tax_payer_id' => $row[$request->tax_payer_id],
                            'created_by' => creatorId(),
                            'workspace' => getActiveWorkSpace(),
                        ]);

                        $user = User::create(
                            [
                                'name' =>$row[$request->name],
                                'email' => $row[$request->email],
                                'password' => Hash::make($row[$request->password]),
                                'email_verified_at' => date('Y-m-d h:i:s'),
                                'type' => !empty($roles->name)?$roles->name:'staff',
                                'lang' => 'en',
                                'workspace_id' => getActiveWorkSpace(),
                                'active_workspace' =>getActiveWorkSpace(),
                                'created_by' => creatorId(),
                                ]
                            );
                            $user->addRole($roles->id);
                    }
                    catch (\Exception $e)
                    {
                        $flag = 1;
                        $html .= '<tr>';

                        $html .= '<td>' . $row[$request->name] . '</td>';
                        $html .= '<td>' . $row[$request->dob] . '</td>';
                        $html .= '<td>' . $row[$request->gender] . '</td>';
                        $html .= '<td>' . $row[$request->phone] . '</td>';
                        $html .= '<td>' . $row[$request->address] . '</td>';
                        $html .= '<td>' . $row[$request->email] . '</td>';
                        $html .= '<td>' . $row[$request->password] . '</td>';
                        $html .= '<td>' . $row[$request->company_doj] . '</td>';
                        $html .= '<td>' . $row[$request->account_holder_name] . '</td>';
                        $html .= '<td>' . $row[$request->account_number] . '</td>';
                        $html .= '<td>' . $row[$request->bank_name] . '</td>';
                        $html .= '<td>' . $row[$request->bank_identifier_code] . '</td>';
                        $html .= '<td>' . $row[$request->branch_location] . '</td>';
                        $html .= '<td>' . $row[$request->account_holder_name] . '</td>';

                        $html .= '</tr>';
                    }
                }
                else
                {
                    $flag = 1;
                    $html .= '<tr>';

                    $html .= '<td>' . $row[$request->name] . '</td>';
                        $html .= '<td>' . $row[$request->dob] . '</td>';
                        $html .= '<td>' . $row[$request->gender] . '</td>';
                        $html .= '<td>' . $row[$request->phone] . '</td>';
                        $html .= '<td>' . $row[$request->address] . '</td>';
                        $html .= '<td>' . $row[$request->email] . '</td>';
                        $html .= '<td>' . $row[$request->password] . '</td>';
                        $html .= '<td>' . $row[$request->company_doj] . '</td>';
                        $html .= '<td>' . $row[$request->account_holder_name] . '</td>';
                        $html .= '<td>' . $row[$request->account_number] . '</td>';
                        $html .= '<td>' . $row[$request->bank_name] . '</td>';
                        $html .= '<td>' . $row[$request->bank_identifier_code] . '</td>';
                        $html .= '<td>' . $row[$request->branch_location] . '</td>';
                        $html .= '<td>' . $row[$request->account_holder_name] . '</td>';

                    $html .= '</tr>';
                }
            }

            $html .= '
                            </table>
                            <br />
                            ';
            if ($flag == 1)
            {

                return response()->json([
                            'html' => true,
                    'response' => $html,
                ]);
            } else {
                return response()->json([
                    'html' => false,
                    'response' => 'Data Imported Successfully',
                ]);
            }
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }
}
