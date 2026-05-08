@extends('website.layout.layout')
@section('title', $page)
@section('content')
<style>
    .box-s1.b {
	margin-top: 0px;
	padding: 20px;
}
.all-pro-box {
	border: 1px solid #002f3433;
	float: left;
	position: relative;
	background: #fff;
	transition: all 0.5s ease;
	width: 32.3%;
}
</style>
<div class="com-pro-pg-bd">
    <div class="container">
        <div class="row">
           <!--START-->
            <div class="box-s1">
                <div class="pro-pg-logo">
                    @if(isset($customerinfo->image))
                        <img src="{{ $customerinfo->image }}" alt=""
                                loading="lazy">
                    @else
                        <img src="{{ asset('assets/website/images/quote.png')}}" alt=""
                                loading="lazy">
                    @endif
                        </div>
                        <div class="pro-pg-bio">
                            <h1>{{ $customerinfo->name}}<i class="li-veri" title="Verified"><img
                                        src="{{ asset('assets/website/images/icon/verified.png')}}"></i>
                            </h1>
                            <ul class="bio">
                                <li><span><img src="{{ asset('assets/website/images/icon/line/map.png')}}">{{ $customerinfo->address}}, {{ $customerinfo->cities->name}}, {{ $customerinfo->states->name}}, {{ ucfirst($customerinfo->countries->name)}}</span>
                                </li>
                                <!--<li><a href="Tel:9876543210"><img-->
                                <!--            src="{{ asset('assets/website/images/icon/line/phone.png')}}">-->
                                <!--        9876543210 </a></li>-->
                                <!--<li><a href="mailto:welcomepost@gmail.com"><img-->
                                <!--            src="{{ asset('assets/website/images/icon/line/email.png')}}">-->
                                <!--        welcomepost@gmail.com</a></li>-->
                                @if(isset($customerinfo->website))
                                <li><a target="_blank" href="#"><img
                                            src="{{ asset('assets/website/images/icon/line/website.png')}}">
                                        {{ $customerinfo->website}}</a></li>
                                @endif
                                @if(isset($customerinfo->tax_no))
                                <li><img src="{{ asset('assets/website/images/icon/line/website.png')}}">Tax no:
                                    {{ $customerinfo->tax_no}} </li>
                                @endif

                            </ul>
                            <ul class="soc">
                                @if(isset($customerinfo->facebook))
                                <li><a href="{{ $customerinfo->facebook}}" target="_blank"><img
                                            src="{{ asset('assets/website/images/icon/line/facebook.png')}}"></a>
                                </li>
                                @endif
                                
                                @if(isset($customerinfo->twitter))
                                <li><a href="{{ $customerinfo->twitter}}" target="_blank"><img
                                            src="{{ asset('assets/website/images/icon/line/twitter.png')}}"></a>
                                </li>
                                @endif
                                
                                @if(isset($customerinfo->whatsapp))
                                <li><a href="{{ $customerinfo->whatsapp}}" target="_blank"><img
                                            src="{{ asset('assets/website/images/icon/line/whatsapp.png')}}"></a>
                                </li>
                                @endif
                                
                                @if(isset($customerinfo->youtube))
                                <li><a href="{{ $customerinfo->youtube}}"
                                        target="_blank"><img
                                            src="{{ asset('assets/website/images/icon/line/youtube.png')}}"></a>
                                </li>
                                @endif
                            </ul>
                        </div>
                        <div class="pro-pg-cts">
                            <a href="#home_enquiry_form" class="cta1">Get quote</a>
                            <!--<a href="Tel:{{$customerinfo->mobile}}" class="cta2">Call Now</a>-->
                            <!--<a target="_blank" href="https://wa.me/{{$customerinfo->whatsapp}}" class="cta3">WhatsApp</a>-->
                            <!--<a target="_blank" href="{{ url('user-chat')}}" class="cta3">Chat Now</a>-->
                        </div>
                    </div>
                    <!--END-->
                    <!--START-->
                    <div class="box-s1 b">
                        <div class="comp-abo" id="about">
                                <h2>About Us</h2>
                                <p>{{ $customerinfo->introduction}}

                                </p>
                            </div>
                            <div class="comp-pro" id="prod">
                                <h2>Products</h2>
                                
                                @foreach($allads as $orderDetail)
                                <div class="all-pro-box">
                                    <div class="all-pro-img">
                                         <img src="{{$orderDetail->image}}" alt="" loading="lazy">
                                    </div>
                                    <div class="all-pro-txt">
                                        <h4>{{ $orderDetail->ad_title}}</h4>
                                        <span class="pri"><b class="pro-off">
                                        @if(isset($orderDetail->price))
                                            {{ $orderDetail->price}}
                                        @endif
                                        
                                        @if(isset($orderDetail->salary_from))
                                            {{ $orderDetail->salary_from}} -
                                        @endif
                                        
                                        @if(isset($orderDetail->salary_to))
                                            {{ $orderDetail->salary_to}}
                                        @endif
                                        </b></span>
                                    </div>
                                </div>
                                @endforeach
                                
                                
                            </div>
                    </div>
                    <!--<div class="box-s2">-->
                    <!--    <div class="lhs">-->
                    <!--        <div class="comp-abo" id="about">-->
                    <!--            <h2>About Us</h2>-->
                    <!--            <p>{{ $customerinfo->introduction}}-->

                    <!--            </p>-->
                    <!--        </div>-->
                    <!--        <div class="comp-pro" id="prod">-->
                    <!--            <h2>Products</h2>-->
                                
                    <!--            @foreach($allads as $orderDetail)-->
                    <!--            <div class="all-pro-box">-->
                    <!--                <div class="all-pro-img">-->
                    <!--                     <img src="{{$orderDetail->image}}" alt="" loading="lazy">-->
                    <!--                </div>-->
                    <!--                <div class="all-pro-txt">-->
                    <!--                    <h4>{{ $orderDetail->ad_title}}</h4>-->
                    <!--                    <span class="pri"><b class="pro-off">-->
                    <!--                    @if(isset($orderDetail->price))-->
                    <!--                        {{ $orderDetail->price}}-->
                    <!--                    @endif-->
                                        
                    <!--                    @if(isset($orderDetail->salary_from))-->
                    <!--                        {{ $orderDetail->salary_from}} --->
                    <!--                    @endif-->
                                        
                    <!--                    @if(isset($orderDetail->salary_to))-->
                    <!--                        {{ $orderDetail->salary_to}}-->
                    <!--                    @endif-->
                    <!--                    </b></span>-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--            @endforeach-->
                                
                                
                    <!--        </div>-->
                            
                    <!--    </div>-->
                        <!--<div class="rhs">-->
                        <!--    <div class="cpro-form">-->
                        <!--        <div class="box templ-rhs-eve">-->
                        <!--            <div class="hot-page2-hom-pre-head">-->
                        <!--                <h4>Change Your Password</h4>-->
                        <!--            </div>-->
                        <!--            <div class="templ-rhs-form">-->
                        <!--                <form action="{{ route('user-update-password')}}" name="home_enquiry_form" id="home_enquiry_form" method="post"-->
                        <!--                    enctype="multipart/form-data">-->
                        <!--                    @csrf-->
                        <!--                    <div class="form-group">-->
                        <!--                        <label>Enter your old password</label>-->
                        <!--                        <input type="password" name="old_password" required="required"-->
                        <!--                            class="form-control" placeholder="Old password">-->
                        <!--                    </div>-->
                        <!--                    <div class="form-group">-->
                        <!--                        <label>Enter your new password</label>-->
                        <!--                        <input type="password" name="new_password" class="form-control"-->
                        <!--                            placeholder="New password*" required="">-->
                        <!--                    </div>-->
                        <!--                    <div class="form-group">-->
                        <!--                        <label>Enter confirm password*</label>-->
                        <!--                        <input type="password" name="confirm_password" class="form-control"-->
                        <!--                            placeholder="Confirm password*" required="">-->
                        <!--                    </div>-->
                        <!--                    <button type="submit" name="home_enquiry_submit"-->
                        <!--                        class="btn btn-primary">Change Password</button>-->
                        <!--                </form>-->
                        <!--            </div>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->
                    <!--</div>-->
                    <!--END-->
                </div>
            </div>
        </div>
    </section>
@stop