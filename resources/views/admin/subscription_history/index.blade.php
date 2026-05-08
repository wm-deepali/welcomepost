@extends('admin.layout.layout')
@section('content')
<?php error_reporting(0); ?>
  <div class="content-wrapper">
  
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Subscription Orders</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Subscription Orders</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
	
	@if (session('success'))
	<div class="card-body">
	<div class="alert alert-success alert-dismissible">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h5>{{ Session::get('success') }}</h5>
	<?php Session::forget('success');?>
	</div>
    </div>
	@endif
	
	
	
	 <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
		  
		
            <div class="card">
              <!--<div class="card-header">
                <h3 class="card-title"><a href="{{url('add-freetrail')}}"><button type="button" class="btn btn-block bg-gradient-primary">Add Subscription</button></a></h3>
              </div>-->
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S.No</th>
                    <th>User</th>
                    <th>Category</th>
                    <th>Subscription</th>
                    <th>Used Ads</th>
                    <th>Remaining Ads</th>
                    <th>Subscription Expiry</th>
                    <th>Payment Method</th>
                    <th>Payment Status</th>
                    <th>Update Payment</th>
                    <th>Registered Date</th>
                  </tr>
                  </thead>
                  <tbody>
                  
                  @foreach($subscription_order as $key => $orderDetails)
                  <tr>
                    <td>{{$key + 1}}</td>
                    <td>
					<?php
					$result = DB::table('customers')->where('id',$orderDetails->user_id)->get();
					echo $result[0]->name;
					?>
					</td>
					 <td>
					<?php
					$result = DB::table('categories')->where('id',$orderDetails->category_id)->get();
					echo $result[0]->name;
					?>
					</td>
					<td>
					<?php
					$result = DB::table('subscriptions')->where('id',$orderDetails->subscription_id)->get();
					echo $result[0]->package;
					?>
					</td>
					
                    <td>{{$orderDetails->used_ads}}</td>
                    <td>{{$orderDetails->remaining_ads}}</td>
                    <td>{{$orderDetails->subscription_expiry}}</td>
                    <td>@if($orderDetails->payment_method == 'offline') Cash on Delivery @else Online @endif</td>
                    <td>{{$orderDetails->payment_status}}</td>
                    <td>
					<button type="button" class="btn btn-success"  data-toggle="modal" data-target="#rejectreason<?php echo $orderDetails->id; ?>" @if($orderDetails->payment_status == 'Completed') disabled @endif>Update</button>
					</td>
					<td>{{$orderDetails->created_at}}</td>
                   
                  </tr>
				  
					
				  <div class="modal fade" id="modal-delete<?php echo $orderDetails->id; ?>">
					<div class="modal-dialog">
					  <div class="modal-content">
						<div class="modal-header">
						  <h4 class="modal-title">Alert</h4>
						  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						  </button>
						</div>
						<div class="modal-body">
						  <p>Are You Sure You Want To Delete This Item ?</p>
						</div>
						<div class="modal-footer justify-content-between">
						  <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						  <a href="{{url('delete-freetrail/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary">Yes</button></a>
						</div>
					  </div>
					  <!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				  </div>
				  
				  
				   <div class="modal fade" id="rejectreason<?php echo $orderDetails->id; ?>">
						<div class="modal-dialog">
						  <div class="modal-content">
							<div class="modal-header">
							  <h4 class="modal-title">Update Payment Status</h4>
							  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							  </button>
							</div>
							@csrf
							
							
							<div class="modal-footer justify-content-between">
							  <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
							  <a href="{{url('subscription-order-payment-status/'.$orderDetails->id)}}"><button type="submit" class="btn btn-primary">Mark as Complete</button></a>
							</div>
						  </div>
						 
						  <!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					  </div>
				  
				  @endforeach	
                  
                  </tbody>
                  <tfoot>
                   <tr>
                    <th>S.No</th>
                    <th>User</th>
                    <th>Category</th>
                    <th>Subscription</th>
                    <th>Used Ads</th>
                    <th>Remaining Ads</th>
                    <th>Subscription Expiry</th>
                    <th>Payment Status</th>
					<th>Update Payment</th>
                    <th>Registered Date</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
			
			
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
	
	
  
  
   </div>
  @endsection