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
                        <h4>Raise a Ticket</h4>
                        <form id="raise_ticket" name="raise_ticket" method="post" action="{{ route('post.raise-ticket')}}"  enctype="multipart/form-data">
    				        @csrf
    				        <div class="form-group">
                                <select name="subject" id="subject" class="form-control ca-check-plan"  required="">
                                    <option value="">Select Subject</option>
                                    @foreach($subject as $sub)
                                    <option value="{{ $sub->name }}">{{ $sub->name }}</option>
                                    @endforeach
					            </select>
                            </div>
    				        
    				        <div class="form-group">
    				            <textarea rows="4" autocomplete="off" name="subject_query" class="form-control" Placeholder="Query here...." required></textarea>
                                
    				        </div>
    				        
    				        <div class="form-group">
    				            <input type="file"  name="file"  class="form-control"/>
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