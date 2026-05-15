<?php

namespace App\Http\Middleware;

use App\Events\UserOnlineStatusChanged;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserOnlineStatus
{
   public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (!$user->is_online) {
                $user->update([
                    'is_online'    => true,
                    'last_seen_at' => Carbon::now(),
                ]);

                broadcast(new UserOnlineStatusChanged($user, true));
            }
        }

        return $next($request);
    }
}