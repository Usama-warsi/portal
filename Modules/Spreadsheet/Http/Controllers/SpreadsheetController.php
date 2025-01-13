<?php

namespace Modules\Spreadsheet\Http\Controllers;

use App\Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Google\Service\FirebaseRules\FunctionMock;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Contract\Entities\Contract;
use Modules\Lead\Entities\Deal;
use Modules\Lead\Entities\Lead;
use Modules\Spreadsheet\Entities\Related;
use Modules\CMMS\Entities\Workorder;
use Modules\Spreadsheet\Entities\Spreadsheets;
use Modules\Taskly\Entities\Project;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Modules\Spreadsheet\Events\CreateSpreadsheet;
use Modules\Spreadsheet\Events\UpdateSpreadsheet;
use Modules\Spreadsheet\Events\DestroySpreadsheet;


class SpreadsheetController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request, $parent_id = 0)
    {
        if(Auth::user()->isAbleTo('spreadsheet manage'))
        {
            $related_id = null;

            $related =null;
            if(Auth::user()->type == 'company')
            {
                $spreadsheets = Spreadsheets::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->with('relatedGet')->where('parent_id',$parent_id);
            }else{
                $spreadsheets = Spreadsheets::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->with('relatedGet')->whereRaw("FIND_IN_SET(?, user_assign)", [Auth::user()->id])->where('parent_id',$parent_id);
            }

            if(isset($request->related) && isset($request->related_id))
            {
                $related = $request->related;
                $related_id = is_array($request->related_id) ? $request->related_id : explode(',',$request->related_id);
                $spreadsheet_releted = Related::find($request->related);

                if($spreadsheet_releted)
                {
                    $spreadsheets = $spreadsheets->where('related', $spreadsheet_releted->id)
                    ->where(function ($query) use ($related_id) {
                        foreach ($related_id as $rela_id) {
                            $query->whereRaw("FIND_IN_SET(?, related_assign)", [$rela_id]);
                        }
                    });

                    // $spreadsheets = $spreadsheets->where('related',$spreadsheet_releted->id)->whereRaw("FIND_IN_SET(?, related_assign)", [5,9]);
                }
            }

            $spreadsheets = $spreadsheets->get();
            $relateds = Related::get()->pluck('related', 'id');
            if(!empty($related_id)){
                $related_id = implode(',',$related_id);

            }

            return view('spreadsheet::spreadsheet.index', compact('spreadsheets','parent_id','related_id','related','relateds'));
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
    public function create($parent_id = 0)
    {
        return view('spreadsheet::spreadsheet.spreadsheet',compact ('parent_id'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $updatedData = $request->updatedData;
        $parent_id = $request->parent_id;

        $spreadsheet = new Spreadsheet();
        $worksheet_temp = $spreadsheet->getSheetByName('Worksheet'); // Get the default worksheet
        $spreadsheet->removeSheetByIndex($spreadsheet->getIndex($worksheet_temp)); // Remove the default worksheet

        foreach ($updatedData as $sheetData) {

            $sheet = new Worksheet($spreadsheet, $sheetData['name']);

            $spreadsheet->addSheet($sheet);

            if (isset($sheetData['freeze']))
            {
                $sheet->freezePane($sheetData['freeze']);
            }

            $rowCount = 1;

            if (isset($sheetData['rows']) && is_array($sheetData['rows'])) {

                foreach ($sheetData['rows'] as $rowKey => $rowData) {

                    if ($rowKey === 'len')
                    {
                        $rowCount += (int) $rowData;
                    }else{

                        $colCount = 0;

                        foreach ($rowData['cells']  as $key => $cellData) {

                            $cellValue = isset($cellData['text'])?$cellData['text']:"";

                            $sheet->setCellValueByColumnAndRow($key+1, $rowKey+1, $cellValue);

                            $colCount++;
                        }
                        $rowCount++;
                    }
                }
            }

            // Set column widths if specified
            if (isset($sheetData['cols']) && is_array($sheetData['cols'])) {
                foreach ($sheetData['cols'] as $colKey => $colWidth) {
                    if ($colKey === 'len') {
                        $sheet->getDefaultColumnDimension()->setWidth($colWidth);
                    } else {
                        $sheet->getColumnDimension($colKey)->setWidth($colWidth);
                    }
                }
            }
        }

        $writer = new Xlsx($spreadsheet);

        $filename = $request->file_name.time() . '.xlsx';

        $tempFilePath = tempnam(sys_get_temp_dir(), 'example');
        $writer->save($tempFilePath);

        $storageDisk = admin_setting('storage_setting');

        $save = Storage::disk($storageDisk)->putFileAs(
            'spreadsheet',
            new \Illuminate\Http\File($tempFilePath),
            $filename
        );

        // Determine the URL based on the storage disk
        if ($storageDisk == 'wasabi') {
            $url = $save;
        } elseif ($storageDisk == 's3') {
            $url = $save;
        } else {
            $url = 'uploads/' . $save;
        }

        // Set appropriate permissions for the saved file (optional)
        chmod(Storage::disk($storageDisk)->path("spreadsheet/$filename"), 0777);

        $spreadsheets = new Spreadsheets(); // Replace with your actual model name

        $spreadsheets->folder_name          = $filename;
        $spreadsheets->path                 = $url;
        $spreadsheets->parent_id            = $parent_id;
        $spreadsheets->user_id              = Auth::user()->id;
        $spreadsheets->type                 = 'file';
        $spreadsheets->created_by           = creatorId();
        $spreadsheets->workspace            = getActiveWorkSpace();
        $spreadsheets->save();

        event(new CreateSpreadsheet($request,$spreadsheets));
        // Optionally, you can return a JSON response with the URL
        return response()->json(['url' => $url]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $spreadsheet = Spreadsheets::findOrFail($id);
        $spreadsheetPath = base_path($spreadsheet->path);
        $spreadsheetData = [];
        try {

            $spreadsheet = IOFactory::load($spreadsheetPath);

            foreach ($spreadsheet->getWorksheetIterator() as $worksheet)
            {
                $sheetName = $worksheet->getTitle();

                $spreadsheetData[$sheetName] = [];

                foreach ($worksheet->toArray() as $row) {
                    $spreadsheetData[$sheetName][] = $row;
                }
            }

        } catch (\Exception $e){

            return redirect()->route('spreadsheet.index')->with('error', $e->getMessage());
        }

        return view('spreadsheet::spreadsheet.spreadsheet_show',compact('spreadsheetData','spreadsheetPath'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $sheet = Spreadsheets::findOrFail($id);
        $spreadsheetPath = base_path($sheet->path);
        $spreadsheetData = [];
        try {

            $spreadsheet = IOFactory::load($spreadsheetPath);

            foreach ($spreadsheet->getWorksheetIterator() as $worksheet)
            {
                $sheetName = $worksheet->getTitle();

                $spreadsheetData[$sheetName] = [];

                foreach ($worksheet->toArray() as $row) {
                    $spreadsheetData[$sheetName][] = $row;
                }
            }

        } catch (\Exception $e){

            return redirect()->route('spreadsheet.index')->with('error', $e->getMessage());
        }


        return view('spreadsheet::spreadsheet.spreadsheet_edit', compact('sheet','spreadsheetData','spreadsheetPath'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $updatedData = $request->updatedData;
        $parent_id = $request->parent_id;
        $id = $request->id;
        $spreadsheetfile =  Spreadsheets::findOrFail($id);

        $spreadsheet = new Spreadsheet();
        $worksheet_temp = $spreadsheet->getSheetByName('Worksheet'); // Get the default worksheet
        $spreadsheet->removeSheetByIndex($spreadsheet->getIndex($worksheet_temp)); // Remove the default worksheet

        foreach ($updatedData as $sheetData) {

            $sheet = new Worksheet($spreadsheet, $sheetData['name']);

            $spreadsheet->addSheet($sheet);

            if (isset($sheetData['freeze']))
            {
                $sheet->freezePane($sheetData['freeze']);
            }

            $rowCount = 1;

            if (isset($sheetData['rows']) && is_array($sheetData['rows'])) {

                foreach ($sheetData['rows'] as $rowKey => $rowData) {

                    if ($rowKey === 'len')
                    {
                        $rowCount += (int) $rowData;
                    }else{

                        $colCount = 0;

                        foreach ($rowData['cells']  as $key => $cellData) {

                            $cellValue = isset($cellData['text'])?$cellData['text']:"";

                            $sheet->setCellValueByColumnAndRow($key+1, $rowKey+1, $cellValue);

                            $colCount++;
                        }
                        $rowCount++;
                    }
                }
            }

            // Set column widths if specified
            if (isset($sheetData['cols']) && is_array($sheetData['cols'])) {
                foreach ($sheetData['cols'] as $colKey => $colWidth) {
                    if ($colKey === 'len') {
                        $sheet->getDefaultColumnDimension()->setWidth($colWidth);
                    } else {
                        $sheet->getColumnDimension($colKey)->setWidth($colWidth);
                    }
                }
            }
        }

        $writer = new Xlsx($spreadsheet);

        $filename = explode('/',$spreadsheetfile->path) ;
        $filename =end($filename);

        $tempFilePath = tempnam(sys_get_temp_dir(), 'example');
        $writer->save($tempFilePath);

        $storageDisk = admin_setting('storage_setting');

        $save = Storage::disk($storageDisk)->putFileAs(
            'spreadsheet',
            new \Illuminate\Http\File($tempFilePath),
            $filename
        );

        // Determine the URL based on the storage disk
        if ($storageDisk == 'wasabi') {
            $url = $save;
        } elseif ($storageDisk == 's3') {
            $url = $save;
        } else {
            $url = 'uploads/' . $save;
        }
        chmod(Storage::disk($storageDisk)->path("spreadsheet/$filename"), 0777);

        return response()->json(['url' => $url]);
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

    public function foldercreate($parent_id = 0)
    {
        return view('spreadsheet::spreadsheet.folder_create',compact('parent_id'));
    }

    public function folderstore(Request $request,$parent_id = 0)
    {
        if (Auth::user()->isAbleTo('spreadsheet create'))
        {
            $validatorArray = [
                'name' => 'required|max:120',
            ];

            $validator = \Validator::make(
                $request->all(),
                $validatorArray
            );
            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $spreadsheets                       = new Spreadsheets();
            $spreadsheets->folder_name          = $request->name;
            $spreadsheets->path                 = null;
            $spreadsheets->parent_id            = $parent_id;
            $spreadsheets->user_id              = Auth::user()->id;
            $spreadsheets->type                 = 'folder';
            $spreadsheets->created_by           = creatorId();
            $spreadsheets->workspace            = getActiveWorkSpace();
            $spreadsheets->save();

            event(new CreateSpreadsheet($request,$spreadsheets));

            return redirect()->route('spreadsheet.index')->with('success', __('Folder successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function folderedit($id)
    {

        $spreadsheet = Spreadsheets::find($id);
        return view('spreadsheet::spreadsheet.folder_edit', compact('spreadsheet'));
    }

    public function folderupdate(Request $request, $id)
    {
        // if (\Auth::user()->isAbleTo('spreadsheet edit')) {
            $spreadsheets                  = Spreadsheets::find($id);
            $spreadsheets->folder_name     = $request->name;
            $spreadsheets->update();

            event(new UpdateSpreadsheet($request,$spreadsheets));

            return redirect()->back()->with('success', __('Folder Successfully Updated!'));

        // } else{
        //     return redirect()->back()->with('error', __('Permission Denied.'));
        // }
    }

    public function foldershow($id)
    {
        $spreadsheet = Spreadsheets::find($id);
        return view('spreadsheet::spreadsheet.folder_show', compact('spreadsheet'));
    }

    public function related($id)
    {
        $spreadsheet = Spreadsheets::find($id);
        $related = Related::get()->pluck('related', 'id');

        if($spreadsheet->related != null)
        {
            $related_name = Related::find($spreadsheet->related);

            $value = null;

            if($related_name->related == 'Project')
            {
                $value = Project::where('workspace', getActiveWorkSpace())->projectonly()->pluck('name','id');
            }
            elseif($related_name->related == 'Contract')
            {
                $value = Contract::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('subject','id');
            }
            elseif($related_name->related == 'Lead')
            {
                $value = Lead::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->pluck('name','id');
            }
            elseif($related_name->related == 'Deal')
            {
                $value = Deal::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->pluck('name','id');
            }
            elseif($related_name->related == 'Tour & Travel')
            {
                $tour = 'Tour';
                $value = explode(',', $tour);
            }
            elseif($related_name->related == 'Hospital')
            {
                $hospital = 'Hospital';
                $value = explode(',', $hospital);
            }
            elseif($related_name->related == 'Purchase')
            {
                $purchase = 'Purchase';
                $value = explode(',', $purchase);
            }
            elseif($related_name->related == 'CMMS')
            {
                $value = WorkOrder::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('wo_name','id');
            }
            elseif($related_name->related == 'LMS')
            {
                $course = 'Course';
                $value = explode(',', $course);
            }
            elseif($related_name->related == 'Sales')
            {
                $sales = 'Salesaccount';
                $value = explode(',', $sales);
            }
            elseif($related_name->related == 'School & Institute')
            {
                $admission = 'Admission';
                $value = explode(',', $admission);
            }
            elseif($related_name->related == 'Newspaper')
            {
                $newspaper = 'Newspaper';
                $value = explode(',', $newspaper);
            }
            elseif($related_name->related == 'Assets')
            {
                $assets = 'Assets';
                $value = explode(',', $assets);
            }

            return view('spreadsheet::spreadsheet.folder_related', compact('related','id','spreadsheet','value'));
        }
            return view('spreadsheet::spreadsheet.folder_related', compact('related','id','spreadsheet'));

    }

    public function relatedStore(Request $request,$id)
    {
        if(!$request->value){
            return redirect()->route('spreadsheet.index')->with('error', __('Please Select Module.'));
        }
        $spreadsheet                    = Spreadsheets::find($id);
        $spreadsheet->related           = $request->input('related_id');
        $spreadsheet->related_assign    = implode(",", $request->value);
        $spreadsheet->save();

        return redirect()->route('spreadsheet.index')->with('success', __('Updated Successfully.'));
    }

    public function relatedGet(Request $request)
    {
        if($request->related_id)
        {
            $related = Related::find($request->related_id);
            $value = null;
            if($related != null){
                if($related->related == 'Project')
                {
                    $value = Project::where('workspace', getActiveWorkSpace())->pluck('name','id');
                }
                elseif($related->related == 'Contract')
                {
                    $value = Contract::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('subject','id');
                }
                elseif($related->related == 'Lead')
                {
                    $value = Lead::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->pluck('name','id');
                }
                elseif($related->related == 'Deal')
                {
                    $value = Deal::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->pluck('name','id');
                }
                elseif($related->related == 'Tour & Travel')
                {
                    $tour = 'Tour';
                    $value = explode(',', $tour);
                }
                elseif($related->related == 'Hospital')
                {
                    $hospital = 'Hospital';
                    $value = explode(',', $hospital);
                }
                elseif($related->related == 'Purchase')
                {
                    $purchase = 'Purchase';
                    $value = explode(',', $purchase);
                }
                elseif($related->related == 'CMMS')
                {
                    $value = WorkOrder::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('wo_name','id');
                }
                elseif($related->related == 'LMS')
                {
                    $course = 'Course';
                    $value = explode(',', $course);
                }
                elseif($related->related == 'Sales')
                {
                    $sales = 'Salesaccount';
                    $value = explode(',', $sales);
                }
                elseif($related->related == 'School & Institute')
                {
                    $admission = 'Admission';
                    $value = explode(',', $admission);
                }
                elseif($related->related == 'Newspaper')
                {
                    $newspaper = 'Newspaper';
                    $value = explode(',', $newspaper);
                }
                elseif($related->related == 'Assets')
                {
                    $assets = 'Assets';
                    $value = explode(',', $assets);
                }
            }
            return response()->json($value);
        }else{
            return response()->json(['error' => __('Permission Denied')]);
        }
    }

    public function foldershare($id)
    {

        $spreadsheet = Spreadsheets::find($id);
        $sub_folders = $this->getSubfoldersAndFiles($spreadsheet);
        $staff       = User::where('created_by',creatorId())->where('workspace_id',getActiveWorkSpace())->get()->pluck('name', 'id');

        return view('spreadsheet::spreadsheet.folder_share', compact('staff','spreadsheet'));
    }

    public function folderdestroy($id)
    {
        $spreadsheet       = Spreadsheets::find($id);
        if (!$spreadsheet) {
            return redirect()->back()->with('error', 'Folder not found.');
        }
        $spreadsheet->delete();

        event(new DestroySpreadsheet($spreadsheet));

        return redirect()->back()->with('success', 'Successfully deleted.');
    }

    public function share(Request $request, $id)
    {
        $fieldsArray = [];

        if(isset($request->fields) && count($request->fields) > 0)
        {
            foreach ($request->fields as $key => $value) {
                $fieldsArray[] = [
                    'user_id'        => array_key_exists("user_id",$value) ?  $value['user_id'] : null,
                    'permission'   => array_key_exists("permission",$value) ?  $value['permission'] : null,
                ];
            }
        }
        $userIds = [];

        if(!$request->fields){

            return redirect()->route('spreadsheet.index')->with('error', __('Please Select Staff.'));
        }

        foreach ($request->fields as $values)
        {
            $userIds[] = $values["user_id"];
        }

        $spreadsheets                  = Spreadsheets::find($id);
        $spreadsheets->user_assign     = implode(",", $userIds);
        $spreadsheets->user_and_per    = json_encode($fieldsArray);
        $spreadsheets->save();
        if ($spreadsheets) {
            // Handle the case where the parent folder doesn't exist
            $this->getSubfoldersAndFiles($spreadsheets);
        }

        return redirect()->route('spreadsheet.index')->with('success', __('Successfully Share.'));

    }

    private function getSubfoldersAndFiles($folder)
    {
        $subfolders = Spreadsheets::where('parent_id', $folder->id)->get();
        foreach ($subfolders as $subfolder) {

            $subfolder->user_assign = $folder->user_assign;
            $subfolder->user_and_per = $folder->user_and_per;
            $subfolder->save();
            $this->getSubfoldersAndFiles($subfolder);

        }
    }
}
