@extends('website.layout.layout')
@section('content')

<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>

<section class="blog-body mt-5 pt-5">
    <div class="container">
        <div class="row">
            <div class="login-main">
        <div class="log-bor">&nbsp;</div>
        <div class="log log-1">
          <div class="login login-new">
            
            <form action="{{ route('new-pswd')}}" name="home_slide_enquiry_form" id="home_slide_enquiry_form" method="post"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" value="{{ $id }}" name="customer_id">
            <div class="form-group">
                <input type="password" name="password" value="" required="required" class="form-control"
                    placeholder="Enter password*"  pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
                      title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                      >
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="Enter confirm password*" required="required" value=""
                    name="confirm_password"
                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
                      title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                      >
            </div>
           
            <button type="submit" id="home_slide_enquiry_submit" name="home_slide_enquiry_submit"
                class="btn btn-primary">Create Password</button>
        </form>
        </div>
        </div>
        </div>
        </div>
        </div>
    </div>
</section>

@stop