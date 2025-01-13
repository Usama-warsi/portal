<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'name',
        'card_number',
        'card_exp_month',
        'card_exp_year',
        'student_id',
        'course',
        'price',
        'coupon',
        'coupon_json',
        'discount_price',
        'price_currency',
        'payment_type',
        'payment_status',
        'receipt',
        'store_id',
        'user_id',
        'subscription_id',
        'payer_id',
        'payment_frequency',
        'txn_id',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\CourseOrderFactory::new();
    }
}
