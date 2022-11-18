<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\User;

use Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $credentials = ['email' => $email, 'password' => $password];

        if (Auth::validate($credentials)) {
            $user = User::where('email', $email)->first();

            if ($user['status'] === User::STATUS_BANNED) {
                return $this->customResponse('Login failed, your account has been disabled.', [], Response::HTTP_UNAUTHORIZED, false);
            }

            $data = [
                'user' => $user,
                'token_type' => 'Bearer',
                'token' => $user->createToken('authToken')->plainTextToken
            ];
        } else {
            return $this->customResponse('Login failed', [], Response::HTTP_UNAUTHORIZED, false);
        }

        return $this->customResponse('login successfully', $data, Response::HTTP_OK);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return $this->customResponse('successfully logged out!', [], Response::HTTP_NO_CONTENT);
    }
}
