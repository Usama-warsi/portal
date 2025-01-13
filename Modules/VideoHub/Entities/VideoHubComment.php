<?php

namespace Modules\VideoHub\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoHubComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'file',
        'comment',
        'parent',
        'comment_by',
        'workspace',
    ];

    protected static function newFactory()
    {
        return \Modules\VideoHub\Database\factories\VideoHubCommentFactory::new();
    }
    public function commentUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'comment_by');
    }

    public function subComment()
    {
        return $this->hasMany('Modules\VideoHub\Entities\VideoHubComment', 'parent' , 'id');
    }
}
