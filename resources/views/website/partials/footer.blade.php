<style>
    @media screen and (max-width:480px) {
        .wed-foot-link div {
            width: 49%;
        }
        .wed-foot-link div:last-child ul li {
            width: 100%;
        }
        .wed-foot-link-1 {
            padding-top: 0;
        }
        .foot-count ul {
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap-reverse;
            justify-content: center;
            align-items: center;
            flex-direction: row;
        }
        .foot-count ul li {
            border-right: none; 
            margin-bottom: 10px;
        }
        .wed-hom-footer {
            padding: 0px 0px 20px 0px;
        }
        .fot-app {
            padding: 10px 0px 10px 0px;
        }
    }
    
</style>
<!-- START -->
<!--<div class="ani-quo">-->
<!--    <div class="ani-q1">-->
<!--        <h4>What you looking for?</h4>-->
<!--        <p>We connect you to service experts.</p>-->
<!--        <span>Get experts</span>-->
<!--    </div>-->
<!--    <div class="ani-q2">-->
<!--        <img src="{{ asset('assets/website/images/quote.png')}}" alt="" loading="lazy">-->
<!--    </div>-->
<!--</div>-->
<!-- END -->

<!-- START -->

<a href="{{ route('post-ads') }}" class="btn-ser-need-ani">
    <img src="{{ asset('assets/website/images/icon/blog1.png')}}" alt="" loading="lazy">
</a>

{{--<div class="ani-quo-form">
    <i class="material-icons ani-req-clo">close</i>
    <div class="tit">
        <h3>What service do you need? <span>Welcome Post will help you</span></h3>
        @if (session('success'))
        <h5 style="color:green;">{{ Session::get('success') }}</h5>
        <?php Session::forget('success');?>
        @endif
    </div>
    <div class="hom-col-req">
        <form action="{{route('send-enquiry')}}" name="home_slide_enquiry_form" id="home_slide_enquiry_form" method="post"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <input type="text" name="enquiry_name" value="" required="required" class="form-control"
                    placeholder="Enter name*">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" placeholder="Enter email*" required="required" value=""
                    name="enquiry_email"
                    pattern="^[\w]{1,}[\w.+-]{0,}@[\w-]{2,}([.][a-zA-Z]{2,}|[.][\w-]{2,}[.][a-zA-Z]{2,})$"
                    title="Invalid email address">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" value="" id="enquiry_mobile" name="enquiry_mobile"
                    placeholder="Enter mobile number *" pattern="[7-9]{1}[0-9]{9}"
                    title="Phone number starting with 7-9 and remaining 9 digit with 0-9" required="">
            </div>
            <div class="form-group">
                @php
                	$i++; $oddEven =($i % 2) ? 'active-a':'';
                	$category = DB::table('categories')->where('delete_status',0)->get();
            	@endphp
                <select name="enquiry_category" id="enquiry_category" class="form-control chosen-select">
                    <option value="">Select Category</option>
                    @foreach($category as $cat)
                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <textarea class="form-control" rows="3" name="enquiry_message"
                    placeholder="Enter your query or message"></textarea>
            </div>
            <input type="hidden" id="source">
            <button type="submit" id="home_slide_enquiry_submit" name="home_slide_enquiry_submit"
                class="btn btn-primary">Submit Requirements </button>
        </form>
    </div>
</div>--}}
<!-- END -->

<!-- START -->
@php
    $footer = App\Models\FooterSetting::first();
    $adminsetting = App\Models\Adminsettings::first();
@endphp
<section>
    <div class="full-bot-book">
        <div class="container">
            <div class="row">
                <div class="bot-book">
                    <div class="col-md-12 bb-text">
                        <h4>{{$footer->title}}</h4>
                        {!!$footer->description!!}
                        <a href="{{$footer->url}}">{{$footer->button_text}}<i
                                class="material-icons">arrow_forward</i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END -->
<section class=" wed-hom-footer">
        <div class="container">
            <div class="row foot-supp">
                <h2>@if(isset($adminsetting->contact_no))<span>Free support:</span>  {{$adminsetting->contact_no}} &nbsp;&nbsp;|&nbsp;&nbsp; @endif @if(isset($adminsetting->email_id))<span>Email:</span>
                    {{$adminsetting->email_id}}@endif</h2>
            </div>
            <div class="row wed-foot-link">
                <div class="col-lg-3 col-md-6 col-sm-5 foot-tc-mar-t-o mb-3">
                    <h4>Top Category</h4>
                    <ul>
					@foreach($TopCategories as $key => $orderDetails)
                    <li><a href="{{url('category-ads/'.$orderDetails->id)}}" >{{$orderDetails->name}}</a></li>
					 @endforeach
                        
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-5 mb-3">
                    <h4>Trending Category</h4>
                    <ul>
                        @foreach($TrendingCategories as $key => $orderDetails)
                    <li><a href="{{url('category-ads/'.$orderDetails->id)}}">{{$orderDetails->name}}</a></li>
					 @endforeach
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-5 mb-3">
                    <h4>HELP &amp; SUPPORT</h4>
                    <ul>
                        <li><a href="{{url('abouts')}}">About us</a>
                        </li>
                        <li><a href="{{url('faqs')}}">FAQ</a>
                        </li>
						<li><a href="{{url('chat-with-us')}}">Support Chat</a>
                        </li>
                        
                        <li><a href="{{url('contact')}}">Contact us</a>
                        </li>
                        <!--<li><a href="{{ route('privacy-policy')}}">Privacy Policy</a></li>-->
                        <!--<li><a href="{{ route('term-conditions')}}">Terms of Use</a></li>-->
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-5 mb-3">
                     <h4>Important Links</h4>
<ul>
                        @foreach($Pages as $key => $orderDetails)
						<li><a href="{{url('pages',['id'=>$orderDetails->id,'url'=>$orderDetails->url])}}">{{$orderDetails->name}}</a></li>
						@endforeach
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-5 mb-3">

                </div>
				
            </div>
            <div class="row wed-foot-link-1">
                <div class="col-md-4">
                    <h4>Get In Touch</h4>
                    <p>Address: {{$adminsetting->full_address}}</p>
                    @if(isset($adminsetting->contact_no))
                    <p>Phone: <a href="tel:{{$adminsetting->contact_no}}">{{$adminsetting->contact_no}}</a></p>
                    @endif
                    @if(isset($adminsetting->email_id))
                    <p>Email: <a href="mailto:{{$adminsetting->email_id}}">{{$adminsetting->email_id}}</a></p>
                    @endif
                </div>
                <div class="col-md-4 fot-app">
                </div>
                <div class="col-md-4 fot-soc">
                    <h4>SOCIAL MEDIA</h4>
                    <ul>
                        <li><a target="_blank" href="{{$footer->linkedin_link}}"><img src="{{ asset('assets/website/images/social/1.png')}}" alt="" loading="lazy"></a></li>
                        <li><a target="_blank" href="{{$footer->twitter_link}}"><img src="{{ asset('assets/website/images/social/2.png')}}" alt="" loading="lazy"></a></li>
                        <li><a target="_blank" href="{{$footer->facebook_link}}"><img src="{{ asset('assets/website/images/social/3.png')}}" alt="" loading="lazy"></a></li>
                         <li><a target="_blank" href="{{$footer->instagram_link}}"><img src="{{ asset('assets/website/images/social/7.png')}}" alt="" loading="lazy"></a></li>
                        <li><a target="_blank" href="{{$footer->youtube_link}}"><img src="{{ asset('assets/website/images/social/5.png')}}" alt="" loading="lazy"></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- START -->
    <section>
        <div class="cr">
            <div class="container">
                <div class="row">
                    <p>Copyright &copy; 2024 WelcomePost. Designed by <a href="https://www.webmingo.com/" target="blank">Web Mingo IT Solutions Pvt. Ltd.</a></p>
                </div>
            </div>
        </div>
    </section>
    <!-- END -->
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="{{url('assets/website/js/jquery.min.js')}}"></script>
<script src="{{url('assets/website/js/popper.min.js')}}"></script>
<script src="{{url('assets/website/js/bootstrap.min.js')}}"></script>
<script src="{{url('assets/website/js/jquery-ui.js')}}"></script>
<script src="{{url('assets/website/js/select-opt.js')}}"></script>
 <script src="{{url('assets/website/js/slick.js')}}"></script>
<script src="{{url('assets/website/js/custom.js')}}"></script>
<script src="{{url('assets/website/js/jquery.simplePagination.min.js')}}"></script>
<script>

</script>

    </body>

</html>

<script>
$("#top-select-search").on("keyup", function(){
    let search = $(this).val();
    $.ajax({
        url: `{{ URL::to('top-search') }}`,
        type: "get",
        dataType: "json",
        data:{"search":search, "_token": "{{ csrf_token() }}",},
        success: function(result) {
            console.log(result);
            $("#tser-res1").html(result);
        }
    });
});
</script>
<script>
    $(document).ready(function(){
	
	$('.pmenu-spri li a').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('.pmenu-spri li a').removeClass('current');
		$('.tab-content').removeClass('current');

		$(this).addClass('current');
		$("#"+tab_id).addClass('current');
	});

});


function restrictNumber(e) {
    var newValue = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
    this.value = newValue;
}

// var year = document.querySelector('#year');
// year.addEventListener('input', restrictNumber);

// var price = document.querySelector('#price');
// price.addEventListener('input', restrictNumber);

// var mobile = document.querySelector('#mobile');
// mobile.addEventListener('input', restrictNumber);

// var enquiry_mobile = document.querySelector('#enquiry_mobile');
// enquiry_mobile.addEventListener('input', restrictNumber);
</script>
<script>
//     $('select').chosen({
//   allow_single_deselect: true
// });

</script>
</body>

</html>