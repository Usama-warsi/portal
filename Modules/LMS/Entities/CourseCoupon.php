<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'discount',
        'limit',
        'description',
        'workspace_id',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\CourseCouponFactory::new();
    }

    public function product_coupon()
    {
        return $this->hasMany('Modules\LMS\Entities\CourseOrder', 'coupon', 'id')->count();
    }
}
