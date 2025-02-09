<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    // Add 'name' to the $fillable property for mass assignment
    protected $fillable = ['name', 'slug'];

    // Relationship with the Post model (Many-to-Many)
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
