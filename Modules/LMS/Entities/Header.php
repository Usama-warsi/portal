<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Header extends Model
{
    use HasFactory;

    protected $fillable = [
        'Workspace_id',
        'course',
        'title',
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\HeaderFactory::new();
    }

    public function chapters_data()
    {
        return $this->hasMany('Modules\LMS\Entities\Chapters', 'header_id', 'id');
    }
}
