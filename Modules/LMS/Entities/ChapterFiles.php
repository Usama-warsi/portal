<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChapterFiles extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'chapter_id',
        'chapter_files',
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\ChapterFilesFactory::new();
    }
}
