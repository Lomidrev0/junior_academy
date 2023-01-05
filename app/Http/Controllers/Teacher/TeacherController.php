<?php


namespace App\Http\Controllers\Teacher;


use App\Course;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class TeacherController
{
    public function getForCourseUserUI() {
        return
            Course::with(['users' => function ($query) {
            $query->select('id', 'name','email','created_at','student_info')->where('role', 0);
            }])->where('id', Session::get('selected-course')->id)->get(['id','name']);
    }
    public function index() {
        return view('teacher/home');
    }

    public function password() {
        return view('shared/password');
    }

    public function members() {
        return view('shared/members',[
            'courses' => $this->getForCourseUserUI()
        ]);
    }

    public function updateActive(Request $request) {
        $user =  User::find($request->id);
        $arr = json_decode($user->student_info);
        $arr->active = $request->value;
        $user->update([ 'student_info' => collect($arr) ]);
        return $user;
    }

    public function deleteMember(Request $request) {
        $user = User::find($request->id);
        $user->delete();
        return $this->getForCourseUserUI();
    }

    public function setNote(Request $request) {
        $user =  User::find($request->id);
        $arr = json_decode($user->student_info);
        $arr->notes = $request->value;
        $user->update([ 'student_info' => collect($arr) ]);
        return $user;
    }
}