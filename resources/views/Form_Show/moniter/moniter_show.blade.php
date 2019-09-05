@extends('Form_Show.Layout.master')

@section('show')

    @include('Form_Show.Layout.header')

    <div class="show-monitor">
        {{--        顯示部分--}}
        <div class="container sensor-part border border-light rounded">
            <div class="row my-3 no-gutters text-center">
                @if(is_null($data))
                    <div class="no-create w-100">請新增農場與農田</div>
                @else
                    {{--                    use forech--}}
                    <Conitor-Exponent :value_group={{ json_encode($data) }}></Conitor-Exponent>
                @endif
            </div>
        </div>
    </div>
@endsection