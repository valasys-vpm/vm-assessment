@php
    $module = \App\Models\Module::whereRoleId(Auth::user()->role_id)->first();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title')Valasys Media - Assessment</title>
    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 11]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Valasys Media is a top lead generation company in Dubai & USA providing 360° custom-made & personalized, B2B lead generation services." />
    <meta name="keywords" content="valasys, marketing, lead, generation, b2b">
    <meta name="author" content="Valasys Media" />

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="base-path" content="{{ url('/') }}" />

    <!-- Favicon icon -->
    <link rel="icon" href="{{ asset('public/template') }}/assets/images/favicon.ico" type="image/x-icon">
    <!-- fontawesome icon -->
    <link rel="stylesheet" href="{{ asset('public/template') }}/assets/fonts/fontawesome/css/fontawesome-all.min.css">
    <!-- animation css -->
    <link rel="stylesheet" href="{{ asset('public/template') }}/assets/plugins/animation/css/animate.min.css">
    <!-- pnotify css -->
    <link rel="stylesheet" href="{{ asset('public/template') }}/assets/plugins/pnotify/css/pnotify.custom.min.css">
    <!-- pnotify-custom css -->
    <link rel="stylesheet" href="{{ asset('public/template') }}/assets/css/pages/pnotify.css">

    <!-- vendor css -->
    @yield('stylesheet')

    <link rel="stylesheet" href="{{ asset('public/template') }}/assets/css/style.css">


    @yield('style')
</head>

<body>
<!-- [ Pre-loader ] start -->
<div class="loader-bg">
    <div class="loader-track">
        <div class="loader-fill"></div>
    </div>
</div>
<!-- [ Pre-loader ] End -->

<!-- [ navigation menu ] start -->
<nav class="pcoded-navbar" @if(Request::route()->getName() == 'user.assessment.live') style="display: none;" @endif>
    <div class="navbar-wrapper">
        <div class="navbar-brand header-logo">

            <a href="{{route($module->route_name)}}" class="b-brand">
                <span class="b-title">VM - Assessment</span>
            </a>
            <a class="mobile-menu" id="mobile-collapse" href="javascript:void(0);"><span></span></a>
        </div>
        <div class="navbar-content scroll-div">
            @include('layouts.sidebars.'.$module->slug)
        </div>
    </div>
</nav>
<!-- [ navigation menu ] end -->

@if(Request::route()->getName() != 'user.assessment.live')
<!-- [ Header ] start -->
@include('layouts.header', ['module' => $module])
<!-- [ Header ] end -->
@endif
<!-- [ chat user list ] start -->
<!-- [ chat user list ] end -->

<!-- [ chat message ] start -->

<!-- [ chat message ] end -->

<!-- [ Main Content ] start -->
@yield('content')
<!-- [ Main Content ] end -->

<div id="div-modal"></div>

<script>
    var BASE_PATH = "{{ url('/') }}";
</script>
<!-- Required Js -->
<script src="{{ asset('public/template') }}/assets/js/vendor-all.js"></script>
<script src="{{ asset('public/template') }}/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="{{ asset('public/template') }}/assets/js/pcoded.min.js"></script>

<!-- Float Chart js -->
<script src="{{ asset('public/template') }}/assets/plugins/flot/js/jquery.flot.js"></script>
<script src="{{ asset('public/template') }}/assets/plugins/flot/js/jquery.flot.categories.js"></script>
<script src="{{ asset('public/template') }}/assets/plugins/flot/js/curvedLines.js"></script>
<script src="{{ asset('public/template') }}/assets/plugins/flot/js/jquery.flot.tooltip.min.js"></script>

<!-- pnotify Js -->
<script src="{{ asset('public/template') }}/assets/plugins/pnotify/js/pnotify.custom.min.js"></script>

<!-- Moment Js -->
<script src="https://momentjs.com/downloads/moment-with-locales.min.js"></script>

<script>
    {{-- AJAX ERROR HANDLER CODE=> error: function(jqXHR, textStatus, errorThrown) { checkSession(jqXHR); } --}}
    function checkSession(e){401==e.status&&location.reload()}
    $(".alert-auto-dismiss").fadeTo(5000,500).slideUp(500,function(){$(".alert-auto-dismiss").slideUp(500)});
</script>

<script>
    (function ($) {
        $.fn.doubleClickToGo = function () {
            var secondForDoubleClick = .5; //Add more seconds to increase the interval when two click are considered double click
            var firstClickTime = null;
            var secondClickTime = null;
            this.filter("a").each(function () {
                $(this).click(function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var currentTime = new Date().getTime() / 1000;
                    if ((currentTime - firstClickTime > secondForDoubleClick)){
                        firstClickTime = null;
                    }
                    if (firstClickTime == null) {
                        firstClickTime = currentTime
                        secondClickTime = null;
                    } else {
                        secondClickTime = currentTime
                        console.log((secondClickTime - firstClickTime))
                        if ((secondClickTime - firstClickTime) <= secondForDoubleClick) {
                            firstClickTime = null;
                            secondClickTime = null;
                            var link = $(this);
                            var url = link.attr("href");
                            window.location.href = url;
                        }
                        firstClickTime = null;
                        secondClickTime = null;
                    }
                })
            });
            return this;
        };
    }(jQuery));
</script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(function () {
        $('.double-click').doubleClickToGo();
    });
</script>
<!-- jquery-validation Js -->
<script src="{{ asset('public/template/assets/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{asset('public/js/custom.js?='.time()) }}"></script>

@yield('javascript')



</body>
</html>
