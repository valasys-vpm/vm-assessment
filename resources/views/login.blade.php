<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login | Valasys Media - Assessment</title>
    <!-- HTML5 Shim and Respond.js IE10 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 10]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <meta name="author" content="CodedThemes" />

    <!-- Favicon icon -->
    <link rel="icon" href="{{ asset('public/template') }}/assets/images/favicon.ico" type="image/x-icon">
    <!-- fontawesome icon -->
    <link rel="stylesheet" href="{{ asset('public/template') }}/assets/fonts/fontawesome/css/fontawesome-all.min.css">
    <!-- animation css -->
    <link rel="stylesheet" href="{{ asset('public/template') }}/assets/plugins/animation/css/animate.min.css">
    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset('public/template') }}/assets/css/style.css">
    <link rel="stylesheet" href="{{ asset('public/template') }}/assets/css/layouts/dark.css">

</head>

<body>
<div class="auth-wrapper aut-bg-img" style="background-image: url('{{ asset('public/template') }}/assets/images/bg-images/bg3.jpg');">
    <div class="auth-content">
        <div class="text-white">
            <div class="card-body text-center">
                <div class="mb-4">
                    <i class="feather icon-unlock auth-icon"></i>
                </div>
                <h3 class="mb-4 text-white">Login</h3>
                <form method="post" action="">
                    @csrf
                    @if(session('error'))
                        <div class="input-group mb-3">
                            <span class="text-danger">{{session('error')}}</span>
                        </div>
                    @endif
                    <div class="input-group mb-3">
                        <input @if(Cookie::has('login_email')) value="{{ Cookie::get('login_email') }}" @endif type="email" class="form-control" placeholder="Email" name="email" required>
                    </div>
                    <div class="input-group mb-4">
                        <input @if(Cookie::has('login_password')) value="{{ Cookie::get('login_password') }}" @endif type="password" class="form-control" placeholder="password" name="password" required>
                    </div>
                    <div class="form-group text-left">
                        <div class="checkbox checkbox-fill d-inline">
                            <input type="checkbox" name="checkbox-fill-1" name="remember_me" id="checkbox-fill-a1" {{ old('remember') ? 'checked' : '' }}>
                            <label for="checkbox-fill-a1" class="cr"> Save credentials</label>
                        </div>
                    </div>
                    <button class="btn btn-primary shadow-2 mb-4" type="submit">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Required Js -->
<script src="{{ asset('public/template') }}/assets/js/vendor-all.min.js"></script>
<script src="{{ asset('public/template') }}/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="{{ asset('public/template') }}/assets/js/pcoded.min.js"></script>

</body>
</html>
