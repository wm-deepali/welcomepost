@extends('website.layout.layout')
@section('content')
<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>
<!--PRICING DETAILS-->
<section class="blog-body mt-5 pt-5">
    <div class="container">
        <div class="row">
            <div class="login-main">
                <div class="log-bor">&nbsp;</div>
                <div class="log log-1">
                    <div class="login login-new">
                        @if (session('success'))
                            <h5 style="color:green;">{{ Session::get('success') }}</h5>
                            @php Session::forget('success'); @endphp
                        @endif
                        <h4>Subscription Payment </h4>
                        @php
				            $result         = DB::table('subscriptions')->where('id',$subscriptionid)->get();
				            $offered_price  = $result[0]->offered_price;
				            $id             = $result[0]->id;
				        @endphp
				        <form id="login_form" name="login_form" method="post" action="{{url('user-subscription-payment')}}">
				        @csrf
                            <div class="form-group">
                                <input type="text" autocomplete="off" name="card" id="email_id" class="form-control" placeholder="Enter Card No*" title="Card No"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="16"/>
                            </div>
                            
                            <div class="form-group">
                                <input type="text" autocomplete="off" name="cvv" id="email_id" class="form-control" placeholder="Enter CVV*" 
                                title="CVV" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="3" minlength="3"/>
                            </div>
                            <div class="form-group" id="expiration-date" style="width: 100% !important;">
                                <label>Expiry Date</label>
                                    <div class="row">
                                        <div class="col-sm-6 col-md-6">
                                            <select name="expiry_month" class="form-control">
                                                <option value="January">January</option>
                                                <option value="February">February </option>
                                                <option value="March">March</option>
                                                <option value="April">April</option>
                                                <option value="May">May</option>
                                                <option value="June">June</option>
                                                <option value="July">July</option>
                                                <option value="August">August</option>
                                                <option value="September">September</option>
                                                <option value="October">October</option>
                                                <option value="November">November</option>
                                                <option value="December">December</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <select name="expiry_year" class="form-control">
                                                
                                                <option value="2024"> 2024</option>
                                                <option value="2025"> 2025</option>
                                                <option value="2026"> 2026</option>
                                                <option value="2027"> 2027</option>
                                                <option value="2028"> 2028</option>
                                                <option value="2029"> 2029</option>
                                                <option value="2030"> 2030</option>
                                                <option value="2031"> 2031</option>
                                                <option value="2032"> 2032</option>
                                                <option value="2033"> 2033</option>
                                                <option value="2034"> 2034</option>
                                                <option value="2035"> 2035</option>
                                                <option value="2036"> 2036</option>
                                                <option value="2037"> 2037</option>
                                                <option value="2038"> 2038</option>
                                                <option value="2039"> 2039</option>
                                                <option value="2040"> 2040</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <input  type="hidden" name="price" class="form-control" value="<?php echo $offered_price; ?>"/>
                                <input type="hidden" name="id" class="form-control" value="<?php echo $id; ?>" />
                                <input type="hidden" name="payment_method" class="form-control" value="online" />
                                <button type="submit" name="login_submit" value="submit" class="btn btn-primary">Payment</button>
                            </form>
				            <br>
				 <!--           <h4 class="text-center">OR</h4>-->
				            
				 <!--           <form id="login_form" name="login_form" method="post" action="{{url('user-subscription-payment')}}">-->
				 <!--           @csrf-->
     <!--                           <div class="form-group">-->
                   
					<!--<input-->
     <!--                 type="hidden"-->
     <!--                 name="price"-->
     <!--                 class="form-control"-->
     <!--                 value="<?php echo $offered_price; ?>"-->
     <!--                />-->
					 
					<!-- <input-->
     <!--                 type="hidden"-->
     <!--                 name="id"-->
     <!--                 class="form-control"-->
     <!--                 value="<?php echo $id; ?>"-->
     <!--                />-->
					 
					<!-- <input-->
     <!--                 type="hidden"-->
     <!--                 name="payment_method"-->
     <!--                 class="form-control"-->
     <!--                 value="offline"-->
     <!--                />-->
					
     <!--             </div>-->
                  
     <!--             <button-->
     <!--               type="submit"-->
     <!--               name="login_submit"-->
     <!--               value="submit"-->
     <!--               class="btn btn-primary"-->
     <!--             >-->
     <!--              COD-->
     <!--             </button>-->
     <!--           </form>-->

               
              </div>
			  
			  
            </div>
            <div class="log log-2">
              <div class="login login-new">
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
                      id="email_id"
                      class="form-control"
                      placeholder="Email id*"
                      required=""
                    />
                  </div>
                  <div class="form-group">
                    <input
                      type="password"
                      name="password"
                      id="password"
                      class="form-control"
                      placeholder="Password*"
                      required=""
                    />
                  </div>
                  <div class="form-group">
                    <input
                      type="text"
                      onkeypress="return isNumber(event)"
                      autocomplete="off"
                      name="mobile"
                      id="mobile_number"
                      class="form-control"
					   required=""
                      placeholder="Phone"
                    />
                  </div>
				  
				 
				  <div class="form-group ca-sh-user">
                    <select name="country" id="country" class="form-control ca-check-plan"  required="">
                      <option value="india">India</option>
					</select>
                 </div>
				 
				 <div class="form-group ca-sh-user">
                    <select name="state" id="State" class="form-control ca-check-plan"  required="">
                      <option value="">State</option>
						@foreach($states as $key => $orderDetails)
						 <option value="{{$orderDetails->name}}">{{$orderDetails->name}}</option>
						@endforeach
                     
                    </select>
                 </div>
				 
				 <div class="form-group ca-sh-user">
                    <select name="city" id="City" class="form-control ca-check-plan"  required="">
                      <option value="">City</option>
						@foreach($city as $key => $orderDetails)
						 <option value="{{$orderDetails->name}}">{{$orderDetails->name}}</option>
						@endforeach
                     
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
					<label>I accept the Terms & Conditions and Privacy Policy</label>
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
					<div class="s-fac">
						<a href="#">
						<div class="social-cont">
							<img src="{{url('assets/website/images/tabler_device-mobile-message.png')}}" />
						</div>
						<div class="social-login-text">
							Login with Mobile Number
						</div>
					</a>
					</div>
					<div class="s-fac mt-3">
						<a href="#">
						<div class="social-cont">
							<img src="{{url('assets/website/images/logos_google-icon.png')}}" />
						</div>
						<div class="social-login-text">
							Login with Google
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
                <form
                  id="forget_form"
                  name="forget_form"
                  method="post"
                  action="forgot_process.php"
                >
                  <div class="form-group">
                    <input
                      type="email"
                      autocomplete="off"
                      name="email_id"
                      id="email_id"
                      class="form-control"
                      placeholder="Enter email*"
                      pattern="^[\w]{1,}[\w.+-]{0,}@[\w-]{2,}([.][a-zA-Z]{2,}|[.][\w-]{2,}[.][a-zA-Z]{2,})$"
                      title="Invalid email address"
                      required=""
                    />
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
            <!--<div class="log-bot">-->
            <!--  <ul>-->
            <!--    <li>-->
            <!--      <span class="ll-1">Login?</span>-->
            <!--    </li>-->
            <!--    <li>-->
            <!--      <span class="ll-2">Create an account?</span>-->
            <!--    </li>-->
            <!--    <li>-->
            <!--      <span class="ll-3">Forgot password?</span>-->
            <!--    </li>-->
            <!--  </ul>-->
            <!--</div>-->
          </div>
        </div>
      </div>
    </section>
    <!--END PRICING DETAILS-->
	
@endsection