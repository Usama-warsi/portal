<?php

namespace Modules\FormBuilder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormBuilder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'form_email',
        'recipient_emails',
        'cc_emails',
        'code',
        'created_by',
        'is_active',
        'is_lead_active',
        'module',
        'workspace',
    ];

    public static $fieldTypes = [
        'text' => 'Text',
        'email' => 'Email',
        'number' => 'Number',
        'date' => 'Date',
        'textarea' => 'Textarea',
        'hidden' => 'Hidden',
    ];

    protected static function newFactory()
    {
        return \Modules\FormBuilder\Database\factories\FormBuilderFactory::new();
    }

    public function response()
    {
        return $this->hasMany(FormResponse::class, 'form_id', 'id')->orderBy('created_at', 'desc');
    }

    public function form_field()
    {
        return $this->hasMany(FormField::class, 'form_id', 'id');
    }

    public function fieldResponse()
    {
        return $this->hasOne(FormBuilderModuleData::class, 'form_id', 'id');
    }
}
