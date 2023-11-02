<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{



    public function register(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8'
            ]
        );

        if ($validateUser->fails()) {
            return response(
                [
                    'status' => false,
                    'message' => 'validation errors',
                    'errors' => $validateUser->errors()
                ],
                401
            );
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }

    public function login(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        if ($validateUser->fails()) {
            return response(
                [
                    'status' => false,
                    'message' => 'validation errors',
                    'errors' => $validateUser->errors()
                ],
                401
            );
        }

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'status' => false,
                'message' => 'Email & Password does not match with our record.',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'status' => true,
            'message' => 'User Logged In Successfully',
            'token' => $user->createToken($request->userAgent())->plainTextToken
        ], 200);
    }

    public function logout()
    {
        $user = Auth::guard('sanctum')->user();
        $user->currentAccessToken()->delete();
        return response()->json(
            ['message' => 'Successfully logged out']
        );
    }
}
