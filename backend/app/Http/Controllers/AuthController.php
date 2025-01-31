<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register.
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        return response()->json('Successfully registered!', 201);
    }

    /**
     * Login.
     */
    public function login(Request $request)
    {

        $loginField = $request->input('username');
        $credentials = [
            filter_var($loginField, FILTER_VALIDATE_EMAIL) ? 'email' : 'username' => $loginField,
            'password' => $request->input('password'),
        ];

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['detail' => 'Invalid credentials'], 401);
        }

        return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);

    }
}
