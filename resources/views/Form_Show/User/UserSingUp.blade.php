@extends('Form_Show.Layout.masterUser')

@section('show')
    <div class="flex-total-center h-100 flex-column">
        <div class="item-width rounded-top bg-white flex-total-center border py-1 d-flex justify-content-between px-3">
            <div class="">註冊資訊</div>
            <div class=""><a href="{{route('former_homepage')}}" class="text-font"> 返回登入 </a></div>
        </div>
        <div class="shadow bg-white p-3 rounded-bottom item-width">
            <form class="" method="POST" action="{{route('former_register')}}" id="registerForm">
                @csrf
                <div class="form-group">
                    <label for="register_name" class="text-dark">名稱</label>
                    <input type="text" class="form-control text-font" id="register_name" name="register_name"
                           placeholder="輸入名稱" required>
                </div>
                <div class="form-group">
                    <label for="register_username" class="text-dark">帳號</label>
                    <input type="text" class="form-control text-font" id="register_username" name="register_username"
                           placeholder="輸入帳號" required>
                </div>
                <div class="form-group">
                    <label for="register_password" class="text-dark">密碼</label>
                    <input type="password" class="form-control text-font" id="register_password"
                           name="register_password"
                           placeholder="輸入密碼" required>
                </div>
                <div class="form-group">
                    <label for="register_password_check" class="text-dark">確認密碼</label>
                    <input type="password" class="form-control text-font" id="register_password_check"
                           name="register_password_check"
                           placeholder="輸入密碼" required>
                </div>
                <div class="form-group">
                    <label for="register_email" class="text-dark">信箱</label>
                    <input type="email" class="form-control text-font" id="register_email" name="register_email"
                           aria-describedby="emailHelp" placeholder="輸入信箱" required>
                </div>
                <button type="reset" class="btn btn-warning btn-lg btn-block btn-login text-font register-btn ">清除
                </button>
                <button type="button" class="btn btn-primary btn-lg btn-block btn-login text-font register-btn"
                        onclick="registerInfoCheck()">送出
                </button>
            </form>
        </div>
    </div>
@endsection