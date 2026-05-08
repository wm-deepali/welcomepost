@extends('website.layout.layout')
@section('content')
<?php error_reporting(0); ?>
<!-- Preloader -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .pro-rel-posts .us-ppg-com {
        overflow-x: hidden; /* Hide the horizontal scrollbar */
    }

    .pro-rel-posts .us-ppg-com ul {
        display: flex;
        flex-wrap: wrap;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .pro-rel-posts .us-ppg-com ul li {
        flex: 1 0 100%; /* Default to full width */
        box-sizing: border-box;
        padding: 10px; /* Adjust padding as needed */
    }

    .pro-rel-posts .us-ppg-com ul li .pro-eve-box {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 15px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Mobile view: 2 cards per row */
    @media (min-width: 576px) {
        .pro-rel-posts .us-ppg-com ul li {
            flex: 0 0 50%; /* 2 cards per row */
        }
    }

    /* Desktop view: 5 cards per row */
    @media (min-width: 1200px) {
        .pro-rel-posts .us-ppg-com ul li {
            flex: 0 0 20%; /* 5 cards per row */
        }
    }
</style>

<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    /* Hide the images by default */
    .mySlides {
        display: none;
    }

    /* Add a pointer when hovering over the thumbnail images */
    .cursor {
        cursor: pointer;
    }

    .eve-deta-pg .lhs .img img {
        margin-bottom: 20px;
    }

    /* Position the "next button" to the right */
    .next {
        right: 0;
        border-radius: 3px 0 0 3px;
        cursor: pointer;
        position: absolute;
        top: 40%;
        width: auto;
        padding: 16px;
        margin-top: -50px;
        color: white;
        font-weight: bold;
        font-size: 25px;
        border-radius: 0 3px 3px 0;
        user-select: none;
        -webkit-user-select: none;
    }

    .prev {
        left: 0;
        border-radius: 3px 0 0 3px;
        cursor: pointer;
        position: absolute;
        top: 40%;
        width: auto;
        padding: 16px;
        margin-top: -50px;
        color: white;
        font-weight: bold;
        font-size: 25px;
        border-radius: 0 3px 3px 0;
        user-select: none;
        -webkit-user-select: none;
    }

    /* On hover, add a black background color with a little bit see-through */
    .prev:hover,
    .next:hover {
        background-color: #2196F3;
    }

    /* Number text (1/3 etc) */
    .numbertext {
        color: #f2f2f2;
        font-size: 12px;
        padding: 8px 12px;
        position: absolute;
        top: 0;
    }

    .column img {
        height: 100px !important;
        border-radius: 0px !important;
    }

    /* Container for image text */
    .caption-container {
        text-align: center;
        background-color: #222;
        padding: 2px 16px;
        color: white;
    }

    .row:after {
        content: "";
        display: table;
        clear: both;
    }

    /* Six columns side by side */
    .column {
        float: left;
        width: 25%;
    }

    /* Add a transparency effect for thumnbail images */
    .demo {
        opacity: 0.6;
    }

    .active,
    .demo:hover {
        opacity: 1;
    }

    .column img {
        height: 100px !important;
    }

    .framed {
        margin-bottom: 10px;
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        align-items: center;
        height: 70px;
        width: 350px;
        position: relative;
        transition: box-shadow 0.6s cubic-bezier(.79, .21, .06, .81);
        border-radius: 10px;
    }

    .social_btn {
        height: 40px;
        width: 40px;
        border-radius: 12px;
        background: #e0e5ec;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        -webkit-tap-highlight-color: transparent;
        box-shadow:
            -7px -7px 20px 0px #fff9,
            -4px -4px 5px 0px #fff9,
            7px 7px 20px 0px #0002,
            4px 4px 5px 0px #0001,
            inset 0px 0px 0px 0px #fff9,
            inset 0px 0px 0px 0px #0001,
            inset 0px 0px 0px 0px #fff9, inset 0px 0px 0px 0px #0001;
        transition: box-shadow 0.6s cubic-bezier(.79, .21, .06, .81);
        font-size: 18px;
        color: rgba(42, 52, 84, 1);
        text-decoration: none;
    }

    .social_btn:active {
        box-shadow: 4px 4px 6px 0 rgba(255, 255, 255, .5),
            -4px -4px 6px 0 rgba(116, 125, 136, .2),
            inset -4px -4px 6px 0 rgba(255, 255, 255, .5),
            inset 4px 4px 6px 0 rgba(116, 125, 136, .3);
    }

    i {
        margin-bottom: 15px;
        margin-right: 15px;
    }
</style>
<style>
    .modal-body {
        position: relative;
        text-align: center;
    }

    /* CSS for image inside modal body */
    .modal-body img {
        max-width: 100%;
        height: auto;
    }

    /* CSS for previous and next buttons */
    .prev1,
    .next1 {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: auto;
        padding: 10px;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .prev1 {
        left: 15px;
    }

    .next1 {
        right: 15px;
    }

    .prev1:hover,
    .next1:hover {
        background-color: rgba(0, 0, 0, 0.8);
    }

    @media screen and (max-width: 620px) {

        .desktop__view {
            display: none;
        }
         .right-side{
            display:none !important; 
         } 

    }

    @media screen and (min-width: 620px) {

         .right-side1{
            display:none !important; 
         } 
    }
</style>
<style>
    .ads-details-page {
        width: 100%;
        height: auto;
        background-color: #002f3408;

    }

    .ads-details-image-list {
        width: 100%;
        height: auto;
        display: grid;
        grid-template-columns: 7fr 5fr;
        /* margin-bottom: 20px; */

    }

    .carousel-item img {
        width: auto;

        height: 400px;
    }

    .ads-details-content {
        width: 98%;
        height: auto;
        display: grid;
        grid-template-columns: 3fr 1fr;
        gap: 20px;
        margin: auto;
        /* border: 1px solid gray; */
        padding-bottom: 20px;
    }

    .left-side {
        margin-top: 50px;
        width: 100%;
        height: auto;
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
        padding: 20px;
        border-radius: 5px;

    }

    .left-side h3 {
    /*display: flex;*/
    /*align-items: flex-start;*/
    /*color: #3d3f94;*/
    /*font-size: 20px;*/
    /*font-weight: 500;*/
    /*line-height: 26px;*/
    color: #24272c;
    font-size: 22px;
    font-weight: 600;
    line-height: 35px;
      
    }
    .left-side .address--data div h1 {
        /*color: #24272c;*/
        /*font-size: 22px;*/
        /*font-weight: 600;*/
        /*line-height: 35px;*/
         display: flex;
    align-items: flex-start;
    color: #3d3f94;
    font-size: 20px;
    font-weight: 500;
    line-height: 26px;
        
    }

    .budge {
        width: 100px;
        padding: 5px 10px;
        background-color: #ffce32;
        font-weight: 600;
        border-radius: 3px;
        text-align: center;
    }

    .address--data {
        width: 100%;
        display: flex;
        justify-content: space-between;
        /* padding: 20px; */
    }

    .titl-date h1 {
        width: 60% !important;
    }

    .titl-date p {
        font-size: 16px !important;
        font-weight: 600 !important;
    }

    .feature-share h4 {
        /* width: 30px;
        height: 30px; */
        padding: 5px;
        border: 1px solid gray;
        border-radius: 3px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: 600;
    }

    .right-side {
          position: -webkit-sticky;
          position: sticky;
          top: 80px;
        margin-top: 10px;
        width: 100%;
        height: auto;
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
        padding: 20px;
        border-radius: 5px;
        
       
    }

    .right-side1 {
        margin-top: 50px;
        width: 100%;
        height: auto;
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
        /* padding: 5px; */
        border-radius: 1px;
    }

    .price-section {
        width: 100%;
        height: auto;
        display: grid;
        grid-template-columns: 1fr;
    }

    .price-section h1 {
       
        font-weight: 600;
    }

    .price-section button {
        width: 100%;
        height: 50px;
        border-radius: 3px;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        border: none;
        background-color: blue;
        font-size: 18px;
        font-weight: 500;
        color: white;
    }

    .over-view {
        width: 100%;
        display: flex;
        justify-content: space-between;

    }

    .over-view p {
        padding: 0px;
        margin: 0px;
    }

    .add-section {

        width: 100%;
        height: 300px;
        overflow: hidden;
        /* padding: 5px; */
    }

    .image-card-list {
        max-width: 80%;
        height: 70px;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
        gap: 10px;
    }

    .image-card-list img {
        height:50px;
        border: 1px solid gray;
        cursor: pointer;
    }

    .budge-banner {
        position: relative;
        z-index: 10;
        width: 100px;
        height: 30px;
        padding: 5px 10px;
        background-color: #ffce32;
        font-weight: 600;
        border-radius: 3px;
        text-align: center;
        right: -18px;
        top: 23px;

    }

    .share-flag {
        position: relative;
        z-index: 10;
        width: 26px;
        padding: 5px 10px;
        /* background-color: #ffce32; */
        font-weight: 600;
        border-radius: 3px;
        text-align: center;
        left: -18px;
        top: 5px;
    }

    .share-flag h4 {

        color: white;
        padding: 5px;
        width: 30px;
        border: 3px;
        /* border: 1px solid gray; */
    }

    .profile-section {
        width: 100%;
        height: auto;
        display: grid;
        grid-template-columns: 2fr 10fr;
        margin-bottom: 20px;
        padding-bottom: 10px;
        /*gap: 20px;*/
        border-bottom: 0.5px solid gray;
    }

    .profile-section img {
        max-width: 100%;
        max-height: 60px;
        border-radius: 50%
    }

    .profile-name-section {
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center
        
    }

    .profile-name-section h3 {
            font-size: 17px;
        font-weight: 600 !important;
    }

    .profile-name-section p {
        font-size:12px !important;
        font-weight: 500 !important;
        color: gray !important;
        margin: 0;
    }

    .left-content-details {
        width: 49%;
        height: auto;
        display: flex;
        flex-direction: column;
    }

    .center-line {
        width: 1px;
        height: auto;
        background-color: gray;
    }
    .test-start{
        padding:0px !important;
        font-weight:500 !important;
        margin-top:5px !important;
         margin-bottom:5px !important;
    }
    .right-side div p{
        display:flex;
        font-size: 14px;
        margin-right:20px;
        font-weight:bold;
    }
    .add_titles{
        font-weight:600 !important;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif !important;
    }
    @media screen and (max-width: 620px) {
        .ads-details-page {
            width: 100%;
            height: auto;
            background-color: #002f3408;
    
        }

        .ads-details-image-list {
            width: 100%;
            height: auto;
            display: flex;
            flex-direction: column;
            /* margin-bottom: 20px; */
    
        }
    
        .carousel-item img {
            width: auto;
    
            height: 200px;
        }

        .ads-details-content {
            width: 98%;
            height: auto;
            display: grid;
            grid-template-columns:1fr;
            gap: 20px;
            margin: auto;
            /* border: 1px solid gray; */
            padding-bottom: 20px;
        }

        .left-side {
           
            width: 95%;
            height: auto;
            box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
            padding: 20px;
            border-radius: 5px;
            margin:auto;
             margin-top: 20px;
    
        }

        .left-side h3 {
                font-size: 16px;
            font-weight: 600;
        }
        .framed {
            margin-bottom: 10px;
            display: flex;
            flex-direction: row;
            justify-content: space-around;
            align-items: center;
            height: 70px;
            width: 275px;
            position: relative;
            transition: box-shadow 0.6s cubic-bezier(.79, .21, .06, .81);
            border-radius: 10px;
        }
        .budge {
            width: 100px;
            padding: 5px 10px;
            background-color: #ffce32;
            font-weight: 600;
            border-radius: 3px;
            text-align: center;
        }

        .address--data {
            width: 100%;
            display: flex;
            flex-direction:column;
            gap:10px;
            /*justify-content: space-between;*/
            /* padding: 20px; */
        }
        .ic-view:before {
            display:none;
        }
        .titl-date h1 {
            width: 60% !important;
        }

        .titl-date p {
            font-size: 16px !important;
            font-weight: 600 !important;
        }

        .feature-share h4 {
            /* width: 30px;
            height: 30px; */
            padding: 5px;
            border: 1px solid gray;
            border-radius: 3px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 600;
        }

        .right-side {
            margin-top: 10px;
            width: 100%;
            height: auto;
            box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
            padding: 20px;
            border-radius: 5px;
        }

        .right-side1 {
            margin-top: 50px;
            width: 95%;
            height: auto;
            box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
            /* padding: 5px; */
            border-radius: 1px;
            margin:auto;
            padding:10px;
        }

        .price-section {
            width: 100%;
            height: auto;
            display: grid;
            grid-template-columns: 1fr;
        }

        .price-section h1 {
             font-size: 22px;
            font-weight: 600;
        }

        .price-section button {
            width: 100%;
            height: 50px;
            border-radius: 3px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            border: none;
            background-color: blue;
            font-size: 18px;
            font-weight: 500;
            color: white;
        }

        .over-view {
            width: 100%;
            display: flex;
            flex-direction:column;
            /*justify-content: space-between;*/
    
        }

        .over-view p {
            padding: 0px;
            margin: 0px;
        }
    
        .add-section {
    
            width: 100%;
            height: 200px;
            overflow: hidden;
            /* padding: 5px; */
        }
    
        .image-card-list {
            max-width: 80%;
            max-height: 70px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 10px;
        }
    
        .image-card-list img {
            border: 1px solid gray;
            cursor: pointer;
        }
    
        .budge-banner {
            position: relative;
            z-index: 10;
            width: 100px;
            height: 30px;
            padding: 5px 10px;
            background-color: #ffce32;
            font-weight: 600;
            border-radius: 3px;
            text-align: center;
            right: -18px;
            top: 23px;
    
        }
    
        .share-flag {
            position: relative;
            z-index: 10;
            width: 26px;
            padding: 5px 10px;
            /* background-color: #ffce32; */
            font-weight: 600;
            border-radius: 3px;
            text-align: center;
            left: -18px;
            top: 5px;
        }
    
        .share-flag h4 {
    
            color: white;
            padding: 5px;
            width: 30px;
            border: 3px;
            /* border: 1px solid gray; */
        }

        .profile-section {
            width: 100%;
            height: auto;
            display: grid;
            grid-template-columns: 2fr 10fr;
            margin-bottom: 10px;
            padding-bottom: 10px;
            gap: 10px;
            border-bottom: 0.5px solid gray;
        }
    
        .profile-section img {
            max-width: 100%;
            max-height: 60px;
            border-radius: 50%
        }
    
        .profile-name-section {
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center
        }
    
        .profile-name-section h3 {
            font-size:14px;
            font-weight: 600 !important;
        }
    
        .profile-name-section p {
             font-size:10px !important;
            font-weight: 500 !important;
            color: gray !important;
            margin: 0;
        }
    
        .left-content-details {
            width: 100%;
            height: auto;
            display: flex;
            flex-direction: column;
        }
    
        .center-line {
            width: 1px;
            height: auto;
            background-color: gray;
        }
        .right-side .breadcrumb1 p{
            font-size:12px;
        }
    }

</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/photoswipe.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/default-skin/default-skin.css">
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>
<section class="ads-post-details-page ">
    <div class="ads-details-page">
        <div class="ads-details-image-list">
            <div id="carouselExampleControls" class="carousel slide col-12" data-ride="carousel">
                <div class="carousel-inner">
                    @if(count($adsinfoimages)>0)
                    @foreach($adsinfoimages as $index => $orderDetail)
                    <div class="mt-3 carousel-item {{$index == 0 ? 'active' : ''}}">
                        <img class="d-block w-100 thumBum" id="mainImage" style="cursor:pointer;" src="{{$orderDetail->image}}" alt="Slide {{$index + 1}}">
                    </div>
                    @endforeach
                    @else
                    <div class="mt-3 carousel-item active">
                        <img class="d-block w-100 thumBum" id="mainImage" style="cursor:pointer;" src="{{ $adsinfo->image}}" alt="First slide">
                    </div>
                    @endif
                    <div class="d-flex justify-content-between">
                        @if($customer->subscriptionhistory[0]->type == 'Premium')
                        <div class="budge-banner mb-3">Featured</div>
                        @else
                        <div></div>
                        @endif
                        <div class="share-flag">
                            <h4>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-flag" viewBox="0 0 16 16">
                                    <path d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12 12 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A20 20 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a20 20 0 0 0 1.349-.476l.019-.007.004-.002h.001M14 1.221c-.22.078-.48.167-.766.255-.81.252-1.872.523-2.734.523-.886 0-1.592-.286-2.203-.534l-.008-.003C7.662 1.21 7.139 1 6.5 1c-.669 0-1.606.229-2.415.478A21 21 0 0 0 3 1.845v6.433c.22-.078.48-.167.766-.255C4.576 7.77 5.638 7.5 6.5 7.5c.847 0 1.548.28 2.158.525l.028.01C9.32 8.29 9.86 8.5 10.5 8.5c.668 0 1.606-.229 2.415-.478A21 21 0 0 0 14 7.655V1.222z" />
                                </svg>
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="image-card-list mt-5">
                    @if(count($adsinfoimages)>0)
                    @foreach($adsinfoimages as $index => $orderDetail)
                    <img class="d-block w-100 thumbnail-image" src="{{$orderDetail->image}}" data-slide-to="{{$index}}" alt="Thumbnail {{$index + 1}}" onclick="changeMainImage('{{$orderDetail->image}}')">
                    @endforeach
                    @else
                    <img class="d-block w-100 thumbnail-image" src="{{$adsinfo->image}}" data-slide-to="{{1}}" alt="Thumbnail {{1}}" onclick="changeMainImage('{{$orderDetail->image}}')">
                    @endif
                </div>
            </div>
            <div class="col-12 ">
                <div class="right-side">
                   
                    <!--<div class="mb-3 add_titles" >-->
                    <!--    <h3 class="m-0 p-0">{{$adsinfo->ad_title}}</h3>-->
                    <!--</div>-->
                    <div class="profile-section">
                        <img src="{{$customer->image}}" alt="{{$customer->name}}">
                        <div class="profile-name-section">
                            <h3 class="m-0"><a href="{{url('profile')}}/{{$adsinfo->user_id}}">{{ ucfirst($adsinfo->fullname) }}</a></h3>
                            <p>Since {{ \Carbon\Carbon::parse($customer->created_at)->format('d M Y') }}</p>
                        </div>

                    </div>
                    <div class="price-section">
                       
                        <?php if (session()->has('id')) { $user_id = session('id'); ?>
                            <h4>@if (session('success'))
    							{{ Session::get('success') }}
    							<?php Session::forget('success');?>
    							@endif
    							@if (session()->has('error'))
                                    <p style="color:red;margin-top:5px;">{{ session()->get('error') }}</p>
                                @endif
    							@if ($errors->any())
    						@foreach ($errors->all() as $error)
    							<p>{{ $error }}</p>
    						@endforeach
    						@endif</h4>
    						@if(!$enquiryExist)
    						<form id="ads-enquiry-form" action="{{url('ads-enguiry')}}" method="post">
    						    @csrf
                                <div class="form-group">
                                    {{-- <textarea class="form-control" rows="4" name="message"
                                            placeholder="Enter your query or message"></textarea> --}}
                                            <input type="hidden" name="post_id" value="{{$adsinfo->id}}">
                                </div>
                                <button type="submit" name="enquiry_submit" class="mt-3">CONTACT SELLER</button>
                            </form>
                            @else
                            <button class="mt-3" disabled="">Enquiry already sent by you</button>
                            @endif
                        <?php } else{?>
                            <a href="{{route('login')}}" class="btn btn-primary mt-2 ">CONTACT SELLER</a>
                        <?php }?>
                        @php
                            $twitterShareUrl = 'https://twitter.com/intent/tweet';
                            $title = $adsinfo->ad_title;
                            $imageUrl = $adsinfo->image;
                            $description = $adsinfo->description;
                            $url = url('/ads-details/' . $adsinfo->id);


                            $twitterShareUrl .= '?text=' . urlencode($title . ': ' . $description);
                            $twitterShareUrl .= '&url=' . urlencode($url);
                            $twitterShareUrl .= '&media=' . urlencode($imageUrl);
                            $twitterShareUrl .= '&hashtags=welcomepost';
                            if(session()->has('id')){
                                $userLogged = App\Models\Customer::findOrFail(session('id'));
                                $whatsappText = $title . ', Description: ' . $description . ', Link:' . $url.', Register Now: https://welcomepost.in/login?showLogin=true&referralCode='.$userLogged->referral_code;
                            }else{
                                $whatsappText = $title . ', Description: ' . $description . ', Link:' . $url.', Register Now: https://welcomepost.in/login?showLogin=true';
                            }
                            
                            $whatsappShareUrl = 'https://api.whatsapp.com/send?text=' . urlencode($whatsappText);
                        @endphp
                        <div class="framed mt-3">
                            <strong>Share</strong>
                            <a class="social_btn"
                                href="{{ $whatsappShareUrl}}">
                                <i class="bi bi-whatsapp" style="color: #3b5998;"></i>
                            </a>
                            <a class="social_btn"
                                href="http://www.facebook.com/sharer.php?u={{url('/ads-details')}}/{{ $adsinfo->id }}"><i
                                    class="bi bi-facebook" style="color: blue;"></i></a>
                            <a class="social_btn"
                                href="https://t.me/share/url?url={{$url}}&text={{$title}}: {{$description}}&photo={{$imageUrl}}">
                                <i class="bi bi-telegram" style="color: #3b5998;"></i></a>
                            <a class="social_btn"
                               href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url('/ads-details/' . $adsinfo->id)) }}&title={{ urlencode($adsinfo->title) }}&summary={{ urlencode($whatsappText) }}&source=LinkedIn" target="_blank"><i
                               class="bi bi-linkedin" style="color: #3b5998;"></i></a>
                            <a class="social_btn"
                                href="http://pinterest.com/pin/create/button/?url={{url('ads-details')}}/{{ $adsinfo->id }}&description={{urlencode($whatsappText)}}&media={{urlencode($imageUrl)}}"><i
                                    class="bi bi-pinterest" style="color: #3b5998;"></i></a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<section class="ads-post-details-page ">
    <div class="ads-details-page">
        <div class="ads-details-content">
            <div class="left-side">
                @php
                    $state_name = DB::table('states')->where('id',$moreadsinfo->state)->first();
                    $city_name = DB::table('cities')->where('id',$moreadsinfo->city)->first();
                @endphp
                 <div class="d-flex breadcrumb1">
                        <p style="color:green;">Category</p>&nbsp; <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-double-right" viewBox="0 0 16 16">
                          <path fill-rule="evenodd" d="M3.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L9.293 8 3.646 2.354a.5.5 0 0 1 0-.708"/>
                          <path fill-rule="evenodd" d="M7.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L13.293 8 7.646 2.354a.5.5 0 0 1 0-.708"/>
                        </svg> </span>&nbsp;
                                                <p>{{ ucfirst($category->name) }}</p> <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-double-right" viewBox="0 0 16 16">
                          <path fill-rule="evenodd" d="M3.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L9.293 8 3.646 2.354a.5.5 0 0 1 0-.708"/>
                          <path fill-rule="evenodd" d="M7.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L13.293 8 7.646 2.354a.5.5 0 0 1 0-.708"/>
                        </svg> </span>&nbsp;
                        <p>@if(isset($subcategory)) {{ ucfirst($subcategory->name) }} @endif</p>
                    </div>
                <div class="address--data mt-4">
                    <div>
                        <h3 class="m-0 p-0">{{$adsinfo->ad_title}}</h3>
                         @if(isset($adsinfo->price))
                            @if($adsinfo->price>0)
                                <h1 class="m-0">₹ {{ $adsinfo->price }}</h1>
                            @endif
                        @else
                        <h1 class="m-0">₹ {{ $adsinfo->salary_from }} - ₹ {{ $adsinfo->salary_to }}</h1>
                        @endif
                        
                    </div>
                    @if($state_name||$city_name||$moreadsinfo->neibourhood)
                    <p class="m-0 p-0 "><span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                            <path
                                d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10" />
                            <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                        </svg></span>&nbsp; @if(isset($moreadsinfo->neibourhood)) {{ucfirst($moreadsinfo->neibourhood .',')}} @endif{{ ucfirst($city_name->name.',') }} {{ ucfirst($state_name->name) }}
                    </p>
                    @else
                    <p class="m-0 p-0 "><span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                            <path
                                d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10" />
                            <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                        </svg></span>&nbsp; {{ucfirst($locationData->cityName .', ')}} {{ ucfirst($locationData->regionName) }}
                    </p>
                    @endif
                </div>
                


                <div class="address--data mb-3">
                    <p></p>
                    <p class="m-0 p-0"><span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-calendar-check" viewBox="0 0 16 16">
                                <path
                                    d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0" />
                                <path
                                    d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z" />
                            </svg></span>&nbsp;{{ \Carbon\Carbon::parse($adsData->created_at)->format('j F Y') }}</p>
                </div>
                <div class="address--data mb-3">
                    <p></p>
                    <p><span class="ic-view">View: {{$adsinfo->ad_view_count}}</span></p>
                </div>
                 
                
                    
                 <div class="over-view border-top pt-3 border-bottom pb-3">
                    <div class="left-content-details">
                        @if(isset($moreadsinfo->project_name))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Project Name</strong></p>
                            <p>{{ $moreadsinfo->project_name}}</p>
                        </div>
                        @endif
                        
                        @if(isset($moreadsinfo->salary_period))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Salary Period</strong></p>
                            <p>{{$moreadsinfo->salary_period}}</p>
                        </div>
                        @endif
                        
                        @if(isset($moreadsinfo->position_type))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Position</strong></p>
                            <p>{{ $moreadsinfo->position_type}}</p>
                        </div>
                        @endif
                        
                        @if(isset($moreadsinfo->vehicle_type))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Vehicle Type</strong></p>
                            <p>{{ $moreadsinfo->vehicle_type}}</p>
                        </div>
                        @endif
                        
                        @if(isset($moreadsinfo->km))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>KM</strong></p>
                            <p>{{ $moreadsinfo->km}}</p>
                        </div>
                        @endif
                        
                        @if(isset($moreadsinfo->property_type))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Property Type</strong></p>
                            <p>{{ $moreadsinfo->property_type}}</p>
                        </div>
                        @endif
                        
                        @if(isset($moreadsinfo->bedroom))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Bedroom</strong></p>
                            <p>{{ $moreadsinfo->bedroom}}</p>
                        </div>
                        @endif
                        
                        @if(isset($moreadsinfo->bathroom))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Bathroom</strong></p>
                            <p>{{ $moreadsinfo->bathroom}}</p>
                        </div>
                        @endif
                        
                        @if(isset($moreadsinfo->furnishing_status))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Furnishing</strong></p>
                            <p>{{ $moreadsinfo->furnishing_status}}</p>
                        </div>
                        @endif
                        
                        @if(isset($moreadsinfo->construction_status))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Construction</strong></p>
                            <p>{{ $moreadsinfo->construction_status}}</p>
                        </div>
                        @endif
                        
                        @if(isset($moreadsinfo->listed_by))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Listed By</strong></p>
                            <p>{{ $moreadsinfo->listed_by}}</p>
                        </div>
                        @endif
                        
                        @if(isset($moreadsinfo->plot_type))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Plot Type</strong></p>
                            <p>{{ $moreadsinfo->plot_type}}</p>
                        </div>
                        @endif
                        
                        @if(isset($moreadsinfo->price_mention))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Price for Mention</strong></p>
                            <p>{{ $moreadsinfo->price_mention}}</p>
                        </div>
                        @endif
                        
                        @if(isset($moreadsinfo->builtup_area))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Builtup Area</strong></p>
                            <p>{{ $moreadsinfo->builtup_area}}</p>
                        </div>
                        @endif
                    </div>
                
                    <div class="center-line"></div>
                
                    <div class="left-content-details">
                        @if(isset($moreadsinfo->brand))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Brand</strong></p>
                            <p>{{ $moreadsinfo->brand}}</p>
                        </div>
                        @endif
                        
                        @if(isset($moreadsinfo->fuel_type))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Fuel Type</strong></p>
                            <p>{{ $moreadsinfo->fuel_type}}</p>
                        </div>
                        @endif
                        
                        @if(isset($moreadsinfo->transmission))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Transmission</strong></p>
                            <p>{{ $moreadsinfo->transmission}}</p>
                        </div>
                        @endif
                        
                        @if(isset($moreadsinfo->year))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Year</strong></p>
                            <p>{{ $moreadsinfo->year}}</p>
                        </div>
                        @endif
                        
                        @if(isset($moreadsinfo->carpet_area))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Carpet Area</strong></p>
                            <p>{{ $moreadsinfo->carpet_area}}</p>
                        </div>
                        @endif
                        
                        @if(isset($moreadsinfo->maintenance))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Maintenance</strong></p>
                            <p>{{ $moreadsinfo->maintenance}}</p>
                        </div>
                        @endif
                        
                        @if(isset($moreadsinfo->total_floor))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Total Floor</strong></p>
                            <p>{{ $moreadsinfo->total_floor}}</p>
                        </div>
                        @endif
                        
                        @if(isset($moreadsinfo->floor_no))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Floor No.</strong></p>
                            <p>{{ $moreadsinfo->floor_no}}</p>
                        </div>
                        @endif
                        
                        @if(isset($moreadsinfo->car_parking))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Car Parking</strong></p>
                            <p>{{ $moreadsinfo->car_parking}}</p>
                        </div>
                        @endif
                        
                        @if(isset($moreadsinfo->facing))
                        <div class="d-flex justify-content-between p-3">
                            <p><strong>Facing</strong></p>
                            <p>{{ $moreadsinfo->facing}}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <h4 style="font-weight:600">Description</h4>
                <p>{{$adsinfo->description}}</p>
            </div>
            <div class="right-side1">
                <div class="add-section">
                    <img src="https://www.templeduniya.com/wp-content/uploads/2022/12/Green-Plant-Blog-Collage-Instagram-Post.jpg"
                        alt="">

                </div>
            </div>
        </div>
<!--mobile view-->

                <div class="right-side1">
                   
                    <div class="mb-3 add_titles" >
                        <h3 class="m-0 p-0">{{$adsinfo->ad_title}}</h3>
                    </div>
                    <div class="profile-section">
                        <img src="{{$customer->image}}" alt="{{$customer->name}}">
                        <div class="profile-name-section">
                            <h3 class="m-0"><a href="{{url('profile')}}/{{$adsinfo->user_id}}">{{ ucfirst($adsinfo->fullname) }}</a></h3>
                            <p>Since {{ \Carbon\Carbon::parse($customer->created_at)->format('d M Y') }}</p>
                        </div>

                    </div>
                    <div class="price-section">
                       
                        <?php if (session()->has('id')) { $user_id = session('id'); ?>
                            <h4>@if (session('success'))
    							{{ Session::get('success') }}
    							<?php Session::forget('success');?>
    							@endif
    							@if (session()->has('error'))
                                    <p style="color:red;margin-top:5px;">{{ session()->get('error') }}</p>
                                @endif
    							@if ($errors->any())
    						@foreach ($errors->all() as $error)
    							<p>{{ $error }}</p>
    						@endforeach
    						@endif</h4>
    						@if(!$enquiryExist)
    						<form id="ads-enquiry-form-mobile" action="{{url('ads-enguiry')}}" method="post">
    						    @csrf
                                <div class="form-group">
                                    {{-- <textarea class="form-control" rows="4" name="message"
                                            placeholder="Enter your query or message"></textarea> --}}
                                            <input type="hidden" name="post_id" value="{{$adsinfo->id}}">
                                </div>
                                <button type="submit" name="enquiry_submit" class="mt-3">CONTACT SELLER</button>
                            </form>
                            @else
                            <button class="mt-3" disabled="">Enquiry Sent</button>
                            @endif
                        <?php } else{?>
                            <a href="{{route('login')}}" class="btn btn-primary mt-2 ">CONTACT SELLER</a>
                        <?php }?>
                        @php
                            $twitterShareUrl = 'https://twitter.com/intent/tweet';
                            $title = $adsinfo->ad_title;
                            $imageUrl = $$adsinfo->image;
                            $description = $adsinfo->description;
                            $url = url('/ads-details/' . $adsinfo->id);


                            $twitterShareUrl .= '?text=' . urlencode($title . ': ' . $description);
                            $twitterShareUrl .= '&url=' . urlencode($url);
                            $twitterShareUrl .= '&media=' . urlencode($imageUrl);
                            $twitterShareUrl .= '&hashtags=welcomepost';
                            
                            if(session()->has('id')){
                                $userLogged = App\Models\Customer::findOrFail(session('id'));
                                $whatsappText = $title . ', Description: ' . $description . ', Link:' . $url.', Register Now: https://welcomepost.in/login?showLogin=true&referralCode='.$userLogged->referral_code;
                            }else{
                                $whatsappText = $title . ', Description: ' . $description . ', Link:' . $url.', Register Now: https://welcomepost.in/login?showLogin=true';
                            }
                            
                            $whatsappShareUrl = 'https://api.whatsapp.com/send?text=' . urlencode($whatsappText);
                        @endphp



                        <div class="framed mt-3">
                            <strong>Share</strong>
                            <a class="social_btn"
                                href="{{ $whatsappShareUrl }}">
                                <i class="bi bi-whatsapp" style="color: #3b5998;"></i>
                            </a>
                            <a class="social_btn"
                                href="http://www.facebook.com/sharer.php?u={{url('/ads-details')}}/{{ $adsinfo->id }}"><i
                                    class="bi bi-facebook" style="color: blue;"></i></a>
                            <a class="social_btn"
                                href="https://t.me/share/url?url={{$url}}&text={{$whatsappText}}: {{$description}}&photo={{$imageUrl}}">
                                <i class="bi bi-telegram" style="color: #3b5998;"></i></a>
                            <a class="social_btn"
                                href="http://www.linkedin.com/shareArticle?mini=true&url={{url('/ads-details')}}/{{ $adsinfo->id }}"><i
                                    class="bi bi-linkedin" style="color: #3b5998;"></i></a>
                            <a class="social_btn"
                                href="http://pinterest.com/pin/create/button/?url={{url('/ads-details')}}/{{ $adsinfo->id }}&media={{$imageUrl}}"><i
                                    class="bi bi-pinterest" style="color: #3b5998;"></i></a>
                        </div>

                    </div>
                </div>
          
<!--mobile view-->
    </div>
    <div class="pro-rel-posts ">
        <h4 class="ml-4">Related Posts</h4>
        <div class="us-ppg-com us-ppg-blog">
            <ul class="d-flex">

                @foreach($latestpost as $key => $orderDetails)
                    <li>
                        <div class="pro-eve-box p-3">
                            <div>
                                <a href="{{url('ads-details')}}/{{ $orderDetails->id}}">
                                    <img src="{{$orderDetails->image}}" alt="" loading="lazy">
                                </a>
                            </div>
                               <h4 class="test-start m-0 p-o" >{{$orderDetails->ad_title}}</h4>
                                 @if(isset($orderDetails->price)&&$orderDetails->price!=0)
                                <h3 class="m-0 p-0" style="color:#1a068b; font-weight:600; padding-bottom:5px;font-size: 18px; line-height: 20px;">&#8377; {{$orderDetails->price}}
                                </h3>
                                @else
                                @if(isset($orderDetails->salary_from))
                                <h3 class="m-0 p-0" style="color:#1a068b; font-weight:600; padding-bottom:5px;font-size: 18px; line-height: 20px;">&#8377; {{ $adsinfo->salary_from }} - {{ $adsinfo->salary_to }}
                                </h3>
                                @endif
                                @endif
                            <div class="d-flex justify-content-between" style="border-top: 1px dashed rgba(36, 39, 44, .15); margin-top:10px;">
                                <p class="m-0 p-0 ">{{ ucfirst($orderDetails->ad_city->name ?? "")}}, {{ ucfirst($orderDetails->ad_city->state->name ?? "")}}</p>
                                <p>{{ \Carbon\Carbon::parse($adsData->created_at)->format('j F Y') }}</p>
                            </div>
                         
                            {{--<p style="padding-left:15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{$orderDetails->description}}</p>--}}
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
<div class="pswp" tabindex="9999" role="dialog" aria-hidden="true">
    <div class="pswp__bg"></div>
    <div class="pswp__scroll-wrap">
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>
        <div class="pswp__ui pswp__ui--hidden">
            <div class="pswp__top-bar">
                <div class="pswp__counter"></div>
                <button class="pswp__button pswp__button--close" aria-label="Close (Esc)"></button>
                <button class="pswp__button pswp__button--zoom" aria-label="Zoom in/out"></button>
                <div class="pswp__preloader">
                    <div class="loading-spin"></div>
                </div>
            </div>
            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div>
            </div>
            <button class="pswp__button pswp__button--arrow--left" aria-label="Previous (arrow left)"></button>
            <button class="pswp__button pswp__button--arrow--right" aria-label="Next (arrow right)"></button>
            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>
        </div>
    </div>
</div>

<!-- START -->
{{--<section class=" eve-deta-body blog-deta-body pt-5 ">
    <div class="container">
        <!-- <div class="eve-deta-body-main mobile__view">
            <div class="lhs">
                <div class="head d-flex justify-content-between titl-date mb-3">

                    <h1 class="m-0 p-0">{{$adsinfo->ad_title}}</h1>
                    <p class="m-0 p-0">{{ \Carbon\Carbon::parse($adsinfo->created_at)->format('j F') }}</p>
                   
                </div>
                <div class="Owner-info">
                    <p><b>Post By:</b> <a
                            href="{{url('profile')}}/{{$adsinfo->user_id}}">{{ ucfirst($adsinfo->fullname) }}</a></p>


                    @if(session('id') == $adsinfo->user_id)
                        <label class="switch" style="display:none;">Hide Email
                            <input type="checkbox" id="chkMobile" class="chkMobile" value="1"
                                @if($adsinfo->is_email_hide == 1) checked @endif>
                            <span class="slider round"></span>
                        </label>
                        <input type="hidden" value="{{$adsinfo->id}}" class="adsID">
                    @endif



                    @if(isset($adsinfo->price))
                        <p><b>Price:</b> ₹ {{ $adsinfo->price }}</p>
                    @else
                        <p><b>Salary:</b> {{ $adsinfo->salary_from }} - {{ $adsinfo->salary_to }}</p>
                    @endif

                    @if(isset($moreadsinfo->salary_period))
                        <p><b>Salary Period: </b> {{ $moreadsinfo->salary_period}}</p>
                    @endif

                    @if(isset($moreadsinfo->position_type))
                        <p><b>Position: </b> {{ $moreadsinfo->position_type}}</p>
                    @endif

                    @if(isset($moreadsinfo->brand))
                        <p><b>Brand: </b> {{ $moreadsinfo->brand}}</p>
                    @endif

                    @if(isset($moreadsinfo->vehicle_type))
                        <p><b>Vehicle Type: </b> {{ $moreadsinfo->vehicle_type}}</p>
                    @endif
                    @if(isset($moreadsinfo->fuel_type))
                        <p><b>Fuel Type: </b> {{ $moreadsinfo->fuel_type}}</p>
                    @endif

                    @if(isset($moreadsinfo->transmission))
                        <p><b>Transmission: </b> {{ $moreadsinfo->transmission}}</p>
                    @endif
                    @if(isset($moreadsinfo->year))
                        <p><b>Year: </b> {{ $moreadsinfo->year}}</p>
                    @endif
                    @if(isset($moreadsinfo->km))
                        <p><b>KM: </b> {{ $moreadsinfo->km}}</p>
                    @endif

                    @if(isset($moreadsinfo->property_type))
                        <p><b>Property Type: </b> {{ $moreadsinfo->property_type}}</p>
                    @endif

                    @if(isset($moreadsinfo->bedroom))
                        <p><b>Bedroom: </b> {{ $moreadsinfo->bedroom}}</p>
                    @endif

                    @if(isset($moreadsinfo->bathroom))
                        <p><b>Bathroom: </b> {{ $moreadsinfo->bathroom}}</p>
                    @endif

                    @if(isset($moreadsinfo->furnishing_status))
                        <p><b>Furnishing: </b> {{ $moreadsinfo->furnishing_status}}</p>
                    @endif

                    @if(isset($moreadsinfo->construction_status))
                        <p><b>Construction: </b> {{ $moreadsinfo->construction_status}}</p>
                    @endif

                    @if(isset($moreadsinfo->listed_by))
                        <p><b>Listed By: </b> {{ $moreadsinfo->listed_by}}</p>
                    @endif

                    @if(isset($moreadsinfo->plot_type))
                        <p><b>Plot Type: </b> {{ $moreadsinfo->plot_type}}</p>
                    @endif

                    @if(isset($moreadsinfo->price_mention))
                        <p><b>Price for Mention: </b> {{ $moreadsinfo->price_mention}}</p>
                    @endif

                    @if(isset($moreadsinfo->builtup_area))
                        <p><b>Builtup Area: </b> {{ $moreadsinfo->builtup_area}}</p>
                    @endif

                    @if(isset($moreadsinfo->carpet_area))
                        <p><b>Carpet Area: </b> {{ $moreadsinfo->carpet_area}}</p>
                    @endif

                    @if(isset($moreadsinfo->maintenance))
                        <p><b>Maintenance: </b> {{ $moreadsinfo->maintenance}}</p>
                    @endif

                    @if(isset($moreadsinfo->total_floor))
                        <p><b>Total Floor: </b> {{ $moreadsinfo->total_floor}}</p>
                    @endif

                    @if(isset($moreadsinfo->floor_no))
                        <p><b>Floor No. : </b> {{ $moreadsinfo->floor_no}}</p>
                    @endif

                    @if(isset($moreadsinfo->car_parking))
                        <p><b>Car Parking : </b> {{ $moreadsinfo->car_parking}}</p>
                    @endif

                    @if(isset($moreadsinfo->facing))
                        <p><b>Facing : </b> {{ $moreadsinfo->facing}}</p>
                    @endif

                    @if(isset($moreadsinfo->project_name))
                        <p><b>Project Name : </b> {{ $moreadsinfo->project_name}}</p>
                    @endif
                    <p><b>Category: </b> {{ ucfirst($category->name) }} > @if(isset($subcategory))
                    {{ ucfirst($subcategory->name) }} @endif</
                    p>
                    @php
                        $state_name = DB::table('states')->where('id', $moreadsinfo->state)->first();
                        $city_name = DB::table('cities')->where('id', $moreadsinfo->city)->first();
                    @endphp
                    <p><b>Location: </b> @if(isset($moreadsinfo->neibourhood)) {{ucfirst($moreadsinfo->neibourhood)}}
                    @endif ,{{ ucfirst($city_name->name) }}, {{ ucfirst($state_name->name) }}</
                    p>
                </div>
                <p><b>Description:</b> {{$adsinfo->description}}</p>
                @php
                    $twitterShareUrl = 'https://twitter.com/intent/tweet';
                    $title = $adsinfo->ad_title;
                    $imageUrl = $$adsinfo->image;
                    $description = $adsinfo->description;
                    $url = url('/ads-details/' . $adsinfo->id);


                    $twitterShareUrl .= '?text=' . urlencode($title . ': ' . $description);
                    $twitterShareUrl .= '&url=' . urlencode($url);
                    $twitterShareUrl .= '&media=' . urlencode($imageUrl);
                    $twitterShareUrl .= '&hashtags=welcomepost';
                @endphp



                <div class="framed">
                    <strong>Share</strong>
                    <a class="social_btn" href="whatsapp://send?text={{ $title }}%0A{{ $description }}%0A{{ $url }}">
                        <i class="bi bi-whatsapp" style="color: #3b5998;"></i>
                    </a>
                    <a class="social_btn"
                        href="http://www.facebook.com/sharer.php?u={{url('/ads-details')}}/{{ $adsinfo->id }}"><i
                            class="bi bi-facebook" style="color: blue;"></i></a>
                    <a class="social_btn"
                        href="https://t.me/share/url?url={{$url}}&text={{$title}}: {{$description}}&photo={{$imageUrl}}">
                        <i class="bi bi-telegram" style="color: #3b5998;"></i></a>
                    <a class="social_btn"
                        href="http://www.linkedin.com/shareArticle?mini=true&url={{url('/ads-details')}}/{{ $adsinfo->id }}"><i
                            class="bi bi-linkedin" style="color: #3b5998;"></i></a>
                    <a class="social_btn"
                        href="http://pinterest.com/pin/create/button/?url={{url('/ads-details')}}/{{ $adsinfo->id }}&media={{$imageUrl}}"><i
                            class="bi bi-pinterest" style="color: #3b5998;"></i></a>
                </div>
            </div>
            <div class="rhs">
                <div class="sec-4">
                    <h4>Other Post</h4>
                    <ul id="pg-resu">
                        @foreach($relatedpost as $key => $orderDetails)
                            <li><a href="{{url('ads-details/' . $orderDetails->id)}}">{{$orderDetails->ad_title}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div> -->


    </div>
</section>--}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/photoswipe.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/photoswipe-ui-default.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    function changeMainImage(src) {
        document.getElementById('mainImage').src = src;
    }
     (function() {
        var originalAddEventListener = EventTarget.prototype.addEventListener;
        EventTarget.prototype.addEventListener = function(type, listener, options) {
            if (type === 'wheel') {
                options = options || {};
                options.passive = true;
            }
            originalAddEventListener.call(this, type, listener, options);
        };
    })();
    
    document.addEventListener('DOMContentLoaded', function () {
        var pswpElement = document.querySelectorAll('.pswp')[0];
        var items = [];

        // Fetch image data
        document.querySelectorAll('.thumBum').forEach(function (el, index) {
            items.push({
                src: el.getAttribute('src'),
                w: el.naturalWidth,
                h: el.naturalHeight
            });

            el.addEventListener('click', function () {
                var options = {
                    index: index,
                    bgOpacity: 0.8, // Change background opacity if needed
                    showHideOpacity: true // Show/Hide animation effect
                };
                var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
                gallery.init();
            });
        });
    });

    $("#chkMobile").click(function () {
        if ($(this).is(":checked")) {
            $(".toggle_email").hide();
            var hidemail = 1;
        } else {
            $(".toggle_email").show();
            var hidemail = 0;
        }

        var id = $(".adsID").val();

        //alert(hidemail);
        $.ajax({
            url: `{{ URL::to('hide-email/${hidemail}/${id}') }}`,
            type: "get",
            dataType: "json",
            success: function (result) {
                console.log(result);
                // if (result.success) {
                //     $("#city").html(result.html);
                // }
            }
        });

    });
    document.getElementById('ads-enquiry-form-mobile').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent the default form submission

        Swal.fire({
            title: 'Are you sure?',
            text: "You want to share your contact with the seller.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, share it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the form
                event.target.submit();
            }
        });
    });
    document.getElementById('ads-enquiry-form').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent the default form submission

        Swal.fire({
            title: 'Are you sure?',
            text: "You want to share your contact with the seller.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, share it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the form
                event.target.submit();
            }
        });
    });

</script>
@endsection