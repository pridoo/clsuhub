<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'feeling',
        'location',
        'hidden',
        'privacy',
        'department_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->hasMany(PostMedia::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->with('user', 'replies');
    }

    public function reposts()
    {
        return $this->hasMany(Repost::class);
    }

        public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
