@extends('website.layout.layout')
@section('title', $page)
@section('content')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">

<style>
    .welcome-dialog {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
    .loc-date{
        display:flex;
        justify-content:space-between;
    }
    .background-img {
        position: absolute;
        bottom: 10px;
        right: 10px;
        z-index: 0;
        opacity: .9; /* Adjust opacity if needed */
        width: 200px; /* Adjust width if needed */
        height: auto;
    }
    
    .hom2-hom-ban2 p, h2{
        z-index: 10;
        text-shadow: 1px 1px 1px black;
    }

    .content {
        text-align: center;
    }

    .content h2 {
        font-size: 24px;
        margin-bottom: 10px;
    }

    .content p {
        font-size: 18px;
        color: black;
    }

    .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        /* Adjust the opacity to control the blur intensity */
        backdrop-filter: blur(6px);
        /* Adjust the blur radius as needed */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .popup-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        text-align: center;
    }

    #ticketButton #logoutButton {
        display: inline-block;
        margin: 10px;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    #ticketButton {
        background-color: #007bff;
        color: #fff;
    }

    #ticketButton:hover {
        background-color: #0056b3;
    }

    #logoutButton {
        background-color: #dc3545;
        color: #fff;
    }

    #logoutButton:hover {
        background-color: #c82333;
    }

    .image-container {
        margin-bottom: 20px;
    }

    .image-container img {
        max-width: 100%;
        max-height: 200px;
        /* Adjust the height as needed */
        display: block;
        margin: 0 auto;
    }

    .popup-open {
        overflow: hidden;
    }
    
    .background-img2{
        display:none;
    }

    @media screen and (max-width: 620px) {

        .slider__desktop {
            display: none;
        }

        .category-list {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 7px;
        }

        .category-data-list {
            width: 100%;
            height: 100px;
            /* background-color: red; */
            border-radius: 4px;
            box-shadow: rgba(0, 0, 0, 0.05) 0px 0px 0px 1px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3px;
            gap: 5px;
        }

        .category-data-list img {
            width: 40px;
            height: 40px;
        }

        .category-data-list h4 {
            font-size: 12px;
            font-weight: 600 !important;
        }

        .category-data-list p {
            font-size: 11px;
        }

        .category-list1 {
            display: none;
        }

        .our-recommendation-section {
            width: 100%;
            height: auto;
            display: flex;
            flex-direction: row;
            gap: 15px;
            overflow: scroll
        }

        .post-data {
            width: 100%;
            height: auto;
            display: flex;
            flex-direction: column;
            padding: 5px;
            border-radius: 5px;
            box-shadow: rgba(0, 0, 0, 0.05) 0px 0px 0px 1px;
            /*margin-bottom: 10px;*/
            /*margin-left: 2px;*/
            /*margin-top: 2px;*/
            background-color: #fff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            gap: 5px;
        }

        .post-data img {
            width: 100%;
            height: 130px;
            object-fit: cover; /* Ensures the image covers the area while maintaining aspect ratio */
            object-position: center; /* Centers the image */
        }
         .loc-date{
        display:flex;
        flex-direction:column;
        gap:10px

    }

        .post-data h4 {
            font-size: 14px;
            font-weight: 600 !important;
        }

        .post-data p {
          font-size: 11px;
        font-weight: 250;
        line-height: 23px;
        color: gray;
        }
        .auth-name{
            width: 100%;
            display: flex;
            /* gap: 20px; */
        }
        .auth-name div{
            display: flex;
            flex-direction: column;
        }
        .post-data .price-section h4{
            font-size: 16px;
        }
        .our-recommendation-section::-webkit-scrollbar{
            display: none;
        }
    }
     .our-recommendation-section {
            width: 100%;
            height: auto;
            display: grid;
            gap: 15px; /* Space between grid items */
            grid-template-columns: repeat(4, 1fr); /* 4 columns for desktop view */
        }
        
        @media (max-width: 1024px) { /* Adjust the max-width as needed for tablet view */
            .our-recommendation-section {
                grid-template-columns: repeat(3, 1fr); /* 3 columns for tablet view */
            }
            .background-img2 {
                display:block;
                top:20px;
                z-index: 0;
                opacity: .9;
                position: absolute;
                right: 20px;
                width: 80px; /* Adjust width if needed */
                height: auto;
            }
            .background-img{
                display:none;
            }
        }

        /* Grid layout for mobile view */
        @media (max-width: 768px) {
            .our-recommendation-section {
                grid-template-columns:  1fr 1fr; /* 2 columns for mobile view */
                gap:10px
            }
            
        }

    @media screen and (min-width: 620px) {

        .slider__data {
            display: none;
        }

        .category-list {
            display: none;
        }

        .category-list1 {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
            gap: 15px;
        }

        .category-data-list {
            width: 100%;
            height: 140px;
            /* background-color: red; */
            border-radius: 4px;
            box-shadow: rgba(0, 0, 0, 0.05) 0px 0px 0px 1px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3px;
            gap: 5px;
        }

        .category-data-list img {
            width: 40px;
            height: 40px;
        }

        .category-data-list h4 {
            font-size: 17px;
            font-weight: 500;
        }

        .category-data-list p {
            font-size: 13px;
        }

        /* .our-recommendation-section {
            display: none;
        } */
        /* desktop */
        
        .our-recommendation-section::-webkit-scrollbar{
            display: none;
        }
        .post-data {
            /*max-width: 260px;*/
            height: 300px;
            display: flex;
            flex-direction: column;
            padding: 10px;
            border-radius: 15px;
            box-shadow: rgba(0, 0, 0, 0.05) 0px 0px 0px 1px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 10px;
            margin-left: 2px;
            margin-top: 2px;
            background-color: #fff;
            gap: 5px;
        }
.caro-home .inn{
    width:100%;
}
.hom2-hom-ban2 {
    width:100%;
}
        .post-data img {
            width: 100%;
            height: 170px;
            object-fit: cover; /* Ensures the image covers the area while maintaining aspect ratio */
            object-position: center; /* Centers the image */
        }

        .post-data h4 {
            font-weight: 500 !important;
            font-size: 13px important;
            line-height: 20px important;
            margin: 0 0 4px important;
            color: #77797a important;
            white-space: nowrap important;
            text-overflow: ellipsis important;
            overflow: hidden important;
        }
        .post-data h4 a{
            text-decoration:none  !important;
             color: #3d3f94 !important;
             font-size:16px;
             
        }
        

        .post-data p {
            white-space: nowrap;
            text-overflow: ellipsis;
            color: #24272c;
            font-size: 11px;
            font-style: normal;
            font-weight: 400;
            line-height: 25px;
        }
        .auth-name{
            width: 100%;
            display: flex;
            /* gap: 20px; */
        }
        .auth-name div{
            display: flex;
            flex-direction: column;
        }
        .post-data .price-section h4{
            font-weight: 600 !important;
            font-size: 18px;
            line-height: 20px;
            /*padding-top: 8px !important;*/
            color: #24272c;
        }
        .home-tit h2 span {
            font-weight: 700;
            font-size: 16px !important;
            color: #4a5e95;
            position: relative;
            z-index: 2;
        }
    }
    .thumbnailImage{
        border-radius: 13px;
    }
    .post-data:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        .how-wrks-inn {
    padding: 20px;
}
.slider .card {
    height:200px;
    display:flex;
    justify-content:center;
    text-align: center;
    padding: 10px;
    /*background: #f9f9f9;*/
    border-radius: 5px;
     margin: 0 10px;
}
.slider .card img {
    width: 100px;
    margin-bottom: 10px;
    margin:auto;
}
.slider .card h4 {
    font-size: 1.2rem;
    margin: 10px 0 5px;
}
.slider .card p {
    font-size: 0.9rem;
    color: #666;
}

</style>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!-- START -->
<section>
    @if(session()->has('user-blocked') || $block_count >= 5)
        <div class="popup-overlay">
            <div class="popup-content">
                <div class="image-container">
                    <img src="{{ asset('assets/website/images/blocked-user.jpg') }}" alt="Blocked User Image">
                </div>
                <p>Your account is suspended due to policy violation and reports by the users.</p>
                <p>To unblock your account, please submit your request ticket or contact through email, which will be
                    further evaluated by our team.</p>
                <p>Thank you</p>
                <a href="{{ route('raise-ticket') }}" class="btn btn-info" id="ticketButton">Ticket</a>
                <a href="{{ route('user-logout') }}" class="btn btn-danger" id="logoutButton">Logout</a>
            </div>
        </div>
        <script>
            document.body.classList.add('popup-open');
        </script>
    @endif
    <div class="all-jobs-ban">
        <div class="container">
            @if($adminSetting->welcome_amount!=0)
            <div id="welcomeDialog" class="welcome-dialog">
                <span class="close" style="cursor: pointer;" onclick="closeDialog()">&times;</span>
                <div class="content">
                    <h2>Congratulations!</h2>
                    <p>You received a welcome bonus of ₹<span id="welcomeAmount"></span></p>
                    <div class="text-center">
                        <a href="{{route('purchase-subscription')}}" class="btn btn-info">Buy Subscription</a>
                    </div>
                </div>
            </div>
            @endif

            <div class="row">
                <div class="jtit">
                    <h1>Connecting Businesses</h1>
                    <p>Connect with The Best Service to Grow Your Business.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END -->
    <!-- START -->
<section class="event-body mt-5">



<div class="str count">
<div class="container">
            <div class="row">

                <div class="how-wrks">
                   <!-- <div class="home-tit">
                        <h2><span>How It Works</span></h2>
                        <p>Explore some of the best tips from around the world from our<br>partners and
                            friends.</p>
                   </div> -->
                <div class="how-wrks-inn">
    <div class="slider">
        @foreach($infocards as $card)
        <div class="card">
            <a href="{{$card->url}}">
                <img src="{{ asset('public/'.$card->icon)}}" alt="{{$card->title}}" loading="lazy">
                <h4>{{$card->title}}</h4>
                <p>{{$card->description}}</p>
            </a>
        </div>
        @endforeach
    </div>
</div>

                    <!-- <div class="how-wrks-inn">-->
                    <!--   <ul>-->
                    <!--      @foreach($infocards as $key=>$card)-->
                    <!--       <li>-->
                    <!--          <div>-->
                    <!--                {{--<span>{{$key}}</span>--}}-->
                    <!--               <a href="{{$card->url}}">-->
                    <!--                  <img src="{{ asset('public/'.$card->icon)}}" alt="{{$card->title}}" loading="lazy">-->
                    <!--                   <h4>{{$card->title}}</h4>-->
                    <!--                   <p>{{$card->description}}</p></p>-->
                    <!--               </a>-->
                    <!--           </div>-->
                    <!--       </li>-->
                    <!--        @endforeach-->
                    <!--    </ul>-->
                    <!--</div>-->
                </div>

            </div>
        </div>
    </div>
</section>
<!--END -->
    
    
    
    <div class="container">
        <div class="row">
            <div class="home-tit">
                <h2><span>Our Recommendations</span> </h2>
            </div>
            <div class="our-recommendation-section">
                @php
                   $getAds = null; // Initialize $getAds

                    //if (isset($cityAd->name)) {
                        //$getAds = DB::table('ads_postings')
                            //->where('city', $cityAd->id)
                            //->where('delete_status', '0')
                            //->where('status', '1')
                            //->orderBy('created_at', 'DESC')
                            //->get();
                    //}
                    
                    $ldate = date('d-m-Y');
                    $adType = 'Paid';
                    
                    // Check if $getAds is either null or empty
                   // if (!isset($getAds) || count($getAds) == 0) {
                        $getAds = DB::table('ads_postings')
                            ->join('subscription_history', 'ads_postings.subscription_id', '=', 'subscription_history.id')
                            ->where('ads_postings.delete_status', '0')
                            ->where('ads_postings.status', '1')
                            ->whereDate('ads_postings.ad_expiry', '>', date('Y-m-d'))
                            //->orderBy('subscription_history.offered_price', 'DESC')
                            ->orderByRaw("IF(ads_postings.ad_type = '{$adType}', ads_postings.ad_type, ads_postings.id) DESC")
                            ->select('ads_postings.*')
                            ->get();
                    //}
                @endphp


                
                
                @foreach($getAds as $adsData)
                    @php
                        if ($adsData->city) {
                            $getcity = App\Models\City::find($adsData->city);
                            $getstate = DB::table('states')->where('id', $getcity->state_id)->first();
                        }
                        $locationInfo = \Location::get($adsData->location);
                     @endphp
                    <div class="post-data">
                        <a href="{{url('ads-details/' . $adsData->id)}}">
                            <img class="thumbnailImage" src="{{$adsData->image}}" alt="" loading="lazy">
                        </a>
                        <h4 class="m-0 p-0"> <a href="{{url('ads-details/' . $adsData->id)}}"> {{ strlen($adsData->ad_title) > 25 ? substr($adsData->ad_title, 0, 25) . '...' : $adsData->ad_title }}</a></h4>
                        <div class="d-flex justify-content-between">
                            <div class="price-section">
                                <?php    if ($adsData->price == ''&&$adsData->salary_from!=0&&$adsData->salary_from!=0) { ?>
                                <h4 class="m-0 p-0">&#8377; {{$adsData->salary_from}} - &#8377; {{$adsData->salary_from}}</h4>
                                <?php    } else { ?>
                                @if($adsData->price != 0)
                                    <h4 class="m-0 p-0">&#8377; {{$adsData->price}}</h4>
                                @endif
                                <?php    } ?>
                            </div>
                             <p class="ml-0 pl-0"><span class="ic-view">{{$adsData->ad_view_count}}</span></p>
                        </div>
                        <div class="loc-date" style="border-top: 1px dashed rgba(36, 39, 44, .15);">
                             <p class="m-0 p-0 "><span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                            <path
                                d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10" />
                            <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                            </svg></span>&nbsp;{{ ucfirst($getcity->name ?? $locationInfo->cityName ?? '')}}, {{ ucfirst($getstate->name ?? $locationInfo->regionName ?? '')}}</p>
                             <p> {{ \Carbon\Carbon::parse($adsData->created_at)->format('j F') }}</p>
                             
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


<!-- START -->
<section>
    <div class="str">
        <div class="container">
            <div class="row">
                <div class="home-tit">
                    <h2><span>Popular Categories</span></h2>
                </div>
                <div class="category-list mobile-category-view">
                    @foreach($homecategories as $key => $orderDetails)

                                        @php
                                            $countAds = DB::table('ads_postings')->where('category_id', $orderDetails->id)->where('delete_status', '0')->where('status', '1')->whereDate('ad_expiry', '>', date('Y-m-d'))->count();
                                        @endphp
                                        <div class="category-data-list">
                                            <img src="{{$orderDetails->image}}" alt="" loading="lazy">
                                            <h4 class="m-0 p-0">{{$orderDetails->name}}</h4>
                                            <a href="{{url('category-ads/'.$orderDetails->id)}}"><p class="m-0 p-0">Show All ({{ $countAds}})</p></a>
                                        </div>
                    @endforeach

                </div>
                <div class="category-list1 desktop-category-view">
                    @foreach($homecategories as $key => $orderDetails)

                                        @php
                                            $countAds = DB::table('ads_postings')->where('category_id', $orderDetails->id)->where('delete_status', '0')->where('status', '1')->whereDate('ad_expiry', '>', date('Y-m-d'))->count();
                                        @endphp
                                        <div class="category-data-list">
                                            <img src="{{$orderDetails->image}}" alt="" loading="lazy">
                                            <h4 class="m-0 p-0">{{$orderDetails->name}}</h4>
                                            <a href="{{url('category-ads/'.$orderDetails->id)}}"><p class="m-0 p-0">Show All ({{ $countAds}})</p></a>
                                        </div>
                    @endforeach

                </div>
                <a href="{{ route('all-product')}}" class="more text-center w-100 mt-5">View All Category</a>
            </div>
        </div>
    </div>
</section>
<section>
    <div id="demo" class="carousel slide cate-sli caro-home" data-ride="carousel">
        <div class="container">
            <div class="row">
                <div class="inn">
                    <div class="carousel-inner">
                        @foreach($banners as $key => $banner)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                <img src="{{ url('public/' . $banner->image) }}" alt="{{ $banner->title }}" 
                                    >
                                @if($banner->link != '')
                                <a href="{{$banner->link}}" target="_blank"></a>
                                
                                @else
                                <a href="javascript:void(0);"></a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <a class="carousel-control-prev" href="#demo" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a class="carousel-control-next" href="#demo" data-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- START -->
<section>
    <div class="str">
        <div class="container">
            <div class="row">
                <div class="home-tit">
                    <h2><span>Join us Now</span></h2>
                    <p>We connect with targeted customers for greater business conversion</p>
                </div>
                <div class="hom2-hom-ban-main">
                   <!-- <div class="hom2-hom-ban hom2-hom-ban1">
                        <h2>Looking for a Service Expert?</h2>
                        <p>Tell us your service needs, we help you to send best Service Experts</p>
                        <a href="{{route('category-ads', 27)}}">Book Experts</a>
                    </div> -->
                    <div class="hom2-hom-ban hom2-hom-ban2">
                        <h2>Looking for smoother experience?</h2>
                        <p>Download our Android app to find the best deals and post your ads quickly</p>
                        <a href="https://play.google.com/store/apps/details?id=com.webmingo.welcomepost" download="Welcomepost" style=" align-items: center;">
                            Download the App <img src="https://icons.iconarchive.com/icons/dtafalonso/android-lollipop/256/Downloads-icon.png" alt="Download Logo" style="width: 24px; height: 24px; margin-left: 8px;">
                        </a>
                        <img class="background-img" src="https://welcomepost.in/public/uploads/android_logo.png" alt="Background Image">
                        <img class="background-img2" src="https://welcomepost.in/public/uploads/android_logo2.png" alt="Background Image">
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<!--END -->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var welcomeAmount = '{{ session('welcomeAmount') }}';
        var welcomeDialog = document.getElementById('welcomeDialog');

        if (welcomeAmount&&welcomeAmount!=0) {
            document.getElementById('welcomeAmount').innerText = welcomeAmount;
            welcomeDialog.style.display = 'block';
            fetch('{{ route('removeWelcomeAmount') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data.message);
                })
                .catch(error => console.error('Error:', error));
        }
    });

    function closeDialog() {
        document.getElementById("welcomeDialog").style.display = "none";
    }
    $(".hide_ads_post").on("click", function () {
        let hide_id = $(this).attr('id');
        let all_hide_id = $(".hide_reco_ads").attr('id');

        if (hide_id == all_hide_id) {
            $(".hide_reco_ads").css('display', 'none');
        } else {
            $(".hide_reco_ads").css('display', 'block');
        }
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script>
    $(document).ready(function(){
        $('.slider').slick({
            slidesToShow: 4,    
            slidesToScroll: 1,
            autoplay: true,      
            autoplaySpeed: 2000, 
            responsive: [
                {
                    breakpoint: 768, 
                    settings: {
                        slidesToShow: 2, 
                    }
                }
            ]
        });
    });
</script>

@endsection