<style>
    .table-responsive table {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
</style>
@extends('website.layout.layout')
@section('title', $page)
@section('content')
@include('website.partials.user_sidebar')
<div class="col-sm-12 col-md-9">
	<div class="profile-cont">
	    <div class="row mt-4">
    	    <div class="col-2">
    	       <a href="{{ route('help') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-return-left"></i> Back
                </a>
    	    </div>
    	</div>
    	<h3>Tickets</h3>
    	<div class="table-responsive">
 			 <table class="table">
 				<thead>
 					<tr>
 						<th scope="col">Date & Time</th>
 						<th scope="col">Image</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Query</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                	@if(isset($tickets) && count($tickets)>0)
                        @foreach($tickets as $index=>$ticket)
                    	<tr>
                    		<td scope="row">{{$ticket->created_at}}</td>
                    		<td><img src="{{ $ticket->image}}" width="60px" ></td>
                    		<td>{{$ticket->subject}}</td>
                    		<td>{{$ticket->subject_query}}</td>
                    		<td>
                                @if($ticket->isResolved == 0)
                                    <span class="badge badge-warning">Under Processing</span>
                                @elseif($ticket->isResolved == 1)
                                    <span class="badge badge-success">Closed</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
 		</div>
    </div>
</div>
</section>
@stop