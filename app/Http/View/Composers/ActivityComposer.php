<?php

namespace App\Http\View\Composers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ActivityComposer {
    public function compose(View $view)
    {
        $ttl = now()->addMinutes(20);
        $mostCommented = Cache::remember('mostComments', $ttl, function () {
            return Post::mostCommented()->take(5)->get();
        });

        $mostUsersActive = Cache::remember('mostUsersActive', $ttl, function () {
            return User::usersMostPostWritten()->take(5)->get();
        });

        $mostUserActiveInLastMonth = Cache::remember('mostUserActiveInLastMonth', $ttl, function () {
            return User::usersActiveInLastMonth()->take(5)->get();
        });

        $view->with([
            'mostComments' => $mostCommented,
            'activeUsers'  => $mostUsersActive,
            'activeUsersInLastMonth' => $mostUserActiveInLastMonth,
        ]);
    }
}
