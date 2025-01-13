<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wishlist extends Model
{
    use HasFactory;
    private static $wishlist = null;

    protected $fillable = [
        'course_id',
        'student_id',
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\WishlistFactory::new();
    }

    public static function wishCount()
    {
        if(is_null(self::$wishlist))
        {
            $wishlist =  Wishlist::where('student_id', Auth::guard('students')->id())->count();
            self::$wishlist = $wishlist;
        }
        return self::$wishlist;
    }
}
