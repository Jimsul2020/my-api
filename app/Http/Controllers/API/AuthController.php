<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            'email' => 'required|email|unique:users,email',
            "password" => "required",
            'confirm_password' => "required|same:password"
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "message" => "validator error",
                "data" => $validator->errors()->all()
            ], 422);
        }

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password)
        ]);
        $response = [];
        $response["token"] = $user->createToken("my-api")->plainTextToken;
        $response["name"] = $user->name;
        $response["email"] = $user->email;

        return response()->json([
            "status" => 1,
            "message" => "user registered",
            "data" => $response
        ]);
    }
    function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required",
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "message" => "validator error",
                "data" => $validator->errors()->all()
            ], 422);
        }
        if (Auth::attempt(["email" => $request->email, "password" => $request->password])) {
            $user = Auth::user();
            $response = [];
            $response["token"] = $user->createToken("my-api")->plainTextToken;
            $response["name"] = $user->name;
            $response["email"] = $user->email;
            return response()->json([
                "status" => 1,
                "message" => "user login",
                "data" => $response
            ], 200);
        }
        return response()->json([
            "status" => 0,
            "message" => "auth error",
            "data" => null
        ], 501);
    }
}
