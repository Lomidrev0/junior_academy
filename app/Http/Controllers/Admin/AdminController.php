<?php


namespace App\Http\Controllers\Admin;


use App\Article;
use App\Course;
use App\Jobs\SendWatchDogsMails;
use App\Mail\WelcomeMail;
use App\SystemVariable;
use App\User;
use App\UserCourse;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Ramsey\Uuid\Type\Integer;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use function Composer\Autoload\includeFile;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Bus;

class AdminController
{
    public function index() {
        return view('admin/home',[
            'courses' => Course::withCount(['users' => function ($query) {
            $query->where('role', 0);
        }])->with('media')->get(['id','name','active']),
            'teachers' => User::where('role', 1)->with('courses')->get(['id','name','email']),
            'admins' => User::where('role', 2)->get(['id','name']),
        ]);
    }
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

    public function getForCourseUI($column, $value){
        $course =  Course::with(['users' => function ($query) {
            $query->select('id', 'name');},
            'media','admin'=> function ($query) {
                $query->select('id', 'name');
            }])->where($column,$value)->first();
        $course['active'] = $course['active'] == 1 ? true : false;
        unset($course['user_id']);
        return $course;
    }

    public function courses() {
        return view('admin/courses',[
            'courses' => $this->getForCoursesUI(),
            'users' => User::where('role', 1)->get(['id','name']),
            'registration' => json_decode(SystemVariable::where('name','registration')->first()->value)->permit,
        ]);
    }

    public function members() {
        $courses=Course::with(['users' => function ($query) {
            $query->select('id', 'name','email','created_at','student_info')->where('role', 0);
        }])->get(['id','name']);

        return view('shared/members',[
            'courses' => $courses
        ]);
    }

    public function password() {
        return view('shared/password');
    }



    public function getUsers() {
        return view('admin/addUser',[
            'courses'=> Course::where('active', true)->with('media')->get(['id','name'])
        ]);
    }

    public function addUser(Request $request) {

        if (Hash::check($request->password,Auth::user()->password)) {
            if (Validator::make($request->newUser, ['email' => 'unique:users'])->fails()) {
               return 2;
            }
            else {
                $data = collect([
                    'name' => $request->newUser['name'],
                    'email' => $request->newUser['email'],
                    'password' => Str::random(12),
                ]);
                if ($request->newUser['school'] == null || $request->newUser['school'] == null) {
                    $student_info = null;
                }
                else {
                    $student_info = collect([
                        'school' => $request->newUser['school'],
                        'class' => $request->newUser['class'],
                        'active' => false,
                        'notes' => '',
                    ]);
                }
                $user = User::create([
                    'name' => $request->newUser['name'],
                    'email' => $request->newUser['email'],
                    'student_info' => $student_info,
                    'password' => Hash::make($data['password']),
                    'role' => $request->newUser['role'],
                ]);
                if(isset($request->newUser['course'])) {
                    $userCourse = $request->newUser['course'];
                    try{
                        $user->courses()->attach($userCourse);
                    }catch (\Throwable $exception){
                        report($exception);
                    }
                }
                try{
                    Mail::to($request->newUser['email'])->send(new WelcomeMail($data));
                } catch(\Exception $e){
                    Log::critical('Welcome email was not send: ' . $e);
                    return 3;
                }
                return 0;
            }
        }
        else{
            return 1;
        }
    }

    public function storeCourse(Request $request) {
        $newCourse = Course::create([
            'name'=> $request->name,
            'about'=> $request->about,
            'description'=> $request->description,
            'active' => $request->isActive === 'true'? true: false,
            'slug' => Str::slug($request->name),
            'user_id' => Auth::user()->id,
        ]);
        try{
            $newCourse->users()->attach(json_decode($request->teachers));
        }catch (\Throwable $exception){
            report($exception);
        }
        //TODO posielanie vlastneho mail templatu pre ucitela ze bol pridany ku kurzu
//        try{
//            //mail boli ste priradeny ku kurzu...
//        //Mail::to($request->newUser['email'])->send(new WelcomeMail($data));
//        } catch(\Exception $e){
//          //  Log::critical('Welcome email was not send: ' . $e);
//
//        }
        $newCourse->addMedia($request->file('logo'))->toMediaCollection('logo-collect', 'logo');
        $newCourse->addMedia($request->file('bgImg'))->toMediaCollection('bg-photo-collect', 'bg-photo');

        return $this->getForCoursesUI();
    }

    public function courseDetail(Request $request) {
        return view('admin/detail', [
            'course' => $this->getForCourseUI('slug',$request->segment(3)),
            'users' => User::where('role', 1)->get(['id','name'])
        ]);
    }
    public function updateCourse(Request $request) {

        $redireect = false;
        $course = Course::find($request->id);
        if(isset($request->name)) {
            $course->fill([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'about' => $request->about,
                'active' => $request->isActive === 'true' ? true : false,
                'user_id' => Auth::user()->id
            ])->save();
            try{
                $course->users()->sync(json_decode($request->teachers));
            }catch (\Throwable $exception){
                report($exception);
            }
            return $course->first(['slug']);
        }
        else {
            $course->fill([
                'description' => $request->description,
                'about' => $request->about,
                'active' => $request->isActive === 'true' ? true : false,
                'user_id' => Auth::user()->id
            ])->save();
            try{
                $course->users()->sync(json_decode($request->teachers));
            }catch (\Throwable $exception){
                report($exception);
            }
            if ($request->logo) {
                $course->clearMediaCollection('logo-collect');
                $course->addMedia($request->logo)->toMediaCollection('logo-collect','logo');
            }
            if ($request->bgImg) {
                $course->clearMediaCollection('bg-photo-collect');
                $course->addMedia($request->bgImg)->toMediaCollection('bg-photo-collect', 'bg-photo');
            }
            return [
                $this->getForCourseUI('id',$request->id),
                User::where('role', 1)->get(['id','name'])
            ] ;
        }
    }
    public function deleteCourse(Request $request) {
        $course = Course::find($request->id);
        $course['user_id'] = Auth::user()->id;
        $course->delete();
        return $this->getForCoursesUI();
    }

    public function updateActive(Request $request) {
        $course =  Course::find($request->id);
        $course->update(['user_id' => Auth::user()->id, 'active'=> $request->value]);
        return $this->getForCourseUI('id',$request->id);
    }

    public function articles() {
        $article = SystemVariable::where('name', 'article')->first();
        return view('admin/articles',[
            'article' => $article,
            ]);
    }
    public function updateArticle(Request $request) {
        $article =  SystemVariable::find($request->id);
        $data = [
            'text' => $request->article
        ];
        $article->update([
            'user_id' => Auth::user()->id,
            'value' => json_encode($data),
        ]);
       return $article;
    }

//    public function storeArticle(Request $request) {
//        //dd($request->article['active']);
//        $newArticle = Article::create([
//            'title'=> $request->article['title'],
//            'content'=> $request->article['content'],
//            'slug'=> $request->article['title'] ? Str::slug($request->article['title']) : null,
//            'active' => $request->article['active'],
//            'type' => 'front',
//            'user_id' => Auth::user()->id,
//        ]);
//        return $newArticle;
//    }

    public function updateRegistration(Request $request) {
        $var = SystemVariable::where('name', 'registration')->first();
        if ($request->permit != false && json_decode($var->value)->permit == false ){
            SendWatchDogsMails::dispatch();
        }
        $arr = json_decode($var->value);
        $arr->permit = $request->permit;
        $var->update([
            'value' => collect($arr),
            'user_id' => Auth::user()->id,
            ]);
        return $var->value;
    }
}