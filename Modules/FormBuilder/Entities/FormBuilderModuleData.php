<?php

namespace Modules\FormBuilder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormBuilderModuleData extends Model
{
    use HasFactory;

    protected $fillable = [

        'form_id',
        'module',
        'response_data',
        'workspace',
    ];
    
    protected static function newFactory()
    {
        return \Modules\FormBuilder\Database\factories\FormBuilderModuleDataFactory::new();
    }
}
