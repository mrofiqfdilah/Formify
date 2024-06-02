<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|min:5'
        ]);

        if($validator->fails()){
            return response()->json([
            'message' => 'Invalid field',
            'errors' => $validator->errors()
            ], 422);
        }

        $onlys = $request->only('email','password');

        if(Auth::attempt($onlys)){
            $user = Auth::user();

            $token = $user->createToken('Access_token')->plainTextToken;

            return response()->json([
            'message' => 'Login success',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'accessToken' => $token
            ],
            ], 200);
        }

        return response()->json([
        'message' => 'Email or password incorrect'
        ], 401);

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
        'message' => 'Logout success'
        ], 200);
    }
}
