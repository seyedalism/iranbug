{{--<!doctype html>--}}
{{--<html lang="en">--}}
{{--<head>--}}
{{--    <meta charset="UTF-8">--}}
{{--    <meta name="viewport"--}}
{{--          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">--}}
{{--    <meta http-equiv="X-UA-Compatible" content="ie=edge">--}}
{{--    <title>Document</title>--}}
{{--</head>--}}
{{--<body>--}}


{{--<!-- Start Live Chat Code -->--}}
{{--<script type="text/javascript" src="http://go.iranbaguette.com/assets/js/jquery.min.js"></script>--}}
{{--<script type="text/javascript">jQuery.noConflict();</script>--}}
{{--<script type="text/javascript">jQuery(document).ready(function($) {$.ajaxSetup({xhrFields: {withCredentials: true},headers: {"X-Requested-With": "XMLHttpRequest"}});$.ajax({type: "GET",url: "http://go.iranbaguette.com/live_chat",dataType: "html",success: function(data) {$("body").append(data);}});});</script>--}}
{{--<!-- End Live Chat Code -->--}}
{{--</body>--}}
{{--</html>--}}
@extends('master')

@section('content')

    <div id="demo" class="carousel slide" data-ride="carousel">
        <ul class="carousel-indicators">
            @for($i=0;$i<3;$i++)
                <li  data-slide-to="{{$i}}" @if($i==0)  class="active" @endif ></li>
            @endfor

        </ul>
        <div class="carousel-inner">

            @for($i=0;$i<3;$i++)
                <div class="carousel-item @if($i==0) active @endif ">
                    <a href="{{asset('www.digikala.com')}}">
                        <img src="{{asset('img/loginBack.jpg')}}" alt="Los Angeles" width="1100" height="500"
                         style="width: 100%;height: 100%;">
                    </a>
                    <div class="carousel-caption">
                        <h3>{{$i}}</h3>
                        <p>فروشگاه دیجی کالا فروشگاه خوبیه</p>
                    </div>
                </div>
            @endfor


        </div>
        <p class="carousel-control-prev" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </p>
        <p class="carousel-control-next" data-slide="next">
            <span class="carousel-control-next-icon"></span>
        </p>
    </div>
@endsection
