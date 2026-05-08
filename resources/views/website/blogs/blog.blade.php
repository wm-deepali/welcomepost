@extends('website.layout.layout')
@section('content')

<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>
<!-- START -->
<section class="blog-body mt-5 pt-5">
    <div class="container">
        <div class="us-ppg-com us-ppg-blog">
            <ul id="intseres" class="blog-wrapper row">
                @foreach($blog as $key => $orderDetails)
                    <li class="col-lg-4 blog-item">
                        <div class="pro-eve-box">
                            <div>
                                <img src="{{$orderDetails->image}}" alt="" loading="lazy">
                            </div>
                            <div>
                                <h2>{{$orderDetails->title}}</h2>
                                <span class="ic-time">{{$orderDetails->created_at}}</span>
                                <span class="ic-view">{{$orderDetails->view_count}}</span>
                            </div>
                            <a href="{{url('blog-details/'.$orderDetails->id)}}" class="fclick">
                                &nbsp;</a>
                        </div>
                    </li>
				@endforeach	
			</ul>
        </div>
    </div>
</section>
<!--END-->

<div id="blog-pagination-container"></div>
    <!-- Optional JavaScript -->
	<script>
        var items = $(".blog-wrapper .blog-item");
        var numItems = items.length;
        var perPage = 12;

        items.slice(perPage).hide();

        $('#blog-pagination-container').pagination({
            items: numItems,
            itemsOnPage: perPage,
            prevText: "&laquo;",
            nextText: "&raquo;",
            onPageClick: function (pageNumber) {
                var showFrom = perPage * (pageNumber - 1);
                var showTo = showFrom + perPage;
                items.hide().slice(showFrom, showTo).show();
                $("html, body").animate({ scrollTop: 0 }, "fast");
                return false;
            }
        });
        $('.multiple-items1').slick({
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            responsive: [{
                breakpoint: 992,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    centerMode: false
                }
            }]

        });
    </script>
    

@endsection