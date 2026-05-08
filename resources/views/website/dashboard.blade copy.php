@extends('website.layout.layout')
@section('title', $page)
@section('content')
@include('website.partials.user_sidebar')
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
      width: 308px;
      max-width: 100%;
   }
</style>
<div class="col-sm-7 col-md-9">
   <div class="profile-cont">
      <h3>Profile & Accounts</h3>
      <div class="row">
         <div class="col-sm-12 col-md-6 mt-10">
            <div class="add-types">
               <div class="add-heading">Full Name</div>
               <div class="select-add-type">{{$customerinfo->name}}</div>
            </div>
         </div>
         <div class="col-sm-12 col-md-6 col-md-6 mt-10">
            <div class="add-types">
               <div class="add-heading">Email Id</div>
               <div class="select-add-type">{{$customerinfo->email}} </div>
            </div>
         </div>
         <div class="col-sm-12 col-md-6 col-md-6 mt-10">
            <div class="add-types">
               <div class="add-heading">Mobile Number</div>
               <div class="select-add-type">+91-{{$customerinfo->mobile}}</div>
            </div>
         </div>

         @if(!empty($customerinfo->referralto))
         <div class="col-sm-12 col-md-6 col-md-6 mt-10">
            <div class="add-types">
               <div class="add-heading">Referral By</div>
               <div class="select-add-type">{{$customerinfo->referralto}}</div>
            </div>
         </div>
         @endif


         <div class="col-sm-12 col-md-6 col-md-6 mt-10">
            <div class="add-types">
               <div class="add-heading">Referral Id</div>
               <div class="Referral_id select-add-type">
                  <div class="copy-text">
                     <input type="text"  class="Referral_id text" value="{{$customerinfo->referral_code}}" />
                     <button>copy</button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="profile-cont mt-10">
      <h3>About me</h3>
      <div class="row">
         <!--<div class="col-sm-12 col-md-6 mt-10">-->
         <!--    <div class="add-types">-->
         <!--        <div class="add-heading"> Gender</div>-->
         <!--        <div class="select-add-type">{{$customerinfo->gender}}</div>-->
         <!--    </div>-->
         <!--</div>-->
         <!--<div class="col-sm-12 col-md-6 col-md-6 mt-10">-->
         <!--    <div class="add-types">-->
         <!--        <div class="add-heading">Date of Birth</div>-->
         <!--        <div class="select-add-type"> {{$customerinfo->dob}}</div>-->
         <!--    </div>-->
         <!--</div>-->
         <div class="col-sm-12 col-md-6 col-md-6 mt-10">
            <div class="add-types">
               <div class="add-heading"> Full Address</div>
               <div class="select-add-type">{{$customerinfo->address}}</div>
            </div>
         </div>
         <div class="col-sm-12 col-md-6 col-md-6 mt-10">
            <div class="add-types">
               <div class="add-heading">Country</div>
               <div class="select-add-type">{{$customerinfo->country}}</div>
            </div>
         </div>
         <div class="col-sm-12 col-md-6 col-md-6 mt-10">
            <div class="add-types">
               <div class="add-heading"> State</div>
               <div class="select-add-type">{{$customerinfo->state}}</div>
            </div>
         </div>
         <div class="col-sm-12 col-md-6 col-md-6 mt-10">
            <div class="add-types">
               <div class="add-heading">City</div>
               <div class="select-add-type">{{$customerinfo->city}}</div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
</div>
</section>
<script>
   let copyText = document.querySelector(".copy-text");
   copyText.querySelector("button").addEventListener("click", function () {
   	let input = copyText.querySelector("input.text");
   	input.select();
   	document.execCommand("copy");
   	copyText.classList.add("active");
   	window.getSelection().removeAllRanges();
   	setTimeout(function () {
   		copyText.classList.remove("active");
   	}, 2500);
   });
   
   
</script>
@endsection