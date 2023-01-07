<?php


namespace App\Http\Controllers\Teacher;


use App\Course;
use App\User;
use App\Directory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Session;

class TeacherController
{
    public function getForCourseUserUI() {
        return
            Course::with(['users' => function ($query) {
            $query->select('id', 'name','email','created_at','student_info')->where('role', 0);
            }])->where('id', Session::get('selected-course')->id)->get(['id','name']);
    }

    public function getForDirUI($column, $value){
        $dir =  Directory::with(['user' => function ($query) {
            $query->select('id', 'name');},
            'course'=> function ($query) {
                $query->select('id', 'name');
            }])->where([
                [$column,$value],
                ['course_id', Session::get('selected-course')->id],
        ])->first();
        unset($dir['course_id'], $dir['user_id'], $dir['deleted_at']);
        $dir['active'] = $dir['active'] == 1 ? true : false;
        return $dir;
    }

    public function getForDirsUI($disk) {
        $dirs= Directory::with(['user' => function ($query) {
            $query->select('id', 'name');
        }, 'course' => function ($query){
            $query->select('id','name');
        }])->where([
            ['disk', $disk],
            ['course_id', Session::get('selected-course')->id],
        ])->get();
        $dirs = $dirs->map(function($dir) {
            unset($dir['course_id'], $dir['user_id'], $dir['deleted_at']);
            $dir['active'] = $dir['active'] == 1 ? true : false;
            return $dir;
        });
        return $dirs;
    }

    public function index() {
        return view('teacher/home');
    }

    public function members() {
        return view('shared/members',[
            'courses' => $this->getForCourseUserUI()
        ]);
    }

    public function gallery() {
        return view('teacher/gallery',[
            'albums' => $this->getForDirsUI('gallery')
        ]);
    }

    public function password() {
        return view('shared/password');
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

    public function saveAlbum(Request $request) {
        Storage::disk($request->disk)->makeDirectory(Str::slug($request->newAlbum['name']));
        $dir = Directory::create([
            'name' => $request->newAlbum['name'],
            'slug' => Str::slug($request->newAlbum['name']),
            'description' => $request->newAlbum['description'],
            'active' => $request->newAlbum['isActive'],
            'disk' => $request->disk,
            'user_id' => Auth::user()->id,
            'course_id' => Session::get('selected-course')->id,
        ]);

        return $this->getForDirsUI($request->disk);
    }

    public function directory(Request $request) {
        return view('teacher/directory', [
            'dir' => Directory::with('media')->where('slug', $request->segment(3))->first(['id','name','description','active']),
        ]);
    }

    public function updateActiveAlbum(Request $request){
        $dir =  Directory::find($request->id);
        $dir->update(['user_id' => Auth::user()->id, 'active'=> $request->value]);
        return $this->getForDirUI('id',$request->id);
    }

    public function deleteAlbum(Request $request) {
        Storage::disk($request->disk)->deleteDirectory(Str::slug($request->name));
        $dir = Directory::find($request->id);
        $dir['user_id'] = Auth::user()->id;
        $dir->delete();
        return $this->getForDirsUI($request->disk);
    }
}