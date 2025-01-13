<?php

namespace Modules\Newsletter\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsletterModule extends Model
{
    use HasFactory;
    protected $table = 'newsletter_module';
    protected $fillable = [
        'module',
        'submodule',
        'field_json'
    ];


    protected static function newFactory()
    {
        return \Modules\Newsletter\Database\factories\NewsletterModuleFactory::new();
    }

}
