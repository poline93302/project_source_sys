@extends('Form_Show.Layout.master')

@section('show')
    <div class="container shadow my-3">
        <div class="row monitor-items no-gutters">
            <monitor-items-show :monitor_target=" {{ $data['target']['environment'] }}"
                                :monitor_items="{'air_hun':{{$data['weights']['air_hun']}},'air_tem':{{$data['weights']['air_tem']}}}"
                                :target_name="'environment'"
                                :url_api="{{ json_encode(route('api.get.item.number'))}}"
                                :farmland="{{$farmland}}"
                                :farm_id="{{$farmNumber}}"
                                :name="{{json_encode($name)}}"
            >
            </monitor-items-show>
            <monitor-items-show :monitor_target=" {{ $data['target']['air'] }}"
                                :monitor_items="{'air_cp':{{$data['weights']['air_cp']}},'air_ph4':{{$data['weights']['air_ph4']}}}"
                                :target_name="'air'"
                                :url_api="{{ json_encode(route('api.get.item.number'))}}"
                                :farmland="{{$farmland}}"
                                :farm_id="{{$farmNumber}}"
                                :name="{{json_encode($name)}}"
            >
            </monitor-items-show>
            <monitor-items-show :monitor_target=" {{ $data['target']['weather'] }}"
                                :monitor_items="{'weather_rainAccumulation':{{$data['weights']['weather_rainAccumulation']}},'weather_windSpeed':{{$data['weights']['weather_windSpeed']}},'weather_windWay':{{$data['weights']['weather_windWay']}}}"
                                :target_name="'weather'"
                                :url_api="{{ json_encode(route('api.get.item.number'))}}"
                                :farmland="{{$farmland}}"
                                :farm_id="{{$farmNumber}}"
                                :name="{{json_encode($name)}}"
            >
            </monitor-items-show>
            <monitor-items-show :monitor_target=" {{ $data['target']['water'] }}"
                                :monitor_items="{'water_level':{{$data['weights']['water_level']}},'water_ph':{{$data['weights']['water_ph']}},'water_soil':{{$data['weights']['water_soil']}}}"
                                :target_name="'water'"
                                :url_api="{{ json_encode(route('api.get.item.number'))}}"
                                :farmland="{{$farmland}}"
                                :farm_id="{{$farmNumber}}"
                                :name="{{json_encode($name)}}"
            >
            </monitor-items-show>
            <monitor-items-show :monitor_target=" {{ $data['target']['light'] }}"
                                :monitor_items=" {'light_lux':{{$data['weights']['light_lux']}} }"
                                :target_name="'light'"
                                :url_api="{{ json_encode(route('api.get.item.number'))}}"
                                :farmland="{{$farmland}}"
                                :farm_id="{{$farmNumber}}"
                                :name="{{json_encode($name)}}"
            >
            </monitor-items-show>
        </div>
        <hr>
        <config-place :config_infos="{{ json_encode($formerConfig) }}"
                      :back_url="{{ json_encode(route('monitor_homepage')) }}"
                      :name_critical="{{ json_encode($sensor) }}"
                      :farm_id="{{$farmNumber}}"
                      :farmland="{{$farmland}}"
                      :api_url="[ {{ json_encode(route('api.post.config.create')) }}, {{ json_encode(route('api.post.config.delete')) }} , {{ json_encode(route('api.post.config.update')) }} , {{ json_encode(route('api.post.config.switch')) }} ]"
                      :former_name="{{json_encode($name)}}"></config-place>
    </div>
@endsection