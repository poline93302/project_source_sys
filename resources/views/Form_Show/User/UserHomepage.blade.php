@extends('Form_Show.Layout.master')

@section('show')
    <div class="container">
        <div class="row flex-total-center mt-5 ">
            <form class="col-4 bg-white py-3 rounded" method="POST" action="{{route('former_login')}}">
                @csrf
                <div class="form-group">
                    <label for="username" class="text-dark">帳號</label>
                    <input type="text" class="form-control" id="username" name="login_username" placeholder="輸入帳號">
                </div>
                <div class="form-group">
                    <label for="password" class="text-dark">密碼</label>
                    <input type="password" class="form-control" id="password" name="login_password" placeholder="輸入密碼">
                </div>
                <div class="btn-group float-right">
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#registerModal">註冊
                    </button>
                    <button type="submit" class="btn btn-primary">登入</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="my-3 mx-3" method="POST" action="{{route('former_register')}}">
                    @csrf
                    <div class="form-group">
                        <label for="register_username" class="text-dark">帳號</label>
                        <input type="text" class="form-control" id="register_username" name="register_username"
                               placeholder="輸入帳號">
                    </div>
                    <div class="form-group">
                        <label for="register_password" class="text-dark">密碼</label>
                        <input type="password" class="form-control" id="register_password" name="register_password"
                               placeholder="輸入密碼">
                    </div>
                    <div class="form-group">
                        <label for="register_email" class="text-dark">信箱</label>
                        <input type="text" class="form-control" id="register_email" name="register_email"
                               aria-describedby="emailHelp" placeholder="輸入信箱">
                    </div>
                    <div class="btn-group float-right">
                        <button type="reset" class="btn btn-warning">清除</button>
                        <button type="submit" class="btn btn-primary">送出</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection