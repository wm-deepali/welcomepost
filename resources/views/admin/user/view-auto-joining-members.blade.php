<style>
.table-responsive {
    width: 100% !important;
    aspect-ratio: 0 / 1;
    overflow-x: scroll !important;
    overflow-y: visible !important;
    white-space: nowrap;
    scroll-snap-type: x mandatory;
}
</style>

@extends('admin.layout.layout')
@section('content')
  <div class="content-wrapper">
  
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>View Auto Joining  </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">View Auto Joining </li>
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
                 <table id="example1" class="table-responsive table table-bordered table-striped">
                  <thead>
                  <tr>
                  
                    <th>Date & Time </th>
                    <th>Member Name </th>
                    <th>Mobile Number </th>
                    <th>Email Id  </th>
                    <th>Active Subscription</th>
                    <th>Total Purchasing </th>
                    <th>Parent Id </th>
                    <th>Reserve Expiry </th>
                    <th>Total Earnings </th>
                    <th>Total Child </th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                 
            @foreach ($info as $items)
                  
                  <tr>
                    
                    <td>{{ date('Y-m-d', strtotime($items->datetime)) }}</td>
                    <td>{{ $items->name }}</td>
                    <td>{{ $items->mobile }}</td>
                    <td>{{ $items->email }}</td>
                    <td> @if($items->subscriptionhistory->where('subscription_expiry','>',date('Y-m-d'))->count() > 0) Yes @else No @endif</td>
                    <td>{{ $items->subscriptionhistorypaymentchild->sum('offered_price') }}</td>
                    <td>{{ $items->customerparent->name."(".$items->customerparent->mobile.")" ?? "-" }}</td>
                    <td>{{ date('d-M-Y',strtotime($items->membership_expiry_at)) }}</td>
                    <td>{{ $items->subscriptionhistorypaymentchild->sum('comission_paid_amount') }}</td>
                    <td>{{ $items->customerallchild->count() ?? 0 }}</td>
                    <td> <a href="{{ url('update-user/'.$items->id)}}">
                     @if($items->status == 0)
                     <button type="button" class="btn btn-success">Active</button>
                     @else
                     <button type="button" class="btn btn-danger">Deactive</button>
                     @endif	
                     </a>
                  </td>
                    <td>
					<a href="{{url('view-user/'.$items->id)}}"><button type="button" class="btn btn-primary">View User Detail </button></a>
                        <a href="{{url('view-subscriptions/'.$items->id)}}"><button type="button" class="btn btn-primary">View All Subscrptions </button></a>
                        <a href="{{url('earnings/'.$items->id)}}"><button type="button" class="btn btn-primary">View All Earnings</button></a>
					</td>
                  </tr>
				  @endforeach
					
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
				  
				 
                  
                  </tbody>
                  <tfoot>
                    <tr>
                    
                     <th>Date & Time </th>
                    <th>Member Name </th>
                    <th>Mobile Number </th>
                    <th>Email Id  </th>
                    <th>Active Subscription</th>
                    <th>Total Purchasing </th>
                    <th>Parent Id </th>
                    <th>Reserve Expiry </th>
                    <th>Total Earnings </th>
                    <th>Total Child </th>
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