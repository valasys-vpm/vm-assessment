<header class="navbar pcoded-header navbar-expand-lg navbar-dark header-dark brand-dark">
    <div class="m-header">
        <a class="mobile-menu" id="mobile-collapse1" href="#!"><span></span></a>
        <a href="javascript:void(0);" class="b-brand">
            <img class="img-fluid horizontal-dasktop" src="{{ asset('public/template/frontend') }}/assets/img/logo.png" alt="Theme-Logo" style="width: 200px;"/>
            <span class="b-title">Assessment Portal</span>
        <!-- <img class="img-fluid horizontal-dasktop" src="{{ asset('public/template') }}/assets/images/logo-dark.png" alt="Theme-Logo" />
            <img class="img-fluid horizontal-mobile" src="{{ asset('public/template') }}/assets/images/logo.png" alt="Theme-Logo" /> -->
        </a>
    </div>
    <a class="mobile-menu" id="mobile-header" href="#!">
        <i class="feather icon-more-horizontal"></i>
    </a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            <li><a href="#!" class="full-screen" onclick="javascript:toggleFullScreen()"><i class="feather icon-maximize"></i></a></li>
        </ul>
        <ul class="navbar-nav ml-auto">

            <li>
                <div class="counter text-center">
                    <h4 id="timer" class="text-white m-0"></h4>
                </div>
            </li>

            <li><a href="javascript:void(0);">{{ Auth::user()->first_name.' '.Auth::user()->last_name }}</a></li>
        </ul>
    </div>
</header>
