<?php

namespace Modules\LMS\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\LMS\Entities\Store;
use Illuminate\Support\Facades\Auth;
use Modules\LMS\Entities\Student;

class StudentAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $slug    = \Request::segment(1);
        $auth_student = Auth::guard('students')->user();

        if (!empty($auth_student)) {
            $store   = Store::where('slug', $slug)->pluck('id');
            $student = Student::studentAuth($store);

            if($student>0){
                return $next($request);
            }else{
                return redirect($slug.'/student-login');
            }
        }
        return redirect($slug.'/student-login');
    }
}
