@extends('website.layout.layout')
@section('title', $page)
@section('content')
@if(session()->has('id'))
    @include('website.partials.user_sidebar')
@endif
@php
    $referalCode = '';
    $ct_id = session('id');
    if(isset($ct_id) && $ct_id !='')
    {
        $username = DB::table('customers')->where('id',$ct_id)->first();
        if(isset($username) && !empty($username))
        {
            $referalCode= $username->referral_code ?? '';
        }
    }
    $adminsetting = DB::table('admin_setting')->first();
@endphp
 <style>
 .copy-text {
   position: relative;
   padding: 5px;
   background: #fff;
   border: 1px solid #ddd;
   border-radius: 10px;
   display: flex;
   justify-content: space-between;
   }
   .copy-text input.text {
   color: #555;
   border: none;
   outline: none;
   }
   .copy-text button {
   padding: 8px 12px;
    background: #5784f5;
    color: #fff;
    font-size: 14px;
    border: none;
    outline: none;
    border-radius: 10px;
    cursor: pointer;
   }
   .copy-text button:active {
   background: #809ce2;
   }
   .copy-text button:before {
   content: "Copied";
   position: absolute;
   top: -45px;
   right: 0px;
   background: #5c81dc;
   padding: 8px 10px;
   border-radius: 20px;
   font-size: 15px;
   display: none;
   }
   .copy-text button:after {
   content: "";
   position: absolute;
   top: -20px;
   right: 25px;
   width: 10px;
   height: 10px;
   background: #5c81dc;
   transform: rotate(45deg);
   display: none;
   }
   .copy-text.active button:before,
   .copy-text.active button:after {
   display: block;
   }
   .Referral_id.select-add-type {
      width: 78%;
      max-width: 100%;
   }
    /* Custom Tooltip Style */
    .ui-tooltip-content {
      background-color: #3d3f94;
      color: #fff;
      border: 1px solid #007bff;
    }
    .subscription-cont {
        border: 1px solid #ddd;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 10px;
        background-color: #f9f9f9;
    }
    
    .price {
        font-size: 1.2rem;
        color: #333;
        margin: 10px 0;
    }
    
    .bb-text {
        margin-top: 15px;
    }
    .bb-text.b.s a{
        color: #fff !important;
    }
    @media (max-width: 768px) {
        .col-sm-6, .col-6 {
            width: 50%;
        }
    
        .price {
            font-size: 1rem;
        }
    
        .bb-text {
            font-size: 0.875rem;
        }
    
        .subscription-cont {
            padding: 15px;
        }
        .text-center{
            font-size:2rem;
        }
    }
  </style>
@if(!session()->has('id'))
<div class="row">
@endif
<div class="{{ session()->has('id') ? 'col-lg-9 col-md-8 col-sm-12' : 'col-lg-9 col-md-8 col-sm-12 mx-auto mt-5 mb-5' }}">
    <div class="profile-cont">
        @if (session('form-submitted'))
        <h5 style="color:green;">{{ Session::get('form-submitted') }}, goto <a href="{{url('my-ads')}}">My Ads</a></h5>
        @php Session::forget('form-submitted')@endphp
        @endif
        @if (session('success'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert" style="opacity:1;">
          <strong>{{ Session::get('success') }}</strong>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        @php Session::forget('success')@endphp
        @endif
         @if (session('error'))
         <div class="alert alert-danger alert-dismissible fade show" role="alert" style="opacity:1;">
          <strong style="font-size:17px;">{{ Session::get('error') }}</strong>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        @php Session::forget('error')@endphp
        @endif
        <h3>Buy New Subscription</h3>
        <div class="sub-exp">
 		    <p>All active or live ads will expire on Subscription Expiry Date</p>
 		    
 		</div>
        <div class="row">
            @foreach($subscription as $key => $orderDetails)
            <div class="col-lg-4 col-sm-12 col-md-6 text-center">
                <div class="subscription-cont">
                    <h3 class="text-center">{{$orderDetails->package}} 
                    @php
                        $category_list = explode(',', $orderDetails->category_id);
                        $result = App\Models\Categories::whereIn('id',$category_list)->pluck('name');
                        $categoryall = $result->implode(',');
                    @endphp
                    <i data-toggle="tooltip" data-placement="top" data-html="true" title="{!! $categoryall !!}" style="font-size:24px" class="fa">&#xf05a;</i> </h3>
                    @if($orderDetails->package_validity == '1' || $orderDetails->package_validity == '0')
                        @php
                            $total_day = 'Day';
                        @endphp
                    @else
                        @php
                            $total_day = 'Days';
                        @endphp
                    @endif
        
                    <div class="price">{{ $orderDetails->package_validity}} {{ $total_day }} Validity Plan</div>
                    <div class="price">{{ $orderDetails->no_of_ads}} Ads Bucket</div>
                    @php $managecommission = \App\Models\managecommission::where('subscription_packge_id',$orderDetails->id)->where('delete_status',0)->first();
                        $settings = \App\Models\Adminsettings::first();
                    @endphp
                    @if($settings->auto_join!=0)
                    <div class="price">{{ $managecommission->auto_join_member ?? 0}} Seeds</div>
                    @else
                    <div class="price s">{{ $managecommission->auto_join_member ?? 0}} Seeds</div>
                    @endif
                    @if($orderDetails->is_free=='no')
                        @if(isset($orderDetails->discount)&&$orderDetails->discount!=0)
                        <div class="price s">INR {{ $orderDetails->mrp }}</div>
                        @endif
                    <div class="price">INR {{ $orderDetails->offered_price }}</div>
                    @else
                        <div class="price">INR 0</div>
                    @endif
        
                    <div class="bb-text b s">
                        @if($orderDetails->is_free=='yes')
                            @if(session()->has('id'))
                            <button class="razorpay-payment-button pay_now" name="pay_now" id="pay_now" subscription_id="{{$orderDetails->id}}">Free Subscription</button>
                            @else
                            
                                <a href="{{route('login')}}" class="btn btn-primary">Free Subscription</a>
                            @endif
                        @else
                        <a href="{{route('checkout',encrypt($orderDetails->id))}}" class="btn btn-primary">Buy Now</a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
            
            <div class="col-lg-12 col-sm-12 col-md-12 text-left">
 		    
 		    @if($referalCode !='' && $adminsetting->referal_join == 1)
 		    <div class="Referral_id select-add-type">
 		         <strong>Referral Link: </strong>
                <div class="copy-text">
                   <input type="text"  class="Referral_id text" value="{{ route('login',['showLogin'=>'true', 'referralCode'=>$referalCode]) }}" style="width:100%;"/>
                    <button>copy</button>
                </div>
            </div>
 		    @endif
 		</div>
        </div>
    </div>
</div>
@if(session()->has('id'))
</div>
</div>
</section>
@else
</div>
@endif
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.10.4/sweetalert2.min.css" >
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.10.4/sweetalert2.min.js"></script>

<script>
    $(".pay_now").on("click",function(){
        Swal.fire({
            title: 'Are you sure?',
            
            icon: 'success',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Subcribe Free'
            }).then((result) => {
                if (result.isConfirmed) {
                    var id = $(this).attr('subscription_id');
            
                    $.ajax({
        		url:'{{url("free-subscription")}}',
        		method:'POST',
        		data:{id:id,'_token':"{{csrf_token()}}",'type':'3'},
        		success:function(data){
                    console.log(data);
                    if (data.success) 
                    {
                        Swal.fire(
                            "Package Purchased Successfully."
                        );
                        setTimeout(function() {
                            location.reload();
                        }, 40);
                    }else{
                         Swal.fire(
                            data.msgText
                        );
                    }
        		}
        	});
                }
            })
        
    });
    
     let copyText = document.querySelector(".copy-text");

// Add an event listener to the button inside the "copy-text" element
copyText.querySelector("button").addEventListener("click", function () {
    // Select the input element inside the "copy-text" element
    let input = copyText.querySelector("input.text");

    // Select the text inside the input element
    input.select();

    // Copy the selected text
    document.execCommand("copy");
});
</script>
@stop