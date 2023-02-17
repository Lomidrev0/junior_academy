<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Course extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'about',
        'description',
        'active',
        'slug',
        'user_id'
    ];

    public static $get = [
        'id',
        'name',
        'about',
        'description',
        'active',
        'created_at',
        'updated_at',
        'slug'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_courses','course_id','user_id');
    }

    public function admin()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function directories()
    {
        return $this->hasMany(Directory::class);
    }

}
