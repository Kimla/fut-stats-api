<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request) : object
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::guard()->login($user);

        return $this->authedResponse(__('auth.register'));
    }

    public function login(Request $request) : object
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => __('auth.failed'),
            ], 422);
        }

        return $this->authedResponse(__('auth.login'));
    }

    public function logout() : object
    {
        Auth::logout();

        return response()->json([
            'message' => __('auth.logout'),
        ], 200);
    }

    public function user()
    {
        return response()->json([
            'user' => Auth::user(),
        ], 200);
    }

    protected function authedResponse(string $message) : object
    {
        $user = Auth::user();
        $token = $user->createToken('token-name');

        return response()->json([
            'message'=> $message,
            'user'=> $user,
            'token' => $token->plainTextToken,
        ], 200);
    }
}
