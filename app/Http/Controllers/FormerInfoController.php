<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class FormerInfoController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function username()
    {
        return 'name';
    }

    public function index()
    {
        return view('Form_Show.User.UserHomepage');
//        dd('homepage');
    }

    public function login(Request $req)
    {
        if (Auth::attempt(['name' => $req['login_username'], 'password' => $req['login_password']])) {
            return redirect()->to(route('monitor_homepage'));
        } else {
            return $message = '請輸入正確帳號密碼';
        }
    }


    public function logout()
    {

    }

    public function register(Request $req)
    {
        dd($req);
    }
}
