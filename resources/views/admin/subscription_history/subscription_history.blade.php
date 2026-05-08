<style>
.table-responsive {
    width: 100% !important;
    aspect-ratio: 2 / 1;
    overflow-x: scroll !important;
    overflow-y: visible !important;
    scroll-snap-type: x mandatory;
}
</style>
@extends('admin.layout.layout')
@section('content')
<?php error_reporting(0); ?>
  <div class="content-wrapper">
  
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Subscription History</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Homes</a></li>
              <li class="breadcrumb-item active">Subscription History</li>
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
                <table id="example1" class="table-responsive table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S.No</th>
                    <th>User</th>
                    <th>Category</th>
                    <th>Subscription</th>
                    <th>Used Ads</th>
                    <th>Remaining Ads</th>
                    <th>Subscription Expiry</th>
                    <th>Payment Status</th>
                    <th>Registered Date</th>
                  </tr>
                  </thead>
                  <tbody>
				<?php $subscription_history = DB::table('subscription_history')->where('delete_status','0')->get();?>

                  @foreach($subscription_history as $key => $orderDetails)
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
					$result = DB::table('categories')->whereIn('id',explode(",",$orderDetails->category_id))->pluck('name');
					echo $result->implode(",");
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
                    <td>{{$orderDetails->payment_status}}</td>
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