<?php


namespace App\Http\Controllers\Shared;


use App\Message;
use App\Traits\MessageTrait;
use App\User;
use Illuminate\Support\Arr;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MessageController
{
    use MessageTrait;

    public function sendMessage(Request $request) {
        $recipients = null;
        $group = collect([
            'name' => null,
            'recipients' => null,
        ]);
        $reqData = json_decode($request->data);
        if ($reqData->multipleSelect == null){
            $recipients = $reqData->selected;
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
            }
        }
        $data = [
            'subject'=> $reqData->title,
            'content'=> $reqData->content,
            'attachments'=> count($request->files) > 0 ? true : false,
            'sender_id' => Auth::user()->id,
            'course_id' => Session::get('selected-course')->id,
        ];
        if ( $group['name']) {
            $data = Arr::add($data, 'groups', $group);
        }
        $message = Message::create($data);

        foreach ( $request->files as $file) {
            $message->addMedia($file)->toMediaCollection('attachments', 'attachments');
        }

        try{
            $message->users()->attach(json_decode($recipients));
        }catch (\Throwable $exception){
            report($exception);
        }
        return collect($this->getMsgList())->sortByDesc('created_at')->values();
    }
}