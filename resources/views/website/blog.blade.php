@extends('website.layout.layout')
@section('content')

<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>


<!--
    <section class=" blog-head">
        <div class="container">
            <div class="blog-head-inn">
                <h1>Blog posts</h1>
                <p>Here submit your blogs and make your own audiance.</p>
            </div>
            <div class="ban-search">
                <form>
                    <ul>
                        <li class="sr-sea">
                            <input type="text" id="blog-search" class="autocomplete" placeholder="Search blog posts...">
                        </li>
                    </ul>
                </form>
            </div>
            <div class="blog-sli">
                <ul class="multiple-items1">
                    <li>
                        <div class="blog-sli-box">
                            <div class="lhs">
                                <img src="images/services/blog-1.jpg" alt="" loading="lazy">
                            </div>

                            <div class="rhs">
                                <span class="hig">Top posts</span>
                                <h4>Trip your first solo adventure</h4>
                                <div class="sli-desc">
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                        Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                        unknown printer took a galley of type and scrambled it to make a type specimen
                                        book. It has survived not only five centuries<br><br> but also the leap into
                                        electronic typesetting, remaining essentially unchanged. It was popularised in
                                        the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
                                        and more recently with desktop publishing software like Aldus PageMaker
                                        including versions of Lorem Ipsum<br><br>Lorem Ipsum is simply dummy text of the
                                        printing and typesetting industry. Lorem Ipsum has been the industry's standard
                                        dummy text ever since the 1500s, when an unknown printer took a galley of type
                                        and scrambled it to make a type specimen book. It has survived not only five
                                        centuries<br><br> but also the leap into electronic typesetting, remaining
                                        essentially unchanged. It was popularised in the 1960s with the release of
                                        Letraset sheets containing Lorem Ipsum passages, and more recently with desktop
                                        publishing software like Aldus PageMaker including versions of Lorem Ipsum</p>
                                </div>
                                <div class="auth">
                                    <img src="images/icon/service.png" alt="" loading="lazy">
                                    <b>Posted by</b><br>
                                    <h6>Richflayer</h6>
                                </div>
                            </div>
                            <a href="blog-details.html" class="fclick"></a>
                        </div>
                    </li>
                    <li>
                        <div class="blog-sli-box">
                            <div class="lhs">
                                <img src="images/services/blog-2.jpg" alt="" loading="lazy">
                            </div>

                            <div class="rhs">
                                <span class="hig">Top posts</span>
                                <h4>My test blog</h4>
                                <div class="sli-desc">
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                        Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                        unknown printer took a galley of type and scrambled it to make a type specimen
                                        book. It has survived not only five centuries, but also the leap into electronic
                                        typesetting, remaining essentially unchanged. It was popularised in the 1960s
                                        with the release of Letraset sheets containing Lorem Ipsum passages, and more
                                        recently with desktop publishing software like Aldus PageMaker including
                                        versions of Lorem Ipsum.</p>
                                </div>
                                <div class="auth">
                                    <img src="images/icon/service.png" alt="" loading="lazy">
                                    <b>Posted by</b><br>
                                    <h6>Richflayer</h6>
                                </div>
                            </div>
                            <a href="blog-details.html" class="fclick"></a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>
    -->
	
	<!--END-->

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
                                <span class="ic-view">113</span>
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