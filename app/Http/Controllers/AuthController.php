<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'email'      =>  'required:email',
            'password'   =>  'required'
        ]);

        if ($validator->fails()) {
            $response = [
                'status'   =>  false,
                'message'   =>  $validator->errors(),
            ];
            return response()->json($response);
        }
        
        $user= User::where('email', $request->email)->first();
        
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'status'   => false,
                'message'   => 'These credentials do not match our records.'
            ]);
        }
    
        $token = $user->createToken('ApiToken')->plainTextToken;
    
        $response = [
            'status'   => true,
            'data'      => $user,
            'token'     => $token
        ];
        
        return response($response, 201);
    }
}
