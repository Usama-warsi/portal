<?php

namespace Modules\Commission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommissionModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'module',
        'submodule'
    ];

    protected static function newFactory()
    {
        return \Modules\Commission\Database\factories\CommissionModuleFactory::new();
    }
}
