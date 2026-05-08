@extends('website.layout.layout')
@section('title', $page)
@section('content')
@include('website.partials.user_sidebar')


<div class="col-sm-12 col-md-9">
   <div class="profile-cont">
       @if($adminsetting->referal_join==1)
       <a href="#" id="referralButton" class="btn btn-primary btn-md mb-2"><i class="user-plus"></i> Refer a Friend</a>
           @if($adminsetting->is_active_ad_referral == 1)
             <p class="text">Need to have atleast one active paid subscription before you share the referral link!</p>
           @endif
       @else
           @if($adminsetting->is_active_ad_referral == 1)
           <p class="text">This Service is temporary not available.</p>
           @endif
       @endif
       
      <h3>
         Referral Seeds
      </h3>
      <input type="text" value="{{$my_referral_id}}" id="referral_code" style="display:none;">
      <table class="table table-responsive">
         <thead>
            <tr>
               
               <th>Date & Time</th>
               <th>User Name</th>
               <th>Total Earning</th>
               <th>Expiry Date</th>
               <th>Status</th>
              
            </tr>
         </thead>
         <tbody id="sort-ads-html">
             
         @php
            
            @endphp
            @foreach ($referals as $items)
            <tr>
                <td class="myfontsize" >{{ date('d-M-Y', strtotime($items->datetime)) }}</td>
                <td class="myfontsize" >{{ $items->name}}</td>
                <td>{{$items->subscriptionhistorypayment->sum('comission_paid_amount')}}</td>
                <td class="myfontsize">{{$items->reserve_expiry_at ?? '--'}}</td>
                <td>{{ $items->reserve_expiry_at > date('Y-m-d') ? "Active" :"Expired" }}</td>
            </tr>
            @endforeach
         </tbody>
      </table>
   </div>
</div>
</div>
</div>
</section>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Function to open mobile share intent
    function openMobileShare() {
        // Replace 'Your referral link' with the actual referral link
        var referralCode = $('#referral_code').val();
        var referralLink = 'Register now for best classified ads, Referral-Link: https://welcomepost.in/login?showLogin=true&referralCode='+referralCode;
        
        if (navigator.share) {
            // If the navigator.share method is supported
            navigator.share({
                title: 'WelcomePost: Join with my referral link',
                text: referralLink,
            })
            .then(() => Swal.fire(
                  'Referral!',
                  'Referral link shared successfully!',
                  'success'
               ))
            .catch((error) => console.error('Share failed', error));
        } else {
            copyToClipboard('Your device does not support sharing. Link copied to clipboard.');
        }
    }

    // Function to copy referral link to clipboard
    function copyToClipboard(text) {
        // Replace 'Your referral link' with the actual referral link
        var referralCode = $('#referral_code').val();
        var referralLink = 'Register now for best classified ads, Referral-Link: https://welcomepost.in/login?showLogin=true&referralCode='+referralCode;
        
        // Create a dummy input element to copy the text
        var dummyInput = document.createElement('input');
        document.body.appendChild(dummyInput);
        dummyInput.setAttribute('value', referralLink);
        dummyInput.select();
        document.execCommand('copy');
        document.body.removeChild(dummyInput);

        // Display a success message
        Swal.fire(
                  'Referral!',
                  text,
                  'success'
               );
    }

    // Add event listener to referral button
    document.getElementById('referralButton').addEventListener('click', function(event) {
        // Prevent default action
        event.preventDefault();

        // Check if it's a mobile device or desktop
        if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            // Open mobile share intent
            openMobileShare();
        } else {
            // If it's not a mobile device, copy referral link to clipboard
            copyToClipboard('Referral link copied to clipboard! Now you can paste this link on Whatsapp Group or other apps.');
        }
    });
</script>
@stop