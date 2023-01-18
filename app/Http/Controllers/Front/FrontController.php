<?php


namespace App\Http\Controllers\Front;


use App\Course;
use Illuminate\Http\Request;

class FrontController
{
    public function getForCoursesUI(){
        $courses = new Course();
        $courses = Course::with(['media', 'admin' => function ($query) {
            $query->select('id', 'name');
        }])->get(['id','name','about','description','active','slug','user_id','created_at','updated_at']);

        $courses = $courses->map(function($course) {
            unset($course['user_id']);
            $course['active'] = $course['active'] == 1 ? true : false;
            return $course;
        });
        return $courses;
    }


    public function index()
    {
        return view('front/home',[
            'courses' => $this->getForCoursesUI(),
        ]);
    }
    public function getContact(){
        return view('front/contact',[
            'courses' => Course::with(['users' => function ($query) {
                $query->select('name','email');
                }])->where('active',true)->get(),
        ]);
    }
    public function getDetail(Request $request){
        return view('front/detail',[
            'course' =>  json_decode(Course::with(['users' => function ($query) {
                $query->select('name','email');
            },'media'])->where('slug',$request->slug)->first()
            )]);
    }
}
