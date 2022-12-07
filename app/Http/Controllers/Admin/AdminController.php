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
        if (Hash::check($request->old_password,Auth::user()->password)) {

            $user = User::where('id',Auth::user()->id)->first();
            $update = array();
            $validatedData = $request->validate(
                [
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                ]);

            if (Hash::check($request->password, $user->password)) {
                // dd(json_encode([false, __('New password matches old')]));
                return redirect()->back()->with('message', 1);

            } else {
                $user->fill([
                    'password' => Hash::make($request->password)
                ])->save();
                return redirect()->back()->with('message', 0);
            }
        }
        else {
            return redirect()->back()->with('message', 2);
        }

    }

    public function getUsers() {

        return view('admin/admin',[
            'courses'=> Course::where('active', true)->get(['id','name'])
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
                    ]);
                }
                //dd($student_info);
                $user = User::create([
                    'name' => $request->newUser['name'],
                    'email' => $request->newUser['email'],
                    'student_info' => $student_info,
                    'password' => Hash::make($data['password']),
                    'role' => $request->newUser['role'],
                ]);
                //dd("som tu");
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

        //dd($request->teachers);
        $newCourse = Course::create([
            'name'=> $request->name,
            'about'=> $request-> content,
            'description'=> $request->description,
            'active' => $request->isActive === 'true'? true: false,
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
        $newCourse->addMedia($request->file('logo'))->toMediaCollection('logo', 'logo');
        $newCourse->addMedia($request->file('bgImg'))->toMediaCollection('main_photo', 'bg-photo');
        return Course::with('media')->get(['id','name','about','description','active','created_at','updated_at']);
    }
}