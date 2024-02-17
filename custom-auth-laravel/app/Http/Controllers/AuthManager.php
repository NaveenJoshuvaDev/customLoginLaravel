<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthManager extends Controller
{
    //
   public function login()
    {
        if(Auth::check())
        {
          return redirect(route('home'));
        }
        return view('login');
    }

    public function registration()
    {
        
      if(Auth::check())
        {
          return redirect(route('home'));
        }
        return view('registration');
    }
    public function loginPost(Request $request)
    {
          $request->validate([
            'email' => 'required',
            'password' => 'required'
          ]);

          $credentials = $request->only('email', 'password'); 
          if(Auth::attempt($credentials)){
            return redirect()->intended(route('home'));
          }
          return redirect(route('login'))->with("error", "Login details are not valid");
    }

    public function registrationPost(Request $request)
    {
        $request->validate([
          'name'=>'required',
          'email'=>'required|email|unique:users',
          'password'=>'required'
        ]);
        
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        //To encrypt password use hash
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);
        if(!$user){
          return redirect(route('registration'))->with("error", "Reg failed");
        }
        return redirect(route('login'))->with("success", "Reg successful,please login now");
    }

    public function logout()
    {
      Session::flush();
      Auth::logout();
      return redirect(route('login'));
    }
}
