@extends('website.layout.layout')
@section('title', $page)
@section('content')
@include('website.partials.user_sidebar')
<div class="col-sm-12 col-md-9">
	<div class="banner-t">
		<div class="search">
			<h1>Hi, How Can We Help You?</h1>
			<!--<div class="sr-seaad">-->
			<!--	<input type="text" autocomplete="off" id="top-select" placeholder="What are you looking for?">-->
			<!--</div>-->
		</div>
	</div>
	<div class="login-main add-list posr x mt-4">
		<div class="log log-1">
			<div class="login">
				<h4>Frequently Asked Questions</h4>
				@foreach($faqCategories as $category)
				<div class="col-md-12 mb-4">
					<div class="how-to-coll">
					    <h4 class="text mt-4">{{$category->name}}</h4>
						<ul>
							@foreach($category->faqs as $key => $orderDetails)
							 <li class="{{$key == 0 ? 'colact':''}}">
                                <h4>{{$orderDetails->question}}</h4>
                                <div style="display:{{$key == 0 ? 'block;':''}}">
                                    <p>{{$orderDetails->answer}}</p>
                                </div>
                            </li>
							@endforeach
                        </ul>
					</div>
				</div>
				@endforeach
			</div>
		</div>
	</div>
	<div class="row">
	    <div class="col-2">
	        <a href="{{route('tickets')}}" class="btn btn-primary">Your Tickets</a>
	    </div>
	</div>
	<div class="row mt-10x d-flex justify-content-center">
		<div class="col-lg-4 col-md-4 col-sm-12 mb-3">
			<div class="inner-sectn text-center">
			    <a href="{{ route('raise-ticket')}}">
    				<img src="{{ asset('assets/website/images/22.png')}}">
    				<div class="bold-c">Raise a Ticket</div>
    				<div class="desc-inner">
    				    Need assistance? Click here to raise a support ticket and our team will assist you shortly.
    				</div>
    			</a>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-12 mb-3">
			<div class="inner-sectn text-center">
			    <a href="{{ route('chat-with-us')}}">
    				<img src="{{ asset('assets/website/images/33.png')}}">
    				<div class="bold-c">Chat with Us</div>
    				<div class="desc-inner">
    				    Have questions or need immediate assistance? Start a chat with us now!
    				</div>
    			</a>
			</div>
		</div>
	</div>
</div>
</div>
</div>
</section>
@stop