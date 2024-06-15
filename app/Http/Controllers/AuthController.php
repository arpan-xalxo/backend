<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

use App\Mail\ForgotMail;
use App\Mail\RegisterMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public  function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');


        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('main')->plainTextToken;
            return response()->json([
                'user'=> $user,
                'token' => $token]);
        }
       return response()->json([
           'message'=>"invalid credentials",
       401
       ]);
    }

    public  function register(RegisterRequest $request)
    {
       $data = $request->validated();

       $user=User::create([
          'name' => $data['name'],
           'email' => $data['email'],
           'password' => bcrypt($data['password']),
           'remember_token' => Str::random(60),
       ]);

     Mail::to($user->email)->send(new RegisterMail($user));
       return response()->json([
           'message'=>'Registration Successful'],
           201);
    }

    public  function  verify($token)
    {

        $user =User::where('remember_token',$token)->first();

        if (!empty($user)) {
          $user->email_verified_at = now();
          $user->save();

          $token = $user->createToken('main')->plainTextToken;

          return redirect()->to('http://localhost:1776/verify-success?token=' . $token);

        }
        else{
            return response()->json([
               'message'=>'User not found'
            ] , 404);
        }
    }

    public  function logout(Request $request)
    {

        $user = $request->user();
        $user->currentAccessToken()->delete();

        return response('' , 204) ;
    }

    public function forgotPassword(Request $request)
    {
        $user = $request->validate(['email' => 'required|email']);
        $email = $request->input('email');
        $user = User::where('email' , $email)->first();
        if (!$user) {
            return response()->json([
                'status' => 'User not found',
                'email' => $email
            ], 404);
        }

        Mail::to($email)->send(new ForgotMail($user));

        return response()->json([
            'status' => 'Password reset email sent',
            'user' => $user,
            'email' => $email
        ]);
    }

    public function resetPassword(Request $request){
         $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed|min:6',
        ]);
        $user = User::where('email' , $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        //verifying the token (remember_token is used for password reset)
        if ($user->remember_token !== $request->token) {
            return response()->json(['message' => 'Invalid token'], 422);
        }
        //Update the user's password
     $user->password = Hash::make($request->password);
     $user->remember_token = null;
     $user->save();

        return response()->json([
           'status' => 'Password reset successful',
            ]);

    }
}
