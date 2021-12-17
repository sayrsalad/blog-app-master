<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // relationship
    public function user(){ 
        return $this->belongsTo(User::Class);
    }

    public function comments(){
        return $this->hasMany(Comment::Class);
    }

    public function likes(){
        return $this->hasMany(Like::Class);
    }
}
