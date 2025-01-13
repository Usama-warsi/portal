<?php

namespace Modules\Rotas\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'user_id',
        'Leave_type_id',
        'applied_on',
        'start_date',
        'end_date',
        'total_leave_days',
        'leave_reason',
        'remark',
        'status',
        'workspace',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\Rotas\Database\factories\LeaveFactory::new();
    }

    // public static function getLeaveType($leave_type)
    // {
    //     $leavetype = LeaveType::where('id', '=', $leave_type)->first();
    //     $leavetype = !empty($leavetype) ? $leavetype : null;
    //     return $leavetype;
    // }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }
}
