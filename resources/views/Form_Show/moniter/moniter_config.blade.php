@extends('Form_Show.Layout.master')

@section('show')
    <div class="container shadow my-3">
        <div class="row monitor-items no-gutters">
            <monitor-items-show :monitor_value= {{ json_encode($data)}}></monitor-items-show>
        </div>
        <hr>
        <config-place></config-place>
    </div>
@endsection