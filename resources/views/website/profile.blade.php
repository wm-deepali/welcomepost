@extends('website.layout.layout')
@section('title', $page)
@section('content')
@include('website.partials.user_sidebar')
<div class="col-sm-7 col-md-9">
	<div class="templ-rhs-form">
		<div class="d-flex justify-content-between">
            <div class="">
                <h3 class="mt-3">Profile & Account</h3>
            </div>
            <div class="mt-3 mx-4">
                <a href="{{route('user-dashboard')}}" class="border rounded px-3 p-2">Back</a>
            </div>
        </div>
        <hr>
		@if (session('success'))
            <h5 style="color:green;">{{ Session::get('success') }}</h5>
            <?php Session::forget('success');?>
        @endif
        @if($errors->has('error'))
            <div class="alert alert-danger">
                {{ $errors->first('error') }}
            </div>
        @endif
		<form name="home_enquiry_form" id="home_enquiry_form" method="post" enctype="multipart/form-data" action="{{url('update-profile-account')}}">
		     @csrf
		     <div class="row">
			    <div class="col-sm-6">
                    <div class="form-group">
                        <label>Profile Picture</label>
                         <img id="profilePreview" style="display:none;" class="form-control" src=" {{ $customerinfo->image }}" alt="Profile Picture" style="height: 200px; width: auto;">
                        @if(isset($customerinfo->image))
                            <script>
                                document.getElementById('profilePreview').style = 'height:200px;width:auto;';
                            </script>
                        @endif
                        <input type="file" class="form-control" name="image" id="profile_picture" placeholder="Select Profile Picture">
                    </div>
                </div>
			</div>
		    <div class="row">
		        <div class="col-sm-6">
		            <div class="form-group">
        				<label>Full Name</label>
        				<input type="text" value="{{ $customerinfo->name }}" name="name" class="form-control" placeholder="Enter Full Name">
        			</div>
		        </div>
		        
		        <div class="col-sm-6">
		            <div class="form-group">
        				<label>Email ID</label>
        				<input type="email" name="email" value="{{ $customerinfo->email }}" id="email_input"  class="form-control" placeholder="Enter Email Address" disabled="">
        				<small><a href="#" id="openChangeEmailModal">change email id</a></small>
        			</div>
		        </div>
		    </div>
		    
		    <div class="row">
		        <div class="col-sm-6">
		            <div class="form-group">
        				<label>Mobile</label>
        					<div class="col-lg-12 ml-0 p-0">
        				<input style="height:34px;" class="col-8" type="number" name="mobile" value="{{ $customerinfo->mobile }}" id="mobile_input" class="form-control" placeholder="Enter Mobile" disabled=""><br>
        				<small><a href="#" id="openChangeMobileModal">change mobile number</a></small>
        				 </div>
        			</div>
        			
		        </div>
		        <div class="col-sm-6">
		            <div class="form-group">
        				<label>Gender</label>
        				<select class="form-control mb-3" name="gender" >
        				    <option value="">Select Gender</option>
        				    <option value="Male" @if($customerinfo->gender == 'Male') selected @endif >Male</option>
        				    <option value="Female" @if($customerinfo->gender == 'Female') selected @endif>Female</option>
        				</select>
        			</div>
		        </div>
		    </div>
			
			<div class="row">
			    <div class="col-sm-6 mt-10 mb-3">
			        	<div class="form-group">
        				<label>DOB</label>
        				<input type="date" value="{{ $customerinfo->dob }}" name="dob" class="form-control" placeholder="">
        			</div>
			    </div>
			    
			    <div class="col-sm-6 mb-3">
			        <div class="form-group">
        				<label>Country</label>
        				<select name="country" class="form-control select_country">
        				    <option value="">Select Country</option>
        				    @if(isset($countries))
        				    @foreach($countries as $country)
        				   <option value="{{$country->id}}" @if($customerinfo->country == $country->id) selected @endif>{{$country->name}}</option>
        				   @endforeach
        				   @endif
        				</select>
        			</div>
			    </div>
			</div>
			
			<div class="row">
			    <div class="col-sm-6">
			        <div class="form-group">
        				<label>State</label>
        				<select name="state" id="state" class="form-control ca-check-plan">
        				    <option value="">Select State</option>
        				    @if(isset($states))
        				    @foreach($states as $orderDetail)
        				    <option value="{{ $orderDetail->id }}" @if($customerinfo->state == $orderDetail->id) selected @endif>{{ ucfirst($orderDetail->name)}}</option>
        				    @endforeach
        				    @endif
        				</select>
        			</div>
			    </div>
			    
			    <div class="col-sm-6">
			        <div class="form-group">
        				<label>City</label>
        				<select name="city" id="city" class="form-control ca-check-plan">
        				    <option value="">Select City</option>
        				    @if(isset($city))
        				    @foreach($city as $orderDetail)
        				    <option value="{{ $orderDetail->id }}" @if($customerinfo->city == $orderDetail->id) selected @endif>{{ ucfirst($orderDetail->name)}}</option>
        				    @endforeach
        				    @endif
        				</select>
        			</div>
			    </div>
			</div>
			
			<div class="row">
			    <div class="col-sm-12">
    			   <div class="form-group">
        				<label>Address</label>
        				<textarea class="form-control" name="address" rows="4">{{ $customerinfo->address }}</textarea>
        			</div>
        		</div>
			</div>
			
			<hr class="hr_line">
			
			<div class="row">
			    <div class="col-sm-12">
			        <div class="form-group">
			            <label>Website Url</label>
        				<input type="text" value="@if(isset($customerinfo->website)) {{ $customerinfo->website }} @endif" name="website" class="form-control" placeholder="Enter Website">
        			</div>
			    </div>
			    
			</div>
			<div class="row">
			    <div class="col-sm-12">
		            <div class="form-group">
        				<label>Introduction</label>
        				<textarea class="form-control" name="introduction" id="introduction" rows="4">@if(isset($customerinfo->introduction)) {{ $customerinfo->introduction}} @endif</textarea>
        				
        			</div>
		        </div>
			</div>
			
			<hr class="hr_line mt-4">
			
			<div class="row">
			    <div class="col-sm-6">
			        <div class="form-group">
			            <label>Facebook Link</label>
			            <input type="url" class="form-control" value="@if(isset($customerinfo->facebook)) {{ $customerinfo->facebook}} @endif" name="facebook" id="facebook" placeholder="https://">
			        </div>
			    </div>
			    <div class="col-sm-6">
			        <div class="form-group">
			            <label>Twitter Link</label>
			            <input type="url" class="form-control" value="@if(isset($customerinfo->twitter)) {{ $customerinfo->twitter}} @endif" name="twitter" id="twitter" placeholder="https://">
			        </div>
			    </div>
			</div>
			
			<div class="row mt-4">
			    <div class="col-sm-6">
			        <div class="form-group">
			            <label>Whatsapp Link</label>
			            <input type="url" class="form-control" value="@if(isset($customerinfo->whatsapp)) {{ $customerinfo->whatsapp}} @endif" name="whatsapp" id="whatsapp" placeholder="https://">
			        </div>
			    </div>
			    <div class="col-sm-6">
			        <div class="form-group">
			            <label>Youtube Link</label>
			            <input type="url" class="form-control" value="@if(isset($customerinfo->youtube)) {{ $customerinfo->youtube}} @endif" name="youtube" id="youtube" placeholder="https://">
			        </div>
			    </div>
			</div>
			
		<!--	<div class="row mt-4">
			    <div class="col-sm-4">
			        <div class="form-group">
			            <label>Pan Number</label>
			            <input type="text" class="form-control" value="@if(isset($customerinfo->pancard_num)) {{ $customerinfo->pancard_num}} @endif" name="pancard_num"  placeholder="Pan Number">
			        </div>
			    </div>
			    <div class="col-sm-4">
			        <div class="form-group">
			            <label>Pan Card Photo</label>
			            <img id="panPreview" style="display:none;" class="form-control" src=" {{ url('public/admin/images/'.$customerinfo->pancard) }}" alt="Pan Card" style="height: 200px; width: auto;">
                        @if(isset($customerinfo->pancard))
                            <script>
                                document.getElementById('panPreview').style = 'height:200px;width:auto;';
                            </script>
                        @endif
			            <input type="file" id="panSelect" class="form-control" value="@if(isset($customerinfo->pancard)) {{ $customerinfo->pancard}} @endif" name="pancard"  placeholder="Select Pan Card Photo">
			        </div>
			    </div>
			    <div class="col-sm-4">
                    <div class="form-group">
                        <label>QR Code</label>
                         <img id="qrCodePreview" style="display:none;" class="form-control" src=" {{ url('public/admin/images/'.$customerinfo->qr_code_image) }}" alt="QR Code Image" style="height: 200px; width: auto;">
                        @if(isset($customerinfo->qr_code_image))
                            <script>
                                document.getElementById('qrCodePreview').style = 'height:200px;width:auto;';
                            </script>
                        @endif
                        <input type="file" class="form-control" name="qr_code_image" id="qr_code_image" placeholder="Select QR Code">
                    </div>
                </div>
			</div>

			<div class="row mt-4">
			    <div class="col-sm-4">
			        <div class="form-group">
			            <label>Aadhar Number</label>
			            <input type="text" class="form-control" value="@if(isset($customerinfo->adhar_number)) {{ $customerinfo->adhar_number}} @endif" name="adhar_number"  placeholder="Aadhar Number">
			        </div>
			    </div>
			    <div class="col-sm-4">
			        <div class="form-group">
			            <label>Aadhar Upload Image (Front)</label>
			            <img id="aadharFrontPreview" style="display:none;" class="form-control" src=" {{ url('public/admin/images/'.$customerinfo->aadharfronts) }}" alt="Pan Card" style="height: 200px; width: auto;">
                        @if(isset($customerinfo->aadharfronts))
                            <script>
                                document.getElementById('aadharFrontPreview').style = 'height:200px;width:auto;';
                            </script>
                        @endif
			            <input type="file" id="aadharFrontSelect" class="form-control" value="@if(isset($customerinfo->aadharfronts)) {{ $customerinfo->aadharfronts}} @endif" name="aadharfront"  placeholder="Select Aadhar Front Photo">
			        </div>
			    </div>
				<div class="col-sm-4">
			        <div class="form-group">
			            <label>Aadhar Upload Image(Back)</label>
			            <img id="aadharBackPreview" style="display:none;" class="form-control" src=" {{ url('public/admin/images/'.$customerinfo->aadharback) }}" alt="Pan Card" style="height: 200px; width: auto;">
                        @if(isset($customerinfo->aadharback))
                            <script>
                                document.getElementById('aadharBackPreview').style = 'height:200px;width:auto;';
                            </script>
                        @endif
			            <input type="file" id="aadharBackSelect" class="form-control" value="@if(isset($customerinfo->aadharback)) {{ $customerinfo->aadharback}} @endif" name="aadharback"  placeholder="Select Aadhar Back Photo">
			        </div>
			    </div>
			</div> -->
			
			<hr>
			
			<div class="row mt-4">
			    <div class="col-sm-4">
			        <div class="form-group">
			            <label>Bank Name</label>
			            <input type="text" class="form-control" value="@if(isset($customerinfo->bank_name)) {{ $customerinfo->bank_name}} @endif" name="bank_name"  placeholder="ex: State Bank of India">
			        </div>
			    </div>
			     <div class="col-sm-4">
			        <div class="form-group">
			            <label>Bank Branch</label>
			            <input type="text" class="form-control" value="@if(isset($customerinfo->bank_branch)) {{ $customerinfo->bank_branch}} @endif" name="bank_branch"  placeholder="ex: Noida">
			        </div>
			    </div>
			     <div class="col-sm-4">
			        <div class="form-group">
			            <label>Account Name</label>
			            <input type="text" class="form-control" value="@if(isset($customerinfo->account_name)) {{ $customerinfo->account_name}} @endif" name="account_name"  placeholder="ex: Adil Khan">
			        </div>
			    </div>
		    </div>
		    
		    <div class="row mt-4">
			    <div class="col-sm-4">
			        <div class="form-group">
			            <label>Account Number</label>
			            <input type="text" class="form-control" value="@if(isset($customerinfo->account_number)) {{ $customerinfo->account_number}} @endif" name="account_number"  placeholder="ex: 123456789101112">
			        </div>
			    </div>
			     <div class="col-sm-4">
			        <div class="form-group">
			            <label>Confirm Account Number</label>
			            <input type="text" class="form-control" value="@if(isset($customerinfo->confirm_acct_num)) {{ $customerinfo->confirm_acct_num}} @endif" name="confirm_acct_num"  placeholder="ex: 123456789101112">
			        </div>
			    </div>
			     <div class="col-sm-4">
			        <div class="form-group">
			            <label>IFSC Code</label>
			            <input type="text" class="form-control" value="@if(isset($customerinfo->account_ifsc)) {{ $customerinfo->account_ifsc}} @endif" name="account_ifsc"  placeholder="ex: SBI00786L">
			        </div>
			    </div>
		    </div>
			
			<button type="submit" name="home_enquiry_submit" class="btn btn-primary">Update Profile</button>
		</form>
	</div>
</div>
</section>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        document.getElementById('openChangeEmailModal').addEventListener('click', function(event) {
			event.preventDefault();
			Swal.fire({
				title: 'Change Email',
				html:
					'<input type="password" id="currentPassword" class="swal2-input" placeholder="Current Password">' +
					'<input type="email" id="newEmail" class="swal2-input" placeholder="New Email">' +
					'<button id="sendEmailOtpBtn" class="btn btn-primary mt-2">Send OTP</button>' +
					'<input type="text" id="emailOtp" class="swal2-input" placeholder="Enter OTP" style="display: none;">',
				focusConfirm: false,
				showCancelButton: true,
				confirmButtonText: 'Verify',
				preConfirm: () => {
					const emailOtp = Swal.getPopup().querySelector('#emailOtp').value;
					const newEmail = Swal.getPopup().querySelector('#newEmail').value;
					const currentPassword = Swal.getPopup().querySelector('#currentPassword').value;
					if (!emailOtp) {
						Swal.showValidationMessage('Please enter the OTP sent to your new email address');
					}
					return { emailOtp: emailOtp, newEmail: newEmail, currentPassword: currentPassword };
				},
				didOpen: () => {
					const sendEmailOtpBtn = Swal.getPopup().querySelector('#sendEmailOtpBtn');
					sendEmailOtpBtn.addEventListener('click', function() {
						const newEmail = Swal.getPopup().querySelector('#newEmail').value;
						const currentPassword = Swal.getPopup().querySelector('#currentPassword').value;
						if (newEmail && currentPassword) {
							$.ajax({
								url: '{{ route('change.email') }}',
								type: 'POST',
								dataType: 'json',
								headers: {
									'X-CSRF-TOKEN': '{{ csrf_token() }}'
								},
								data: {
									currentEmail: '{{ $customerinfo->email }}',
									newEmail: newEmail,
									currentPassword: currentPassword
								},
								success: function(data) {
									console.log(data);
									Swal.getPopup().querySelector('#sendEmailOtpBtn').style.display = 'none';
									Swal.getPopup().querySelector('#emailOtp').style.display = 'block';
								},
								error: function(xhr) {
									console.log()
									Swal.showValidationMessage(xhr.responseJSON.message);
								}
							});
						} else {
							Swal.showValidationMessage('Please enter a valid email and current password');
						}
					});
				}
			}).then((result) => {
				if (result.isConfirmed) {
					const emailOtp = result.value.emailOtp;
					const newEmail = result.value.newEmail;
					const currentPassword = result.value.currentPassword;
					$.ajax({
						url: '{{ route('change.email') }}',
						type: 'POST',
						dataType: 'json',
						headers: {
							'X-CSRF-TOKEN': '{{ csrf_token() }}'
						},
						data: {
							otp: emailOtp,
							currentEmail: '{{ $customerinfo->email }}',
							newEmail: newEmail,
							currentPassword: currentPassword
						},
						success: function(data) {
							console.log(data);
							if (data.message === 'Email updated successfully.') {
								Swal.fire('Success!', 'Your email has been updated.', 'success').then(() => {
									location.reload();
								});
							} else {
								Swal.fire('Error', data.message, 'error');
							}
						},
						error: function(xhr) {
							Swal.fire('Error', 'Failed to update email. Please try again.', 'error');
						}
					});
				}
			});
		});
		
        document.getElementById('openChangeMobileModal').addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Change Mobile Number',
                html:
                    '<input type="password" id="currentPassword" class="swal2-input" placeholder="Current Password">' +
                    '<input type="tel" id="newMobile" class="swal2-input" placeholder="New Mobile Number">' +
                    '<button id="sendMobileOtpBtn" class="btn btn-primary mt-2">Send OTP</button>' +
                    '<input type="text" id="mobileOtp" class="swal2-input" placeholder="Enter OTP" style="display: none;">',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Verify',
                preConfirm: () => {
                    const mobileOtp = Swal.getPopup().querySelector('#mobileOtp').value;
                    const newMobile = Swal.getPopup().querySelector('#newMobile').value;
                    const currentPassword = Swal.getPopup().querySelector('#currentPassword').value;
                    if (!mobileOtp) {
                        Swal.showValidationMessage('Please enter the OTP sent to your new mobile number');
                    }
                    return { mobileOtp: mobileOtp, newMobile: newMobile, currentPassword: currentPassword };
                },
                didOpen: () => {
                    const sendMobileOtpBtn = Swal.getPopup().querySelector('#sendMobileOtpBtn');
                    sendMobileOtpBtn.addEventListener('click', function() {
                        const newMobile = Swal.getPopup().querySelector('#newMobile').value;
                        const currentPassword = Swal.getPopup().querySelector('#currentPassword').value;
                        if (newMobile && currentPassword) {
                            $.ajax({
                                url: '{{ route('change.mobileVerify') }}',
                                type: 'POST',
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                data: {
                                    mobile: newMobile,
                                    userId: '{{ $customerinfo->id }}',
                                    currentPassword: currentPassword
                                },
                                success: function(data) {
                                    console.log(data);
                                    if (data.success) {
                                        Swal.getPopup().querySelector('#sendMobileOtpBtn').style.display = 'none';
                                        Swal.getPopup().querySelector('#mobileOtp').style.display = 'block';
                                    } else {
                                        Swal.showValidationMessage(data.message);
                                    }
                                },
                                error: function(xhr) {
                                    Swal.showValidationMessage('Failed to send OTP. Please try again.');
                                }
                            });
                        } else {
                            Swal.showValidationMessage('Please enter a valid mobile number and current password');
                        }
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const mobileOtp = result.value.mobileOtp;
                    const newMobile = result.value.newMobile;
                    const currentPassword = result.value.currentPassword;
                    $.ajax({
                        url: '{{ route('change.verifyOTP') }}',
                        type: 'POST',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            otp: mobileOtp,
                            userId: '{{ $customerinfo->id }}',
                            mobile: newMobile,
                            currentPassword: currentPassword
                        },
                        success: function(data) {
                            console.log(data);
                            if (data.success) {
                                Swal.fire('Success!', 'Your mobile number has been updated.', 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', 'Invalid OTP. Please try again.', 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error', 'Failed to update mobile number. Please try again.', 'error');
                        }
                    });
                }
            });
        });

        
    });
</script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on("change", "#state", function() {
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
</script>
<script>
document.getElementById('qr_code_image').addEventListener('change', function(event) {
    if (event.target.files && event.target.files[0]) {
        document.getElementById('qrCodePreview').style = 'height:200px;width:auto;';
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('qrCodePreview').src = e.target.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
});
document.getElementById('profile_picture').addEventListener('change', function(event) {
    if (event.target.files && event.target.files[0]) {
        document.getElementById('profilePreview').style = 'height:200px;width:auto;';
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePreview').src = e.target.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
});
document.getElementById('panSelect').addEventListener('change', function(event) {
    if (event.target.files && event.target.files[0]) {
        document.getElementById('panPreview').style = 'height:200px;width:auto;';
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('panPreview').src = e.target.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
});
document.getElementById('aadharFrontSelect').addEventListener('change', function(event) {
    if (event.target.files && event.target.files[0]) {
        document.getElementById('aadharFrontPreview').style = 'height:200px;width:auto;';
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('aadharFrontPreview').src = e.target.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
});
document.getElementById('aadharBackSelect').addEventListener('change', function(event) {
    if (event.target.files && event.target.files[0]) {
        document.getElementById('aadharBackPreview').style = 'height:200px;width:auto;';
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('aadharBackPreview').src = e.target.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
});
</script>
@stop