<?php

namespace Modules\SalesAgent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\SalesAgent\Entities\Program;
use Modules\SalesAgent\Entities\ProgramItems;
use Modules\SalesAgent\Entities\PurchaseOrderItems;
use Modules\SalesAgent\Entities\SalesAgentUtility;

class SalesAgentPurchase extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\SalesAgent\Database\factories\SalesAgentPurchaseFactory::new();
    }

    public static $approvalStatus = [
        'Draft',
        'Approved',
        'Rejected',
        'Canceled',
    ];

    public static $purchaseOrder = [
        'New Order',
        'Confirmed',
        'Delivering',
        'Delivered',
        'Canceled',
    ];

    public static function getdiscount($program_id, $product_id, $price)
    {
        $program = Program::find($program_id);
        $programItems = ProgramItems::where('program_id', $program_id)
                                    ->whereRaw('FIND_IN_SET(?, items)', [$product_id])
                                    ->where(function ($query) use ($price) {
                                        $query->where('from_amount', '<=', $price)
                                            ->where('to_amount', '>=', $price);
                                    })
                                    ->orWhere(function ($query) use ($price) {
                                        $query->where('from_amount', '>=', $price)
                                            ->where('to_amount', '<=', $price);
                                    })
                                    ->get()->pluck('discount');
       return $programItems;                             
    }

    public static function getDiscountRange($program_id, $product_id)
    {
        $DiscountRange = ProgramItems::where('program_id', $program_id)
                                    ->whereRaw('FIND_IN_SET(?, items)', [$product_id])
                                    ->first(['from_amount', 'to_amount','discount']);
       return $DiscountRange;                             
    }

    public function user()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'user_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItems::class, 'purchase_order_id', 'id')->with(['product','program']);
    }

    public function salesagent()
    {
        return $this->hasOne(\Modules\SalesAgent\Entities\Customer::class, 'user_id' , 'user_id');
    }

    public function getSubTotal()
    {
        $subTotal = 0;
        foreach ($this->items as $product) {
            $subTotal += ($product->price * $product->quantity);
        }

        return $subTotal;
    }

    public function getTotalDiscount()
    {
        $totalDiscount = 0;
        foreach ($this->items as $product) {
            $totalDiscount += $product->discount;
        }

        return $totalDiscount;
    }

    public function getTotal()
    {
        return ($this->getSubTotal() + $this->getTotalTax()) - $this->getTotalDiscount();
    }

    public function getTotalTax()
    {
        $totalTax = 0;
        foreach ($this->items as $product) {
            $taxes = SalesAgentUtility::totalTaxRate($product->tax);
            $totalTax += ($taxes / 100) * ($product->price * $product->quantity - $product->discount) ;
        }

        return $totalTax;
    }

}
