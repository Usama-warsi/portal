<?php

namespace Modules\Retainer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RetainerPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'date',
        'amount',
        'account_id',
        'payment_method',
        'order_id',
        'currency',
        'txn_id',
        'payment_type',
        'receipt',
        'add_receipt',
        'reference',
        'description',
    ];

    public function bankAccount()
    {
        return $this->hasOne(\Modules\Account\Entities\BankAccount::class, 'id', 'account_id');
    }
}
