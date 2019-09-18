@extends('Form_Show.Layout.master')

@section('show')

    @include('Form_Show.Layout.header')

    <div class="show-monitor">
        {{--        顯示部分--}}
        @if(is_null($data))
            <div class="no-create w-100 flex-total-center">請新增農場與農田</div>
        @else
            @foreach($data as $key=>$d)
                <Conitor-Exponent :value_group={{ json_encode($d) }} :config_number={{$key}} ></Conitor-Exponent>
            @endforeach
        @endif
    </div>
@endsection