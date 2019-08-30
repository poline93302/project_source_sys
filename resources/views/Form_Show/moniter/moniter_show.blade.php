@extends('Form_Show.Layout.master')

@section('show')
    <div class="show-monitor">
        {{--        顯示部分--}}
        <div class="container sensor-part">
            <div class="row my-3 no-gutters text-center">
                <div class="col-12 col-lg-3 my-5 h-100 d-flex justify-content-lg-center  align-items-lg-center">
                    <div class="monitor-items">
                        <div class="hexagon" id="monitor-water">
                            <div class="paint-exponent">
                                水健康指數
                            </div>
                        </div>
                        <div class="monitor-item-close" id="water-healthy">
                            <div class="monitor-status" id="">水位</div>
                            <div class="monitor-status" id="">水PH</div>
                            <div class="monitor-status" id="">土壤濕度</div>
                            <i class="fa fa-times fa-style" aria-hidden="true" id="water-close"></i>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3 my-5 h-100 d-lg-flex justify-content-lg-center  align-items-lg-center">
                    <div class="monitor-item">
                        <div class="hexagon" id="monitor-light">
                            <div class="paint-exponent">
                                光照健康指數
                            </div>
                        </div>
                        <div class="monitor-item-close" id="light-healthy">
                            <div class="monitor-status" id="">燈泡亮度</div>
                            <div class="monitor-status" id="">光照時間</div>
                            <i class="fa fa-times fa-style" aria-hidden="true" id="light-close"></i>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3 my-5 h-100 d-lg-flex justify-content-lg-center  align-items-lg-center">
                    <div class="monitor-items">
                        <div class="hexagon" id="monitor-air">
                            <div class="paint-exponent">
                                空氣健康指數
                            </div>
                        </div>
                        <div class="monitor-item-close" id="air-healthy">
                            <div class="monitor-status" id="">一氧化碳</div>
                            <div class="monitor-status" id="">甲烷</div>
                            <div class="monitor-status" id="">溫度</div>
                            <div class="monitor-status" id="">相對濕度</div>
                            <i class="fa fa-times fa-style" aria-hidden="true" id="air-close"></i>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3 my-5 h-100 d-lg-flex justify-content-lg-center  align-items-lg-center">
                    <div class="monitor-items">
                        <div class="hexagon" id="monitor-weather">
                            <div class="paint-exponent">
                                氣候健康指數
                            </div>
                        </div>
                        <div class="monitor-item-close" id="weather-healthy">
                            <div class="monitor-status" id="">風向</div>
                            <div class="monitor-status" id="">瞬時風速</div>
                            <div class="monitor-status" id="">累積雨量</div>
                            <div class="monitor-status" id="">降雨機率</div>
                            <i class="fa fa-times fa-style" aria-hidden="true" id="weather-close"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--        監控設定--}}
        {{--        <div class="container config-part">--}}
        {{--            <div class="row">--}}
        {{--                --}}
        {{--            </div>--}}
        {{--        </div>--}}
    </div>
@endsection