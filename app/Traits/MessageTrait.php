<?php

namespace App\Traits;

use App\Message;
use App\User;
use Session;
use Illuminate\Support\Facades\Auth;

trait MessageTrait {


    public function getMsgList() {
        return array_merge(json_decode($this->getSentMsgs()), json_decode($this->getReceivedMsgs()));
    }

    public function getSentMsgs() {
        $sMsgs = Message::where('sender_id', Auth::user()->id)
            ->where('course_id', Session::get('selected-course')->id)
            ->with([
                'users' => function ($query) {
                    $query->select('id', 'name','email','role');
                },
            ])->get();


        return $sMsgs->map(function ($msg){
            if ($msg->groups){
                if (json_decode($msg->groups)->recipients == 'all') {
                    $msg['groups'] = 'all';
                    $msg->unsetRelation('users');
                }
                else {
                    $msg['groups'] = 'selected';
                    $string = '"active":true';
                    $save = $msg->users->diff(User::where('student_info', 'like', "%$string%")->get());
                    $msg->unsetRelation('users');
                    if (count($save) > 0){
                        $msg['users'] = $save;
                    }
                }
            }
            $msg['sender'] = [
                'id' => Auth::user()->id,
                'name' => 'Me',
                'email' => Auth::user()->email,
                'role' => Auth::user()->role,
            ];
            unset($msg['sender_id']);
            unset($msg['deleted_at']);
            return $msg;
        });
    }

    public function getReceivedMsgs(){
        $rMsgs = Message::whereHas(
            'users', function ($query) {
            return $query
                ->where('user_id', '=', Auth::user()->id)
                ->where('course_id', Session::get('selected-course')->id);
        })
            ->with([
                'users' => function ($query) {
                    $query->select('id', 'name','email','role');
                },
                'sender' => function ($query) {
                    $query->select('id', 'name','email','role');
                }
            ])->get();

        return $rMsgs->map(function ($msg){
            if ($msg->groups){
                if (json_decode($msg->groups)->recipients == 'all') {
                    $msg['groups'] = 'all';
                    $msg->unsetRelation('users');
                }
                else {
                    $msg['groups'] = 'selected';
                    $string = '"active":true';
                    $save = $msg->users->diff(User::where('student_info', 'like', "%$string%")->get());
                    $msg->unsetRelation('users');
                    if (count($save) > 0){
                        $msg['users'] = $save;
                    }
                }
            }
            unset($msg['sender_id']);
            unset($msg['deleted_at']);
            return $msg;
        });
    }
}