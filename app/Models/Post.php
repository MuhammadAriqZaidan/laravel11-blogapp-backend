<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Comment;
use App\Models\Like;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'user_id', 'image'];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }



    // Relasi ke Comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Relasi ke Likes
    public function likes()
    {
        return $this->hasMany(Like::class);
    }


}


