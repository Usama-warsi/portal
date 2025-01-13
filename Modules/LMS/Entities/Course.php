<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;
    private static $ratting = null;
    private static $userratting = null;
    protected $fillable = [
        'title',
        'type',
        'course_requirements',
        'course_description',
        'has_certificate',
        'status',
        'category',
        'sub_category',
        'level',
        'lang',
        'duration',
        'is_free',
        'price',
        'has_discount',
        'discount',
        'featured_course',
        'is_preview',
        'preview_type',
        'preview_content',
        'thumbnail',
        'meta_keywords',
        'meta_description',
        'meta_image',
        'workspace_id',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\CourseFactory::new();
    }

    public function course_rating()
    {
        if(is_null(self::$ratting))
        {
            $ratting    = Ratting::where('course_id', $this->id)->where('rating_view', 'on')->sum('ratting');
            self::$ratting = $ratting;
        }
        if(is_null(self::$userratting))
        {
            $user_count = Ratting::where('course_id', $this->id)->where('rating_view', 'on')->count();
            self::$userratting = $user_count;
        }
        if(self::$userratting > 0)
        {
            $avg_rating = number_format(self::$ratting / self::$userratting, 1);
        }
        else
        {
            $avg_rating = number_format(self::$ratting / 1, 1);

        }

        return $avg_rating;
    }

    public static function courseCount()
    {
        return PurchasedCourse::where('student_id', Auth::guard('students')->id())->count();
    }

    public function student_count()
    {
        return $this->hasMany(PurchasedCourse::class, 'course_id', 'id');
    }

    public function chapter_count()
    {
        return $this->hasMany('Modules\LMS\Entities\Chapters', 'course_id', 'id');
    }

    public function student_wl()
    {
        return $this->belongsToMany(
                'Modules\LMS\Entities\Student', 'wishlists', 'course_id', 'student_id'
            )->where('student_id','=',\Auth::guard('students')->user()->id);

    }

    public function tutor_id()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    public function star_rating($course_id)
    {
        $user_count = Ratting::where('course_id', $this->id)->where('rating_view', 'on')->count();
        $ratting=[];
        $rating_count5 = Ratting::where('course_id',$course_id)->where('rating_view','on')->where('ratting',5)->count();
        $rating_count4 = Ratting::where('course_id',$course_id)->where('rating_view','on')->where('ratting',4)->count();
        $rating_count3 = Ratting::where('course_id',$course_id)->where('rating_view','on')->where('ratting',3)->count();
        $rating_count2 = Ratting::where('course_id',$course_id)->where('rating_view','on')->where('ratting',2)->count();
        $rating_count1 = Ratting::where('course_id',$course_id)->where('rating_view','on')->where('ratting',1)->count();
        if($user_count != 0){
            $ratting['ratting5'] = number_format($rating_count5 * 100 / $user_count,2);
            $ratting['ratting4'] = number_format($rating_count4 * 100 / $user_count,2);
            $ratting['ratting3'] = number_format($rating_count3 * 100 / $user_count,2);
            $ratting['ratting2'] = number_format($rating_count2 * 100 / $user_count,2);
            $ratting['ratting1'] = number_format($rating_count1 * 100 / $user_count,2);
        }else{
            $ratting['ratting5'] = 0;
            $ratting['ratting4'] = 0;
            $ratting['ratting3'] = 0;
            $ratting['ratting2'] = 0;
            $ratting['ratting1'] = 0;
        }

        return $ratting;
    }

    public function category_id()
    {
        return $this->hasOne(CourseCategory::class, 'id', 'category');
    }

}
