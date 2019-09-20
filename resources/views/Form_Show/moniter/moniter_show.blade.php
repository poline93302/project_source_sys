@extends('Form_Show.Layout.master')

@section('show')
    <header class="my-3">
        <div class="container my-3">
            <div class="row text-center bg-white shadow rounded-top">
                <div class="col-7 col-lg-8 my-3">
                    <img src="http://placeimg.com/1500/550/any/1" class="img-fluid" alt="">
                </div>
                <div class="col-5 col-lg-4 my-3 ">
                    <div class="row mt-3">
                        <div class="col-12 mb-3">
                            <span class="text-dark">歡迎 {{ $former }} 抵達本站</span>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="former"></label>
                                <select class="form-control-sm w-75  text-dark" id="selectForm"
                                        onchange="linkSelectChange({{ json_encode($formList) }})">
                                    <option>請選擇農場</option>
                                    {{ $listStatus = "" }}
                                    @foreach( ($formList) as $List)
                                        $pos計算_的所在長度 ; $formTitle文字切割
                                        {{ $pos = strpos($List, '_') , $formTitle = substr($List,0,$pos)}}
                                        @if($listStatus !== $formTitle)
                                            <option>{{ ($formTitle) }}</option>
                                        @endif
                                        {{ $listStatus = $formTitle }}
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="former-crop"></label>
                                <select class="form-control-sm w-75 text-dark" id="selectCrop">
                                    <option>請選擇農田</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 row no-gutters">
                            <div class="col-7">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#FormerInfoModal">
                                    農夫資訊
                                </button>
                            </div>
                            <div class="col-5">
                                <button type="button" class="btn btn-primary">
                                    登出
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="show-monitor">
        {{--                顯示部分--}}
        @if(is_null($formList))
            <div class="no-create w-100 flex-total-center">請新增農場與農田</div>
        @else
            @foreach($formList as $key=>$d)
                <Conitor-Exponent :form_crop={{ json_encode($d) }} :config_number={{$key}} ></Conitor-Exponent>
            @endforeach
        @endif
    </div>

    {{-- 農夫資訊的Modal--}}
    <former-info-config :formername=" {{ json_encode($former) }} " :formcrop="{{ json_encode($formList) }}"
                        :formaddress="{{json_encode($formerAddress)}}"></former-info-config>
@endsection