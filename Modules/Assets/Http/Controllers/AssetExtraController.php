<?php

namespace Modules\Assets\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assets\Entities\Asset;
use Modules\Assets\Entities\AssetExtra;
use Modules\Assets\Entities\AssetUtility;
use Modules\Assets\Events\CreateAssetExtra;

class AssetExtraController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('assets::index', compact('assets'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id)
    {
        $asset = Asset::find($id);
        return view('assets::extra.create', compact('asset'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request,$id)
    {
        if (Auth::user()->isAbleTo('assets create'))
        {
            $validator = \Validator::make(
                $request->all(),
                [
                    'serial_code' => 'required',
                    'quantity' => 'required',
                    'date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $asset = Asset::find($id);
            $asset->quantity   = $asset->quantity + $request->quantity;

            $asset->save();

            $assetextra                 = new AssetExtra();
            $assetextra->asset_id       = $asset->id;
            $assetextra->code           = $request->serial_code;
            $assetextra->quantity       = $request->quantity;
            $assetextra->date           = $request->date;
            $assetextra->description    = $request->description;
            $assetextra->save();

            event(new CreateAssetExtra($request,$assetextra));

            AssetUtility::AssetQuantity($assetextra->asset_id,$assetextra->quantity,$assetextra->date,'Extra');

            return redirect()->route('asset.index')->with('success', __('Extra asset successfully added.'));
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
