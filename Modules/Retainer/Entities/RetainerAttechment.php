<?php

namespace Modules\Retainer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RetainerAttechment extends Model
{
    use HasFactory;

    protected $fillable = [
        'retainer_id',
        'file_name',
        'file_path',
        'file_size',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Retainer\Database\factories\RetainerAttechmentFactory::new();
    }
}