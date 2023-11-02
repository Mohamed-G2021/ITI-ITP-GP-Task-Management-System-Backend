<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Laravel\Sanctum\PersonalAccessToken;

class AccessTokensController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([
            'email' => 'required | email | max:255',
            'password' => 'required | string | min:8'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $device_name = $request->post('device_name', $request->userAgent());
            $token = $user->createToken($device_name);

            return Response::json(
                [
                    'code' => 1,
                    'token' => $token->plainTextToken,
                    'user' => $user,
                ],
                201
            );
        }

        return Response::json(
            [
                'code' => 0,
                'message' => 'Invalid credentials',
            ],
            401
        );
    }

    public function destroy($token = null)
    {
        $user = Auth::guard('sanctum')->user();

        if (null === $token) {
            $user->currentAccessToken()->delete();
            return response(null, 204);
        }

        $personal_access_token = PersonalAccessToken::findToken($token);
        if (
            $user->id == $personal_access_token->tokenable_id
            && get_class($user) == $personal_access_token->tokenable_type
        ) {
            $personal_access_token->delete();
        }
        return response(null, 204);
    }
}
