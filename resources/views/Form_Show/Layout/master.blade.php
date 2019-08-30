<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>農場監控系統F.M.@Farm_Monitoring</title>
</head>
<link
        href="https://fonts.googleapis.com/css?family=Open+Sans"
        rel="stylesheet"
/>
<link
        href="https://fonts.googleapis.com/earlyaccess/cwtexyen.css"
        rel="stylesheet"
/>

<link rel="stylesheet" href="{{asset('/css/form_page.css')}}">
<link rel="stylesheet" href="{{asset('/css/bundle/index.bundle.css')}}">
<script src="https://use.fontawesome.com/ee22e681c4.js"></script>
<body>
<div id="app">
    <header>
        <div class="container-fluid logo-place my-3">
            <div class="row">
                <div class="col-12 text-center">
                    <img src="http://placeimg.com/150/150/any/1" class="img-fluid rounded" alt="">
                </div>
            </div>
        </div>
        <div class="container mb-3">
            <div class="row text-center">
                <div class="d-non d-lg-block col-lg-2"></div>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-6 w-100 order-1 order-lg-1">
                            <div class="form-group">
                                <label for="former"></label>
                                <select class="form-control-sm w-50  text-dark" id="former">
                                    <option>請選擇農場</option>
                                    <option>開心蟲農場</option>
                                    <option>跟屁蟲農場</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6 w-100 order-2 order-lg-3">
                            <div class="form-group">
                                <label for="former-crop"></label>
                                <select class="form-control-sm w-50 text-dark" id="former-crop">
                                    <option>請選擇農田</option>
                                    <option>巧克力田</option>
                                    <option>可憐蟲田</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-non d-lg-block col-lg-2"></div>
                {{--             按鈕組合   --}}
                <div class=" info-group col-12 my-3 d">
                    <div class="info-btn-group rounded" id="info-btn-group">
                        <div class="container info-items">
                            <div class="row">
                                <div class="info-item col-3 text-dark  border-dark text-font" id="new-form-btn">新增農地
                                </div>
                                <div class="info-item col-3 text-dark  border-dark text-font" id="new-crop-btn">新增農田
                                </div>
                                <div class="info-item col-3 text-dark  border-dark text-font" id="new-monitor">農場數值
                                </div>
                                <div class="info-item col-3 text-dark  border-dark text-font" id="logout-btn">登出</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    @yield('show')
    <footer class="text-center mt-5">
        <pre>© 2018 Maselab318.  All Rights Reserved.   Designed By  YingLu_Chen.</pre>
    </footer>
</div>
</body>
<script src="{{asset('js/bundle/index.bundle.js')}}"></script>
</html>


