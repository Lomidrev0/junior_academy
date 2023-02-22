<?php


namespace App\Http\Controllers\Front;


use App\Article;
use App\Course;
use App\Directory;
use Illuminate\Http\Request;

class FrontController
{
    public function getForCoursesUI(){
        $courses = new Course();
        $courses = Course::with(['media', 'admin' => function ($query) {
            $query->select('id', 'name');
        }])->where('active',true)->get(['id','name','about','description','active','slug','user_id','created_at','updated_at']);

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
            'article' => Article::where('type','front')->first('content'),
        ]);
    }
    public function getContact(){
        return view('front/contact',[
            'courses' => Course::with(['users' => function ($query) {
                $query->where('role', 1)->select('name','email');
                }])->where('active',true)->get(),
        ]);
    }
    public function getDetail(Request $request){
        return view('front/detail',[
            'course' =>  json_decode(Course::with(['users' => function ($query) {
                $query->select('name','email')->where('role', 1);
                },'media'])->where('slug',$request->slug)->first()
            )]);
    }
    public function getGallery() {
        return view('front/gallery',[
            'courses' => Course::whereHas('directories', function ($query) {
                $query->where('active', true);
            })->with(['directories' => function ($query) {
                $query->where('active', true)->withCount('media');
            }, 'directories.media'])->get(['id','name','slug']),
        ]);
    }

    public function directory(Request $request) {
        $dir = Directory::with('media')->where('slug', $request->segment(2))->first(['id','name','description','active','slug','created_at']);
         if ($dir->active == 0) {
             abort(404);
         }
        return view('front/directory', [
            'dir' =>  $dir,
        ]);
    }
}
