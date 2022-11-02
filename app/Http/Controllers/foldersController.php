<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class foldersController extends Controller
{
    public function index(Reqest $request) {
        $bodyContent = $request->getContent();
        $bodyContent = json_decode($bodyContent, true);
        $token = $bodyContent['Authorization'];
        $id = JWT.encode($token, 'e2UKQYYYklJJNgtNo6Lpkgwp6bWwpatMu0pIQXEijiWNYSv8d4p5aOXsDPI0eAC8');
        return response()->json(['id' => $id]);
    }

}
