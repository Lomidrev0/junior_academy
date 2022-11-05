<?php


namespace App\Http\Controllers\Admin;


use App\Course;
use App\User;
use App\UserCourse;

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
}