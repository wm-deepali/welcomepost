@extends('website.layout.layout')

@section('title', $page)
@section('content')
<style>
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.4); /* Semi-transparent white */
        backdrop-filter: blur(5px); /* Frosted glass effect */
        z-index: 9998; /* Below the form card */
        pointer-events: all; /* Block clicks */
    }
    #scrollToTopBtn {
        display: none; /* Hidden by default */
        position: fixed;
        bottom: 20px;
        right: 30px;
        z-index: 9999; /* Ensure it's above other elements */
        background-color: #007bff; /* Primary color */
        color: white; /* Text color */
        border: none; /* Remove borders */
        border-radius: 50%; /* Rounded corners */
        padding: 10px; /* Adjust padding for SVG */
        cursor: pointer; /* Pointer cursor on hover */
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); /* Optional: add shadow */
        width: 50px; /* Set a fixed width */
        height: 50px; /* Set a fixed height */
        display: flex; /* Center the SVG */
        align-items: center; /* Center the SVG */
        justify-content: center; /* Center the SVG */
    }
    
    #scrollToTopBtn svg {
        fill: white; /* Set the fill color of the SVG path */
        width: 24px; /* Adjust the size of the SVG */
        height: 24px; /* Adjust the size of the SVG */
    }
    
    #scrollToTopBtn:hover {
        background-color: #0056b3; /* Darker blue on hover */
    }

    /* Ensure the form card is above the overlay */
    .log {
        position: relative;
        z-index: 9999; /* Above the overlay */
    }
    .swal2-container{
        z-index: 10000;
    }
</style>
<div id="preloader">
   <div id="status">&nbsp;</div>
</div>
<!--PRICING DETAILS-->
<section class="blog-body mt-5 pt-5">
   <div class="container">
       <div class="row">
            <div class="login-main">
                <div class="log">
                   <div class="login login-new">
                       @if (session('success'))
                      <h5 style="color:green;">{{ Session::get('success') }}</h5>
                      <?php Session::forget('success');?>
                      @endif
                      @if (session('error'))
                      <h5 style="color:red;">{{ Session::get('error') }}</h5>
                      <?php Session::forget('error');?>
                      @endif
                      @if($errors->any())
                      <h5 style="color:red;">  {{ implode('', $errors->all(':message')) }} </h5>
                      @endif 
                      <h4>Fill Details</h4>
                      <p>
                         Complete the Sign Up with Google
                      </p>
                      <form name="register_form" id="register_form" method="post" action="{{route('first.details.store')}}">
                        @csrf
                         <div class="form-group">
                            <input
                               type="text"
                               autocomplete="off"
                               name="name"
                               id="first_name"
                               class="form-control"
                               placeholder="Name" onkeydown="return /[a-z]/i.test(event.key)"
                               value="{{$user->name}}"
                               required=""
                               disabled=""
                               />
                         </div>
                         <div class="form-group">
                            <input
                               type="email"
                               autocomplete="off"
                               name="email"
                               id="email_id"
                               class="form-control"
                               value="{{$user->email}}"
                               placeholder="Email id*"
                               required=""
                               disabled=""
                               />
                         </div>
                         <div class="form-group">
                            <input
                               type="password"
                               name="password"
                               id="password"
                               class="form-control"
                               placeholder="Password*"
                               pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
                               title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                               required=""
                               />
                         </div>
                         <div class="form-group">
                            <input
                                type="tel"
                                onkeypress="return isNumber(event)"
                                autocomplete="off"
                                name="mobile"
                                id="mobile_number"
                                class="form-control"
                                required=""
                                placeholder="Phone"
                                minlength="10"
                                maxlength="10"
                            />
                            <!--p id="verified_badge" style="color:green;display:none;">Verified</p-->
                        </div>
                        <!--input type="tel" name="mobile" id="mob_in" class="form-control" style="display:none;"/-->
                        <input type="text" name="isValid" id="is_valid_number" value="1" class="form-control" style="display:none;"/>
                        <!--div class="form-group mb-2" id="otp_field" style="display: none;">
                            <input
                                type="text"
                                class="form-control"
                                id="otp"
                                name="otp"
                                placeholder="Enter OTP"
                                maxlength="6"
                            />
                        </div-->
                        <!--button type="button" class="btn btn-primary mb-2" id="send-otp-bt" onclick="sendOTP()">Send OTP</button>
                        <button type="button" class="btn btn-primary mb-2" id="verify-otp-bt" style="display: none;" onclick="verifyOTP()">Verify</button-->
                         <div class="form-group ca-sh-user">
                            <select name="country" id="country" class="form-control ca-check-plan select-sear"  required="">
                               <option value="">Country</option>
                               <option value="1">India</option>
                            </select>
                         </div>
                         <div class="form-group ca-sh-user">
                            <select name="state" id="state" class="form-control ca-check-plan state select-sear"  required="">
                               <option value="">State</option>
                               @foreach($states as $key => $orderDetails)
                               <option value="{{$orderDetails->id}}">{{$orderDetails->name}}</option>
                               @endforeach
                            </select>
                         </div>
                         <div class="form-group ca-sh-user">
                            <select name="city" id="city" class="form-control ca-check-plan city select-sear" data-live-search="true"  required="">
                               <option value="">City</option>
                               
                            </select>
                         </div>
                         <div class="form-group">
                            <input
                               type="text"
                               autocomplete="off"
                               name="pin"
                               id="first_name"
                               class="form-control"
                               placeholder="Pin Code"
                               required=""
                               />
                         </div>
                         @php $adminsetting = \App\Models\Adminsettings::first();  @endphp
                        @if($adminsetting->referal_join == "1")
                         <div class="form-group">
                            <input
                               type="text"
                               autocomplete="off"
                               name="referralto"
                               class="form-control referralCode"
                               placeholder="Enter Referral Code" value="" />
                               <span id="errors" style="color:brown"></span>
                            <input type="text" name="isRef" id="is_valid_refer" value="0" class="form-control" style="display:none;"/>
                         </div>
                      
    
                         <div class="form-group">
                            <input
                               type="text"
                               autocomplete="off"
                               class="form-control"
                               placeholder="Enter Referral Name" id="names" value="" readonly/>
                         </div>
    
                        @endif
                         <div class="term-d-cnd">
                            <div class="form-check">
                               <input
                                  type="checkbox"
                                  autocomplete="off"
                                  name="first_name"
                                  id="first_name"
                                  class="form-check"
                                  required=""
                                  />
                            </div>
                            <div class="labe">
                               <label>I accept the <a href="{{url('pages/13/pages-terms-of-use')}}" target="_blank">Terms & Conditions</a> and <a href="{{url('pages/12/pages-privacy-policy')}}" target="_blank">Privacy Policy</a></label>
                            </div>
                         </div>
                         <button
                            type="submit"
                            name="register_submit"
                            class="btn btn-primary"
                            >
                         Save
                         </button>
                      </form>
                   </div>
                </div>
            </div>
        </div>
   </div>
   
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Function to show the overlay
        function showOverlay() {
            const overlay = document.createElement('div');
            overlay.className = 'overlay';
            document.body.appendChild(overlay);
        }
    
        // Function to hide the overlay
        function hideOverlay() {
            const overlay = document.querySelector('.overlay');
            if (overlay) {
                overlay.remove();
            }
        }
    
        // Assuming you want to show the overlay when the page loads or a specific action occurs
        showOverlay();
    
        // Optional: Add logic to hide the overlay based on some condition or event
        // hideOverlay();
    });
    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
        const scrollToTopBtn = document.getElementById("scrollToTopBtn");
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            scrollToTopBtn.style.display = "block";
        } else {
            scrollToTopBtn.style.display = "none";
        }
    }
    
    // When the user clicks on the button, scroll to the top of the document
    function scrollToTop() {
        window.scrollTo({top: 200, behavior: 'smooth'});
    }
</script>
<script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.all.min.js
"></script>
<link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.min.css
" rel="stylesheet">
<script>
    let referral = '{{ session('referralCode') }}';
    let referralCodeElement = $(".referralCode");
    referralCodeElement.val(referral);
    if (referral) {
        setTimeout(function() {
            referralCodeElement.trigger('keyup'); // Trigger the keyup event
            console.log(referral);
        }, 100);
    }
    $(".referralCode").keyup(function() {
        let referralValue = $(this).val();
        if (referralValue !== "") {
            $.ajax({
                type: "GET",
                url: "{{ url('getusername') }}/" + referralValue,
                success: function(data) {
                    if (data.status == 1) {
                        document.getElementById('is_valid_refer').value = '1';
                        $("#names").val(data.name);
                        $("#errors").html("");
                    } else if (data.status == 3) {
                        document.getElementById('is_valid_refer').value = '1';
                        $("#names").val("");
                        $(".referralCode").val("");
                        $("#errors").html("This referral code(" + referralValue + ") does not fulfill the Active Paid Subscription criteria.");
                    } else {
                        document.getElementById('is_valid_refer').value = '0';
                        $("#names").val("");
                        $("#errors").html("Not Found");
                    }
                }
            });
        } else {
            document.getElementById('is_valid_refer').value = '0';
            $("#names").val("");
            $("#errors").html("");
        }
    });
</script>
<script>
    $(document).on("change", ".state", function() {
        $("#city").html("");
       let state_id = $(this).val();  
       $.ajax({
           url: `{{ URL::to('cities-by-state') }}`,
           type: "post",
           dataType: "json",
           data:{"state_id":state_id, "_token": "{{ csrf_token() }}",},
           success: function(result) {
               console.log(result);
               $("#city").html(result);
              
           }
       });
   });
    function sendOTP() {
        var mobileNumber = document.getElementById('mobile_number').value;
        document.getElementById('mob_in').value = mobileNumber;
        var token = '{{ csrf_token() }}';
        $.post('{{ route("mobileVerify") }}', { _token: token,mobile: mobileNumber }, function(data) {
            // Show OTP field if OTP is sent successfully
            if (data.success) {
                document.getElementById('otp_field').style.display = 'block';
                document.getElementById('send-otp-bt').style.display = 'none';
                document.getElementById('verify-otp-bt').style.display = 'block';
                Swal.fire({
                  title: "OTP Sent!",
                  text: "OTP sent to the entered mobile number...",
                  icon: "success"
                });
            }else{
                document.getElementById('otp_field').style.display = 'none';
                document.getElementById('send-otp-bt').style.display = 'block';
                document.getElementById('verify-otp-bt').style.display = 'none';
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Please retry after sometime.."
                });
            }
        }).fail(function(response) {
            // Handle server-side validation errors
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: response.responseJSON.error
            });
        });
    }
    function verifyOTP() {
        var otp = $('#otp').val();
        var mobileNumber = document.getElementById('mobile_number').value;
        $.ajax({
            url: '{{ route("verifyOTP") }}',
            type: 'POST',
            data: {
                otp: otp,
                mobile: mobileNumber,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                if (data.success) {
                    document.getElementById('is_valid_number').value = '1';
                    document.getElementById('mobile_number').classList.add('verified');
                    document.getElementById('otp_field').style.display = 'none';
                    document.getElementById('send-otp-bt').style.display = 'none';
                    document.getElementById('verify-otp-bt').style.display = 'none';
                    document.getElementById('mobile_number').disabled = true;
                    document.getElementById('verified_badge').style.display = 'block';
                    Swal.fire({
                          title: "OTP Verified!",
                          icon: "success"
                        });
                } else {
                     Swal.fire({
                      icon: "error",
                      title: "Oops...",
                      text: "You entered incorrect otp.."
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    $('#register_form').submit(function(event) {
        if (document.getElementById('is_valid_number').value == '0') {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Phone Number Not Verified',
                text: 'Please verify your phone number before submitting the form.'
            });
        }
        if($('#is_valid_refer').val()==0 && $('.referralCode').val().trim() !== ''){
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Not a valid referral code!',
                text: 'Please check the entered referral code'
            });
        }
    });
</script>
<button onclick="scrollToTop()" id="scrollToTopBtn" title="Go to top"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2 160 448c0 17.7 14.3 32 32 32s32-14.3 32-32l0-306.7L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z"/></svg></button>
@endsection