<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email',
            'password_' => 'required',
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if(Hash::check($request->input('password_'), $user->password)){
            $apikey = base64_encode(Str::random(40));
            User::where('email', $request->input('email'))->update(['api_token' => "$apikey"]);

            return response()->json(['status' => 'success','api_token' => $apikey]);
        }else{
            return response()->json(['status' => 'fail'],401);
        }
    }
}
