@extends('website.layout.layout')
@section('title', $page)
@section('content')



    <!-- START -->
    <section>
        <div class="all-jobs-ban">
            <div class="container">
                <div class="row">
                    <div class="jtit">
                        <h1>Find Service Experts</h1>
                        <p>Search what you want, We provide the best services and <br /> make it easy for you.</p>
                    </div>
                    <br>
                    <div class="job-sear" style="margin-bottom:50px;">
                        <form action="{{url('category-location-ads')}}" method="post" name="expert_filter_form" id="expert_filter_form" class="expert_filter_form">
						@csrf
                            <ul>
                                <li class="sr-sea">
                                    <select class="chosen-select" id="expert-select-search1"
                                        name="category" required="">
                                        <option value="">Select Category</option>
										@foreach($allcategories as $key => $orderDetails)
										<option value="{{$orderDetails->id}}">{{$orderDetails->name}}</option>
										@endforeach
									</select>
                                </li>
                                <li class="sr-loc">
                                    <select class="form-control" id="job-select-city" name="city" required="">
                                        <option value="">Select Location</option>
                                        @foreach($city as $key => $orderDetails)
											<option value="{{$orderDetails->id}}">{{$orderDetails->name}}</option>
										@endforeach
                                    </select>
                                </li>
                                <li class="sr-btn">
                                    <button type="submit" id="expert_filter_submit"><i class="material-icons">search</i></button>
                                </li>
                            </ul>
                        </form>
                    </div>
                 
                    <!--<div class="job-pop-tag">
                        <a href="all-products.html">AC Services</a>
                        <a href="all-products.html">LED Tv Services</a>
                        <a href="all-products.html">Car service</a>
                        <a href="all-products.html">Electrical service</a>
                        <a href="all-products.html">Plumbers</a>
                    </div>-->
					
                </div>
            </div>
        </div>
    </section>
    <!-- END -->
    
    <section class="event-body mt-5">
        <div class="container">
            <div class="row">
                <div class="home-tit">
                    <h2><span>Our Recommendations</span> </h2>                
				</div>

                <div class="us-ppg-com">
                    <ul id="intseres" class="events-wrapper row">
                        @php
				            $getAds = DB::table('ads_postings')->where('delete_status','0')->where('status','1')->orderBy('price','DESC')->get();
                           
                            
                            $ldate = date('d-m-Y');
                             
                        @endphp
                        
                        
                        
                        @if(!isset($getAds))
                           $getAds = DB::table('ads_postings')->where('delete_status','0')->where('status','1')->orderBy('salary_from','DESC')->get();
                            
                        @endif
                       
                         @foreach($getAds as $adsData)
                         @php
                            $getcity = App\Models\City::find($adsData->city);
                            $getstate = DB::table('states')->where('id',$getcity->state_id)->first();
                            
                         @endphp
                                   
                                        <!--<li class="events-item col-lg-4">-->
                                        <li class="events-item">
                                            <div class="eve-box">
                                                <div>
                                                    <a href="{{url('ads-details/'.$adsData->id)}}">
                                                        <img src="{{$adsData->image}}" alt="" loading="lazy">
                                                        <span>{{$adsData->created_at}}</span>
                                                    </a>
                                                </div>
                                            <div>
                                                <h4>
                                                    <a href="{{url('ads-details/'.$adsData->id)}}">{{$adsData->ad_title}}</a>
                                                    <!--<button class="btn btn-primary btn-sm hide_ads_post" id="{{ $adsData->id }}" style="float:right !important;">Hide All</button>-->
                                                </h4>
                                                <div class="hide_reco_ads" id="{{ $adsData->id }}">
                                                    
                                                    @if($adsData->is_mobile_hide == 0)
                                                        <span class="pho">{{$adsData->mobile}}</span>
                                                    @endif
                                                    <span> Total Views: {{$adsData->ad_view_count}}</span>
                                                    <span class="addr">Address: {{ ucfirst($getstate->name)}}, {{ ucfirst($getcity->name)}}</span>
                                                </div>
                                            </div>
                                            <div class="all-pro-txt">
								<?php if($adsData->price == ''){ ?>
								
                                    <span class="pri"><b class="pro-off">&#8377; {{$adsData->salary_from}} - &#8377; {{$adsData->salary_from}}</b></span>
								<?php }else{ ?>
									<span class="pri"><b class="pro-off">Rs {{$adsData->price}}</b></span>

								<?php } ?>
							
                                </div>

                                <div>
                                    <div class="auth">
                                        <img src="{{$adsData->image}}" alt="" loading="lazy">
                                        <b>Hosted by</b><br>
                                        <h4>{{$adsData->fullname}}</h4>
                                    </div>
                                </div>
                            </div>
                        </li>
                        
                            @endforeach
                       
					   
					   
						
					   
					   

                    </ul>
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
                    <div class="land-pack">
                        <ul>
						
							@foreach($homecategories as $key => $orderDetails)
							
							@php
							    $countAds = DB::table('ads_postings')->where('category_id',$orderDetails->id)->where('delete_status','0')->where('status','1')->count();
							@endphp
							
                            <li>
                                <div class="land-pack-grid">
                                    <div class="land-pack-grid-img">
                                        <img src="{{$orderDetails->image}}" alt="" loading="lazy">
                                    </div>
                                    <div class="land-pack-grid-text">
                                        <h4>{{$orderDetails->name}} <span class="dir-ho-cat">Show All ({{ $countAds}})</span>
                                        </h4>
                                    </div>
                                    <a href="{{url('category-ads/'.$orderDetails->id)}}" class="land-pack-grid-btn">View all listings</a>
                                </div>
                            </li>
							
							@endforeach
                          
                            
                           
                        </ul>
                        <a href="{{ route('all-product')}}" class="more">View all Ads</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END -->

   
   
    <!-- START Recommendations -->
    <div class="ban-ql pt-4">
        <div class="container">
            <div class="row">
                <ul>
                    <li>
                        <div>
                            <img src="{{ asset('assets/website/images/icon/1.png')}}" alt="" loading="lazy" loading="lazy">
                            <h4>24 Thousands <br>Business</h4>
                            <p>Choose from a collection of handpicked luxury villas & apartments</p>
                            <a href="{{ route('login')}}">Explore Now</a>
                        </div>
                    </li>
                    <li>
                        <div>
                            <img src="{{ asset('assets/website/images/icon/2.png')}}" alt="" loading="lazy" loading="lazy">
                            <h4>500+ Service <br>Experts</h4>
                            <p>Are you looking for the best Service Expert? We make it easy to hire the right
                                professional

                            </p>
                            <a href="{{ route('login')}}">Book Expert Now</a>
                        </div>
                    </li>
                    <li>
                        <div>
                            <img src="{{ asset('assets/website/images/icon/3.png')}}" alt="" loading="lazy" loading="lazy">
                            <h4>Find Your Next Job <br>Now</h4>
                            <p>Search latest job openings online including IT, Sales, Banking, Fresher, Walk-ins,
                                Part-time & more</p>
                            <a href="{{ route('login')}}">Find you Job</a>
                        </div>
                    </li>
                    <li>
                        <div>
                            <img src="{{ asset('assets/website/images/icon/4.png')}}" alt="" loading="lazy" loading="lazy">
                            <h4>Sell & Buy Product <br>Online</h4>
                            <p>Bizbook Online store. Everything you need to sell & buy online.
                            </p>
                            <a href="{{ route('login')}}">Start Selling Online</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!--END-->
     <section>
        <div id="demo" class="carousel slide cate-sli caro-home" data-ride="carousel">
            <div class="container">
                <div class="row">
                    <div class="inn">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset('assets/website/images/slider/90890557952.jpg')}}" alt="Los Angeles" width="1100" height="500">
                                <a href="#" target="_blank"></a>
                            </div>
                            <div class="carousel-item ">
                                <img src="{{ asset('assets/website/images/slider/27459517111.jpg')}}" alt="Los Angeles" width="1100" height="500">
                                <a href="#" target="_blank"></a>
                            </div>
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
                        <div class="hom2-hom-ban hom2-hom-ban1">
                            <h2>Looking for a Service Expert?</h2>
                            <p>Tell us your service needs, we help you to send best Service Experts</p>
                            <a href="#">Book Experts</a>
                        </div>
                        <div class="hom2-hom-ban hom2-hom-ban2">
                            <h2>Are you a Service Expert?</h2>
                            <p>Join us today and earn more money and move your business to next level</p>
                            <a href="#">Join now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END -->

    <!-- START -->
    <section>
        <div class="str count">
            <div class="container">
                <div class="row">

                    <div class="how-wrks">
                        <div class="home-tit">
                            <h2><span>How It Works</span></h2>
                            <p>Explore some of the best tips from around the world from our<br>partners and
                                friends.</p>
                        </div>
                        <div class="how-wrks-inn">
                            <ul>
                                <li>
                                    <div>
                                        <span>1</span>
                                        <img src="{{ asset('assets/website/images/icon/how1.png')}}" alt="" loading="lazy">
                                        <h4>Create an account</h4>
                                        <p>Fusce imperdiet ullamcorper metus eu fringilla. from around the world from
                                            our partners and friends.</p>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <span>2</span>
                                        <img src="{{ asset('assets/website/images/icon/how2.png')}}" alt="" loading="lazy">
                                        <h4>Add your business</h4>
                                        <p>Fusce imperdiet ullamcorper metus eu fringilla. from around the world from
                                            our partners and friends.</p>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <span>3</span>
                                        <img src="{{ asset('assets/website/images/icon/how3.png')}}" alt="" loading="lazy">
                                        <h4>Get more leads</h4>
                                        <p>Fusce imperdiet ullamcorper metus eu fringilla. from around the world from
                                            our partners and friends.</p>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <span>4</span>
                                        <img src="{{ asset('assets/website/images/icon/how4.png')}}" alt="" loading="lazy">
                                        <h4>Archive goals</h4>
                                        <p>Fusce imperdiet ullamcorper metus eu fringilla. from around the world from
                                            our partners and friends.</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- END -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    $(".hide_ads_post").on("click", function(){
        let hide_id = $(this).attr('id');
        let all_hide_id = $(".hide_reco_ads").attr('id');
        
        if(hide_id == all_hide_id)
        {
            $(".hide_reco_ads").css('display','none');
        }else{
            $(".hide_reco_ads").css('display','block');
        }
    });
</script>
   @endsection