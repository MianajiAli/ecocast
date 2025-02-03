<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Relationship with the Post model (One-to-Many)
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
