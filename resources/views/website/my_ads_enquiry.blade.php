@extends('website.layout.layout')
@section('title', $page)
@section('content')



<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>


<section class="">
 <div class="com-pro-pg-bd">
            <div class="container">
                <div class="row">
                    <!--START-->
                    <div class="box-s1">
                        <div class="pro-pg-logo">
                            <img src="{{$customerinfo->image}}" alt=""
                                loading="lazy">
                        </div>
                        <div class="pro-pg-bio">
                            <h1>Welcome Post<i class="li-veri" title="Verified"><img
                                        src="{{url('assets/website/images/icon/verified.png')}}"></i>
                            </h1>
                            <ul class="bio">
                                <li><span><img src="{{url('assets/website/images/icon/line/map.png')}}">{{$customerinfo->address}}</span>
                                </li>
                                <li><a href="Tel:9876543210"><img
                                            src="images/icon/line/phone.png">
                                        {{$customerinfo->mobile}} </a></li>
                                <li><a href="mailto:welcomepost@gmail.com"><img
                                            src="images/icon/line/email.png">
                                       {{$customerinfo->email}}</a></li>
                               

                            </ul>
                           
                        </div>
                        <div class="pro-pg-cts">
                            <a href="#home_enquiry_form" class="cta1">Get quote</a>
                            <a href="Tel:9876543210" class="cta2">Call Now</a>
                            <a target="_blank" href="https://wa.me/919876543210" class="cta3">WhatsApp</a>
                        </div>
                    </div>
                    <!--END-->
					
					
					
                    <!--START-->
                    <div class="box-s2">
                        <div class="lhs">
                            
                            <!--START-->
                            <div class="comp-pro" id="prod">
                                <h2>My Ads</h2>
								
								@foreach($myads as $key => $orderDetails)
								
								
                                <div class="all-pro-box">
                                    <div class="all-pro-img">
                                         <img src="{{$orderDetails->image}}" alt="" loading="lazy">
                                    </div>
                                    <div class="all-pro-txt">
                                        <h4>{{$orderDetails->ad_title}}</h4>
                                        <span class="pri"><b class="pro-off">{{$orderDetails->salary_from}}</b></span>
                                    </div>
                                </div>
								@endforeach
								
								
                            </div>
                            <!--END-->
                        </div>
						
						
                    </div>
                    <!--END-->
					
					<div class="rhs">
                            <div class="cpro-form">
                                <div class="box templ-rhs-eve">
                                    <div class="hot-page2-hom-pre-head">
                                        <h4>Change Your Password</h4>
                                    </div>
                                    <div class="templ-rhs-form">
                                        <form name="home_enquiry_form" id="home_enquiry_form" method="post"
                                            enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label>Enter your old password</label>
                                                <input type="password" name="enquiry_name" required="required"
                                                    class="form-control" placeholder="Old password">
                                            </div>
                                            <div class="form-group">
                                                <label>Enter your new password</label>
                                                <input type="password" class="form-control"
                                                    placeholder="New password*" required="">
                                            </div>
                                            <div class="form-group">
                                                <label>Enter confirm password*</label>
                                                <input type="password" class="form-control"
                                                    placeholder="Confirm password*" required="">
                                            </div>
                                            <button type="submit" name="home_enquiry_submit"
                                                class="btn btn-primary">Change Password</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                </div>
            </div>
        </div>
    </section>
@endsection