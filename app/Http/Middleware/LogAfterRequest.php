<?php

namespace App\Http\Middleware;

use App\LogRequest;
use Closure;

class LogAfterRequest
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
        return $next($request);
    }

    public function terminate($request, $response)
    {
        $url = $request->fullUrl();
        $ip  = $request->ip();

        $startTime  = date("Y-m-d H:i:s", LARAVEL_START);
        $endTime    = date("Y-m-d H:i:s");
        $timeTaken  = strtotime($endTime) - strtotime($startTime);

        try {
            $r              = new LogRequest;
            $r->ip          = $ip;
            $r->url         = $url;
            $r->status_code = $response->status();
            $r->method      = $request->method();
            $r->request     = json_encode($request->all());
            $r->response    = !empty($response) ? json_encode($response) : json_encode([]);
            $r->start_time  = $startTime;
            $r->end_time    = $endTime;
            $r->time_taken  = $timeTaken;
            $r->save();
        } catch (\Exception $e) {
            \Log::info("Log after request has issue " . $e->getMessage());
        }

    }
}
