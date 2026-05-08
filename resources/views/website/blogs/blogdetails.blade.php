@extends('website.layout.layout')
@section('content')
<meta name="viewport" content="width=device-width, initial-scale=1" />  
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .fa {
  padding: 20px;
  font-size: 30px;
  width: 50px;
  text-align: center;
  text-decoration: none;
}

/* Add a hover effect if you want */
.fa:hover {
  opacity: 0.7;
}

/* Set a specific color for each brand */

/* Facebook */
.fa-facebook {
  background: #3B5998;
  color: white;
}

/* Twitter */
.fa-twitter {
  background: #55ACEE;
  color: white;
}
.framed{
    margin-bottom: 10px;
  display: flex;
  flex-direction: row;
  justify-content: space-around;
  align-items: center;
  height: 70px;
  width: 350px;
  position: relative;
 transition:box-shadow 0.6s cubic-bezier(.79,.21,.06,.81);
   border-radius: 10px;
}

.social_btn{
  height: 40px;
  width: 40px;
  border-radius: 12px;
  background: #e0e5ec;
  display: flex;
  justify-content: center;
  align-items:center;
  text-align:center;
  -webkit-tap-highlight-color: rgba(0,0,0,0);
  -webkit-tap-highlight-color: transparent;
  box-shadow:
   -7px -7px 20px 0px #fff9,
   -4px -4px 5px 0px #fff9,
   7px 7px 20px 0px #0002,
   4px 4px 5px 0px #0001,
   inset 0px 0px 0px 0px #fff9,
   inset 0px 0px 0px 0px #0001,
   inset 0px 0px 0px 0px #fff9,        inset 0px 0px 0px 0px #0001;
 transition:box-shadow 0.6s cubic-bezier(.79,.21,.06,.81);
  font-size: 18px;
  color: rgba(42, 52, 84, 1);
  text-decoration: none;
}
.social_btn:active{
  box-shadow:  4px 4px 6px 0 rgba(255,255,255,.5),
              -4px -4px 6px 0 rgba(116, 125, 136, .2), 
    inset -4px -4px 6px 0 rgba(255,255,255,.5),
    inset 4px 4px 6px 0 rgba(116, 125, 136, .3);
}
i{
    margin-bottom: 15px;
    margin-right: 15px;
}
</style>
<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>


<section class="eve-deta-pg">
        <div class="container">
            <div class="eve-deta-pg-main">
               

			   <div class="lhs">
                    <div class="img">
                        <img src="{{$bloginfo->image}}" alt="" loading="lazy">
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!--END-->

    <!-- START -->
    <section class=" eve-deta-body blog-deta-body pt-5">
        <div class="container">
            <div class="eve-deta-body-main">
                <div class="lhs">
                    <div class="head">
                        <h1>{{$bloginfo->title}}</h1>
                        <div class="blog-bred-post-date">
                            <span class="ic-time">{{$bloginfo->created_at}}</span>
                            <span class="ic-view">{{$bloginfo->view_count}}</span>
                        </div>
                    </div>
                    <p>{{$bloginfo->description}}</p>

                    {{-- <div class="list-sh">
                        <span class="share-new" title="facebook">
                            <a target="_blank" href="http://www.facebook.com/sharer.php?u={{url('/blog-details')}}/{{ $bloginfo->id }}" class="fa fa-facebook"></a>
                        </span>
                        <span class="share-new" title="twitter">
                            <a target="_blank" href="http://twitter.com/share?text={{$bloginfo->title}}&url={{url('/blog-details')}}/{{ $bloginfo->id }}" class="fa fa-twitter"></a>
                        </span>
                        <span class="share-new" title="linkedin">
                            <a target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&url={{url('/blog-details')}}/{{ $bloginfo->id }}" class="fa fa-linkedin"></a> 
                        </span>
                        <span class="share-new" title="pinterest">
                            <a target="_blank" href="http://pinterest.com/pin/create/button/?url={{url('/blog-details')}}/{{ $bloginfo->id }}&media={{$bloginfo->image}}" class="fa fa-pinterest"></a> 
                        </span>
                        <span class="share-new" title="reddit">
                            <a target="_blank" href="http://www.reddit.com/submit?url={{url('/blog-details')}}/{{ $bloginfo->id }}" class="fa fa-reddit"></a>
                        </span>
                        <!--<span class="share-new" data-toggle="modal" data-target="#sharepop">-->
                        <!--    <i class="material-icons">share</i> Share now-->
                        <!--</span>-->
                    </div> --}}
                    @php
                        $twitterShareUrl = 'https://twitter.com/intent/tweet';
                        $title = $bloginfo->ad_title;
                        $imageUrl = $bloginfo->image;
                        $description = $bloginfo->description;
                        $url = url('/blog-details/' . $bloginfo->id); // Corrected URL construction
                        
                        // Constructing the sharing URI
                        $twitterShareUrl .= '?text=' . urlencode($title . ': ' . $description);
                        $twitterShareUrl .= '&url=' . urlencode($url);
                        $twitterShareUrl .= '&media=' . urlencode($imageUrl);
                        $twitterShareUrl .= '&hashtags=welcomepost';
                    @endphp

                    

                    <div class="framed">
                        <span>Share</span>
                        <a class="social_btn" href="whatsapp://send?text={{ $title }}%0A{{ $description }}%0A{{ $url }}">
                            <i class="bi bi-whatsapp" style="color: #3b5998;"></i>
                        </a>
                        <a class="social_btn"  href="http://www.facebook.com/sharer.php?u={{url('/blog-details')}}/{{ $bloginfo->id }}"><i class="bi bi-facebook"style="color: blue;"></i></a>
                        <a class="social_btn" href="https://t.me/share/url?url={{$url}}&text={{$title}}: {{$description}}&photo={{$imageUrl}}"> <i class="bi bi-telegram" style="color: #3b5998;"></i></a>
                        <a class="social_btn" href="http://www.linkedin.com/shareArticle?mini=true&url={{url('/blog-details')}}/{{ $bloginfo->id }}"><i class="bi bi-linkedin" style="color: #3b5998;"></i></a>
                        <a class="social_btn" href="http://pinterest.com/pin/create/button/?url={{url('/blog-details')}}/{{ $bloginfo->id }}&media={{$imageUrl}}"><i class="bi bi-pinterest" style="color: #3b5998;"></i></a>
                    </div>
                </div>
                <div class="rhs">
                    
                    <div class="sec-4">
                        <h4>Other Post</h4>
                      
                        <ul id="pg-resu">
						
							@foreach($otherpost as $key => $orderDetails)
							
							<li><a href="{{url('blog-details/'.$orderDetails->id)}}">{{$orderDetails->title}}</a></li>
							
							@endforeach
                            

                        </ul>
                    </div>
                </div>
            </div>

            <div class="pro-rel-posts">
                <h4>Related Blogs</h4>
                <div class="us-ppg-com us-ppg-blog">
                    <div class="row">
                        @foreach($blog as $key => $orderDetails)
                        <div class="col-sm-6 col-md-4">
                            <a href="{{url('blog-details/'.$orderDetails->id)}}"><div class="pro-eve-box">
                                <div>
                                    <img src="{{$orderDetails->image}}" alt="" loading="lazy">
                                </div>
                                <div>
                                    <h2>{{$orderDetails->title}}</h2>
                                </div>
                            </div></a>
                        </div>
                        @endforeach
                    </div>
                    {{--<ul class="d-flex">
					
						<!--@foreach($blog as $key => $orderDetails)-->
						
						<!--<li>-->
      <!--                      <a href="{{url('blog-details/'.$orderDetails->id)}}"><div class="pro-eve-box">-->
      <!--                          <div>-->
      <!--                              <img src="{{$orderDetails->image}}" alt="" loading="lazy">-->
      <!--                          </div>-->
      <!--                          <div>-->
      <!--                              <h2>{{$orderDetails->title}}</h2>-->
      <!--                          </div>-->
      <!--                      </div></a>-->
      <!--                  </li>-->
						 
						<!--@endforeach-->
                        
						
						
                    </ul>--}}
                </div>
            </div>
        </div>
    </section>
    <!--END-->

@endsection