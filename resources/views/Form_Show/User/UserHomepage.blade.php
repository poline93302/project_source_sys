@extends('Form_Show.Layout.masterUser')

@section('show')
    <div class="flex-total-center h-100 flex-column">
        <div class="border rounded-top item-width-login bg-white logo-show flex-total-center">
            <img src={{asset("picture/logo.gif")}} alt="標題" width="200" height="200">
        </div>
        <div class="item-width-login bg-white border border-top-0 py-1 d-flex justify-content-between px-3">
            <div class="">登入資訊</div>
            <div class=""><a href="{{route('former_singUp')}}" class="text-font"> 註冊 </a></div>
        </div>
        <form class="shadow bg-white p-3 rounded-bottom item-width-login" method="POST"
              action="{{route('former_login')}}"
              id="loginForm">
            @csrf
            <div class="form-group">
                <label for="username" class="text-secondary">帳號</label>
                <input type="text" class="form-control text-font" id="username" name="login_username"
                       placeholder="輸入帳號"
                       required
                        @if(!is_null($name))
                            value="{{$name}}"
                        @endif
                >

            </div>
            <div class="form-group">
                <label for="password" class="text-secondary">密碼</label>
                <input type="password" class="form-control text-font" id="password" name="login_password"
                       placeholder="輸入密碼"
                       required
                        @if(!is_null($pass))
                            value="{{$pass}}"
                        @endif
                >


            </div>
            @if ($errors->any())
                <div class="error-block rounded flex-total-center mb-1">
                    @foreach ($errors->all() as $error)
                        <div class="error-text text-font text-center">{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            <button type="button" class="btn btn-primary btn-lg btn-block text-font btn-login mt-4"
                    onclick="loginInfoCheck();">
                登入
            </button>


        </form>
    </div>
@endsection