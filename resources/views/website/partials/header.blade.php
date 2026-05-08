<!-- START -->
<?php error_reporting(0);?>
<style>
    .search-button {
        background-color: rgb(59, 60, 147);
        border: none;
        padding: 5px 10px;
        color: white;
        display: flex;
        height:100%;
        align-items: center;
        justify-content: center;
    }
    
    .search-button i {
        margin-right: 5px; /* Adjust margin as needed */
    }
    
    @media screen and (max-width: 992px) {
        .hom-nav{
            display:flex;
            justify-content:space-between;
        }
        .job-sear form ul {
            display: inline-block;
            width:50%;
            left:100px;
        }
        form.f-location.expert_filter_form {
            line-height: 0;
            margin-top: -4px;
        }
        form.f-location.expert_filter_form .sr-loc:before {
            margin-top: 24px;
        }
        form.f-location.expert_filter_form .sr-loc .form-control {
            padding: 2px 2px 2px 36px;
            background: transparent;
            font-size: 14px;
        }
        form.f-location.expert_filter_form .sr-btn #expert_filter_submit {
            padding: 10px 10px 2px 15px;
            margin-top: 4px;
        }
        form.f-location.expert_filter_form .sr-btn #expert_filter_submit i {
            font-size: 20px;
            line-height: 1;
        }
        .hom-nav .ic-logo {
            width: 62px;
        }
       
       
    }
    
    @media screen and (max-width: 320px) {
         .hom-nav .ic-logo {
            width: 60px;
        }
        .hom-nav .bl {
            padding-top: 8px;
        }
        .mobile-bell
        {
            position: fixed !important;
            left: 78% !important;
        }
    
    }
     @media screen and (max-width: 620px) {
         .top__header{
            display:flex;
            flex-direction:column;
            gap:20px;
            overflow:hidden;
        }
    .top__header-desktop{
        display:none;
    }
    .mobile-bell
        {
            position: fixed !important;
            left: 78% !important;
        }
    /*.top__header{*/
    /*    display:none;*/
    /*}*/
    }
    @media screen and (min-width: 620px) {
        
    .top__header{
        display:none;
    }
    }
    /*.top__header{*/
    /*    display:none;*/
    /*}*/
    
</style>
<style>
    .bootstrap-select.btn-group .dropdown-toggle .caret {
        display: none;
    }
    
    .bootstrap-select.btn-group .dropdown-toggle {
        padding-right: 25px; /* Adjust padding as needed */
    }
    
    .bootstrap-select.btn-group .dropdown-toggle:after {
        content: none; /* Disable the default arrow */
    }
    
    .bootstrap-select.btn-group .dropdown-menu {
        min-width: 200px; /* Set the width of the dropdown menu */
    }
    .search-card{
        width:630px;
        height:50px;
        /*border:1px solid gray;*/
        display:flex;
    }
    .search-card input{
        width:280px;
        height:50px;
         border-top-right-radius: 0px;
          border-bottom-right-radius: 0px;
    }
    .search-card .custom-select-container select{
        width:200px;
        height:50px;
    }
    .search-card button{
        width:60px;
        height:50px;
        display: flex;
    align-items: center;
    justify-content: center;
        border:none;
        background-color:#3d3f94;
        color:white;
        border-top-right-radius: 3px;
          border-bottom-right-radius: 3px;
        
    }
    .custom-select-container {
    position: relative;
    display: inline-block;
    width: 200px;
}

.custom-select {
    display: inline-block;
    width: 100%;
    padding: 10px 15px;
    font-size: 16px;
    font-weight: 500;
    color: #333;
    border: 1px solid #ccc;
    border-radius: 0px;
    background-color: #fff;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    cursor: pointer;
    transition: border-color 0.3s ease;
}

.custom-select:focus {
    border-color: #007bff;
    outline: none;
}

.custom-select-container::after {
    /*content: '▼';*/
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-50%);
    pointer-events: none;
    font-size: 16px;
    color: #333;
}
@media screen and (max-width: 620px) {
     .search-card{
        width:100%;
        height:50px;
        /*border:1px solid gray;*/
        display:flex;
        /*margin:auto;*/
        justify-content:center;
    }
    .search-card input{
        width: 141px;
        height: 40px;
         border-top-right-radius: 0px;
          border-bottom-right-radius: 0px;
       border-right:0;outline:0;
    }
    .search-card .custom-select-container select{
        width:145px;
        height:40px;
        border-radius:0;
    }
    .search-card button{
        width:60px;
        height:40px;
        border:none;
        background-color:#3d3f94;
        color:white;
        border-top-right-radius: 3px !important;
          border-bottom-right-radius: 3px !important;
        
    }
    .custom-select-container {
    position: relative;
    display: inline-block;
    width: 146px;
}

.custom-select {
    display: inline-block;
    width: 100%;
    padding: 10px 15px;
    font-size: 16px;
    font-weight: 500;
    color: #333;
    border: 1px solid #ccc;
    border-radius: 0px;
    background-color: #fff;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    cursor: pointer;
    transition: border-color 0.3s ease;
}

.custom-select:focus {
    border-color: #007bff;
    outline: none;
}

.custom-select-container::after {
    /*content: '▼';*/
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-50%);
    pointer-events: none;
    font-size: 16px;
    color: #333;
}
.right-side div p {
    display: flex;
    font-size: 10px;
    margin-right: 20px;
    font-weight: bold;
}
}
.hom-nav .ic-logo {
    width: 70px;
    margin-top: 1px;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />

<script>
    $(function() {
      $('.selectcity').selectpicker();
    });
</script>



<section>
    
    <div class="str ind2-home">
        <div class="hom-head">
            <div class="hom-top lr">
                <div class="container-fluid">
                     <div class="row top__header-desktop" >
                        <div class="hom-nav "><!--MOBILE MENU-->
                           <a href="{{url('/')}}" class="top-log col-sm"><img src="{{url('assets/website/images/logo.png')}}" alt="" loading="lazy" class="ic-logo"></a>
                            <div class="search-card ">
                                <form action="{{ url('city-ads') }}" style="display: flex; align-items: left;" method="post" name="expert_filter_form" id="expert_filter_form">
                                    @csrf
                                    <input type="text" class="form-control" placeholder="Find Car and phone more..." autocomplete="off" id="top-select-search" name="search_txt" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                     <ul id="tser-res1" class="tser-res tser-res2" style="list-style: none;padding: 0;top:66px;position: absolute;left: 467px;width: 18%;background-color: #fff;border: 1px solid #ccc;"></ul>
                                    <div class="custom-select-container">
                                       <select class="custom-select form-control selectcity" name="city_id" data-live-search="true">
                                          <option value="">Select Location</option>
                                            @foreach($city as $key => $orderDetails)
                                                <option value="{{ $orderDetails->id }}">{{ $orderDetails->name }}</option>
                                            @endforeach
                                       </select>
                                    </div>
                                    <button type="submit"><i class="material-icons">search</i></button>
                                </form>
                            </div>
                            
                            <ul class="bl">
                            @if (session()->has('id'))
                                @php
                                    //$count = DB::table('chat_messages')->where('consumer_id',session('id'))->where('is_read','0')->count();
                                    $enquirycount =  DB::table('ads_enquiries')->where('receiver_id',session('id'))->orderby('id','desc')->count();
                                    $activesub = DB::table('subscription_orders')->where('user_id',session('id'))->where('payment_status','Completed')->where('subscription_expiry',NULL)->where('delete_status',0)->orderby('id','desc')->count();
                                    $activeads = DB::table('ads_postings')->where('user_id',session('id'))->where('active_status','1')->where('delete_status',0)->orderby('id','desc')->count();
                                    $defaultnoti = DB::table('default_notification_history')->where('customer_id',session('id'))->whereNull('read_at')->count();
									$customnoti = DB::table('custom_notification_history')->where('customer_id',session('id'))->whereNull('read_at')->count();
									$totalCount =  $enquirycount + $activesub + $activeads + $customnoti + $defaultnoti;	           
                                @endphp
                            <li id="notification-icon" class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="http://example.com"
                                        id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                       <img src="{{url('/assets/website/images/bellico.png')}}" />
                                       <span class="count-notify" style="color: white;background-color: red;border-radius: 8px;padding: 2px;">{{ $totalCount }}</span>
                                    </a>
                                    <ul class="dropdown-menu mobile-bell" aria-labelledby="navbarDropdownMenuLink" style="width:auto !important;">
                                        
										@php
                                        $defaultnoti1 = DB::table('default_notification_history')->where('customer_id',session('id'))->whereNull('read_at')->count();
									    $customnoti1 = DB::table('custom_notification_history')->where('customer_id',session('id'))->whereNull('read_at')->count();
                                            $totalnotifi = $defaultnoti1+$customnoti1;
                                        @endphp
                                            
                                            <li style="font-size:16px;width: -webkit-fill-available;margin-left: 0;"><a href="{{url('notifications')}}" class="dropdown-item" 
                                                    style="background:none;border:none;color:#000;padding-left: initial;">Notifications ({{$totalnotifi}}) </a>
    								    </li>
									    @php
                                        $totalActiveSubscriptions = DB::table('subscription_history')
                                            ->where('user_id', session('id'))
                                            ->where('delete_status', 0)
                                            ->whereDate('subscription_expiry', '>=', now())
                                            ->count();
                                            
                                        @endphp
                                            
                                            <li style="font-size:16px;width: -webkit-fill-available;margin-left: 0;"><a href="{{url('my-subscription')}}" class="dropdown-item" 
                                                    style="background:none;border:none;color:#000;padding-left: initial;">Active Subscription ({{$totalActiveSubscriptions}}) </a>
    								    </li>
    								    
    								    @php
    								        $setting       = App\Models\Adminsettings::first();
                                            $joinCount = DB::table('subscription_history')
                                                        ->where('user_id', session('id'))
                                                        ->where('status', '0')
                                                        ->whereDate('subscription_expiry', '>=', now())
                                                        ->where('delete_status', '0')
                                                        ->sum('total_joined');
                                            $isAutoJoin = DB::table('subscription_history')
                                                        ->where('user_id', session('id'))
                                                        ->where('status', '0')
                                                        ->where('auto_join','1')
                                                        ->whereDate('subscription_expiry', '>=', now())
                                                        ->where('delete_status', '0')->exists();
                                        
                                            $totalSeed = DB::table('subscription_history')
                                                        ->where('user_id', session('id'))
                                                        ->where('status', '0')
                                                        ->whereDate('subscription_expiry', '>=', now())
                                                        ->where('delete_status', '0')
                                                        ->sum('auto_join_member');
                                        @endphp
                                        @if($isAutoJoin)
										<li style="font-size:16px;width: -webkit-fill-available; margin-left: 0;"><a href="{{url('my-autojoining')}}" class="dropdown-item" 
                                                style="background:none;border:none;color:#000">Seed ({{$joinCount}}/{{$totalSeed}})</a>
										</li>
										@endif
    								      
										@php
                                        
										    $totalads = DB::table('subscription_history')->whereDate('subscription_expiry', '>=', now())->where('user_id',session('id'))->where('delete_status',0)->sum('remaining_ads');
										    $activeads = DB::table('ads_postings')->where('user_id',session('id'))->where('status',1)->where('delete_status',0)->orderby('id','desc')->count();
										@endphp
										<li style="font-size:16px;width: -webkit-fill-available;margin-left: 0;"><a href="{{url('my-ads')}}" class="dropdown-item" 
                                                style="background:none;border:none;color:#000;padding-left: initial;">My Active ads ({{$activeads}}/{{$totalads}})</a>
										</li>
										@php
                                        $enquirycount =  DB::table('ads_enquiries')->where('receiver_id',session('id'))->orderby('id','desc')->count();
                                        @endphp
                                        <li style="font-size:16px;width: -webkit-fill-available; margin-left: 0;"><a href="{{url('owner-enquiry')}}" class="dropdown-item" 
                                                style="background:none;border:none;color:#000">Enquiry({{$enquirycount}})</a>
                                        </li>
									</ul>
                                </li>
                                
                         
                               @else
                               <li class="noti-f">
                                    <img src="{{url('/assets/website/images/bellico.png')}}" />
                               </li>
                               @endif
								
								<?php if (session()->has('id')) { ?>
								
								<li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="http://example.com"
                                        id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <?php 
									    $ct_id = session('id');
										$username = DB::table('customers')->where('id',$ct_id)->get();
										echo $username[0]->name;
										?>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="width:auto !important;">
          <!--                              <li style="font-size:16px;width: -webkit-fill-available; margin-left: 0;"><a href="{{url('user-chat')}}" class="dropdown-item" -->
          <!--                                      style="background:none;border:none;color:#000">Chat</a>-->
										<!--</li>-->
										<li style="font-size:16px;width: -webkit-fill-available; margin-left: 0;"><a href="{{url('user-dash')}}" class="dropdown-item" 
                                                style="background:none;border:none;color:#000">Dashboard</a>
										</li>
                                        <li style="font-size:16px;width: -webkit-fill-available;margin-left: 0;"><a href="{{url('user-logout')}}" class="dropdown-item" 
                                                style="background:none;border:none;color:#000;padding-left: initial;">Sign Out</a>
										</li>
									</ul>
                                </li>
                                
								<li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ session('city_name', $locationinfo->cityName) }}
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="overflow:auto; height:350px; width:auto !important;">
                                        <?php $cities = DB::table('cities')->orderBy('name', 'asc')->get(); ?>
                                        @foreach($cities as $cityd)
                                            <li>
                                                <a href="#" class="select-city" data-city-id="{{ $cityd->id }}" data-city-name="{{ $cityd->name }}">{{ $cityd->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
								
								<?php  }else{	?>
								<li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ session('city_name', $locationinfo->cityName) }}
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="overflow:auto; height:350px; width:auto !important;">
                                        <?php $cities = DB::table('cities')->orderBy('name', 'asc')->get(); ?>
                                        @foreach($cities as $cityd)
                                            <li>
                                                <a href="#" class="select-city" data-city-id="{{ $cityd->id }}" data-city-name="{{ $cityd->name }}">{{ $cityd->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
								<li>
                                    <a href="{{url('login')}}">Sign in</a>
                                </li>
								<?php  } ?>
                                
                                <li>
                                    <a href="{{url('post-ads')}}">Sell for Free</a>
                                </li>
                            </ul>
                            <!--MOBILE MENU-->
                           
                            <div class="mob-menu">
                                
                                <div class="mob-me-ic"><i class="material-icons">menu</i></div>
                                <div class="mob-me-all">
                                    <div class="mob-me-clo"><i class="material-icons">close</i></div>
                                    <div class="mv-bus">
                                        <h4></h4>
                                        <ul>
                                            <li>
                                                <a href="{{ url('post-ads')}}">Post Ad</a>
                                            </li>
                                            
                                            @if(session()->has('id'))
                                                @php
        										    $customerExist = App\Models\Customer::where('id',session('id'))->whereNotNull('password')->exists();
        										@endphp
        										@if($customerExist)
                                                <li>
                                                    <a href="{{url('user-dash')}}" style="background:none;border:none;color:#000">Dashboard</a>
                                                </li>
                                                @endif
                                                <li class="nav-item dropdown">
                                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    @php
                                                        $ct_id = session('id');
                                                        $username = DB::table('customers')->where('id',$ct_id)->get();
                                                    @endphp
                                                    {{ $username[0]->name ?? '' }}
                                                    </a>
                                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="width:auto !important;">
                                                        <!--<li style="font-size:16px;width: -webkit-fill-available;"><a href="{{url('user-chat')}}" class="dropdown-item" -->
                                                        <!--        style="background:none;border:none;color:#000">Chat</a>-->
                                                        <!--</li>-->
                                                        
                										@if($customerExist)
        										        <li style="font-size:16px;width: -webkit-fill-available; margin-left: 0;"><a href="{{url('user-dashboard')}}" class="dropdown-item" 
                                                        style="background:none;border:none;color:#000">Your Profile</a>
                                                        @endif
                                                        <li style="font-size:16px;width: -webkit-fill-available;"><a href="{{url('user-logout')}}" class="dropdown-item" 
                                                                style="background:none;border:none;color:#000;padding-left: initial;">Sign Out</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="nav-item dropdown">
                                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        {{ session('city_name', $locationinfo->cityName) }}
                                                    </a>
                                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="overflow:auto; height:350px; width:auto !important;">
                                                        <?php $cities = DB::table('cities')->orderBy('name', 'asc')->get(); ?>
                                                        @foreach($cities as $cityd)
                                                            <li>
                                                                <a href="#" class="select-city" data-city-id="{{ $cityd->id }}" data-city-name="{{ $cityd->name }}">{{ $cityd->name }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @else
                                                <li class="nav-item dropdown">
                                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        {{ session('city_name', $locationinfo->cityName) }}
                                                    </a>
                                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="overflow:auto; height:350px; width:auto !important;">
                                                        <?php $cities = DB::table('cities')->orderBy('name', 'asc')->get(); ?>
                                                        @foreach($cities as $cityd)
                                                            <li>
                                                                <a href="#" class="select-city" data-city-id="{{ $cityd->id }}" data-city-name="{{ $cityd->name }}">{{ $cityd->name }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                                <li>
                                                    <a href="{{url('login')}}">Sign in</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="mv-cate">
                                        <h4>All Categories</h4>
                                        <ul>
                                            @foreach($allcategories as $orderDetails)
                                            <li>
                                                <a href="{{url('category-ads/'.$orderDetails->id)}}">{{ $orderDetails->name }}</a>
                                            </li>
                                            @endforeach
                                            
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!--END MOBILE MENU-->
                             
                        </div>
                        
                    </div>
                    <div class="row top__header" >
                        <div class="hom-nav"><!--MOBILE MENU-->
                        
                           <a href="{{url('/')}}" class="top-log col-sm"><img src="{{url('assets/website/images/logo.png')}}" alt="" loading="lazy" class="ic-logo"></a>
                            
                            
                            <ul class="bl">
                            @if (session()->has('id'))
                                @php
                                    //$count = DB::table('chat_messages')->where('consumer_id',session('id'))->where('is_read','0')->count();
                                    $enquirycount =  DB::table('ads_enquiries')->where('receiver_id',session('id'))->orderby('id','desc')->count();
                                    $activesub = DB::table('subscription_orders')->where('user_id',session('id'))->where('payment_status','Completed')->where('subscription_expiry',NULL)->where('delete_status',0)->orderby('id','desc')->count();
                                    $activeads = DB::table('ads_postings')->where('user_id',session('id'))->where('status','1')->where('delete_status',0)->orderby('id','desc')->count();
									$defaultnoti = DB::table('default_notification_history')->where('customer_id',session('id'))->whereNull('read_at')->count();
									$customnoti = DB::table('custom_notification_history')->where('customer_id',session('id'))->whereNull('read_at')->count();
									$totalCount =  $enquirycount + $activesub + $activeads + $defaultnoti + $customnoti;         
                                @endphp
                            <li id="notification-icon" class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="http://example.com"
                                        id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                       <img src="{{url('/assets/website/images/bellico.png')}}" />
                                       <span class="count-notify" style="color: white;background-color: red;border-radius: 8px;padding: 2px;">{{ $totalCount }}</span>
                                    </a>
                                    <ul class="dropdown-menu mobile-bell" aria-labelledby="navbarDropdownMenuLink" style="width:auto !important;">
										    @php
                                        $defaultnoti1 = DB::table('default_notification_history')->where('customer_id',session('id'))->whereNull('read_at')->count();
									    $customnoti1 = DB::table('custom_notification_history')->where('customer_id',session('id'))->whereNull('read_at')->count();
                                            $totalnotifi = $defaultnoti1+$customnoti1;
                                        @endphp
                                            
                                            <li style="font-size:16px;width: -webkit-fill-available;margin-left: 0;"><a href="{{url('notifications')}}" class="dropdown-item" 
                                                    style="background:none;border:none;color:#000;padding-left: initial;">Notifications ({{$totalnotifi}}) </a>
    								    </li>
									    @php

                                        $totalActiveSubscriptions = DB::table('subscription_history')
                                            ->where('user_id', session('id'))
                                            ->where('delete_status', 0)
                                            ->whereDate('subscription_expiry', '>=', now())
                                            ->count();
                                            
                                        @endphp
                                        
                                            
                                            <li style="font-size:16px;width: -webkit-fill-available;margin-left: 0;"><a href="{{url('my-subscription')}}" class="dropdown-item" 
                                                    style="background:none;border:none;color:#000;padding-left: initial;">Active Subscription ({{$totalActiveSubscriptions}}) </a>
    								    </li>
    								    
    								     @php
                                        
                                         $joinCount = DB::table('subscription_history')
                                                        ->where('user_id', session('id'))
                                                        ->where('status', '0')
                                                        ->whereDate('subscription_expiry', '>=', now())
                                                        ->where('delete_status', '0')
                                                        ->sum('total_joined');
                                        $isAutoJoin = DB::table('subscription_history')
                                                    ->where('user_id', session('id'))
                                                    ->where('status', '0')
                                                    ->where('auto_join','1')
                                                    ->whereDate('subscription_expiry', '>=', now())
                                                    ->where('delete_status', '0')->exists();
                                    
                                        $totalSeed = DB::table('subscription_history')
                                                    ->where('user_id', session('id'))
                                                    ->where('status', '0')
                                                    ->whereDate('subscription_expiry', '>=', now())
                                                    ->where('delete_status', '0')
                                                    ->sum('auto_join_member');
                                        @endphp
                                        @if($isAutoJoin)
										<li style="font-size:16px;width: -webkit-fill-available; margin-left: 0;"><a href="{{url('my-autojoining')}}" class="dropdown-item" 
                                                style="background:none;border:none;color:#000">Seed ({{$joinCount}}/{{$totalSeed}})</a>
										</li>
										@endif
    								      
										@php
                                        
										    $totalads = DB::table('subscription_orders')->whereDate('subscription_expiry', '>=', now())->where('user_id',session('id'))->where('delete_status',0)->orderby('id','desc')->first()->remaining_ads;
										    $activeads = DB::table('ads_postings')->where('user_id',session('id'))->where('status','1')->where('delete_status',0)->orderby('id','desc')->count();
										@endphp
										<li style="font-size:16px;width: -webkit-fill-available;margin-left: 0;"><a href="{{url('my-ads')}}" class="dropdown-item" 
                                                style="background:none;border:none;color:#000;padding-left: initial;">My Active ads ({{$activeads}}/{{$totalads}})</a>
										</li>
										@php
                                        $enquirycount =  DB::table('ads_enquiries')->where('receiver_id',session('id'))->orderby('id','desc')->count();
                                        @endphp
                                        <li style="font-size:16px;width: -webkit-fill-available; margin-left: 0;"><a href="{{url('owner-enquiry')}}" class="dropdown-item" 
                                                style="background:none;border:none;color:#000">Enquiry({{$enquirycount}})</a>
                                        </li>
									</ul>
                                </li>
                                
                         
                               @else
                               <li class="noti-f">
                                    <img src="{{url('/assets/website/images/bellico.png')}}" />
                               </li>
                               @endif
								
								<?php if (session()->has('id')) { ?>
								
								<li class="nav-item dropdown">
								    @php
									    $customerExist = App\Models\Customer::where('id',session('id'))->whereNotNull('password')->exists();
									@endphp
                                    <a class="nav-link dropdown-toggle" href="http://example.com"
                                        id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <?php 
									    $ct_id = session('id');
										$username = DB::table('customers')->where('id',$ct_id)->get();
										echo $username[0]->name;
										?>
                                    </a>
                                    @if($customerExist)
                                    <li>
                                        <a href="{{url('user-dash')}}" style="background:none;border:none;color:#000">Dashboard</a>
                                    </li>
                                    @endif
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="width:auto !important;">
          <!--                              <li style="font-size:16px;width: -webkit-fill-available; margin-left: 0;"><a href="{{url('user-chat')}}" class="dropdown-item" -->
          <!--                                      style="background:none;border:none;color:#000">Chat</a>-->
										<!--</li>-->
										
										@if($customerExist)
    								        <li style="font-size:16px;width: -webkit-fill-available; margin-left: 0;"><a href="{{url('user-dashboard')}}" class="dropdown-item" 
                                            style="background:none;border:none;color:#000">Your Profile</a>
                                        @endif
										</li>
                                        <li style="font-size:16px;width: -webkit-fill-available;margin-left: 0;"><a href="{{url('user-logout')}}" class="dropdown-item" 
                                                style="background:none;border:none;color:#000;padding-left: initial;">Sign Out</a>
										</li>
									</ul>
                                </li>
								
								<li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ session('city_name', $locationinfo->cityName) }}
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="overflow:auto; height:350px; width:auto !important;">
                                        <?php $cities = DB::table('cities')->orderBy('name', 'asc')->get(); ?>
                                        @foreach($cities as $cityd)
                                            <li>
                                                <a href="#" class="select-city" data-city-id="{{ $cityd->id }}" data-city-name="{{ $cityd->name }}">{{ $cityd->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
								
								<?php  }else{	?>
								<li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ session('city_name', $locationinfo->cityName) }}
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="overflow:auto; height:350px; width:auto !important;">
                                        <?php $cities = DB::table('cities')->orderBy('name', 'asc')->get(); ?>
                                        @foreach($cities as $cityd)
                                            <li>
                                                <a href="#" class="select-city" data-city-id="{{ $cityd->id }}" data-city-name="{{ $cityd->name }}">{{ $cityd->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
								<li>
                                    <a href="{{url('login')}}">Sign in</a>
                                </li>
								<?php  } ?>
                                
                                <li>
                                    <a href="{{url('post-ads')}}">Sell for Free</a>
                                </li>
                            </ul>
                            <!--MOBILE MENU-->
                            <div class="mob-menu">
                                
                                <div class="mob-me-ic"><i class="material-icons">menu</i></div>
                                <div class="mob-me-all">
                                    <div class="mob-me-clo"><i class="material-icons">close</i></div>
                                    <div class="mv-bus">
                                        <h4></h4>
                                        <ul>
                                            <li>
                                                <a href="{{ url('post-ads')}}">Post Ad</a>
                                            </li>
                                                @if(session()->has('id'))
                                                @php
        										    $customerExist = App\Models\Customer::where('id',session('id'))->whereNotNull('password')->exists();
        										@endphp
        										@if($customerExist)
        										        <li style="font-size:16px;width: -webkit-fill-available;"><a href="{{url('user-dash')}}"
                                                            style="background:none;border:none;color:#000">Dashboard</a>
                                                        </li>
                                                @endif
                                                <li class="nav-item dropdown">
                                                    <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    @php
                                                        $ct_id = session('id');
                                                        $username = DB::table('customers')->where('id',$ct_id)->get();
                                                    @endphp
                                                    {{ $username[0]->name ?? '' }}
                                                    </a>
                                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="width:auto !important;">
                                                        <!--<li style="font-size:16px;width: -webkit-fill-available;"><a href="{{url('user-chat')}}" class="dropdown-item" -->
                                                        <!--        style="background:none;border:none;color:#000">Chat</a>-->
                                                        <!--</li>-->
                										@if($customerExist)
                										        <li style="font-size:16px;width: -webkit-fill-available; margin-left: 0;"><a href="{{url('user-dashboard')}}" class="dropdown-item" 
                                                                style="background:none;border:none;color:#000">Your Profile</a>
                                                        @endif
                                                        <li style="font-size:16px;width: -webkit-fill-available;"><a href="{{url('user-logout')}}" class="dropdown-item" 
                                                                style="background:none;border:none;color:#000;padding-left: initial;">Sign Out</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="nav-item dropdown">
                                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        {{ session('city_name', $locationinfo->cityName) }}
                                                    </a>
                                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="overflow:auto; height:350px; width:auto !important;">
                                                        <?php $cities = DB::table('cities')->orderBy('name', 'asc')->get(); ?>
                                                        @foreach($cities as $cityd)
                                                            <li>
                                                                <a href="#" class="select-city" data-city-id="{{ $cityd->id }}" data-city-name="{{ $cityd->name }}">{{ $cityd->name }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @else
                                                <li class="nav-item dropdown">
                                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        {{ session('city_name', $locationinfo->cityName) }}
                                                    </a>
                                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="overflow:auto; height:350px; width:auto !important;">
                                                        <?php $cities = DB::table('cities')->orderBy('name', 'asc')->get(); ?>
                                                        @foreach($cities as $cityd)
                                                            <li>
                                                                <a href="#" class="select-city" data-city-id="{{ $cityd->id }}" data-city-name="{{ $cityd->name }}">{{ $cityd->name }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                                <li>
                                                    <a href="{{url('login')}}">Sign in</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="mv-cate">
                                        <h4>All Categories</h4>
                                        <ul>
                                            @foreach($allcategories as $orderDetails)
                                            <li>
                                                <a href="{{url('category-ads/'.$orderDetails->id)}}">{{ $orderDetails->name }}</a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!--END MOBILE MENU-->
                        </div>

                        <!--</div>-->
                        <div class="search-card">
                            <form action="{{ url('city-ads') }}" style="display: flex; align-items: left;" method="post" name="expert_filter_form">
                                @csrf
                                <input type="text" class="form-control" placeholder="Find Car and phone more..." autocomplete="off" id="top-select-search" name="search_txt" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                 <ul id="tser-res1" class="tser-res tser-res2" style="list-style: none;top:10px;position: absolute;left: 10px;width: 14%;background-color: #fff;border: 1px solid #ccc;"></ul>
                                <div class="custom-select-container">
                                   <select class="custom-select form-control selectcity" name="city_id" data-live-search="true">
                                      <option value="">Select Location</option>
                                        @foreach($city as $key => $cityDetails)
                                        
                                            <option value="{{ $cityDetails->id }}">{{ $cityDetails->name }}</option>
                                        @endforeach
                                   </select>
                                </div>
                                <button type="submit" style="margin-left:0;"><i class="material-icons">search</i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- END -->
<section class="news-top-menu" style="position: fixed;z-index: 16;">
    <div class="container-fluid">
        <div class="row">
            <div class="news-menu">
                <ul>
                    <li class="categoru-menu-m">
                        <div class="menu">
                            <h4 class="dropdown-toggle">All Categories</h4>
                            
                        </div>
                        <div class="pop-menu">
                            <div class="container">
                                <div class="row">
                                    
                                    <div class="pmenu-spri">
                                        <ul>
											@foreach($allcategories as $key => $orderDetails)
											<li>
											   <a href="#" class="tab-link {{ $key == 0 ? 'current' : '' }}" data-tab="{{$orderDetails->id}}"><img src="{{$orderDetails->icon}}"
                                                loading="lazy">{{$orderDetails->name}} </a>
											</li>
											@endforeach
									   </ul>
                                    </div>
                                    <div class="pmenu-cat">
                                        <h4>All Categories <i class="material-icons clopme">close</i></h4>
                                        @foreach($allcategories as $key => $orderDetails)
                                        <div id="{{$orderDetails->id}}" class="tab-content {{ $key == 0 ? 'current' : '' }}">
                                            <ul id="pg-resu">
                                                <?php $subcat = DB::table('subcategories')->where('category_id', $orderDetails->id)->where('delete_status',0)->orderBy('name','ASC')->get();?>
                                                @foreach($subcat as $key => $subDetails)
                                                <li>
                                                    <a href="{{url('subcategory-ads/'.$subDetails->id)}}">{{$subDetails->name}}</a>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endforeach
								    </div>
                                </div>
                            </div>
                        </div>
                        
                    </li>
					<li><a href="{{url('/')}}" <?php if($page == 'Home'){echo 'class="act"';} ?>>Home</a></li>
					@foreach($PremiumCategories as $key => $orderDetails)
                    <li><a href="{{url('category-ads/'.$orderDetails->id)}}" >{{$orderDetails->name}}</a></li>
					 @endforeach
                </ul>
            </div>
        </div>
    </div>
    @if (session()->has('id'))
    <input type="hidden"  value="{{session('id')}}" id="session-customer-id">
    @else
    <input type="hidden"  value="" id="session-customer-id">
    @endif
    <script>
        $(document).on('click', '.select-city', function(event) {
            event.preventDefault();
            let cityId = $(this).data('city-id');
            let cityName = $(this).data('city-name');
    
            $.ajax({
                url: '{{ route("set-city-session") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    city_id: cityId,
                    city_name: cityName
                },
                success: function(response) {
                    if(response.success) {
                        location.reload();
                    }
                }
            });
        });
    </script>
</section>
<!--END-->