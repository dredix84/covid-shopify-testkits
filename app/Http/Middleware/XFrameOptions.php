<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class XFrameOptions
{
    /**
     * @param  Request  $request
     * @param  Closure  $next
     */
    public function handle(Request $request, Closure $next): void
    {
        $response = $next($request);

        $option = 'SAMEORIGIN';

        // In this example, we are only allowing the third party to include the "iframe" route
        // It's always better to scope this to a given route / set of routes to avoid any unattended security problems
        if ($request->routeIs('iframe') && $xframeOptions = env('X_FRAME_OPTIONS', 'SAMEORIGIN')) {
            if (false !== strpos($xframeOptions, 'ALLOW-FROM')) {
                $url = trim(str_replace('ALLOW-FROM', '', $xframeOptions));

                $response->header('Content-Security-Policy', 'frame-ancestors '.$url);
            }
        }

        $response->header('X-Frame-Options', $xframeOptions);
    }
}
