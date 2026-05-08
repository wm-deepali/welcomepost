<!doctype html>
<html lang="en">
<head>
   <!-- <title>@yield('title')</title> -->
   <title>Welcome Post - An Online Ad App</title>
    <!--== META TAGS ==-->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="theme-color" content="#76cef1">
    <meta name="google-site-verification" content="Z391u-tzr8_8KVRHbHFiVjTUuFcutOqc5Hpm_kvNKX0" />
    
    @if(isset($adsinfo))
    <meta property="og:title" content="{{$adsinfo->ad_title}}">
    <meta property="og:description" content="{{$adsinfo->description}}">
    <meta property="og:url" content="{{url('/ads-details')}}/{{ $adsinfo->id }}">
    <meta property="og:image" content="{{$adsinfo->image}}">
    @endif
   
    <link rel="shortcut icon" href="{{url('assets/website/images/home/favicon.jpeg')}}" type="image/x-icon">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="{{url('assets/website/css/bootstrap.css')}}">
    <!--== CSS FILES ==-->
    <link rel="stylesheet" href="{{url('assets/website/css/jquery-ui.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/website/css/style.css')}}">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
     <link
     rel="stylesheet"
     href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"
   />
   
   <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
 
    
<style>
    .news-top-menu
    {
        margin-top: var(--topspac1);
        position: static !important;
    }
    @media(max-width:768px)
    {
    .news-top-menu
    {
        margin-top: 0px;
        position: static !important;
    }
    }
    .dropdown-toggle::after
    {
        margin-top:8px;
    }
</style>
</head>
<body>
@include('website.partials.header')



@yield('content')


    <!-- Start Footer Area -->
@include('website.partials.footer')



@yield('script')

</body>
</html>
