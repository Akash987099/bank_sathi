<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Rules\PasswordRule;
use Illuminate\Support\Str;
use Symfony\Component\Mime\Part\HtmlPart;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{

    public function userLogin(){
        // return "11111";
        return view('login');
    }

    public function userLogins(Request $request){
        // dd($request->all());

        $credentials = $request->only('email', 'password');

        // dd($credentials);

        if (Auth::guard('user')->attempt($credentials)) {
            // dd($credentials);
            $user = Auth::guard('user')->user();
            // dd($user);

             return response()->json(['status' => 'success']);
        }
        // dd($credentials);
        return response()->json(['status' => 'error']);

    }



}
