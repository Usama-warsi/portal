<?php

namespace Modules\Rotas\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'name',
        'workspace',
        'created_by'
    ];

    public function branch(){
        return $this->hasOne(Branch::class,'id','branch_id');
    }

    protected static function newFactory()
    {
        return \Modules\Rotas\Database\factories\DepartmentFactory::new();
    }
}
