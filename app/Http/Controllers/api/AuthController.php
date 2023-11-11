<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\ForgotPassword;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

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
            'token' => $user->createToken('API Token')->plainTextToken
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
            'token' => $user->createToken('API Token')->plainTextToken
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

    public function forgotPassword(Hasher $hasher, ForgotPasswordRequest $request)
    {
        $user = ($query = User::query());

        $user = $user->where($query->qualifyColumn('email'), $request->input('email'))->first();

        if (!$user || !$user->email) {
            return response()->json(['message' => 'No record found for this email address'], 404);
        }

        $resetPasswordToken = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        if (!$userPassRest = PasswordReset::where('email', $user->email)->first()) {
            PasswordReset::create([
                'email' => $user->email,
                'token' => $resetPasswordToken,
            ]);
        } else {
            $userPassRest->update([
                'email' => $user->email,
                'token' => $resetPasswordToken,
            ]);
        }


        Mail::to($user->email)->send(new ForgotPassword($resetPasswordToken));

        return response()->json(['message' => 'A reset code has been sent to your email address']);
    }

    public function  resetPassword(ResetPasswordRequest $request)
    {
        $attributes = $request->validated();
        $user = User::where('email', $attributes['email'])->first();

        if (!$user) {
            return response()->json(
                [
                    'message' =>  'No record found for this email address'
                ],
                404
            );
        }

        $resetRequest = PasswordReset::where('email', $user->email)->first();

        if (!$resetRequest || $resetRequest->token != $request->token) {
            return response()->json(
                [
                    'message' =>
                    'An error occurred, Please try again later'
                ],
                404
            );
        }

        $user->update([
            'password' => Hash::make($attributes['password'])
        ]);
        $resetRequest->delete();

        $token = $user->createToken('Reset Password token')->plainTextToken;

        $loginResponse = [
            'user' => $user,
            'token' => $token,
        ];

        return response()->success(
            $loginResponse,
            'Password has been reset successfully',
            201
        );
    }
}
