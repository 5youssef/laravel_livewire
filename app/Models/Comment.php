<?php

namespace App\Models;

use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'comments';

    protected $fillable = [
        'content',
        'user_id',
        'post_id'
    ];

    public function __construct(array $attributes = [])
    {
        if (! isset($attributes['user_id']) && auth()->check()) {
            $attributes['user_id'] = auth()->user()->id;
        }

        parent::__construct($attributes);
    }



    /******************** RELATIONS ****************************** */

    // public function post()
    // {
    //     return $this->belongsTo(Post::class);
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }



    /************************ SCOPES ****************************** */


    public function scopeDernier(Builder $query)
    {
        return $query->orderBy(static::UPDATED_AT, 'desc');
    }

    // public static function boot(){
    //     parent::boot();

    //     static::creating(function(Comment $comment)
    //     {
    //         Cache::forget("post-show-{$comment->commentable->id}");
    //     });
    // }

}
