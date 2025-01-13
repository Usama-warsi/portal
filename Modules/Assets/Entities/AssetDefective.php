<?php

namespace Modules\Assets\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetDefective extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'asset_id',
        'type',
        'code',
        'branch',
        'employee_id',
        'date',
        'reason',
        'quantity',
        'status',
        'image',
        'urgency_level',
        'created_by',
        'workspace_id',
    ];

    protected static function newFactory()
    {
        return \Modules\Assets\Database\factories\AssetDefectiveFactory::new();
    }

    public static $type = [
        'Repair'      => 'Repair',
        'Defective'   => 'Defective',
        'Fail'        => 'Fail',
    ];

    public function module()
    {
       return $this->hasOne(Asset::class, 'id','asset_id');

    }
}
