<?php

namespace Modules\Retainer\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use Rawilk\Settings\Support\Context;

class Retainer extends Model
{
    use HasFactory;

    protected $fillable = [
        'retainer_id',
        'user_id',
        'customer_id',
        'issue_date',
        'status',
        'category_id',
        'is_convert',
        'converted_invoice_id',
        'retainer_module',
        'created_by',
    ];

    public static $statues = [
        'Draft',
        'Open',
        'Accepted',
        'Declined',
        'Paid',
    ];



    public function customer()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function category()
    {
        return $this->hasOne(\Modules\ProductService\Entities\Category::class, 'id', 'category_id');
    }
    public function items()
    {
        return $this->hasMany(\Modules\Retainer\Entities\RetainerProduct::class, 'retainer_id', 'id');
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

    public function getTotalTax()
    {
        $totalTax = 0;
        foreach ($this->items as $product) {
            if (module_is_active('ProductService')) {
                $taxes = $this->totalTaxRate($product->tax);
            } else {
                $taxes = 0;
            }
            $totalTax += ($taxes / 100) * (($product->price * $product->quantity) - $product->discount);
        }

        return $totalTax;
    }

    public static function taxRate($taxRate, $price, $quantity, $discount = 0)
    {
        return (($price * $quantity) - $discount) * ($taxRate / 100);
    }


    public static function tax($taxes)
    {

        if (module_is_active('ProductService')) {
            $taxArr = explode(',', $taxes);
            $taxes  = [];
            foreach ($taxArr as $tax) {
                $taxes[] = \Modules\ProductService\Entities\Tax::find($tax);
            }

            return $taxes;
        } else {
            return [];
        }
    }

    public static function totalTaxRate($taxes)
    {
        $taxArr  = explode(',', $taxes);
        $taxRate = 0;
        if (module_is_active('ProductService')) {
            foreach ($taxArr as $tax) {
                $tax     =  \Modules\ProductService\Entities\Tax::find($tax);
                $taxRate += !empty($tax->rate) ? $tax->rate : 0;
            }
        }
        return $taxRate;
    }
    public function getTotal()
    {
        return ($this->getSubTotal() - $this->getTotalDiscount()) + $this->getTotalTax();
    }

    public static function retainerNumberFormat($number, $company_id = null, $workspace = null)
    {
        if (!empty($company_id) && empty($workspace)) {
            $company_settings = getCompanyAllSetting($company_id);
        } elseif (!empty($company_id) && !empty($workspace)) {
            $company_settings = getCompanyAllSetting($company_id, $workspace);
        } else {
            $company_settings = getCompanyAllSetting();
        }
        $data = !empty($company_settings['retainer_prefix']) ? $company_settings['retainer_prefix'] : '#RET00';

        return $data . sprintf("%05d", $number);
    }


    public function payments()
    {
        return $this->hasMany(RetainerPayment::class, 'retainer_id', 'id');
    }

    public function getDue()
    {

        $due = 0;
        foreach ($this->payments as $payment) {
            $due += $payment->amount;
        }

        return ($this->getTotal() - $due);
    }

    public static function customerRetainer($customerId)
    {
        $retainer = '';
        if (module_is_active('Account')) {
            $customer    =     \Modules\Account\Entities\Customer::find($customerId);

            $retainer     =     Retainer::where('user_id', $customer->user_id)->get();

        }
        return $retainer;
    }


}
