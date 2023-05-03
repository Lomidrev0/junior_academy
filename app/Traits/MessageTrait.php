<?php

namespace App\Traits;

use App\Message;
use App\User;
use Session;
use Illuminate\Support\Facades\Auth;

trait MessageTrait {

    public function getMsgList($url, $filter) {
        if ($filter == 'all'){
            $sMsgs = Message::where('sender_id', Auth::user()->id)
                ->orWhereHas('users', function ($query) {
                    $query->where('user_id', '=', Auth::user()->id);
                    return $query;
                });
        }
        elseif ($filter == 'sent'){
            $sMsgs = Message::where('sender_id', Auth::user()->id);
        }
        else {
            $sMsgs = Message::whereHas('users', function ($query) {
                $query->where('user_id', '=', Auth::user()->id);
                return $query;
            });
        }

            $sMsgs->with([
                'users' => function ($query) {
                    $query->select('id', 'name','email','role');
                },
                'sender' => function ($query) {
                    $query->select('id', 'name','email','role');
                }
            ]);
        $sMsgs = $sMsgs->latest()->paginate(20);
        collect($sMsgs->items())->map(function ($msg){
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
            if ($msg->sender->id == Auth::user()->id){
                $msg->sender->name = 'Me';
            }
            $msg['mail'] = $msg['mail'] == 1 ? true : false;
            unset($msg['sender_id']);
            unset($msg['deleted_at']);
            return $msg;
        });
        $sMsgs->withPath($url);
        return [
            'links' => $sMsgs->links()->toHtml(),
            'items' => $sMsgs->items()
        ];
    }
}