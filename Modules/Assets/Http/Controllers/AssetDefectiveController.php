<?php

namespace Modules\Assets\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assets\Entities\Asset;
use Modules\Assets\Entities\AssetDefective;
use Modules\Assets\Events\CreateAssetDefective;
use Modules\Assets\Entities\AssetUtility;
use Modules\Hrm\Entities\Branch;

class AssetDefectiveController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('assets::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id)
    {
        $asset = Asset::find($id);
        $employees = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', Auth::user()->id)->emp()->get()->pluck('name', 'id');
        if (module_is_active('Hrm')) {
            $branches = Branch::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name','id');
            return view('assets::defective.create',compact('asset','employees','branches'));
        }
        return view('assets::defective.create',compact('asset','employees'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('assets create'))
        {

            $validator = \Validator::make(
                $request->all(),
                [
                    'type' => 'required',
                    'code' => 'required',
                    // 'branch' => 'required',
                    'employee_id' => 'required',
                    'date' => 'required',
                    'reason' => 'required',
                    'quantity' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $assetdefective                     = new AssetDefective();
            $assetdefective->type               = $request->type;
            $assetdefective->asset_id           = $id;
            $assetdefective->code               = $request->code;
            $assetdefective->branch             = $request->branch;
            $assetdefective->employee_id        = $request->employee_id;
            $assetdefective->date               = $request->date;
            $assetdefective->reason             = $request->reason;
            $assetdefective->quantity           = !empty($request->quantity) ? $request->quantity : null;
            $assetdefective->status             = !empty($request->status) ? $request->status : 'Defective';
            $assetdefective->image              = !empty($request->asset_image) ? $request->asset_image : null;
            $assetdefective->urgency_level      = !empty($request->urgency_level) ? $request->urgency_level : null;
            $assetdefective->created_by         = \Auth::user()->id;
            $assetdefective->workspace_id       = getActiveWorkSpace();
            $assetdefective->save();

            event(new CreateAssetDefective($request,$assetdefective));

            if($request->type == "withdraw"){

                $asset = Asset::find($id);
                $asset->quantity   = $asset->quantity - $request->quantity;
                $asset->save();

                AssetUtility::AssetQuantity($asset->id,'-'.$assetdefective->quantity,$assetdefective->date,'Withdraw');
            }else{

                $asset = Asset::find($id);
                $asset->quantity   = $asset->quantity - $request->quantity;
                $asset->save();

                AssetUtility::AssetQuantity($asset->id,'-'.$assetdefective->quantity,$assetdefective->date,'Defective');
            }

            return redirect()->route('asset.index')->with('success', __('Asset Defective successfully created.'));

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
        return view('assets::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('assets::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
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
}
