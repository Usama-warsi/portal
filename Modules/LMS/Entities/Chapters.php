<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chapters extends Model
{
    use HasFactory;

    protected $fillable = [
        'header_id',
        'course_id',
        'name',
        'order_by',
        'type',
        'duration',
        'video_url',
        'video_file',
        'iframe',
        'text_content',
        'chapter_description',
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\ChaptersFactory::new();
    }

    public static function chapterstatus($id)
    {
        $student_id = \Auth::guard('students')->user()->id;
        $chepterstatus = ChapterStatus::where('chapter_id',$id)->where('student_id',$student_id)->first();

        return $chepterstatus;
    }
}
