<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'user_id',
        'post_id',
    ];

    // Relasi ke Post (Many-to-One)
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Relasi ke User (Many-to-One)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

