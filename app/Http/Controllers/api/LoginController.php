<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    public function redirectToProvider($provider)
    {
        $validated = $this->validateProvider($provider);

        if (!is_null($validated)) {
            return $validated;
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $validated = $this->validateProvider($provider);

        if (!is_null($validated)) {
            return $validated;
        }

        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (ClientException $exception) {
            return response()->json(['error' => 'Invalid credentials provided'], 422);
        }

        $userCreated = User::firstOrCreate(
            ['email' => $user->getEmail()],
            [
                'email_verified_at' => now(),
                'name' => $user->getName(),
                'status' => true,
            ]
        );

        $userCreated->providers()->updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $user->getId(),
            ],
            ['avatar' => $user->getAvatar()]
        );

        $token = $userCreated->createToken('Social login token')->plainTextToken;
        /* localStorage.setItem('token',$token); */
        $url = "http://localhost:4200/sign-in?token=" . $token;
        return Redirect::to(url($url));
        /*  return response()->json($userCreated, 200, ['Access-token' => $token]); */
    }

    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['github', 'google'])) {
            return response()->json(['error' => 'Please login using GitHub or Google '], 422);
        }
    }
}
