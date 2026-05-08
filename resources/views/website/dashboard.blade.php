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
   .add-heading {
       font-weight: bold;
   }
   .payment-method {
        color:#000;
   }
   .payment-method.active {
        background:#007bff;
        color:#fff;
   }
   .change-option {
       font-size: 12px;
   }
   .fa.fa-times:before {
        font-family: 'FontAwesome';
        position: relative;
    }
</style>
<style>
    /* Style to make button look like text link */
    .change-option {
        background: none;
        border: none;
        color: blue; /* Adjust color as needed */
        text-decoration: underline;
        cursor: pointer;
    }
    .change-option:focus, .change-option:hover {
        outline: none;
        text-decoration: underline;
    }
</style>
<!--<section>-->
    <div class="col-sm-7 col-md-9">
        <div class="profile-cont mb-4">
            <div class="d-flex justify-content-between">
                <div class="">
                    <h3 class="mt-3">Profile & Accounts</h3>
                </div>
                <div class="mt-3 mx-4">
                    <a href="{{route('user-profile')}}" class="border rounded px-3 p-2">Edit</a>
                </div>
            </div>
            @if(!isset($customerinfo->password))
             <div class="row ml-3 mt-2">
                <p>Create your password, <a href="{{route('first.details')}}"> Click Here</a></p>
            </div>
            @endif
            <hr>
            <div class="row mb-3">
                <div class="col-lg-12">

                    <div class="row">
                        <div class="col-sm-12 col-lg-6 col-md-6 mb-2">
                            <div class="d-flex">
                                <div class="">
                                @if (!isset($customerinfo->image))
                                 <img src="{{ asset('public/admin/') }}/user.png" alt="avatar" class="img-fluid" style="width: 80px;height: 80px;" />
                                 @elseif($customerinfo->google_id!="")
                                    <img src="{{ $customerinfo->image }}" style="width: 80px;height: 80px;">
                                 @else
                                 <img src="{{ $customerinfo->image }}" style="width: 80px;height: 80px;">
                                 @endif
                                </div>
                                <div class="add-types ml-5">
                                <div class="add-heading">Full Name</div>
                                <div class="select-add-type">{{$customerinfo->name ?? "-"}}</div>
                                </div>
                            </div>
                        </div>
                        <!--<div class="col-sm-12 col-lg-6 col-md-6 mb-2">-->
                        <!--    <div class="add-types">-->
                        <!--    <div class="add-heading">User Type</div>-->
                        <!--    <div class="select-add-type">{{$customerinfo->user_type ?? "--"}}</div>-->
                        <!--    </div>-->
                        <!--</div>-->
                    </div>

                    <div class="row">
                        @if(!empty($customerinfo->referralto))
                        <div class="col-sm-12 col-md-6 col-md-6 mt-10">
                            <div class="add-types">
                                <div class="add-heading">Referral By</div>
                                <div class="select-add-type">{{$customerinfo->referralto ?? ""}}</div>
                            </div>
                            
                        </div>
                        @endif
                        <div class="col-sm-12 col-lg-6 col-md-6 mt-10">
                            <div class="add-types">
                            <div class="add-heading">Referral Id</div>
                            <div class="Referral_id select-add-type">
                                <div class="copy-text">
                                    <input type="text"  class="Referral_id text" value="{{$adminsetting->referal_join == 1 ? $customerinfo->referral_code ?? "" :'Temporary unavailable.'}}" />
                                    <button>copy</button>
                                </div>
                            </div>
                            </div>
                            @if($adminsetting->is_active_ad_referral == 1)
                            <p class="text">Need to have atleast one active paid subscription before you share the referral link!</p>
                            @endif
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-12 col-lg-6 col-md-6 mt-5">
                            <div class="add-types">
                                <div class="add-heading">Email Id</div>
                                <div class="select-add-type">{{$customerinfo->email ?? ""}} </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-6 col-md-6 mt-5">
                            <div class="add-types">
                                <div class="add-heading">Mobile Number</div>
                                <div class="select-add-type">+91-{{$customerinfo->mobile ?? '--'}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-lg-6 col-md-6 mt-5">
                            <div class="add-types">
                            <div class="add-heading">Gender</div>
                            <div class="select-add-type">{{$customerinfo->gender ?? '--' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-md-6 mt-5">
                            <div class="add-types">
                            <div class="add-heading">Date of Birth</div>
                            <div class="select-add-type">{{$customerinfo->dob	 ?? 'mm/dd/yyyy'}}</div>
                            </div>
                        </div>
                    </div>
                   
                   
                    <div class="row mt-5">
                        <div class="col-sm-12 col-lg-6 col-md-6 mb-3">
                            <div class="add-types">
                                <div class="add-heading">Full Address</div>
                                <div class="select-add-type">{{$customerinfo->address ?? "--"}}</div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-b">
                            <div class="add-types">
                                <div class="add-heading">Country</div>
                                <div class="select-add-type">{{$customerinfo->countries->name ?? "--"}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <div class="add-types">
                                <div class="add-heading"> State</div>
                                <div class="select-add-type">{{$customerinfo->states->name ?? "--"}}</div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <div class="add-types">
                                <div class="add-heading">City</div>
                                <div class="select-add-type">{{$customerinfo->cities->name ?? "--"}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 2 -->
        <div class="profile-cont mt-10 mb-3">
            <h3 class="mt-3">My Identity</h3>
            <hr>
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-sm-12 col-lg-6 col-md-6 mb-3">
                            <div class="add-types">
                                <div class="add-heading">Aadhar Number</div>
                                <div class="select-add-type">{{$customerinfo->adhar_number ?? 'Not uploaded'}}</div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-6 col-md-6 mb-3">
                            <div class="add-types">
                                <div class="add-heading">PAN Number</div>
                                <div class="select-add-type">{{$customerinfo->pancard_num ?? 'Not uploaded'}}</div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-lg-6 col-md-6 mb-3">
                            <div class="add-types">
                                <div class="">
                                    <label for="formFile" class="add-heading form-label"> Upload Image(Front)</label>
                                <div>
                                    @if (empty($customerinfo->aadharfronts))
                                    <img src="https://www.asiaoceania.org/aogs2021/img/no_uploaded.png" style="width: 150px;height: 80px;">
                                     @else
                                     <img src="{{ asset('public/admin/images/') . '/' . $customerinfo->aadharfronts }}" style="width: 150px;height: 80px;">
                                     @endif
                                </div>

                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-6 col-md-6 mb-3">
                            <div class="add-types">
                                <div class="">
                                    <label for="formFile" class="add-heading form-label"> Upload PAN Card Image</label>
                                    <div>
                                    @if (empty($customerinfo->pancard))
                                    <img src="https://www.asiaoceania.org/aogs2021/img/no_uploaded.png" style="width: 150px;height: 80px;">
                                     @else
                                     <img src="{{ asset('public/admin/images/') . '/' . $customerinfo->pancard }}" style="width: 150px;height: 80px;">
                                     @endif
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-lg-6 col-md-6 mb-3">
                            <div class="add-types">
                                <div class="">
                                    <label for="formFile" class="add-heading form-label"> Upload Image(Back)</label>
                                    <div>
                                    @if (empty($customerinfo->aadharback))
                                    <img src="https://www.asiaoceania.org/aogs2021/img/no_uploaded.png" style="width: 150px;height: 80px;">
                                     @else
                                     <img src="{{ asset('public/admin/images/') . '/' . $customerinfo->aadharback }}" style="width: 150px;height: 80px;">
                                     @endif
                                </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-sm-12 col-lg-6 col-md-6 mb-3">
                            <div class="add-types">
                                <div class="">
                                    <label for="formFile" class="add-heading form-label"> Upload PAN Card Image(Back)</label>
                                    <input class="form-control" type="file" id="formFile">
                                </div>
                            </div>
                        </div> -->
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 mt-10"></div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mt-10"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 mt-10"></div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mt-10"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</section>
<script>
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
@endsection