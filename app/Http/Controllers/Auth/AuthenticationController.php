<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function authenticate(LoginRequest $request)
    {
        $credentials = $request->validated();
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $token = $request->user()->createToken('authenticate')->plainTextToken;

            return response()->json(['status'=> 200,'authentication_token' => $token]);
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register(RegisterRequest $request)
    {
        $user = $request->validated();
        
        $user = User::create([
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => bcrypt($user['password']),
        ]);

        return response()->json(['status' => 200, 'message' =>'User Succesfully Created!']);
    }

    public function tokenVerification(Request $request) 
    {
        $user = $request->user();
        
        $data = [
            'email'      =>  $user->email,
            'name'       =>  $user->name,
            'active'     =>  $user->active,
        ];

        return $data;
    }
}
