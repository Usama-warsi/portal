<?php

namespace Modules\AIDocument\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiTemplatePrompt extends Model
{
    use HasFactory;

    protected $table='ai_template_prompts';
    protected $fillable = [
    	'template_id',
    	'key',
    	'value',
    	'created_by'
    ];
    
    protected static function newFactory()
    {
        return \Modules\AIDocument\Database\factories\AiTemplatePromptFactory::new();
    }
}
