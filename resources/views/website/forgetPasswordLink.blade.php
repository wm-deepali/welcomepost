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
                        <h4>Add New Password</h4>
                        <form id="login_form" name="login_form" method="post" action="{{ route('reset.password.post') }}">
    				        @csrf
    				        <input type="hidden" name="token" value="{{ $token }}">
    				        
    				        <div class="form-group">
                                <input type="password" autocomplete="off" name="password" id="password" class="form-control" placeholder="New Password" required/>
    				            @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
    				        </div>
    				        
    				        <div class="form-group">
                                <input type="password" autocomplete="off" name="password_confirmation" id="password-confirm" class="form-control " placeholder="Confirm New Password" required/>
    				            @if ($errors->has('password_confirmation'))
                                    <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                @endif
    				        </div>
    				        <button type="submit" name="login_submit" value="submit" class="btn btn-primary">Reset Password</button>
				        </form>
				    </div>
				</div>
			</div>
        </div>
    </div>
</section>
@stop