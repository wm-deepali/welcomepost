@extends('website.layout.layout')
@section('title', $page)
@section('content')


<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>


<section class="blog-body mt-5 pt-5">
 <div class="com-pro-pg-bd">
            <div class="container">
                <div class="row">
                    @foreach($about as $key => $orderDetails)
            	
                    <div class="box-s2" >
                        <div class="lhs">
                            <!--START-->
                            <div class="comp-abo" id="about">
                                <h2>{{$orderDetails->heading}}</h2>
                                <p>
								
								{{$orderDetails->description}}
                                </p>
                            </div>
                            <!--END-->
                            <!--START-->
                            <div class="comp-pro" id="prod">
                                <div class="all-pro-box">
                                    <div class="all-pro-img">
                                         <img src="{{$orderDetails->imageone}}" alt="" loading="lazy">
                                    </div>
									
									<div class="all-pro-img">
                                         <img src="{{$orderDetails->imagetwo}}" alt="" loading="lazy">
                                    </div>
                                   
                                </div>
                            </div>
                            <!--END-->
                        </div>
                        
					
					</div>
                  @endforeach
				 
			
					
					
                </div>
            </div>
        </div>
   </section>
@endsection