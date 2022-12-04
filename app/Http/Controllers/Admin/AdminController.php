<?php


namespace App\Http\Controllers\Admin;


use App\Course;
use App\Mail\WelcomeMail;
use App\User;
use App\UserCourse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Ramsey\Uuid\Type\Integer;
use function Composer\Autoload\includeFile;

class AdminController
{
    public function index() {
        return view('admin/home');
    }
    public function courses() {
        //dd(Course::get(['id','name','about','description','active','created_at','updated_at']));
        return view('admin/courses',[
            'courses' => Course::with('media')->get(['id','name','about','description','active','created_at','updated_at']),
            'users' => User::where('role', 1)->get(['id','name'])
        ]);
    }
    public function members() {
        $courses=Course::with(['users' => function ($query) {
            $query->select('id', 'name','email','created_at','student_info')->where('role', 0);
        }])->get(['id','name']);

        return view('admin/members',[
            'courses' => $courses
        ]);
    }
    public function password() {
        return view('admin/password');
    }


    public function passReset(Request $request) {
        $user = User::where('id',Auth::user()->id)->first();
        $update = array();
        $validatedData = $request->validate(
            [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (Hash::check($request->password, $user->password)) {
           // dd(json_encode([false, __('New password matches old')]));
            return redirect()->back()->with('message', false);

        } else {
            $user->fill([
                'password' => Hash::make($request->password)
            ])->save();
            return redirect()->back()->with('message', true);
        }
    }

    public function getUsers() {

        return view('admin/admin',[
            'courses'=> Course::get(['id','name'])
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
                    $student_info = [
                        'school' => $request->newUser['school'],
                        'class' => $request->newUser['class'],
                    ];
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
                        dd($exception);

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

        //dd($request->isActive);
        $newCourse = new Course;
        $newCourse->fill([
            'name'=> $request->name,
            'about'=> $request-> content,
            'description'=> $request->description,
            'active' => $request->isActive === 'true'? true: false,
        ])->save();
        $newCourse->addMedia($request->file('logo'))->toMediaCollection('logo', 'logo');
        $newCourse->addMedia($request->file('bgImg'))->toMediaCollection('main_photo', 'bg-photo');
       // dd(Course::first()->getMedia('logo')[0]->getUrl());
        //$xd = Course::with('media')->get();
       // dd($xd[0]->media[0]->getUrl());
        return Course::with('media')->get(['id','name','about','description','active','created_at','updated_at']);
    }
}