<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

//使用者表單
use App\UserFormer;
use Illuminate\Support\Facades\Hash;

class FormerInfoController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/monitor';
    protected $loginPath = '/';

    public function username()
    {
        return 'name';
    }

    public function index(Request $req)
    {
        return view('Form_Show.User.UserHomepage', ['name' => null, 'pass' => null]);
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
        dd('123');
    }

    public function register(Request $req)
    {
        UserFormer::create([
            'name' => $req['register_name'],
            'username' => $req['register_username'],
            'email' => $req['register_email'],
            'password' => Hash::make($req['register_password']),
        ]);
        return view('Form_Show.User.UserHomepage', ['name' => $req['register_username'], 'pass' => $req['register_password']]);
//        return redirect()->to(route('former_homepage'));
    }
}
