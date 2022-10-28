<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function authorization(Request $request)
    {

        $bodyContent = $request->getContent();
        json_decode($bodyContent, true);
        dd($bodyContent);
//        $email = $bodyContent["email"]; // Dont work
        return $bodyContent;
    }
    public function registration(Request $request)
    {
        $user = User::create($request->validated());
        return $user;
    }

}
