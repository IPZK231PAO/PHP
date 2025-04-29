<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTAuthWeb
{
    public function handle($request, Closure $next)
{
    try {
        $token = $request->cookie('jwt');
        
        if (!$token) {
            return redirect()->route('login');
        }

        $user = JWTAuth::setToken($token)->authenticate();
        
        if (!$user) {
            throw new JWTException('User not found');
        }

        auth()->login($user); 

    } catch (TokenExpiredException $e) {
        return redirect()->route('login');
    } catch (JWTException $e) {
        return redirect()->route('login');
    }

    return $next($request);
}
}