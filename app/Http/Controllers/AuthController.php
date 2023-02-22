<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function auth(){
        return view("admin.auth",["hash" => Hash::make("administrator")]);
    }
    public function authenticate(Request $request){
        $data = $request->only("email","password");

        if(Auth::attempt($data)){
            return redirect("admin/panel");
        }
    return abort(152);
    }
}
