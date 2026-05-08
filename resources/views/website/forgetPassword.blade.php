<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <!--required meta tags-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--meta-->
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content="">
     <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!--favicon icon-->
    <link rel="icon" href="{{ asset('public/frontend/assets/img/favicon.png')}}" type="image/png" sizes="16x16">

    <!--title-->
    <title>Shree Lalji Sweets || Login</title>

    <!--build:css-->
    <link rel="stylesheet" href="{{ asset('public/frontend/assets/css/main.css')}}">
    <!-- endbuild -->
</head>

<body>
    <!--main content wrapper start-->
    <div class="main-wrapper">

        <!--login section start-->
        <section class="login-section py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-12 tt-login-img" data-background="{{ asset('public/frontend/assets/img/banner/login-banner.jpg')}}"></div>

                    <div class="col-lg-5 col-12 bg-white d-flex p-0 tt-login-col shadow">

                        <form class="tt-login-form-wrap p-3 p-md-6 p-lg-6 py-7 w-100 login-form" method="POST" action="{{ route('forget.password.post') }}">
                            @csrf
                            <div class="mb-7">
                                <a href="{{ route('/')}}">
                                    <img src="{{ asset('public/frontend/assets/img/logo1.png')}}" alt="logo">
                                </a>
                            </div>
                            <h2 class="mb-4 h3">Hey there! <br>Welcome back <span class="text-secondary">Shree Lalji Sweets.</span>
                            </h2>
                            @if (Session::has('message'))
                            <div class="alert alert-success" role="alert">
                                {{ Session::get('message') }}
                            </div>
                            @endif
                            <div class="row g-3">
                                <div class="col-sm-12">
                                    <div class="input-field">
                                        <label class="fw-bold text-dark fs-sm mb-1">Email</label>
                                        <input id="email" type="email" class="theme-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email address" autofocus>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="row g-4 mt-4">
                                <div class="col-sm-12">
                                    <button type="submit" id="login-btn" class="btn btn-primary w-100 signinbtn">Send Password Reset Link</button>
                                </div>
                            </div>
                            <p class="mb-0 fs-xs mt-4">Don't have an Account? <a href="{{route('registrationForm')}}">Sign Up</a>
                            </p>
                            <a href="{{ route('signInForm')}}">
                                <p class="mb-0 fs-xs mt-4">Sign In</p>
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!--login section end-->

    </div>
    <!--main content wrapper end-->


    <!--scroll bottom to top button start-->
    <button class="scroll-top-btn">
        <i class="fa-regular fa-hand-pointer"></i>
    </button>
    <!--scroll bottom to top button end-->
    <!--build:js-->
    <script src="{{ asset('public/frontend/assets/js/vendors/jquery-3.6.0.min.js')}}"></script>
    <script src="{{ asset('public/frontend/assets/js/vendors/jquery-ui.min.js')}}"></script>
    <script src="{{ asset('public/frontend/assets/js/vendors/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('public/frontend/assets/js/app.js')}}"></script>
    <!--endbuild-->
    
</body>
</html>