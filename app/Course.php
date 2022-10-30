<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{


    public function users()
    {
        return $this->belongsToMany(User::class, 'user_course');
    }
}
