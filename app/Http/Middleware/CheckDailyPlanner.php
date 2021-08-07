<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class CheckDailyPlanner
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
      if (Auth::check() && Auth::user()->is_planner_completed == 0 && !(strpos($request->getPathInfo(), 'dailyplanner') !== false)) {
        return redirect()->route('dailyplanner.index')->withErrors('Please complete daily planner first!');
      }

      return $next($request);
    }
}
