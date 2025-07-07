<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\ApiResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $request->validated();

        $attempt = auth()->attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ]);

        if (!$attempt) return ApiResponse::unauthorized();

        $user = auth()->user();
        $token = $user->createToken($user->name)->plainTextToken;

        return ApiResponse::success('Login realizado.', ['token' => $token]);
    }
}
