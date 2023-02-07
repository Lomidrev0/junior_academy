<?php


namespace App\Http\Controllers\Shared;


use App\Course;
use App\Mail\MessageMail;
use App\Mail\WelcomeMail;
use App\Message;
use App\Traits\MessageTrait;
use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MessageController
{
    use MessageTrait;

    public function sendMessage(Request $request) {
        $group = collect([
            'name' => null,
            'recipients' => null,
        ]);
        $reqData = json_decode($request->data);
        if ($reqData->multipleSelect == null){
            $recipients = $reqData->selected;
            $mailTo = $reqData->selectedEmails;
        }
        else {
            $group['name'] = Session::get('selected-course')->name;
            if ($reqData->multipleSelect == 'allUsers') {
                $group['recipients'] = 'all';
                $recipients = json_encode(
                    array_unique(
                        array_merge(
                            json_decode(User::where('role', 0)->get(['id'])->map(function ($recipint) { return $recipint->id; })),
                            json_decode($reqData->selected)
                        )
                    )
                );
                $mailTo = json_encode(
                    array_unique(
                        array_merge(
                            json_decode(User::where('role', 0)->get(['email'])->map(function ($recipint) { return $recipint->email; })),
                            json_decode($reqData->selectedEmails)
                        )
                    )
                );
            }
            else {
                $group['recipients'] = 'active';
                $recipients = json_encode(
                    array_unique(
                        array_merge(
                            json_decode(User::whereJsonContains('student_info->active', true)->get(['id'])->map(function ($recipint) { return $recipint->id; })),
                            json_decode($reqData->selected)
                        )
                    )
                );
                $mailTo = json_encode(
                    array_unique(
                        array_merge(
                            json_decode(User::whereJsonContains('student_info->active', true)->get(['email'])->map(function ($recipint) { return $recipint->email; })),
                            json_decode($reqData->selectedEmails)
                        )
                    )
                );
            }
        }
        $data = [
            'subject'=> $reqData->title,
            'content'=> $reqData->content,
            'attachments'=> count($request->files) > 0 ? true : false,
            'sender_id' => Auth::user()->id,
            'course_id' => Session::get('selected-course')->id,
            'mail' => $reqData->mail ? true : false,
        ];
        if ( $group['name']) {
            $data = Arr::add($data, 'groups', $group);
        }
        $message = Message::create($data);
        $media = collect();
        foreach ( $request->files as $file) {
              $media->push($message->addMedia($file)->toMediaCollection('attachments', 'attachments'));
        }
        try{
            $message->users()->attach(json_decode($recipients));
        }catch (\Throwable $exception){
            report($exception);
        }
        if ($reqData->mail) {
            try{
                Mail::to(json_decode($mailTo))->send(new MessageMail($message,$media,Auth::user()->email));
            } catch(\Exception $e){
                Log::critical('Welcome email was not send: ' . $e);
                return $e;
            }
        }
        return collect($this->getMsgList())->sortByDesc('created_at')->values();
    }

    public function getMessage(Request $request) {
        $msg =Message::with(['media',
            'sender',
            'users' =>function ($query) {
                $query->select('id', 'name','email','role');
            },
            'course' =>function ($query) {
                $query->select('id', 'name');
            },
        ])->where('id',$request->id)->first();

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
        $msg['mail'] = $msg['mail'] == 1 ? true : false;
        $msg['attachments'] = $msg['attachments'] == 1 ? true : false;
        return $msg;
    }
}