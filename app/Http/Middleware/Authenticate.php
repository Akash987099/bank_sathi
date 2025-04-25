<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Determine which guard is being used
        $guards = array_keys(config('auth.guards'));
        foreach ($guards as $guard) {
            if (auth()->guard($guard)->check()) {
                continue;
            }

            if ($guard === 'user') {
                return route('userLogin');
            }

        }

        return route('login');
    }
}
