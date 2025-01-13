<?php

namespace Modules\Assets\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetsCategory extends Model
{
    use HasFactory;

    protected $table='assets_category';

    protected $fillable = [
        'name',
        'workspace',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\Assets\Database\factories\AssetsCategoryFactory::new();
    }

  
}
