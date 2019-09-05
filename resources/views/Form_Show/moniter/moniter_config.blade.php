@extends('Form_Show.Layout.master')

@section('show')
    <div class="container">
        <div class="row my-3 monitor-items">
            <div class="col-12 rounded border">
                {{--                下方小項目  --}}
                <div class="monitor-item-water   border my-3 ">
                    <div class="monitor-item-name">
                        <span>
                            水健康指數
                        </span>
                        <i class="fa fa-pencil-square-o mr-2" aria-hidden="true"></i>
                    </div>
                    <div class="row align-items-end my-3 no-gutters">
                        <div class="col-3 flex-total-center">
                            <div class="rounded-circle item-big-status" id="water-total-number">&nbsp;</div>
                        </div>
                        <div class="col-3 flex-total-center">
                            <div class="rounded-circle item-status" id="water-level">&nbsp;</div>
                        </div>
                        <div class="col-3 flex-total-center">
                            <div class="rounded-circle item-status" id="water-ph">&nbsp;</div>
                        </div>
                        <div class="col-3 flex-total-center">
                            <div class="rounded-circle item-status" id="water-soil">&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div class="monitor-item-light   border my-3">
                    <div class="monitor-item-name mb-4">
                        <span>
                            燈光健康指數
                        </span>
                        <i class="fa fa-pencil-square-o mr-2" aria-hidden="true"></i>
                    </div>
                    <div class="row align-items-end my-3 no-gutters">
                        <div class="col-3 flex-total-center">
                            <div class="rounded-circle item-big-status" id="light-total-number">&nbsp;</div>
                        </div>
                        <div class="col-3 flex-total-center">
                            <div class="rounded-circle item-status" id="light-lux ">&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div class="monitor-item-air     border my-3">
                    <div class="monitor-item-name">
                        <span>空氣健康指數</span>
                        <i class="fa fa-pencil-square-o mr-2" aria-hidden="true"></i>
                    </div>
                    <div class="row align-items-end my-3 no-gutters">
                        <div class="col-3 flex-total-center">
                            <div class="rounded-circle item-big-status" id="air-total-number">&nbsp;</div>
                        </div>
                        <div class="col-3 flex-total-center">
                            <div class="rounded-circle item-status" id="air-co">&nbsp;</div>
                        </div>
                        <div class="col-3 flex-total-center">
                            <div class="rounded-circle item-status" id="air-ch">&nbsp;</div>
                        </div>
                        <div class="col-3 flex-total-center">
                            <div class="rounded-circle item-status" id="air-tem">&nbsp;</div>
                        </div>
                        {{--                        <div class="col-3">--}}
                        {{--                            <div class="rounded-circle air-hum item-status">&nbsp;</div>--}}
                        {{--                        </div>--}}
                    </div>
                </div>
                <div class="monitor-item-weather border my-3">
                    <div class="monitor-item-name">
                        <span>氣候健康指數</span>
                        <i class="fa fa-pencil-square-o mr-2" aria-hidden="true"></i>
                    </div>
                    <div class="row align-items-end my-3 no-gutters">
                        <div class="col-3 flex-total-center">
                            <div class="rounded-circle item-big-status" id="weather-total-number">&nbsp;</div>
                        </div>
                        <div class="col-3 flex-total-center">
                            <div class="rounded-circle item-status" id="weather-wind-way">&nbsp;</div>
                        </div>
                        <div class="col-3 flex-total-center">
                            <div class="rounded-circle item-status" id="weather-wind-speed">&nbsp;</div>
                        </div>
                        <div class="col-3 flex-total-center">
                            <div class="rounded-circle item-status" id="weather-rain-acc">&nbsp;</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <config-place></config-place>
    </div>
@endsection