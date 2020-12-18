<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /********************* RELATIONS ************************************* */

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // public function comments()
    // {
    //     return $this->hasMany(Comment::class, 'user_id', 'id');
    // }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }


    /*********************** SCOPES *************************************** */

    public function scopeUsersMostPostWritten(Builder $query)
    {
        return $query->withCount('posts')->orderBy('posts_count', 'desc');
    }

    public function scopeUsersActiveInLastMonth(Builder $query)
    {
        return $query->withCount(['posts' => function(Builder $query){
            $query->whereBetween(static::CREATED_AT, [now()->subMonth(1), now()]);
        }])
        // ->having('posts_count', '>', 57)
        ->orderBy('posts_count', 'desc');
    }

}
