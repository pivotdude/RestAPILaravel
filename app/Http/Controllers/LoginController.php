<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{

    public function logout (Request $request) {
        // Удаление localStorage - token
        return response()->json(['message' => 'Your logout']);
    }

    // User Register
    public function register(Request $request) {
        $validator  =   Validator::make($request->all(), [
            "firstname"  =>  "required",
            "lastname"  =>  "required",
            "email"  =>  "required|email",
            "password"  =>  "required"
        ]);

        if($validator->fails()) {
            return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
        }

        $inputs = $request->all();
        $inputs["password"] = Hash::make($request->password);

        $usercon = User::where('email', $inputs['email'])->get();

        if (!$usercon) {
            return response()->json(["status" => "failed", "message" => "Email is already in use!"]);
        }

        $user   =   User::create($inputs);

        if(!is_null($user)) {
            return response()->json(["status" => "success", "message" => "Success! registration completed"]);
        }
        else {
            return response()->json(["status" => "failed", "message" => "Registration failed!"]);
        }
    }

    // User login
    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
            "email" =>  "required|email",
            "password" =>  "required|min:8",
        ]);

        if($validator->fails()) {
            return response()->json(["validation_errors" => $validator->errors()]);
        }

        $user           =       User::where("email", $request->email)->first();

        if(is_null($user)) {
            return response()->json(["status" => "failed", "message" => "Failed! email not found"]);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user       =       Auth::user();
            $token      =       $user->createToken('token')->plainTextToken;

            return response()->json(["status" => "ok", "data" => [ "token" => $token ]]);
        }
        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! invalid password"]);
        }
    }


    // User Detail
    public function user(Request $request) {
        $user = $request->user();

        if(!is_null($request->user())) {
            return response()->json(["status" => "success", "data" => $user]);
        }

        else {
            return response()->json(["status" => "failed", "message" => "Whoops! no user found"]);
        }
    }
}
