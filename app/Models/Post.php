<?php

namespace App\Models;

use App\Scopes\AdminShowDeleteScope;
use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'content', 'user_id'];



    public function __construct(array $attributes = [])
    {
        if (! isset($attributes['user_id']) && auth()->check()) {
            $attributes['user_id'] = auth()->user()->id;
        }

        parent::__construct($attributes);
    }

    /********************************* RELATIONS   ****************************** */



    public function user(){
        return $this->belongsTo(User::class);
    }

    // public function comments()
    // {
    //     return $this->hasMany(Comment::class)->dernier();
    // }

    // public function tags()
    // {
    //     return $this->belongsToMany(Tag::class)->withTimestamps();
    // }

    // public function image()
    // {
    //     return $this->hasOne(Image::class);
    // }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->dernier();
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }



    /************************************* SCOPES   ****************************** */

    public function scopeMostCommented(Builder $query)
    {
        return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }

    public function scopePostWithUserCommentsTagsImage(Builder $query)
    {
        return $query->withCount('comments')->with(['user', 'tags', 'image']);
    }

    /************************  BOOT ************************* */

    public static function boot(){
        static::addGlobalScope(new AdminShowDeleteScope);
        parent::boot();

        static::addGlobalScope(new LatestScope);

        static::deleting(function(Post $post){
            $post->comments()->delete();
        });

        static::restoring(function(Post $post){
            $post->comments()->restore();
        });

        static::updating(function (Post $post)
        {
            Cache::forget("post-show-{$post->id}");
        });

    }
}
