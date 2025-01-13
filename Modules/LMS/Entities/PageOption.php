<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'contents',
        'enable_page_header',
        'workspace_id',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\PageOptionFactory::new();
    }
    public static function create($data)
    {
        $obj          = new LmsUtility();
        $table        = with(new PageOption)->getTable();
        $data['slug'] = $obj->createSlug($table, $data['name']);
        $store        = static::query()->create($data);

        return $store;
    }
}
