<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\RegisterRequest;

class AuthenticationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function authenticate(Request $request, $socialAuth = '')
    {   
        if(!$socialAuth){
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email'],
                'password' => ['required']
            ]);
            
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
    
            $credentials = $validator->validated();
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
            }else{
                return response()->json(['status'=> 404,'message' => 'Incorrect username or password, Please try again.']);
            }
        }else{
            dd($socialAuth);
            $is_logged_in = Auth::loginUsingId($socialAuth->id);
        }

        $token =  auth()->user()->createToken('authenticate')->plainTextToken;
    
        return response()->json(['status'=> 200,'authentication_token' => $token, 'message' => '']);
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

    public function logout(Request $request){
        
        $request->user()->tokens()->delete();

        return response()->json(['status' => 200]);
    }

    public function authRedirect($provider){
        return Socialite::driver($provider)->redirect();
    }

    public function authCallback(Request $request, $provider){
        try {
            $user = Socialite::driver($provider)->user();

            if(User::where('email', $user->email)->whereNotNull('provider_id')->exists()){
                return response(['status'=> 404,'message' => 'Email already registered!']);
            }
            // dd('ASFAS',$user);
            $new_user = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'provider_id' => $user->id,
                'provider' => $provider,
                'provider_token' => $user->token,
                'password' => null,
            ]);

            $this->authenticate($request, $new_user);

            // return redirect('/');

        } catch (\Exception $e) {
            dd('CATCH MO TO',$e->getMessage());
            // Log or handle the exception
            Log::error($e->getMessage());
            // Handle the error gracefully
            return redirect('/')->with('error', 'Authentication failed.');
        }
    }
}
