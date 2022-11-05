<?php


namespace App\Http\Controllers\Admin;


use App\Course;
use App\User;
use App\UserCourse;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController
{
    public function index()
    {
        return view('admin/home');
    }
    public function courses()
    {
        return view('admin/courses');
    }
    public function members()
    {
        $courses=Course::with(['users' => function ($query) {
            $query->select('id', 'name','email','created_at','student_info');
        }])->get(['id','name']);

        return view('admin/members',[
            'courses' => $courses
        ]);
    }
    public function password()
    {
        return view('admin/password');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function passReset(){
        $update = array();

       // \request('password');
        $user = User::where('id',Auth::user()->id)->first();
        //dd($user->password);

        //dd( Hash::check(\request('password'), $user->password));

    }

}