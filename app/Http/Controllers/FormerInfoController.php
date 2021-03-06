<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Session\Session;
use Dotenv\Validator;
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
        return 'username';
    }

    public function index(Request $req)
    {
        return view('Form_Show.User.UserHomepage', ['name' => null, 'pass' => null]);
    }

    public function login(Request $req)
    {
        if (Auth::attempt(['username' => $req['login_username'], 'password' => $req['login_password']])) {
            Session(['username' => Auth::user()['username']]);
            return redirect()->to(route('monitor_homepage'));
        } else {
            return back()->withErrors(['登入資訊錯誤']);
        }
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->to(route('former_homepage'));
    }

    public function register(Request $req)
    {

        $check = UserFormer::where(['username' => $req['register_username']])->first();
        if (isset($check)) return back()->withErrors(['此帳號已存在']);

        UserFormer::create([
            'name' => $req['register_name'],
            'username' => $req['register_username'],
            'email' => $req['register_email'],
            'password' => Hash::make($req['register_password']),
        ]);
        return view('Form_Show.User.UserHomepage', ['name' => $req['register_username'], 'pass' => $req['register_password']]);
    }
}
