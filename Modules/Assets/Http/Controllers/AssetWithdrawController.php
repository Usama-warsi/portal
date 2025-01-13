<?php

namespace Modules\Assets\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assets\Entities\Asset;
use Modules\Assets\Entities\AssetDefective;
use Modules\Assets\Entities\AssetHistory;
use Modules\Assets\Entities\AssetUtility;

class AssetWithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('assets manage'))
        {
            $assetdefectives = AssetDefective::where('type' ,'=', 'defective')->where('created_by', '=', creatorId())->where('workspace_id',getActiveWorkSpace())->get();
            return view('assets::withdraw.index', compact('assetdefectives'));
        }else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('assets::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request ,$id)
    {
        $assetdefective = AssetDefective::find($id);
        if($assetdefective)
        {
            if($request->status != $assetdefective->status)
            {
                if($request->status == 'Repair')
                {
                    $asset = Asset::where('id',$assetdefective->asset_id)->first();
                    $asset->quantity   = $asset->quantity + $assetdefective->quantity;
                    $asset->save();

                    $history                 = new AssetHistory();
                    $history->assets_id      = $assetdefective->asset_id;
                    $history->quantity       = $assetdefective->quantity;
                    $history->type           = $request->status;
                    $history->date           = date('Y-m-d');


                    $history->save();
                }
                $assetdefective->status = $request->status;
                $assetdefective->save();
            }
            else
            {
                return redirect()->back()->with('error', __('Asset Successfully Repair.'));
            }

        }
        return redirect()->route('assets.defective.index')->with('success', __('Asset Defective status successfully change.'));


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

    public function status($id)
    {
        $assetdefective = AssetDefective::find($id);
        $asset = Asset::where('id',$assetdefective->asset_id)->get();

        return view('assets::withdraw.status',compact('assetdefective','asset'));
    }
}
