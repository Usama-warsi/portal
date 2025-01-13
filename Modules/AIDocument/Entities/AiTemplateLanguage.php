<?php

namespace Modules\AIDocument\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiTemplateLanguage extends Model
{
    use HasFactory;

    protected $table='ai_template_languages';
    protected $fillable = [
    	'language',
    	'code',
    	'flag',
    	'status'
    ];
    
    protected static function newFactory()
    {
        return \Modules\AIDocument\Database\factories\AiTemplateLanguageFactory::new();
    }
}
