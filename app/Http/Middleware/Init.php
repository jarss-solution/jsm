<?php

namespace App\Http\Middleware;

use App\Models\NotificationUser;
use Closure;
use View;

class Init
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $navbar_notifications = NotificationUser::with('notification')
            ->where('user_id', $request->user()->id)
            ->orderBy('seen', 'asc')
            ->orderBy('updated_at', 'desc')
            ->limit(100)
            ->get();

        $navbar_notifications_unseen_count = NotificationUser::with('notification')
            ->where('user_id', $request->user()->id)
            ->where('seen', 0)
            ->count();

        View::share(compact('navbar_notifications', 'navbar_notifications_unseen_count'));

        return $next($request);
    }
}
