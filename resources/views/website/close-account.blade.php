@extends('website.layout.layout')
@section('title', $page)
@section('content')
@include('website.partials.user_sidebar')
<div class="col-sm-7 col-md-9">
	<div class="templ-rhs-form">
		<h3>Close Account</h3>
		@if (session('success'))
		<div class="card-body">
			<div class="alert alert-success alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<h5 style="font-size: medium;">{{ Session::get('success') }}</h5>
				<?php Session::forget('success');?>
			</div>
		</div>
		@endif
		@if (session('error'))
		<div class="card-body">
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<h5 style="font-size: medium;">{{ Session::get('error') }}</h5>
				<?php Session::forget('error');?>
			</div>
		</div>
		@endif
		<div class="close-account-desc">Are you sure want to close your account? You can activate it anytime.</div>
		<form name="close accopunt" action="{{route('deactivate-account')}}" id="home_enquiry_form" method="post" enctype="multipart/form-data">
			@csrf
			<div class="form-group">
				<label>Enter your password</label>
				<input type="password" name="old_password" required="required" class="form-control" placeholder="Old password">
			</div>
			<button type="submit" name="home_enquiry_submit" class="btn btn-primary">Close Account</button>
		</form>
	</div>
</div>
</div>
</div>
</section>
@stop