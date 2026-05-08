@extends('website.layout.layout')
@section('title','Thank You')
@section('content')
<div class="container">
	<div class="row">
        <div class="jumbotron" style="box-shadow: 2px 2px 4px #000000; background-color:#ffffff; margin-top:20px;">
            <center>
                <img width="35%" src="https://welcomepost.in/assets/website/images/thank-you.jpg">
            </center>
            <h2 class="text-center">After review your ad will be published in next few minutes...</h2>
            <center>
                <div class="btn-group" style="margin-top:25px;background-color: #3d3f94 !important;">
                    <a href="{{ url('post-ads')}}" class="btn btn-lg" style="color:white !important;">CONTINUE</a>
                </div>
            </center>
        </div>
	</div>
</div>
@endsection