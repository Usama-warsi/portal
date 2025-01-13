<?php

namespace Modules\SalesAgent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderItems extends Model
{
    use HasFactory;

    protected $table = 'sales_agents_purchase_items';

    protected $fillable = [
        'id',
        'purchase_order_id',
        'program_id',
        'item_id',
        'quantity',
        'tax',
        'discount',
        'price',
        'description',
    ];
    
    protected static function newFactory()
    {
        return \Modules\SalesAgent\Database\factories\PurchaseOrderItemsFactory::new();
    }

    public function product()
    {
        if(module_is_active('ProductService'))
        {
         return $this->hasOne(\Modules\ProductService\Entities\ProductService::class, 'id', 'item_id');
        }
    }

    public function program()
    {
        return $this->hasOne(\Modules\SalesAgent\Entities\Program::class, 'id', 'program_id');
    }

    public function itemTaxPrice($item)
    {
        $taxes = explode(',',$item->tax);
        $taxes = \App\Models\Invoice::tax($item->tax);
        $data = 0;
        $totalTaxRate = 0;
        $totalTaxPrice = 0;
        
        foreach ($taxes as $tax){
            if($tax != null){
                $taxPrice = \App\Models\Invoice::taxRate($tax->rate, $item->price, $item->quantity, $item->discount);
                $totalTaxPrice += $taxPrice;
            }
        }
        
        return $totalTaxPrice;
    }

    public static function totalTaxRate($taxes)
    {
        if(module_is_active('ProductService'))
        {
            $taxArr  = explode(',', $taxes);
            $taxRate = 0;
            foreach($taxArr as $tax)
            {
                $tax     = \Modules\ProductService\Entities\Tax::find($tax);
                $taxRate += !empty($tax->rate) ? $tax->rate : 0;
            }
            return $taxRate;
        }
        else
        {
            return 0;
        }
    }
}
