<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function authorization(Request $request)
    {
        $bodyContent = $request->getContent();
        $bodyContent = json_decode($bodyContent, true);
        $email = $bodyContent['email'];
        $user = User::where('email', $email)->get();
        return $user;
    }
    public function registration(Request $request)
    {
        $user = User::create($request->validated());
        return $user;
    }

}
