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
    @yield('show')
    <footer class="text-center mt-5">
        <pre>© 2018 Maselab318.  All Rights Reserved.   Designed By  YingLu_Chen.</pre>
    </footer>
</div>
</body>
<script src="{{asset('js/bundle/index.bundle.js')}}"></script>
</html>


