<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'comment',
        'parent_id', // add parent_id here
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation to get replies to this comment
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('user', 'replies');
    }

    // Relation to parent comment (optional)
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}
