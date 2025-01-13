<?php

namespace Modules\Assets\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'category',
        'purchase_date',
        'supported_date',
        'quantity',
        'serial_code',
        'assets_unit',
        'purchase_cost',
        'location',
        'asset_image',
        'description',
        'branch',
        'warranty_period',
        'created_by',
        'workspace_id',
    ];

    protected static function newFactory()
    {
        return \Modules\Assets\Database\factories\AssetFactory::new();
    }

    public function categories()
    {
       return $this->hasOne(AssetsCategory::class, 'id','category');
    }
}
