<?php

namespace Modules\AIDocument\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiPromptResponse extends Model
{
    use HasFactory;

    protected $table='ai_prompt_responses';
    protected $fillable = [
    	'template_id',
    	'history_prompt_id',
    	'used_words',
    	'content',
    	'created_by'
    ];
    
    protected static function newFactory()
    {
        return \Modules\AIDocument\Database\factories\AiPromptResponseFactory::new();
    }
}
