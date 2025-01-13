<?php

namespace Modules\Assets\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetExtra extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'asset_id',
        'code',
        'quantity',
        'date',
        'description',
    ];

    protected static function newFactory()
    {
        return \Modules\Assets\Database\factories\AssetExtraFactory::new();
    }

    
}
