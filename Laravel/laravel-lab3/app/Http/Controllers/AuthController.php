<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!$token = JWTAuth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Невірні облікові дані']);
        }

        return redirect()->intended('/')
            ->withCookie($this->createJwtCookie($token));
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'role'     => 'required|in:client,manager,admin'
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
        ]);

        $token = JWTAuth::fromUser($user);

        return redirect('/')->withCookie($this->createJwtCookie($token));
    }

    public function logout()
    {
        auth()->logout();
        $cookie = Cookie::forget('jwt');
        return redirect('/login')->withCookie($cookie);
    }

    protected function createJwtCookie($token)
    {
        return cookie(
            'jwt',
            $token,
            config('jwt.ttl'),
            null,
            null,
            true,
            true,
            false,
            'Lax'
        );
    }
}