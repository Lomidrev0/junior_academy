<?php

namespace App\Traits;

use App\Message;
use App\User;
use Session;
use Illuminate\Support\Facades\Auth;

trait MessageTrait {


    public function getMsgList() {
      //dd($this->getSentMsgs());
        return array_merge($this->getSentMsgs()->toArray(), $this->getReceivedMsgs()->toArray());
    }

    public function getSentMsgs() {
        $sMsgs = Message::where('sender_id', Auth::user()->id)
            ->with([
                'users' => function ($query) {
                    $query->select('id', 'name','email','role');
                },
            ]);
//        if (Auth::user()->role == 1){
//            $sMsgs->where('course_id', Session::get('selected-course')->id);
//        }
        $sMsgs = $sMsgs->get();

        return $sMsgs->map(function ($msg){
            if ($msg->groups){
                if (json_decode($msg->groups)->recipients == 'active') {
                    $string = '"active":true';
                    $save = $msg->users->diff(User::where('student_info', 'like', "%$string%")->get());
                    $msg->unsetRelation('users');
                    if (count($save) > 0){
                        $msg['users'] = $save;
                    }
                }
                $msg['groups'] = json_decode($msg->groups);
                $msg->unsetRelation('users');
            }
            $msg['sender'] = [
                'id' => Auth::user()->id,
                'name' => 'Me',
                'email' => Auth::user()->email,
                'role' => Auth::user()->role,
            ];
            $msg['mail'] = $msg['mail'] == 1 ? true : false;
            unset($msg['sender_id']);
            unset($msg['deleted_at']);
            return $msg;
        });
    }

    public function getReceivedMsgs(){
        $rMsgs = Message::whereHas(
            'users', function ($query) {
             $query->where('user_id', '=', Auth::user()->id);
//                 if (Auth::user()->role == 1 ){
//                     $query->where('course_id', Session::get('selected-course')->id);
//                 }
                 return $query;
        })
            ->with([
                'users' => function ($query) {
                    $query->select('id', 'name','email','role');
                },
                'sender' => function ($query) {
                    $query->select('id', 'name','email','role');
                }
            ])->get();
        return $rMsgs
            ->filter(function ($rMsg) { return $rMsg->sender->id != Auth::user()->id; })
            ->map(function ($msg){
                if ($msg->groups){
                    if (json_decode($msg->groups)->recipients == 'active') {
                        $string = '"active":true';
                        $save = $msg->users->diff(User::where('student_info', 'like', "%$string%")->get());
                        $msg->unsetRelation('users');
                        if (count($save) > 0){
                            $msg['users'] = $save;
                        }
                    }
                    $msg['groups'] = json_decode($msg->groups);
                    $msg->unsetRelation('users');
                }
            $msg['mail'] = $msg['mail'] == 1 ? true : false;
            unset($msg['sender_id']);
            unset($msg['deleted_at']);
            return  $msg;
        });
    }
}