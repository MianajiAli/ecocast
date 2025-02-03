<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'user_id', 'post_id'];

    // Relationship with the Post model (Many-to-One)
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Relationship with the User model (Many-to-One)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
