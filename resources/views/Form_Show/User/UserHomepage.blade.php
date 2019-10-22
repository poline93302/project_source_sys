@extends('Form_Show.Layout.master')

@section('show')
    <div class="container">
        <div class="row flex-total-center mt-5">
            {{--            {{ $name }}--}}
            <form class="col-8 col-md-6 col-lg-4 bg-white py-3 rounded shadow" method="POST"
                  action="{{route('former_login')}}"
                  id="loginForm">
                @csrf
                <div class="form-group">
                    <label for="username" class="text-dark">帳號</label>
                    <input type="text" class="form-control" id="username" name="login_username" placeholder="輸入帳號"
                           required
                           @if(!is_null($name))
                           value="{{$name}}"
                            @endif
                    >
                </div>
                <div class="form-group">
                    <label for="password" class="text-dark">密碼</label>
                    <input type="password" class="form-control" id="password" name="login_password" placeholder="輸入密碼"
                           required
                           @if(!is_null($pass))
                           value="{{$pass}}"
                            @endif
                    >
                </div>

                <div class="btn-group float-right">
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#registerModal">註冊
                    </button>
                    <button type="button" class="btn btn-primary" onclick="loginInfoCheck();">登入</button>
                </div>
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <p class="error-text text-center w-100">{{ $error }}</p>
                    @endforeach
                @endif

            </form>
        </div>
    </div>
    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form class="my-3 mx-3" method="POST" action="{{route('former_register')}}" id="registerForm">
                    @csrf
                    <div class="form-group">
                        <label for="register_name" class="text-dark">名稱</label>
                        <input type="text" class="form-control" id="register_name" name="register_name"
                               placeholder="輸入名稱" required>
                    </div>
                    <div class="form-group">
                        <label for="register_username" class="text-dark">帳號</label>
                        <input type="text" class="form-control" id="register_username" name="register_username"
                               placeholder="輸入帳號" required>
                    </div>
                    <div class="form-group">
                        <label for="register_password" class="text-dark">密碼</label>
                        <input type="password" class="form-control" id="register_password" name="register_password"
                               placeholder="輸入密碼" required>
                    </div>
                    <div class="form-group">
                        <label for="register_password_check" class="text-dark">確認密碼</label>
                        <input type="password" class="form-control" id="register_password_check"
                               name="register_password_check"
                               placeholder="輸入密碼" required>
                    </div>
                    <div class="form-group">
                        <label for="register_email" class="text-dark">信箱</label>
                        <input type="email" class="form-control" id="register_email" name="register_email"
                               aria-describedby="emailHelp" placeholder="輸入信箱" required>
                    </div>
                    <div class="btn-group float-right">
                        <button type="reset" class="btn btn-warning">清除</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">關閉</button>
                        <button type="button" class="btn btn-primary" onclick="registerInfoCheck()">送出</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection