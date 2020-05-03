<?php

namespace App\Http\Controllers;
use App\Http\Models\Entities\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|string|email|unique:users',
            'role_id'    => 'integer|required',
            'password' => 'required|string|confirmed',
        ]);
        $user = new User([
            'name'     => $request->name,
            'email'    => $request->email,
            'active'    => 1,
            'phone'    => $request->phone,
            'role_id'    => $request->role_id,
            'password' => bcrypt($request->password),
        ]);
        $user->save();
        return response()->json([
            'message' => 'Successfully created user!'], 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email'       => 'required|string|email',
            'password'    => 'required|string',
            'remember_me' => 'boolean',
            ]);
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            
            $response = [
                'status'  => 'FAILED',
                'code'    => 401,
                'message' => __('Incorrect Credentials'),
                'data'    => "",
            ];
    
            return response()->json($response);
            
        }
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();
        $data = [
            'user'     => $user,
            'access_token'    => $tokenResult->accessToken,
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at)
                    ->toDateTimeString()
            
        ];
        $response = [
            'status'  => 'OK',
            'code'    => 200,
            'message' => __('Login Correctly'),
            'data'    => $data,
        ];

        return response()->json($response);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 
            'Successfully logged out']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
