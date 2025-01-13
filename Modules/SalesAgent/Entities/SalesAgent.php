<?php

namespace Modules\SalesAgent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\SalesAgent\Entities\SalesAgentPurchase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Rawilk\Settings\Support\Context;
use Modules\SalesAgent\Entities\Program;

class SalesAgent extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'agent_id',
        'user_id',
        'customer_id',
        'is_agent_active',
        'workspace',
        'created_by',
        'remember_token'
    ];
    
    protected static function newFactory()
    {
        return \Modules\SalesAgent\Database\factories\SalesAgentFactory::new();
    }

    public static function salesagentNumberFormat($number)
    {
        $data = !empty(company_setting('salesagent_prefix')) ? company_setting('salesagent_prefix') : '#AGENT0000';

        return $data . sprintf("%05d", $number);
    }

    public static function purchaseOrderNumberFormat($number)
    {
        $data = !empty(company_setting('sales_agent_purchase_order_prefix')) ? company_setting('sales_agent_purchase_order_prefix') : '#PUR000';

        return $data . sprintf("%05d", $number);
    }

    public static function totalOrder($id = null)
    {
        if($id){
            return SalesAgentPurchase::where('user_id', $id)->count();
        }else{
            return SalesAgentPurchase::where('user_id', \Auth::user()->id)->count();
        }
    }

    public static function totalOrderValue($id = null)
    {
        $totalPurchaseOrders = SalesAgentPurchase::where('workspace',getActiveWorkSpace());

            if($id){
                $totalPurchaseOrders->where('user_id', $id);
            }else{
                $totalPurchaseOrders->where('user_id', \Auth::user()->id);
            }
        
            $totalPurchaseOrders = $totalPurchaseOrders->get();  

        $totalSalesOrdersValue = [];
        foreach($totalPurchaseOrders as $order)
        {
            $totalSalesOrdersValue[]  = $order->getTotal();
        }
        $totalSalesOrdersValue = array_sum($totalSalesOrdersValue);

        return  $totalSalesOrdersValue;         
    }

    public static function totalOrderDelivered($id = null)
    {
        if($id){
            return SalesAgentPurchase::where('user_id', $id)->where('order_status','=',3)->count();
        }else{
            return SalesAgentPurchase::where('order_status','=',3)->count();
        }
    }

    public static function programsParticipated()
    {
        
    }

    public static function getAllProgramItems()
    {
        $programIds = Program::getProgramsBySalesAgentId();

        // $ProgramItems ;
        $finalCount = null;
        foreach ($programIds as $key => $value) {
            $programItems               = Program::getProductServicesItems($key);
            $programItemsCount[$key]    = count($programItems);
            $finalCount                 += $programItemsCount[$key];
        }
        return $finalCount == null ? 0 : $finalCount;
    }

    
}
