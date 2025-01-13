<?php

namespace Modules\SalesAgent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Setting;


class SalesAgentUtility extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\SalesAgent\Database\factories\SalesAgentUtilityFactory::new();
    }

    public static function GivePermissionToRoles($role_id = '',$rolename = '')
    {
        $agent_permissions  = [
            'salesagent dashboard ',
            'salesagent purchase manage',
            'salesagent purchase create',
            'salesagent purchase update',
            'salesagent purchase delete',
            'salesagent purchase show',
            'salesagent programs manage',
            'salesagent programs create',
            'salesagent programs update',
            'salesagent programs delete',
            'salesagent programs show',
            'salesagent product list',
            'salesagent customers manage',
            'salesagent customers create',
            'salesagent customers update',
            'salesagent customers delete',
            'salesagent customers show',
            'user profile manage',
            'user reset password',
        ];

        if($role_id == '')
        {
            // salesagent
            $roles = Role::where('name','salesagent')->get();

            foreach($roles as $role)
            {
                foreach($agent_permissions as $permission_v)
                {
                    $check = Permission::where('name',$permission_v)->exists();
                    if(!$check)
                    {
                        Permission::create(
                            [
                                'name' => $permission_v,
                                'guard_name' => 'web',
                                'module' => 'SalesAgent',
                                'created_by' => 0,
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s')
                            ]
                        );
                    }
                    // $permission = Permission::findByName($permission_v);
                    $permission = Permission::where('name',$permission_v)->first();
                    if(!$role->hasPermission($permission_v))
                    {
                        $role->givePermission($permission_v);
                    }
                }
            }
        }
        else
        {
            $role = Role::find($role_id);
            if($role->name == 'salesagent')
            {
                foreach($agent_permissions as $permission_c){
                    $permission = Permission::where('name',$permission_c)->first();
                    if(!$role->hasPermission($permission_c))
                    {
                        $role->givePermission($permission_c);
                    }
                }
            }
        }
    }

    public static function addNumberToString($originalString, $numberToAdd)
    {
        // Split the original string into an array of numbers
        $numbersArray = explode(',', $originalString);

        // Add the new number to the array
        $numbersArray[] = $numberToAdd;

        // Join the updated numbers to create the updated string
        $updatedString = implode(',', $numbersArray);

        return $updatedString;
    }

    public static function removeNumberFromString($originalString, $numberToRemove)
    {
        // Split the original string into an array of numbers
        $numbersArray = explode(',', $originalString);

        // Remove the specified number from the array
        $updatedNumbers = array_diff($numbersArray, [$numberToRemove]);

        // Join the updated numbers to create the updated string
        $updatedString = implode(',', $updatedNumbers);

        return $updatedString;
    }

    public static function totalTaxRate($taxes)
    {
        $taxData = \Modules\SalesAgent\Entities\SalesAgentUtility::getTaxData();

        if(module_is_active('ProductService'))
        {
            $taxArr  = explode(',', $taxes , );
            $taxRate = 0;
            foreach($taxArr as $tax)
            {
                // $tax     = \Modules\ProductService\Entities\Tax::find($tax);
                $taxRate += !empty($taxData[$tax]['rate']) ? $taxData[$tax]['rate'] : 0;

                $taxRate += !empty($tax->rate) ? $tax->rate : 0;
            }
            return $taxRate;
        }
        else
        {
            return 0;
        }
    }

    public static function tax($taxes)
    {
        if(module_is_active('ProductService'))
        {
            $taxArr = explode(',', $taxes);
            $taxes  = [];
            foreach($taxArr as $tax)
            {
                $taxes[] = \Modules\ProductService\Entities\Tax::find($tax);
            }

            return $taxes;
        }
        else
        {
            return [];
        }
    }

    public static function taxRate($taxRate, $price, $quantity,$discount= 0)
    {
        return (($price*$quantity) - $discount) * ($taxRate /100);
    }

    // Save Settings
    public static function saveSettings($post)
    {

        try {

            $getActiveWorkSpace = getActiveWorkSpace();
            $creatorId = creatorId();

            foreach ($post as $key => $value) {
                // Define the data to be updated or inserted
                $data = [
                    'key' => $key,
                    'workspace' => $getActiveWorkSpace,
                    'created_by' => $creatorId,
                ];

                // Check if the record exists, and update or insert accordingly
                Setting::updateOrInsert($data, ['value' => $value]);
            }
            // Settings Cache forget
            comapnySettingCacheForget();

            return true;

        } catch (\Exception $e) {
            return false;
        }
    }

    public static $rates;
    public static $data;

    public static function getTaxData()
    {
        $data = [];
        if(self::$rates == null)
        {
            $rates          =  \Modules\ProductService\Entities\Tax::where('workspace_id',getActiveWorkSpace())->get();
            self::$rates    =  $rates;
            foreach(self::$rates as $rate)
            {
                $data[$rate->id]['name']        = $rate->name;
                $data[$rate->id]['rate']        = $rate->rate;
                $data[$rate->id]['created_by']  = $rate->created_by;
            }
            self::$data    =  $data;
        }
        return self::$data;
    }

    public static $invoiceProductsData = null;

    public static function getOrderProductsData()
    {
        if (self::$invoiceProductsData === null) {
            $taxData = SalesAgentUtility::getTaxData();
            $InvoiceProducts = \DB::table('sales_agents_purchase_items')
                                ->select('purchase_order_id',
                                        \DB::raw('SUM(quantity) as total_quantity'),
                                        \DB::raw('SUM(discount) as total_discount'),
                                        \DB::raw('SUM(price * quantity)  as sub_total'),
                                        \DB::raw('GROUP_CONCAT(tax) as tax_values'))
                                ->groupBy('purchase_order_id')
                                ->get()
                                ->keyBy('purchase_order_id');

            $InvoiceProducts->map(function ($invoice) use ($taxData)
            {
                $taxArr = explode(',', $invoice->tax_values);
                $taxes = 0;
                $totalTax = 0;
                foreach ($taxArr as $tax) {
                    $taxes += !empty($taxData[$tax]['rate']) ? $taxData[$tax]['rate'] : 0;
                }
                $totalTax += ($taxes / 100) * ($invoice->sub_total);
                $invoice->total = $invoice->sub_total + $totalTax - $invoice->total_discount;
                return $invoice;
            });

            self::$invoiceProductsData = $InvoiceProducts;
        }

        return self::$invoiceProductsData;
    }

}
