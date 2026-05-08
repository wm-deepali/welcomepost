@extends('website.layout.layout')
@section('title', $page)
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
                        <h4>Get a Call Back</h4>
                        <form id="call_back" name="call_back" method="post" action="{{ route('post.call-back')}}">
    				        @csrf
    				        <div class="form-group">
                                <input type="text" autocomplete="off" name="name" id="name" class="form-control" placeholder="Name " onkeydown="return /[a-z]/i.test(event.key)" required/>
    				           
    				        </div>
    				        
    				        <div class="form-group">
                                <input type="email" autocomplete="off" name="email" id="email" class="form-control" placeholder="Email id*" required=""/>
    				        </div>
    				        
    				        <div class="form-group">
    				            <input type="tel" onkeypress="return isNumber(event)" autocomplete="off" name="mobile" id="mobile_number" class="form-control" required="" placeholder="Phone"
                                minlength="10" maxlength="10"/>
                            </div>
                  
    				        <button type="submit" name="login_submit" value="submit" class="btn btn-primary">Submit</button>
				        </form>
				    </div>
				</div>
			</div>
        </div>
    </div>
</section>
@stop