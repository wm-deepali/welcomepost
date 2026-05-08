@extends('admin.layout.layout')
@section('content')
  <div class="content-wrapper">
  
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Admin Setting</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Admin Setting</li>
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
              <div class="card-header">
              <!--<h3 class="card-title"> <a href="{{url('add-admin-settings')}}" class="btn btn-block bg-gradient-primary">Add Admin Setting</a></h3>-->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S.No</th>
                    <th>With in Pan Card</th>
                    <th>With out Pan Card</th>
                    <th>Admin Charges</th>
                    <th>Other Charges (%)</th>
                    <th>Reserve Member expiry</th>
                    <th>IGST</th>
                    <th>CGST</th>
                    <th>SGST</th>
                    <th>Company Name</th>
                    <th>GST Number</th>
                    <th>Full Address </th>
                    <th>Email Id</th>
                    <th>Contact No</th>
                    <th>Prefix Number </th>
                    <th>Serial Number</th>
                    <th>Referal Join</th>
                    <th>Auto Join</th>
                    <th>Number Of View</th>
                    <th>Last Update</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  
                  @foreach($adminsetting as $key => $orderDetails)
                  <tr>
                    <td>{{$key + 1}}</td>
                    <td>{{$orderDetails->with_in_pan}}</td>
                    <td>{{$orderDetails->with_out_pan}}</td>
                    <td>{{$orderDetails->admin_charges}}</td>
                    <td>{{$orderDetails->other_charges}}</td>
                    <td>{{$orderDetails->reserve_member_expiry}}</td>
                    <td>{{$orderDetails->igst}}</td>
                    <td>{{$orderDetails->cgst}}</td>
                    <td>{{$orderDetails->sgst}}</td>
                    <td>{{$orderDetails->company_name}}</td>
                    <td>{{$orderDetails->gstno}}</td>
                    <td>{{$orderDetails->full_address}}</td>
                    <td>{{$orderDetails->email_id}}</td>
                    <td>{{$orderDetails->contact_no}}</td>
                    <td>{{$orderDetails->prefix_number}}</td>
                    <td>{{$orderDetails->serial_number}}</td>
                    <td>{{$orderDetails->referal_join}}</td>
                    <td>{{$orderDetails->auto_join}}</td>
                    <td>{{$orderDetails->numer_of_view}}</td>
                    <td>{{$orderDetails->updated_at}}</td>
                    <td>
                     <a href="{{url('edit-admin-setting/'.$orderDetails->id)}}"  class="btn btn-primary"><i class="fa fa-edit"></i></a>
						          <button type="button" class="btn btn-danger" style="margin-left:15px;"  data-toggle="modal" data-target="#modal-delete<?php echo $orderDetails->id; ?>"><i class="fa fa-trash"></i></button>

					  </td>
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
							  <a href="{{url('delete-blog/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary">Yes</button></a>
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
                    <th>With in Pan Card</th>
                    <th>With out Pan Card</th>
                    <th>Admin Charges</th>
                    <th>Other Charges (%)</th>
                    <th>Reserve Member expiry</th>
                    <th>IGST</th>
                    <th>CGST</th>
                    <th>SGST</th>
                    <th>Company Name</th>
                    <th>GST Number</th>
                    <th>Full Address </th>
                    <th>Email Id</th>
                    <th>Contact No</th>
                    <th>Prefix Number </th>
                    <th>Serial Number</th>
                    <th>Referal Join</th>
                    <th>Auto Join</th>
                    <th>Number Of View</th>
                    <th>Last Update</th>
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