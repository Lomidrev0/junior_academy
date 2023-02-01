<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Message extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'subject',
        'content',
        'attachments',
        'sender_id',
        'course_id',
        'groups',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_message','message_id','user_id');
    }
    public function sender()
    {
        return $this->belongsTo('App\User', 'sender_id');
    }
}
