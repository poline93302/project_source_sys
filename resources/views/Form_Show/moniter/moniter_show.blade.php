@extends('Form_Show.Layout.master')

@section('show')
    <header class="my-3">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="container my-3">
            <div class="row text-center bg-white shadow rounded-top">
                <div class="col-6 col-md-7 my-3 ">
                    <img src="http://placeimg.com/1500/400/any/1" class="img-fluid" alt="">
                </div>
                <div class="col-6 col-md-5 my-3 ">
                    <div class="row mt-3">
                        <div class="col-12 mb-3">
                            <span class="text-dark">歡迎 {{ $former }} 抵達本站</span>
                        </div>
                        <form method="POST" action="{{route('monitor_homepage_select')}}" class="col-12 mb-3 mb-md-1">
                            @csrf
                            <div class="form-group mb-1">
                                <label for="former"></label>
                                <select class="form-control-sm w-98" id="selectForm" name="selectForm"
                                        onchange="linkSelectChange({{ json_encode($cropList) }})">
                                    <option value="all">請選擇農場</option>
                                    @foreach( ($farmList) as $List)
                                        <option>{{ ($List['farm']) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-1">
                                <label for="former-crop"></label>
                                <select class="form-control-sm w-98" id="selectCrop" name="selectCrop">
                                    <option value="all">請選擇農田</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-warning  btn-sm btn-block text-font">查詢</button>
                        </form>

                        <div class="col-12 row no-gutters">
                            <div class="col">
                                <button type="button" class="btn btn-primary w-98 btn-sm text-wrap text-font"
                                        data-toggle="modal"
                                        data-target="#FormerInfoModal">
                                    農夫資訊
                                </button>
                            </div>
                            <div class="col">
                                <a href="{{ route('monitor_homepage') }}">
                                    <button type="button" class="btn btn-primary w-98 btn-sm text-font">
                                        顯示全部
                                    </button>
                                </a>
                            </div>
                            <div class="col">
                                <form method="POST" action="{{ route('former_logout') }}" id="loginForm">
                                    @csrf
                                    <button type="button" class="btn btn-primary w-98 btn-sm text-wrap text-font"
                                            onclick="alertLogin()">
                                        登出
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="show-monitor container mt-5">
        {{--                            顯示部分--}}
        @if($resList===[])
            <div class="row">
                <div class="col-12 alert alert-info">
                    <div class="no-create w-100 flex-total-center ">請新增農田</div>
                </div>
            </div>
        @else
            @foreach($resList as $d)
                {{--                      $d[2] = > id--}}
                @if($d['status'] === '444')
                    <conitor-exponent-null
                            :form_crop="{{ json_encode($d['farm'].'_'.$d['crop']) }}"
                    >
                    </conitor-exponent-null>
                @else
                    <Conitor-Exponent
                            :name="{{ json_encode(Auth::user()->username) }}"
                            :form_crop="{{ json_encode($d['farm'].'_'.$d['crop']) }}"
                            :config_number="{{$d['id']}}"
                            :url_api_target="{{json_encode(route('api.get.number.target'))}}"
                            :url_path="{{json_encode(route('monitor_former_config',['farm' => $d['farm'] ,'farmland' => $d['farmland']]))}}">
                    </Conitor-Exponent>
                @endif
            @endforeach
        @endif
    </div>

    {{--     農夫資訊的Modal--}}
    <former-info-config :formername=" {{ json_encode($former) }} "
                        :formeremail="{{ json_encode($formerEmail) }}"
                        :route="{{ json_encode(route('monitor_former_update'))}}"
                        :farms="{{ json_encode($farmList) }}"
                        :crops="{{ json_encode($resList) }}"
    >
    </former-info-config>
@endsection