<?php

namespace Modules\Commission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommissionReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'commission_str',
        'commissionplan_id',
        'agent',
        'amount',
        'workspace',
        'created_by'
    ];

    protected static function newFactory()
    {
        return \Modules\Commission\Database\factories\CommissionReceiptFactory::new();
    }
    public static $status = [
        'Unpaid',
        'Paid'
    ];
}
