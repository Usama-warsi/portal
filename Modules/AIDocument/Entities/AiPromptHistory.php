<?php

namespace Modules\AIDocument\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiPromptHistory extends Model
{
    use HasFactory;

    protected $table='ai_prompt_histories';
    protected $fillable = [
    	'template_id',
    	'doc_name',
    	'model',
    	'creativity',
    	'max_tokens',
    	'max_results',
    	'prompt',
    	'language',
    	'prompt_fields',
        'workspace',
    	'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\AIDocument\Database\factories\AiPromptHistoryFactory::new();
    }
}
