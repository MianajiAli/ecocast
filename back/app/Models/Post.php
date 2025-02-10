<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta_title',
        'meta_description',
        'thumbnail',
        'status',
        'category',
        'tags',
        'user_id'
    ];


    protected $casts = [
        'tags' => 'array', // Ensures tags are stored as JSON
    ];

    /**
     * Get the author of the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comments for the post.
     */
    // public function comments() {
    //     return $this->hasMany(Comment::class);
    // }
}
