@extends('website.layout.layout')
@section('title', $page)
@section('content')

<style>
.box-s2 .lhs {
    float: left;
    width: 68%;
    box-shadow: none; 
    background: none; 
    padding: 30px;
    margin-right: 4%;
    text-align: left;
}
</style>

<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>


<section class="blog-body mt-5 pt-5">
 <div class="com-pro-pg-bd">
            <div class="container">
                <div class="row">
                  
            	
                    <div class="box-s2" >
                        <div class="lhs">
                            <!--START-->
                            <div class="comp-abo" id="about">
                                <h2>{{$info->heading}}</h2>
                                {!! $info->description !!}
                            </div>
                        </div>
					</div>
                </div>
            </div>
        </div>
   </section>
@endsection