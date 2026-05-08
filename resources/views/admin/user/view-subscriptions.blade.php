@extends('admin.layout.layout')
@section('content')
  <div class="content-wrapper">
  
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>View Subscription </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">View Subscription </li>
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
              <!-- <div class="card-header">
                <h3 class="card-title"><a href="{{url('add-vehicletypes')}}"><button type="button" class="btn btn-block bg-gradient-primary">Add Vehicle Types</button></a></h3>
              </div> -->
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                  
                    <th>fjDate & Time </th>
                    <th>Subscription Name </th>
                    <th>Subcription Expiry </th>
                    <th>Subscription Cost </th>
                    <th>Payment Status</th>
                    <th>Total Members</th>
                    <th>Pending Members</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  
                  @foreach($info as $orderDetails)
                  <tr>
                    
                    <td>{{$orderDetails->created_at}}</td>
                    <td>{{$orderDetails->subscriptions->package ?? "NA"}}</td>
                    <td>{{$orderDetails->subscription_expiry}}</td>
                    <td>{{$orderDetails->order_amount_with_gst}}</td>
                    <td>{{$orderDetails->payment_status}}</td>
                    <td>{{$orderDetails->total_joined ?? 0}}</td>
                    <td>{{$orderDetails->auto_join_member - $orderDetails->total_joined}}</td>
                    <td>{{$orderDetails->subscription_expiry > date('Y-m-d') ? "Active" : "Failed"}}</td>
                    <td>
                        <a href="{{route('view-subscriptions-detail',$orderDetails->id)}}"><button type="button" class="btn btn-primary mb-1">View Subscription Detail </button></a>
                        <a href="{{route('view-auto-joining-members-by-subscription',$orderDetails->id)}}"><button type="button" class="btn btn-primary mb-1">View All Member List </button></a>
                        <a href="{{route('admin.subscription.export',$orderDetails->id)}}"><button type="button" class="btn btn-primary mb-1">Download Invoice</button></a>
					</td>
                  </tr>
				  
					
				  <div class="modal fade" id="modal-delete">
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
              <a href=""><button type="button" class="btn btn-primary">Yes</button></a>
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
                  
                    <th>Date & Time </th>
                    <th>Subscription Name </th>
                    <th>Subcription Expiry </th>
                    <th>Subscription Cost </th>
                    <th>Payment Status</th>
                    <th>Total Members</th>
                    <th>Pending Members</th>
                    <th>Status</th>
                    <th>Action</th>
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