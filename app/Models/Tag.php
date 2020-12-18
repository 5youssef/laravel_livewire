<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // public function posts()
    // {
    //     return $this->belongsToMany(Post::class)->withTimestamps();
    // }

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable')->withTimestamps();
    }

    public function comments()
    {
        return $this->morphedByMany(Comm::class, 'taggable')->withTimestamps();
    }

}
