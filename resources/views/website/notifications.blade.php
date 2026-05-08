
<style>
    .notification-section{
        width:100%;
        height:auto;
       
            display: grid;
    grid-template-columns: 1fr;
    gap:20px;
    }
    .notification-message{
        width:100%;
        height:auto;
        display:flex;
        flex-direction:column;
        
        padding:20px;
        border-radius:10px;
        box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
        
        
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/photoswipe.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/default-skin/default-skin.css">
@extends('website.layout.layout')
@section('title', $page)
@section('content')
@include('website.partials.user_sidebar')
	 		<div class="col-sm-12 col-md-9">
<div class="notification-section">
    
    @if(isset($notificationData) && count($notificationData) > 0)
    @for($i=0; $i < count($notificationData); $i++)
    
    <div class="notification-message">
        <p class="m-0 text-right">{{ date('j M, Y g:i A', strtotime($notificationData[$i]->created_at))}}</p>
        <h3 class="m-0">{{$notificationData[$i]->title}}</h3>
        <p class="m-0">{{$notificationData[$i]->message}}</p>
        @if(!empty($notificationData[$i]->image))
        <p class="m-0"><a href="{{$notificationData[$i]->image}}" target="_blank"><img src="{{$notificationData[$i]->image}}" style="width:50px;height:50px;"></a></p>
        @endif
    </div>
    @endfor
    @endif
</div>
	        </div>
 
	



@stop