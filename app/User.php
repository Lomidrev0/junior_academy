<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    private $selectedCourse = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'student_info',
        'password',
        'role',
    ];
    protected $attributes = [
        'student_info' => 'array',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'user_courses','user_id','course_id');
    }

    public function directories()
    {
        return $this->hasMany(Directory::class);
    }

    public function setSelectedCourse(Course $course) {
        $this->selectedCourse = $course;
        session(['selected-course' => $this->selectedCourse]);
    }

    public function getSelectedCourse() {
        if ($this->selectedCourse === null) {
            $this->selectedCourse = Course::find(session('selected-course'))->first();
        }
        return $this->selectedCourse;
    }
}
