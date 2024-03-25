<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyAppVersion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $appVersion = $request->header('app-version');
        $apiAppVersion = env("APP_VERSION");
        if ($appVersion != $apiAppVersion) {
            return response()->json(["APP_VERSION" => false, "message" => "Version de la Aplicacion desactualizada", "status" => false]);
        }
        return $next($request);
    }
}
