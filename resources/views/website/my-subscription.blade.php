@extends('website.layout.layout')
@section('title', $page)
@section('content')
@include('website.partials.user_sidebar')
<div class="col-sm-12 col-md-9">
   <div class="profile-cont">
      <h3>
         My Subscription
         <span class="filter float-right">
            <select class="ads-sort">
               <option value="active">Active Subscription </option>
               <option value="expiry">Expiry Subscription </option>
            </select>
         </span>
      </h3>
      <div class="sub-exp">
         @if(isset($expiry_history))
         @php
         $result 		= DB::table('subscriptions')->where('id',$expiry_history->subscription_id)->first();
         $status_dates 	= date("d-m-Y");  
         $no    			= explode(" ",$expiry_history->subscription_validity);
         $nos            = $no[0] + 1;
         $dates 			= $expiry_history->created_at;	
         $date  			= date_create($dates);
         date_add($date,date_interval_create_from_date_string($nos."days"));
         $subscription_expiry = date_format($date,"d-m-Y");
         $subs_date = explode(" ",$expiry_history->created_at);  
         $dats_subs = date_create($subs_date[0]); 
         $time_subs = date_create($subs_date[1]);
         $cal_date = date_format($dats_subs,"d-m-Y");
         $days_ago = date('Y-m-d', strtotime('-3 days', strtotime($subscription_expiry)));
         @endphp
         {{--Subscription Expiring On -	<span class="red">{{ date("jS F Y", strtotime($subscription_expiry)) }}</span> | {{ $used_ads }}/{{ $remaining_ads }} Ads Used
         @if(strtotime($status_dates) > strtotime($subscription_expiry))
         <a href="{{ url('purchase-subscription')}}"><span style="cursor:pointer;background-color:#3d3f94; color:#fff; padding:7px 15px; margin-left:10px; font-size:14px; border-radius: 5px;">Upgrade Now <i class="material-icons" style="vertical-align:middle;font-size:22px;margin-left:5px;transition:all 0.4s ease;">arrow_forward</i></span></a>
         @endif
         @else
         You have no subscription, please Buy atleast one subscription
         You are subscribe to FREE Subscription to Post the ads, under FREE subscription you will get 1 Free Ad on every Category Valid for 30 Days.
         <a href="{{ url('purchase-subscription')}}"><span style="cursor:pointer;background-color:#3d3f94; color:#fff; padding:10px;">Upgrade Now <i class="material-icons" style="vertical-align:middle;font-size:22px;margin-left:5px;transition:all 0.4s ease;">arrow_forward</i></span></a>--}}
         @endif
         <!--<p>All active or live ads will expire on Subscription Expiry Date</p>-->
      </div>
      <table class="table scroll">
         <thead>
            <tr>
               <th scope="col">#</th>
               <th scope="col">Date & Time</th>
               <th scope="col">Subscription Id</th>
               <th scope="col">Subscription Name </th>
               <th scope="col">Expiry</th>
               <th scope="col">Billed Amount</th>
               <th scope="col">Total Ads</th>
               @if($isAutoJoin)
                <th scope="col">Total Seeds</th>
               @endif
               <th>Transaction ID</th>
               <th>Payment Status</th>
               <th>Status</th>
            </tr>
         </thead>
         <tbody id="sort-ads-html">
            @if(isset($history) && count($history)>0)
            @foreach($history as $index=>$subh)
            @php
            $result 		= DB::table('subscriptions')->where('id',$subh->subscription_id)->first();
            $status_dates 	= date("d-m-Y");  
            $no    			= explode(" ",$result->package_validity);
            $nos            = $no[0] + 1;
            $dates 			= $subh->created_at;	
            $date  			= date_create($dates);
            date_add($date,date_interval_create_from_date_string($subh->subscription_validity."days"));
            $subscription_expiry = date_format($date,"d-m-Y");
            $subs_date = explode(" ",$subh->created_at);  
            $dats_subs = date_create($subs_date[0]); 
            $time_subs = date_create($subs_date[1]);
            $cal_date = date_format($dats_subs,"d-m-Y");
            @endphp
           
            <tr>
               <th scope="row">{{$index+1}}</th>
               <td>{{ date_format($dats_subs,"d-m-Y") }} | {{ date_format($time_subs,"H:i A") }}</td>
               <td>{{$result->subscription_number}}</td>
               <td>{{ $result->package}}</td>
               <td>{{ $subh->subscription_expiry }}</td>
               <td>INR {{ $subh->offered_price}}</td>
               <td> {{ $subh->remaining_ads}}</td>
               @if($isAutoJoin)
               <td> {{ $subh->auto_join_member}}</td>
               @endif
               <td>{{ $subh->transaction_id }}</td>
               <td>{{ $subh->payment_status}}</td>
               <td>@if(strtotime($status_dates) < strtotime($subscription_expiry))
                  Active 
                  @else
                    Expired
                  @endif
               </td>
               <td> @if($result->offered_price > 0) <a href="{{ url('subscription-export')}}/{{ $subh->id }}"><img src="{{ asset('assets/website/images/down.png')}}"></a> @endif </td>
            </tr>
            
            @endforeach
            @endif
         </tbody>
      </table>
   </div>
</div>
</div>
</div>
</section>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"></div>
<div class="modal fade" id="exampleModalError" tabindex="-1" role="dialog" aria-labelledby="exampleModalErrorLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Buy More Ads</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <p class="otherMsg"></p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
   $(document).ready(function(){
   	$(".more_ads").on("click", function(){
   	   let user_id = $(this).attr('user-id');
          $.ajax({
               url:'{{url("check-user-subscription")}}',
               method:'GET',
               data:{user_id:user_id,'_token':"{{csrf_token()}}"},
               success:function(response){
                   if(response.success == '500')
                   {
                       $("#exampleModal").html(response.html);
                       $("#exampleModal").modal('show');
                   }else{
                       $(".otherMsg").html(response.html);
                       $("#exampleModalError").modal('show');
                   }
                  
               }
           });
   	});
   });
   
   $(document).ready(function(){
   	$(".ads-sort").change(function(){
   		var id = $('.ads-sort').val();
   		$.ajax({
               url:'{{url("get-my-subscription")}}',
               method:'POST',
               data:{id:id,'_token':"{{csrf_token()}}"},
               success:function(data){
               	console.log(data);
                   $('#sort-ads-html').html(data);
                   
               }
           });
   	});
   });
</script>



@stop