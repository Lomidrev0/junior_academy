<?php


namespace App\Http\Controllers\Student;


use App\Traits\MessageTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class StudentController
{
    use MessageTrait;

    public function index()
    {
        return view('student/home',[
            'user' => Auth::user()->load('courses.media'),
        ]);
    }
    public function getMessages(){
        $userLR = Carbon::parse(User::where('id',Auth::user()->id)->first()->last_read)->toISOString();
        User::where('id',Auth::user()->id)->update(['last_read' => Carbon::now()]);
        return view('student/messages', [
            'messages' => $this->getMsgList(strtok(url()->current(), '?'), 'all'),
            'last_read'=> $userLR,
        ]);
    }

    public function password() {
        return view('shared/password');
    }

    public function userSearch(Request $request){
       // dd($request->take);
        $val = $request->val;
        $query = User::where('role',1)->orWhere('role', 0)->where('name', 'like', "%$val%");
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