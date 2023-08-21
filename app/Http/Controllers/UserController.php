<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register()
    {
        return view('user.register');
    }

    public function login()
    {
        
        return view('user.login');
    }

    public function check(Request $request)
    {
        $credential = new \stdClass();
        $credential->email = $request->input('email');
        $credential->password = $request->input('password');
        
        if(isset($credential->email) && isset($credential->password)) {
            $response = Http::withoutVerifying()->accept('application/json')->withBody(json_encode($credential))->post(config('app.url').'/api/v1/user/login');
            
            $return = $response->object();

            if($response->successful()) {
                Auth::Attempt($request->only('email', 'password'));

                return redirect()->intended('home');
            }

            return redirect()->route('login')->withInput()->with('failed', $return->message);
        }

        return redirect()->route('login');
    }

    public function logout()
    {
        $response = Http::withoutVerifying()->accept('application/json')->withToken(Auth::user()->api_token)->post(config('app.url').'/api/v1/user/logout');
        
        if($response->successful()) {
            Auth::logout();
            return redirect('/categories');
        }
    }

    public function store(RegisterRequest $request)
    {
        $user = new \stdClass();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        
        $response = Http::withoutVerifying()->accept('application/json')->withBody(json_encode($user))->post(config('app.url').'/api/v1/user/register');
        
        if($response->successful()) {
            return redirect()->route('login')->with('success','Registration was successful, please login.');
        }

        if($response->status() == 422) {
            $return = $response->object();
            
            return redirect()->route('user.register')->withInput()->with('errors', $return->errors);    
        }
     
        abort(500);
    }
}
