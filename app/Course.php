<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Course extends Model implements HasMedia
{
    protected $fillable = [
        'name',
        'about',
        'description',
        'active',
    ];
    use InteractsWithMedia;

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_courses','course_id','user_id');
    }

}
