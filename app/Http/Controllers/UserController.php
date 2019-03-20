<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login()
    {
        $credentials = [
            'email' => request('email'),
            'password' => request('password')
        ];

        if (Auth::attempt($credentials)) {
            $success['token'] = Auth::user()->createToken('MyApp')->accessToken;
            $success['name'] = Auth::user()->name;
            $success['email'] = Auth::user()->email;
            $success['uid'] = Auth::user()->id;
            return response()->json(['success' => $success]);
        }

        return response()->json(['message' => 'Unauthorised'], 401);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['success' => true]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = Auth::user()->name;
        $success['email'] = Auth::user()->email;
        $success['uid'] = Auth::user()->id;

        return response()->json(['success' => $success]);
    }

    public function getDetails()
    {
        return response()->json(['success' => Auth::user()]);
    }
}
