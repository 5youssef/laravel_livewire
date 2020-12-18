<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Arr;

class CommentsTableSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        $users = User::all();
        $posts = Post::all();

        if ($posts->count() === 0 || $users->count() === 0) {
            $this->command->info('There are no posts or users, so no comments will be added');
            return;
        }

        $commentsCount = (int)$this->command->ask('How many comments would you like?', 150);

        Comment::factory()->count($commentsCount)->make()->each(function ($comment) use ($users)
        {
            $commentable = [
                User::class,
                Post::class,
            ];
            $commentableType = Arr::random($commentable);
            if ($commentableType === User::class) {
                $commentId = User::all()->random()->id;
            } else {
                $commentId = Post::all()->random()->id;
            }

            $comment->user_id = $users->random()->id;
            $comment->commentable_type = $commentableType;
            $comment->commentable_id = $commentId;
            $comment->save();
        });
    }
}
