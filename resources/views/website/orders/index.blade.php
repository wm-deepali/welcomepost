<style>
    .table-responsive.table {
        display: block;
        width: 100%;
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
    }
</style>

@extends('website.layout.layout')
@section('title', $page)
@section('content')
@include('website.partials.user_sidebar')
	 		<div class="col-sm-12 col-md-9">
	 			<div class="profile-cont">
	 				<h3 class="mb-3">Orders & Transactions
	 				
	 				</h3>
	 				<table class="table-responsive table">
	 					<thead>
	 						<tr>
	 							<th scope="col">Date & Time</th>
	                            <th scope="col">Order Id </th>
	                            <th scope="col">Payment Id</th>
	                            <th scope="col"> Subscription Id</th>
	                            <th scope="col"> Purchase Date</th>
	                            <th scope="col"> Expiry Date </th>
	                            <th scope="col"> Auto Seeds</th>
	                            <th scope="col"> Payment Status</th>
	                            <th scope="col"> Transaction Id</th>
	                            <th scope="col"> Payment Method</th>
	                            <th scope="col">Status</th>
	                            <th scope="col">Action</th>
	                        </tr>
	                    </thead>
	                    <tbody id="">
	                    	@if(isset($datas) && count($datas)>0)
                            @foreach($datas as $index=>$data)
	                    	<tr>
	                    		<th scope="row">{{$data->created_at}}</th>
	                    		<td>{{ $data->order_number}}</td>
	                    		<td>{{$data->transaction_id}}</td>
	                            <td>{{ $data->subscription_number}}</td>
	                            
	                             <td>{{ $data->created_at->format('d-m-Y') ?? ""}}</td>
	                             <td>{{ date("d-m-Y",strtotime($data->subscription_expiry)) ?? ""}}</td>
	                             <td>{{ $data->total_joined}}</td>
	                             <td>{{ $data->payment_status}}</td>
	                             <td>{{ $data->transaction_id ?? "-"}}</td>
	                             <td>{{ $data->payment_method ?? "-"}}</td>
	                            <td>
	                            @if($data->subscription_expiry > date('Y-m-d'))
	                            Active
	                            @else
	                            Expired
	                            @endif

	                            </td>
	                            <td>
	                                <a title="View Subscription Detail" class="btn-view" data_id="{{$data->id}}" href="javascript:void(0)" ><i class="material-icons">visibility</i></a>
	                                {{--<a title="View Auto Seeds" class="btn-auto-seeds" href="javascript:void(0)" data_id="{{$data->id}}"><i class="material-icons">visibility</i></a>--}}
	                               <a href="{{ url('subscription-export')}}/{{ $data->transaction_id }}"><img src="{{ asset('assets/website/images/down.png')}}" style="width: 24px; margin-top: -20px;"></a>
	                               
	                            </td>
	                        </tr>
	                        @endforeach
	                        @endif
	                    </tbody>
	                </table>
	            </div>
	        </div>
 <!--   	</div>-->
	<!--</div>-->
</section>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"></div>
    <div class="modal fade" id="exampleModalError" tabindex="-1" role="dialog" aria-labelledby="exampleModalErrorLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Buy More Ads</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="otherMsg"></p>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    
                </div>
        
        </div>
    </div>
</div>
 <div id="subsc-modal" class="modal fade" role="dialog">
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
 
        $(document).on("click", ".btn-view", function(event) {
            let id = $(this).attr('data_id');
            $.ajax({
                url: `{{ URL::to('view-subscriptionss/${id}') }}`,
                type: "get",
                dataType: "json",
                success: function(result) {
                    if (result.success) {
                        Swal.fire({
                            title: 'Subscription Details',
                            html: result.html,
                            width: '800px',
                            showCloseButton: true,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire('Error', 'Error encountered: ' + result.msgText, 'error');
                    }
                },
                error: function(error) {
                    Swal.fire('Error', 'Error encountered: ' + error.statusText, 'error');
                }
            });
        });

        $(document).on("click", ".btn-auto-seeds", function(event) {
            let id = $(this).attr('data_id');
            $.ajax({
                url: `{{ URL::to('view-auto-seeds-member/${id}') }}`,
                type: "get",
                dataType: "json",
                success: function(result) {
                    if (result.success) {
                        $("#subsc-modal").html(result.html);
                        $("#subsc-modal").modal('show');
                    } else {
                        Swal.fire('error','error encountered ' + result.msgText);
                    }
                },
                error: function(error) {
                    Swal.fire('eroor','error encountered ' + error.statusText);
                }
            });
        });
$(document).ready(function(){
	$(".ads-sort").change(function(){
		var id = $('.ads-sort').val();
		$.ajax({
            url:'{{url("get-my-ads")}}',
            method:'POST',
            data:{id:id,'_token':"{{csrf_token()}}"},
            success:function(data){
            	console.log(data);
                $('#sort-ads-html').html(data);
                
            }
        });
	});
});
</script>
<script>
$(document).ready(function(){
	$(".more_ads").on("click", function(){
	   let user_id = $(this).attr('user-id');
       $.ajax({
            url:'{{url("check-user-subscription")}}',
            method:'GET',
            data:{user_id:user_id,'_token':"{{csrf_token()}}"},
            success:function(response){
                if(response.success == '500')
                {
                    $("#exampleModal").html(response.html);
                    $("#exampleModal").modal('show');
                }else{
                    $(".otherMsg").html(response.html);
                    $("#exampleModalError").modal('show');
                }
               
            }
        });
	});
});

</script>
@stop