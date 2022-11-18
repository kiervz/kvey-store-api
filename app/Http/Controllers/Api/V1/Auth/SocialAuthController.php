<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;

use App\Models\User;

use Auth;
use Ulid\Ulid;

class SocialAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        $provider = strtolower($provider);
        $validated = $this->validateProvider($provider);

        if (!is_null($validated)) {
            return $validated;
        }

        return Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
    }

    public function handleProviderCallback($provider)
    {
        $provider = strtolower($provider);
        $validated = $this->validateProvider($provider);

        if (!is_null($validated)) {
            return $validated;
        }

        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            return $this->customResponse('Invalid credentials provided.', [], Response::HTTP_UNPROCESSABLE_ENTITY, false);
        }

        $loginUser = null;
        $existingUser = User::where('provider', $provider)->where('provider_id', $user['id'])->first();

        if ($existingUser) {
            $loginUser = $existingUser;
        } else {
            $createdUser = User::create([
                'ulid' => Ulid::generate(),
                'name' => $user['name'],
                'email' => $user['email'],
                'provider' => $provider,
                'provider_id' => $user['id'],
                'role_id' => User::CUSTOMER_ROLE,
                'status' => User::STATUS_ACTIVE,
                'email_verified_at' => now()
            ]);

            $createdUser->customer()->create();
            $loginUser = $createdUser;
        }

        $data = [
            'user' => $loginUser,
            'token_type' => 'Bearer',
            'token' => $loginUser->createToken('authToken')->plainTextToken
        ];

        return $this->customResponse('successfully login!', $data);
    }

    public function validateProvider($provider)
    {
        if (!in_array($provider, ['facebook', 'github', 'google'])) {
            return $this->customResponse('Please login using facebook, github or google', [], Response::HTTP_UNPROCESSABLE_ENTITY, false);
        }
    }
}