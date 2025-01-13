<?php

namespace Modules\VideoHub\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoHubVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'module',
        'sub_module_id',
        'item_id',
        'type',
        'thumbnail',
        'video',
        'description',
        'workspace',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\VideoHub\Database\factories\VideoHubVideoFactory::new();
    }
    
    public function comments()
    {
        return $this->hasMany(VideoHubComment::class, 'video_id');
    }

    public function countVideoComments()
    {
        return $this->comments()->count();
    }

    public function countAttachment()
    {
        $attachments = VideoHubComment::where('video_id', '=', $this->id)->get();
        $count = 0;
        foreach ($attachments as $key => $attachment) {
            if (!empty($attachment->file)) {
                $count++;
            }
        }
        return $count;
    }
}
