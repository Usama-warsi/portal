<?php

namespace Modules\FormBuilder\Entities;

use Illuminate\Database\Eloquent\Model;

class UserForms extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'form_id',
        'created_by',
    ];
}
