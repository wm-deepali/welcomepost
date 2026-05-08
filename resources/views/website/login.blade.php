@extends('website.layout.layout')
@section('content')
<style>
   .iti.iti--allow-dropdown {
   width: 100% !important;
   }
   
   .welcome-dialog {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
    
    .content {
        text-align: center;
    }
    
    .content h2 {
        font-size: 24px;
        margin-bottom: 10px;
    }
    
    .content p {
        font-size: 18px;
    }
    
    .verified {
        border-color: green !important;
    }

</style>

<!-- Preloader -->
<div id="preloader">
   <div id="status">&nbsp;</div>
</div>
<!--PRICING DETAILS-->
<section class="blog-body mt-5 pt-5">
   <div class="container">
       <div id="welcomeDialog" class="welcome-dialog" style="display: none;">
           <span class="close" style="cursor: pointer;" onclick="closeDialog()">&times;</span>
            <div class="content">
                <h2>Congratulations!</h2>
                <p>You received a welcome bonus of ₹<span id="welcomeAmount"></span></p>
                <div class="text-center">
                    <a href="{{route('purchase-subscription')}}" class="btn btn-info">Buy Subscription</a>
                </div>
            </div>
        </div>
        @if(session('loginAttepmt'))
            <div id="attemptDialog" class="welcome-dialog">
               <span class="close" style="cursor: pointer;" onclick="closeAttemptDialog()">&times;</span>
                <div class="content">
                    <h2>Account Locked!</h2>
                    <p>Your account has been locked due to 3 failed attempts,<br> please contact admin to get it unlocked <a href="mailto:welcomepostdesk@gmail.com" >welcomepostdesk@gmail.com</a></p>
                </div>
            </div>
            <?php session()->forget('loginAttepmt');?>
        @endif
      <div class="row">
         <div class="login-main">
            <div class="log-bor">&nbsp;</div>
            <div class="log log-1">
               <div class="login login-new">
                  @if (session('success'))
                  <h5 style="color:green;">{{ Session::get('success') }}</h5>
                  <?php Session::forget('success');?>
                  @endif
                  @if (session('error'))
                  <h5 style="color:red;">{{ Session::get('error') }}
                  @if(session('verifyCheck'))
                  <a href="#EmailVerifymodal" data-toggle="modal" data-target="#EmailVerifymodal">Verify Email</a>
                  @endif
                  </h5>
                  <?php session()->forget('verifyCheck');?>
                  <?php Session::forget('error');?>
                  @endif
                  @if($errors->any())
                  <h5 style="color:red;">  {{ implode('', $errors->all(':message')) }} </h5>
                  @endif 
                 
                    <div class="modal fade" id="EmailVerifymodal" tabindex="-1" role="dialog" aria-labelledby="EmailVerifymodal" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="EmailVerifymodal">Send Verify Email</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form  action="{{url('send-verify-link')}}" method="post">
                     @csrf
                          <div class="modal-body">
                              
                            <div class="form-group">
                                <input
                                   type="email"
                                   autocomplete="off"
                                   name="email_verify"
                                   id="email_id_verify"
                                   class="form-control"
                                   placeholder="Email id*"
                                   required=""
                                   />
                                <span id="email_verify_feedback" style="display:none; color:red;"></span>
                             </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary sendLink">Send Link</button>
                          </div>
                          </form>
                        </div>
                      </div>
                    </div>
                 
                  <h4>Please Login Here !</h4>
                  <form id="login_form_mobile" name="login_form" method="post" action="{{url('login-with-mobile')}}" style="display:none;">
                     @csrf
                     <div class="form-group">
                        <input type="text" autocomplete="off" name="mobile" id="mobile" class="form-control" placeholder="Enter mobile number*" pattern="^[\w]{1,}[\w.+-]{0,}@[\w-]{2,}([.][a-zA-Z]{2,}|[.][\w-]{2,}[.][a-zA-Z]{2,})$" title="Enter mobile number" required=""/>
                     </div>
                     <div class="form-group">
                        <input
                           type="password"
                           name="password"
                           id="password"
                           class="form-control"
                           placeholder="Enter password*"
                           required=""
                           />
                     </div>
                     <button
                        type="submit"
                        name="login_submit"
                        value="submit"
                        class="btn btn-primary"
                        >
                     Sign in
                     </button>
                  </form>
                  <form id="login_form" name="login_form" method="post" action="{{url('user-login')}}">
                     @csrf
                     <div class="form-group">
                        <input type="text" autocomplete="off" name="email" id="email_id" class="form-control" placeholder="Enter email*" pattern="^[\w]{1,}[\w.+-]{0,}@[\w-]{2,}([.][a-zA-Z]{2,}|[.][\w-]{2,}[.][a-zA-Z]{2,})$" title="Enter email address" required=""/>
                     </div>
                     <div class="form-group">
                        <input
                           type="password"
                           name="password"
                           id="password"
                           class="form-control"
                           placeholder="Enter password*"
                           required=""
                           />
                     </div>
                     <button
                        type="submit"
                        name="login_submit"
                        value="submit"
                        class="btn btn-primary"
                        >
                     Sign in
                     </button>
                  </form>
                  <!-- SOCIAL MEDIA LOGIN -->
                  <div class="soc-log">
                     <div class="or">OR</div>
                   <!--  <div class="s-fac" id="mobile-tab">
                        <a href="#">
                           <div class="social-cont">
                              <img src="{{url('assets/website/images/tabler_device-mobile-message.png')}}" />
                           </div>
                           <div class="social-login-text">
                              Login with Mobile Number
                           </div>
                        </a>
                     </div> -->
                     <div class="s-fac" id="email-tab" style="display:none;">
                        <a href="#">
                           <div class="social-cont">
                              <img src="{{url('assets/website/images/tabler_device-mobile-message.png')}}" />
                           </div>
                           <div class="social-login-text">
                              Login with Email Address
                           </div>
                        </a>
                     </div>
                     <div class="s-fac mt-3">
                        <a href="{{ route('login.google') }}">
                           <div class="social-cont">
                              <img src="{{url('assets/website/images/logos_google-icon.png')}}" />
                           </div>
                           <div class="social-login-text">
                              Login with Google
                           </div>
                        </a>
                     </div>
                     <!-- <ul>
                        <li>
                          <a href="javascript:void(0);" class="login-fb"
                            ><img src="images/icon/facebook.png" /> Continue with
                            Facebook</a
                          >
                        </li>
                        <li>
                        <a href="javascript:void(0);" class="login-fb"
                        ><img src="images/icon/facebook.png" /> Continue with
                        Facebook</a
                        >
                        </li>
                        </ul> -->
                  </div>
                  <!-- END SOCIAL MEDIA LOGIN -->
               </div>
            </div>
            <div class="log log-2">
               <div class="login login-new">
                   <p>Welcome Post - A Best Classified & Online Earning Platform</p>
                  <h4>Create an account</h4>
                  <p>
                     Don't have an account? Create your account. It's take less
                     then a minutes
                  </p>
                  <form
                     name="register_form"
                     id="register_form"
                     method="post"
                     action="{{url('user-signup')}}"
                     >
                     @csrf
                     <div class="form-group">
                        <input
                           type="text"
                           autocomplete="off"
                           name="name"
                           id="first_name"
                           class="form-control"
                           placeholder="Name"
                           required=""
                           />
                     </div>
                     <div class="form-group">
                        <input
                           type="email"
                           autocomplete="off"
                           name="email"
                           id="email_id_register"
                           class="form-control"
                           placeholder="Email id*"
                           required=""
                           />
                        <span id="email_feedback" style="display:none; color:red;">Email already exists</span>
                     </div>
                     <div class="form-group">
                        <input
                           type="password"
                           name="password"
                           id="password"
                           class="form-control"
                           placeholder="Create Password*"
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
                    </div>
                    <button type="button" class="btn btn-primary mb-2" id="send-otp-bt" onclick="sendOTP()">Send OTP</button>
                    <button type="button" class="btn btn-primary mb-2" id="verify-otp-bt" style="display: none;" onclick="verifyOTP()">Verify</button0-->
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
                           placeholder="Enter Referral Code" value="{{ Session::get('referral') ?? '' }}" />
                           <span id="errors" style="color:brown"></span>
                           
                       <input type="text" name="isRef" id="is_valid_refer" value="0" class="form-control" style="display:none;"/>
                     </div>
                     
                  

                     <div class="form-group">
                        <input
                           type="text"
                           autocomplete="off"
                           class="form-control"
                           placeholder="Enter Referral Name" id="names" value="{{ Session::get('name') ?? '' }}" readonly/>
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
                     Register Now
                     </button>
                  </form>
                  <!-- SOCIAL MEDIA LOGIN -->
                  <div class="soc-log">
                     <div class="or">OR</div>
                     <!--<div class="s-fac" id="mobile-tab">-->
                     <!--	<a href="#">-->
                     <!--	<div class="social-cont">-->
                     <!--		<img src="{{url('assets/website/images/tabler_device-mobile-message.png')}}" />-->
                     <!--	</div>-->
                     <!--	<div class="social-login-text">-->
                     <!--		Login with Mobile Number-->
                     <!--	</div>-->
                     <!--</a>-->
                     <!--</div>-->
                     <div class="s-fac mt-3 signup-google" id="google_div">
                        <a id="google-signup-link" href="#">
                           <div class="social-cont">
                              <img src="{{url('assets/website/images/logos_google-icon.png')}}" />
                           </div>
                           <div class="social-login-text">
                              Signup with Google
                           </div>
                        </a>
                     </div>
                  </div>
                  <!-- END SOCIAL MEDIA LOGIN -->
               </div>
            </div>
            <div class="log log-3">
               <div class="login login-new">
                  <h4>Forgot password</h4>
                  <form id="forget_form" name="forget_form" method="post" action="{{ route('forget.password.post') }}">
                     @csrf
                     <div class="form-group">
                        <input
                           type="email"
                           autocomplete="off"
                           name="email"
                           id="email"
                           class="form-control"
                           placeholder="Enter email*"
                           pattern="^[\w]{1,}[\w.+-]{0,}@[\w-]{2,}([.][a-zA-Z]{2,}|[.][\w-]{2,}[.][a-zA-Z]{2,})$"
                           title="Invalid email address"
                           required=""
                           />
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                     </div>
                     <button
                        type="submit"
                        name="forgot_submit"
                        class="btn btn-primary"
                        >
                     Submit
                     </button>
                  </form>
               </div>
            </div>
            
            <div class="log-bot">
               <ul>
                  <li class="login_btn_li" style="display:none;">
                     <span class="ll-1">Login?</span>
                  </li>
                  <li class="create_account">
                     <span class="ll-2">Create an account?</span>
                  </li>
                  <li class="forgot_pswd">
                     <span class="ll-3">Forgot password?</span>
                  </li>
               </ul>
            </div>
         </div>
      </div>
   </div>
</section>
<!--END PRICING DETAILS-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.all.min.js
"></script>
<link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.min.css
" rel="stylesheet">
<script>
            
            $(document).ready(function() {
                $('#email_id_register').on('input change', function() {
                    checkEmailExists();
                });
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
            });

            
            
            $.ajaxSetup({
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
            });
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
           $("#mobile-tab").click(function(){
   	        $("#login_form_mobile").css("display","block");
   	       $("#login_form").css("display","none");
   	       $("#email-tab").css("display","block");
   	       $(this).css("display","none");
           });
           
           $("#email-tab").click(function(){
   	        $("#login_form").css("display","block");
   	       $("#login_form_mobile").css("display","none");
   	       $("#mobile-tab").css("display","block");
   	       $(this).css("display","none");
           });
       $(".ll-2").click(function(){
           $(".login_btn_li").css("display",'block');
           $(".create_account").css("display",'none');
           $(".forgot_pswd").css("display",'none');
           $(".signup-google").css("display",'block');
       });
       
       
       $(".ll-1").click(function(){
           $(".create_account").css("display",'block');
           $(".login_btn_li").css("display",'none');
           $(".forgot_pswd").css("display",'block');
           $("#google_div").css("display",'block');
       });
       
       $(".ll-3").click(function(){
           $(".create_account").css("display",'block');
           $(".login_btn_li").css("display",'block');
           $(".forgot_pswd").css("display",'block');
           $("#google_div").css("display",'block');
       });
    
    function checkEmailExists() {
        var email = $('#email_id_register').val();
        var emailFeedback = $('#email_feedback');
        
        // Simple email validation regex
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    
        if (emailPattern.test(email)) {
            $.ajax({
                url: '{{ route("check-email") }}',
                method: 'POST',
                data: {
                    email: email,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data.exists) {
                        emailFeedback.text('Email already exists').show();
                        $('#email_id').removeClass('is-valid').addClass('is-invalid');
                    } else {
                        emailFeedback.hide();
                        $('#email_id').removeClass('is-invalid').addClass('is-valid');
                    }
                }
            });
        } else {
            emailFeedback.text('Invalid email address').show();
            $('#email_id').removeClass('is-valid').addClass('is-invalid');
        }
    }
       
    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }
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

// Function to handle click event and show/hide elements
function handleClick() {
    $(".log-1").css("display",'none');
     $(".log-2").css("display",'block');
    $(".log-3").css("display",'none');
    $(".login_btn_li").css("display",'block');
   $(".create_account").css("display",'none');
   $(".forgot_pswd").css("display",'none');
   $(".signup-google").css("display",'block');
}

// Check if the parameter "showLogin" exists in the URL
var showLoginParam = getUrlParameter('showLogin');

if (showLoginParam === 'true') {
    // If the parameter exists and its value is "true", execute the handleClick function
    handleClick();
}
</script>
<script>

    document.addEventListener('DOMContentLoaded', function() {
        var welcomeAmount = '{{ session('welcomeAmount') }}';
        var welcomeDialog = document.getElementById('welcomeDialog');
    
        if (welcomeAmount) {
            document.getElementById('welcomeAmount').innerText = welcomeAmount;
            welcomeDialog.style.display = 'block';
        }
    });
    
    function closeDialog() {
        document.getElementById("welcomeDialog").style.display = "none";
    }
    
    function closeAttemptDialog(){
        document.getElementById("attemptDialog").style.display = "none";
    }


   const phoneInputField = document.querySelector("#mobile_number");
   const phoneInput = window.intlTelInput(phoneInputField, {
   initialCountry: "in",
	separateDialCode: true,
   utilsScript:
   "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
   });
</script>
<script>
    $(document).ready(function() {
        var referralCode = getUrlParameter('referralCode');
        if (referralCode) {
            $('.referralCode').val(referralCode);
            setTimeout(function() {
                $(".referralCode").trigger('keyup');
            }, 100);
        }
        $(".referralCode").keyup(function() {
            let referral = $(this).val();
            if (referral !== "") {
                $.ajax({
                    type: "GET",
                    url: "{{url('getusername')}}/" + referral,
                    success: function(data) {
                        if (data.status == 1) {
                            document.getElementById('is_valid_refer').value = '1';
                            $("#names").val(data.name);
                            $("#errors").html("");
                        }else if(data.status == 3){
                            document.getElementById('is_valid_refer').value = '1';
                            $("#names").val("")
                            $(".referralCode").val("")
                            $("#errors").html("This referral code("+referral+") does not fulfill the Active Paid Subscription criteria.");
                        }
                        else {
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
        var googleSignupLink = document.getElementById('google-signup-link');

        // Set the URL for the Google signup link
        if (referralCode) {
            var googleSignupUrl = '{{ route('login.google') }}' + '?referralCode=' + referralCode;
        } else {
            var googleSignupUrl = '{{ route('login.google') }}';
        }
        
        // Update the href attribute of the Google signup link
        googleSignupLink.href = googleSignupUrl;
    });
    $(document).ready(function() {
        $('#email_id_verify').on('input change', function() {
            checkVerifyEmailExists();
        });
        
        
    });
    function checkVerifyEmailExists() {
        var email = $('#email_id_verify').val();
        var emailFeedback = $('#email_verify_feedback');
        
        // Simple email validation regex
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    
        if (emailPattern.test(email)) {
            $.ajax({
                url: '{{ route("check-verify-email") }}',
                method: 'POST',
                data: {
                    email: email,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data.check && data.check == 2) {
                        emailFeedback.text('Email id not exists').show();
                        $('#email_id_verify').removeClass('is-valid').addClass('is-invalid');
                    } 
                    else if (data.check && data.check == 1) {
                        emailFeedback.text('Your e-mail is already verified. You can now login.').show();
                        $('#email_id_verify').removeClass('is-valid').addClass('is-invalid');
                    }
                    
                    else {
                        emailFeedback.hide();
                        $('#email_id_verify').removeClass('is-invalid').addClass('is-valid');
                    }
                }
            });
        } else {
            emailFeedback.text('Invalid email address').show();
            $('#email_id_verify').removeClass('is-valid').addClass('is-invalid');
        }
    }
</script>
@endsection