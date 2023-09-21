<?php

namespace App\Http\Controllers\API;

use App\Helper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Rules\Password;


class UserController extends Controller
{
    public function login(Request $request)
    {
        try{
            $request->validate(
                [
                    'username' => ['required', 'string'],
                    'password' => ['required', 'string']
                ]
              );

            $cred = request(['username', 'password']);
            if (!Auth::attempt($cred)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized',
                ], 'Authenticaton Failed', 400);
            }

            $user = User::where('username', $request->username)
            ->first();
            
            if (!Hash::check($request->password, $user->password, []))
        {
            throw new \Exception('Invalid Credential');
        }

            $token = $user->CreateToken('authToken')->plainTextToken;

            return ResponseFormatter::Success([
                'access_token' => $token,
                'token_type' => 'bearer',
                'user' => $user
            ], 'Authentication Sucess');
        }catch (Exception $error){
            return ResponseFormatter::error([
            'message' => 'Something Wrong',
            'error' => $error
        ], 'Authentication Error', 500);
        }

    }
    public function register(request $request)
    {
        try {
            $request->validate(
                [
                    'name' => ['required', 'string'],
                    'username' => ['required', 'string', 'unique:users,username'],
                    'password' => ['required', 'string', new Password],
                    'email' => ['required', 'string'],
                ]
            );

            User::create(
                [
                    'name' => $request->name,
                    'username' => $request->username,
                    'password' =>  Hash::make($request->password),
                    'email' =>  $request->email,
                ]
            );

            $user = User::where('username', $request->username)->first();

            $token = $user->CreateToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $token,
                'token_type' => 'bearer',
                'user' => $user
            ], 'User Registered');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something Wrong',
                'error' => $error
            ], 'User Cannot Registered', 500);
        }
    }


    public function getUser()
    {
        try {
            $user = User::get();

            return ResponseFormatter::success(
                $user,
                'Data User'
            );
        }   catch (Exception  $error) {
            return ResponseFormatter::error([
                'message'=> 'Something Wrong',
                'error' => $error
            ],  'Something Wrong, 500');
        }
    }

   }   //

