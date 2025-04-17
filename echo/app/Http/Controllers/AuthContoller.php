<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthContoller extends Controller
{
    //Authentication
    public function register (Request $request ) {
        $request->validate([
            'name' => 'string|required',
            'email' => 'string|email|required|unique:users',
            'phone' => 'string|unique:users|required',
            'password' => 'confirmed|required|string'

        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password)
        ]);

        $response = [
            'message' => 'user registered success' ,
            'data' => $user->email,
            'status' => 200
        ];

        return response($response , 200);

    }

//============================

    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'phone' => 'string|required'
        ]);

        $user = User::where('email' , $request->email)->orWhere('phone' , $request->phone)->first();

        if(!$user || !Hash::check($request->password , $user->password )) {

            $response = [
                'message' => 'something is not valid',
                'status' => 401
            ];

            return response($response , 200);
        }

            $token = $user->createToken('mytoken')->plainTextToken;

            $response = [
                'message' => 'welcome to our APP',
                'token' => $token,
                'data' => [
                    $user->name,
                    $user->phone,
                 ],

                'status' => 200
            ];



        return response($response , 200);
    }

}
//==============



