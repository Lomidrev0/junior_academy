<?php


namespace App\Http\Controllers\Shared;


use App\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class CourseController
{
    public function changeCourse(Request $request) {
        Auth::user()->setSelectedCourse(
            Course::with(['users' => function ($query) {
                $query->select('id', 'name');
            }])->where('id', $request->id)->first()
        );
        if (count(explode('/',url()->previous())) > 5) {
            return redirect(dirname(url()->previous()));
        }
        else {
            return back();
        }
    }
}