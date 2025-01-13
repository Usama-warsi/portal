<?php

namespace Modules\Assets\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assets\Entities\Asset;
use Modules\Assets\Entities\AssetDistribution;
use Modules\Assets\Events\CreateAssetDistribution;
use Modules\Assets\Entities\AssetUtility;
use Modules\Hrm\Entities\Branch;

class AssetDistributionsController extends Controller
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
        $employees = [];
        $employees = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', Auth::user()->id)->emp()->get()->pluck('name', 'id');
        if (module_is_active('Hrm')) {
            $branches = Branch::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name','id');
            return view('assets::distributions.create',compact('asset','employees','branches'));
        }
        return view('assets::distributions.create',compact('asset','employees'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('assets create')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'employee_id'   => 'required',
                    'serial_code'   => 'required',
                    'assign_date'   => 'required',
                    'return_date'   => 'required',
                    'dist_number'   => 'required',
                    'dis_quantity'  => 'required',
                    'description'  => 'required',
                    // 'assets_branch' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $asset = Asset::find($id);

            if($asset->quantity >= $request->dis_quantity){

                $asset->quantity   = $asset->quantity - $request->dis_quantity;
                
            }else{
                return redirect()->back()->with('error', __('Assets Out Of Stock.'));
            }
            $asset->save();

            $assetdistribution                  = new AssetDistribution();
            $assetdistribution->employee_id     = $request->employee_id;
            $assetdistribution->serial_code     = $request->serial_code;
            $assetdistribution->dist_number     = $request->dist_number;
            $assetdistribution->assign_date     = $request->assign_date;
            $assetdistribution->return_date     = $request->return_date;
            $assetdistribution->quantity        = $request->dis_quantity;
            $assetdistribution->assets_branch   = !empty($request->assets_branch) ? $request->assets_branch : null ;
            $assetdistribution->notes           = !empty($request->description) ? $request->description : null;
            $assetdistribution->save();

            event(new CreateAssetDistribution($request,$assetdistribution));

            AssetUtility::AssetQuantity($asset->id,'-'.$assetdistribution->quantity,$assetdistribution->assign_date,'Distribute');

            return redirect()->route('asset.index')->with('success', __('Asset Distribution successfully created.'));

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
