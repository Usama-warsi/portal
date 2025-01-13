<?php

namespace Modules\Commission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class CommissionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'user_id',
        'commission_type',
        'commission_str',
        'workspace',
        'created_by',
        'commission_module'
    ];

    protected static function newFactory()
    {
        return \Modules\Commission\Database\factories\CommissionPlanFactory::new();
    }
    public static function CommissionPlan($user_ids)
    {
        $commissionModule = CommissionModule::whereIn('id',explode(',',$user_ids))->get();
    }

    public static function CommissionUser($user_id)
    {
        $commissionUser = User::whereIn('id',explode(',',$user_id))->get()->pluck('name')->toArray();
        return implode(',' ,$commissionUser);
    }
}
