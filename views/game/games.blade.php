@extends('master')

@section('title')
    ایران باگت
@endsection

@section('content')
    <div class="w-100 p-0 m-0">
        <div class="bd-example">
            <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    @php($active = 'active')
                    @foreach($ads as $index => $ad)
                        @if($ad->state == 1)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                <a href="{{ url('advertise/'.$ad->id) }}">
                                    <img src="{{ asset('upload/'.$ad->img) }}" class="d-block w-100">
                                </a>
                            </div>
                            @php($active = '')
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 w-100 py-3 bg-dark" style="
    border-top: 15px dashed whitesmoke;
    border-bottom: 15px dashed whitesmoke;
    outline: #343a40 10px solid;
    overflow: hidden;
    ">

        <div class="bg-light w-100 pt-3"
             style="box-shadow:inset 0px 0px 20px black;background: linear-gradient(to right, #f2994a, #f2c94c);">

            <div class="row" style="direction: ltr !important">
                <div class="slider" id="slider">
                    <div class="slide mx-3" id="slide" style="color:#fff;">

                        <a href="{{url('restaurants/1')}}" style="color: white">
                            <div class="ads-parent position-relative m-2"
                                 style="border-radius: 30px;width: 420px !important;">
                                <iframe src="{{asset('games/p1/index.html')}}" style="height: 100%!important;width: 100%!important;" ></iframe>

                                <!--img src="{{ asset('img/play.png') }}" class="bg-light img-fluid"
                                     style="position: absolute;top: 44%;left: 44%;z-index: 100;border-radius: 100%;">
                                <div style="position: relative;">
                                    <div style="
                                        position: absolute;
                                        top: 0;left:0;
                                        width:100%;height: 100%;
                                        background-color: black;
                                        opacity: 0.7;">
                                    </div>
                                    <img class="item img-fluid w-100" src="{{ asset('img/mario.png') }}"-->
                                </div>
                            </div>
                        </a>

                        <div class="ads-parent position-relative m-2 d-flex flex-column justify-content-center align-items-center"
                             style="border-radius: 30px;width: 800px; !important;background: #0c5460">
                            <span class="pb-3">جهت ادامه بازی کد خرید ساندویچ خودرا وارد نمایید</span>
                            <form action="">
                                <div class="form-row">
                                    <div class="col-8">
                                        <input name="buy-code" class="form-control">
                                    </div>
                                    <div class="col-4">
                                        <button type="submit" class="btn btn-default">ارسال</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="justify-content-center d-flex align-items-center w-100 bg-dark"
         style="height: 200px;background: url({{ asset('img/mario.png') }});background-repeat: no-repeat;background-blend-mode: multiply;background-size: cover;">
        <a href="#" style="color: #969896;font-size: 2rem;">خرید و دانلود نسخه کامل بازی</a>
    </div>

    <div class="w-100 p-0 m-0">
        <div class="bd-example">
            <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">

                    @php($active = 'active')
                    @foreach($ads as $index => $ad)
                        @if($ad->state == 0)
                            <div class="carousel-item {{ $active }}">
                                <a href="{{ url('advertise/'.$ad->id) }}">
                                    <img src="{{ asset('upload/'.$ad->img) }}" class="d-block w-100">
                                </a>
                            </div>
                            @php($active = '')
                        @endif
                    @endforeach

                </div>
            </div>
        </div>
    </div>
@endsection