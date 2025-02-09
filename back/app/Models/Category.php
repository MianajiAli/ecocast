<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Define fillable fields to allow mass assignment
    protected $fillable = ['name', 'slug', 'description'];

    // Relationship with the Post model (One-to-Many)
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
