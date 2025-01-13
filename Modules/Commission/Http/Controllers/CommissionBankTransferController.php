<?php

namespace Modules\Commission\Http\Controllers;

use App\Events\BankTransferRequestUpdate;
use App\Models\BankTransferPayment;
use App\Models\User;
use Google\Service\ServiceControl\Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Commission\Entities\CommissionReceipt;

class CommissionBankTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (\Auth::user()->isAbleTo('commission order')) {
            $bank_transfer_payments = BankTransferPayment::where('type','commission')->orderBy('created_at', 'DESC')->where('created_by', '=', creatorId())->where('workspace', '=', getActiveWorkSpace())->get();

            return view('commission::commissionBankTransfer.index',compact('bank_transfer_payments'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('commission::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('commission::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $bank_transfer_payment = BankTransferPayment::find($id);
        if($bank_transfer_payment)
        {
            return view('commission::commissionBankTransfer.edit', compact('bank_transfer_payment'));
        }
        else
        {
            return response()->json(['error' => __('Request data not found!')], 401);
        }
        return view('commission::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {

        $bank_transfer_payment = BankTransferPayment::find($id);
        if($bank_transfer_payment && $bank_transfer_payment->status == 'Pending')
        {
            $requests = json_decode($bank_transfer_payment->request);
            $bank_transfer_payment->status = $request->status;
            $bank_transfer_payment->save();
            $commition = CommissionReceipt::find($requests->commissionReceiptId);
            $commition->status = 1;
            $commition->save();
            if($request->status == 'Approved')
            {
                return redirect()->back()->with('success', __('Commission Bank-transfer request Approve successfully'));
            }
            else
            {
                return redirect()->back()->with('success', __('Commission Bank-transfer request Reject successfully'));
            }

        }
        else
        {
            return response()->json(['error' => __('Request data not found!')], 401);
        }
    }
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $bank_transfer_payment = BankTransferPayment::find($id);
        if($bank_transfer_payment)
        {
            if($bank_transfer_payment->attachment)
            {
                delete_file($bank_transfer_payment->attachment);
            }
            $bank_transfer_payment->delete();

            return redirect()->back()->with('success', __('Commission Bank-transfer request successfully deleted.'));
        }
        else
        {
             return redirect()->back()->with('error', __('Request data not found!'));
        }
    }
}
