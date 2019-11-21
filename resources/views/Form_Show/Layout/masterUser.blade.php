<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>智慧農業感測網路-I.G.A</title>
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
<link rel="stylesheet" href="{{asset('/css/userLink.css')}}">
<link rel="stylesheet" href="{{asset('/css/bundle/index.bundle.css')}}">
<script src="https://use.fontawesome.com/ee22e681c4.js"></script>
<body class="h-100">
<div id="app" class="h-100">
    <div class="container-fluid h-100 div-gradient">
        <div class="h-100">

                @yield('show')
            </div>
        </div>
    </div>
</div>
</body>
<script src="{{ asset('js/bundle/index.bundle.js') }}"></script>
<script src="{{ asset('js/bundle/checkinfo.bundle.js') }}"></script>
</html>


