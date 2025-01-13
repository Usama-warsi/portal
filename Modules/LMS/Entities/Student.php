<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    private static $purchasedcourse = null;
    private static $authstudent = null;
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'store_id',
        'avatar',
    ];


    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\StudentFactory::new();
    }

    public function purchasedCourse()
    {
        if(self::$purchasedcourse === null)
        {
            $purchasecourse = $this->hasMany('Modules\LMS\Entities\PurchasedCourse', 'student_id', 'id')->get()->pluck('course_id')->toArray();
            self::$purchasedcourse = $purchasecourse;
        }
        return self::$purchasedcourse;
    }

    public static function studentAuth($store_id)
    {
        if(is_null(self::$authstudent))
        {
            $auth_student = \Auth::guard('students')->user();
            $student =  Student::where('store_id',$store_id)->where('email',$auth_student->email)->count();
            self::$authstudent = $student;
        }
        return self::$authstudent;
    }
}
