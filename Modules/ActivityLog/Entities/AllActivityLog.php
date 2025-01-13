<?php

namespace Modules\ActivityLog\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'module',
        'sub_module',
        'description',
        'url',
        'user_id',
        'workspace',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\ActivityLog\Database\factories\AllActivityLogFactory::new();
    }
    
}
