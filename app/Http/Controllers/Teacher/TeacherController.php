<?php


namespace App\Http\Controllers\Teacher;


use App\Course;
use App\Message;
use App\User;
use App\Directory;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Session;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\MediaStream;
use function GuzzleHttp\Promise\all;
use App\Traits\MessageTrait;

class TeacherController
{
    use MessageTrait;

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
        }, 'media' => function ($query) {
            $query->first();
        }])->withCount('media')->where([
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
            'dir' =>  Directory::with('media')->where('slug', $request->segment(3))->first(['id','name','description','active','slug']),
        ]);
    }

    public function updateActiveAlbum(Request $request){
        $dir =  Directory::find($request->id);
        $dir->update(['user_id' => Auth::user()->id, 'active'=> $request->value]);
        return $this->getForDirUI('id',$request->id);
    }

    public function deleteAlbum(Request $request) {
        $dir = Directory::find($request->id);
        $dir['user_id'] = Auth::user()->id;
        $dir->delete();
        return $this->getForDirsUI($request->disk);
    }

    public function storeImg(Request $request) {
        $dir = Directory::where('id', $request->id)->first();
        foreach ( $request->files as $file) {
            $dir->addMedia($file)->toMediaCollection($dir->slug, 'gallery');
        }
        return  Directory::with('media')->find($request->id);
    }

    public function deleteImg(Request $request) {
        Directory::where('id',$request->dirId)->first()->deleteMedia($request->id);
        return Directory::with('media')->find($request->dirId);
    }

    public function deleteImgs(Request $request) {
        $dirctory = Directory::with('media')->where('id',$request->dirId)->first();
        foreach ($request->imgs as $img) {
           $dirctory->deleteMedia($img);
        }
        return Directory::with('media')->find($request->dirId);
    }

    public function downloadAlbum(Request $request){
        $directory = $request->dir;
        $downloads = Directory::where('id', $directory['id'])->first()->getMedia($directory['slug']);
        return MediaStream::create($directory['slug'] . ".zip")->addMedia($downloads);
    }

    public function updateAlbum(Request $request) {
        $dir = Directory::find($request->id);
        if (Validator::make($request->dir, ['name' => 'unique:directories'])->fails() && $dir->name != $request->dir['name']) {
            return null;
        }
        else {
            if ($dir->name == $request->dir['name']) {
                $dir->update([
                    'user_id' => Auth::user()->id,
                    'active' => $request->dir['active'],
                    'description' => $request->dir['description'],
                ]);
                return Directory::with('media')->find($request->id);
            }
            else {
                $dir->update([
                    'user_id' => Auth::user()->id,
                    'active' => $request->dir['active'],
                    'description' => $request->dir['description'],
                    'name' => $request->dir['name'],
                    'slug' => Str::slug($request->dir['name']),
                ]);
                return [Directory::with('media')->find($request->id), $dir->slug];
            }
        }
    }
   public function getMessages(){
        $userLR = Carbon::parse(User::where('id',Auth::user()->id)->first()->last_read)->toISOString();
        User::where('id',Auth::user()->id)->update(['last_read' => Carbon::now()]);
        return view('teacher/messages', [
            'messages' => collect($this->getMsgList())->sortByDesc('created_at')->values(),
            'last_read'=> $userLR,
        ]);

   }

   public function userSearch(Request $request){
       $val = $request->val;
       $query = User::where('role',0)->where('name', 'like', "%$val%");
       $ids = $request->ids;
       if ($ids) {
           $query->whereIn('id', $ids);
       } else {
           $take = $request->take;

           if ($take >= 0) {
               $query->take($take);

               $skip = $request->skip;
               if ($skip >= 0) {
                   $query->skip($skip);
               }
           }
       }
       $count = $query->count();
       $users = $query->get();

       return [
           'count' => $count,
           'items' => $users->map(function($user) {
               return [
                   'id'=> $user->id,
                   'name'=> $user->name,
                   'role'=> $user->role,
                   'email'=> $user->email,
               ];
           }),
       ];
   }
}