@extends('website.layout.layout')
@section('title', $page)
@section('content')
@include('website.partials.user_sidebar')
<div class="col-sm-7 col-md-9">
	<div class="templ-rhs-form">
		<h3>New Password</h3>
		<form name="home_enquiry_form" id="home_enquiry_form" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label>Enter your new password</label>
				<input type="password" class="form-control" placeholder="New password*" required="">
			</div>
			<div class="form-group">
				<label>Enter confirm password*</label>
				<input type="password" class="form-control" placeholder="Confirm password*" required="">
			</div>
			<button type="submit" name="home_enquiry_submit" class="btn btn-primary">Create New Password</button>
		</form>
	</div>
</div>
</div>
</div>
</section>
@stop