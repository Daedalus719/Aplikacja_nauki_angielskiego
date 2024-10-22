<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Moderator
{
    public function handle($request, Closure $next)
    {

        if (Auth::check() && (Auth::user()->usertype === 'Admin' || Auth::user()->usertype === 'Moderator')) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Odmowa dostępu: Nie posiadasz wystarczających uprawnień.');
    }
}
