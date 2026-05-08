@extends('website.layout.layout')
@section('title', $page)
@section('content')
@include('website.partials.user_sidebar')
<div class="col-sm-7 col-md-9">
	<div class="templ-rhs-form">
		<h3>Change Password</h3>
		@if (session('success'))
		<div class="card-body">
			<div class="alert alert-success alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<h5>{{ Session::get('success') }}</h5>
				<?php Session::forget('success');?>
			</div>
		</div>
		@endif
		@if (session('error'))
		<div class="card-body">
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<h5>{{ Session::get('error') }}</h5>
				<?php Session::forget('error');?>
			</div>
		</div>
		@endif
		<form action="{{ route('user-update-password')}}" name="home_enquiry_form" id="home_enquiry_form" method="post" enctype="multipart/form-data">
			 @csrf
			<div class="form-group">
				<label>Enter your old password</label>
				<input type="password" name="old_password" required="required" class="form-control" placeholder="Old password">
			</div>
			<div class="form-group">
				<label>Enter your new password</label>
				<input type="password" name="new_password" class="form-control" placeholder="New password*" required="">
			</div>
			<div class="form-group">
				<label>Enter confirm password*</label>
				<input type="password" name="confirm_password" class="form-control" placeholder="Confirm password*" required="">
			</div>
			<button type="submit" name="home_enquiry_submit" class="btn btn-primary">Change Password</button>
		</form>
	</div>
</div>
</div>
</div>
</section>
@stop