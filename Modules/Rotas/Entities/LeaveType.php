<?php

namespace Modules\Rotas\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'days',
        'created_by',
        'workspace',
    ];

    protected static function newFactory()
    {
        return \Modules\Rotas\Database\factories\LeaveTypeFactory::new();
    }
}
