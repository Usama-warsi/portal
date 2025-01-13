<?php

namespace Modules\Retainer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RetainerProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_type',
        'product_id',
        'retainer_id',
        'quantity',
        'tax',
        'discount',
        'total',
    ];

    protected static function newFactory()
    {
        return \Modules\Retainer\Database\factories\RetainerProductFactory::new();
    }


    public function product()
    {
        $retainer =  $this->hasMany(Retainer::class, 'id', 'retainer_id')->first();

        if (!empty($retainer) && $retainer->retainer_module == "account") {
            if (module_is_active('ProductService')) {
                return $this->hasOne(\Modules\ProductService\Entities\ProductService::class, 'id', 'product_id')->first();
            } else {
                return [];
            }
        } elseif (!empty($retainer) && $retainer->retainer_module == "taskly") {
            if (module_is_active('Taskly')) {
                return  $this->hasOne(\Modules\Taskly\Entities\Task::class, 'id', 'product_id')->first();
            } else {
                return [];
            }
        }
    }


    public function tax($taxes)
    {
        $taxArr = explode(',', $taxes);

        $taxes = [];


        return $taxes;
    }
}
