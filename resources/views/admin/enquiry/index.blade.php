@extends('admin.layout.layout')
@section('content')
  <div class="content-wrapper">
  
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Enquiry</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Ads Enquiry</li>
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
              
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Category</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Message</th>
                    <th>Registered Date</th>
                  </tr>
                  </thead>
                  <tbody>
                  
                  @foreach($enquiry as $key => $orderDetails)
                  <tr>
                    <td>{{$key + 1}}</td>
                    <td>
					{{$orderDetails->enquiry_category}}
					</td>
                    <td>{{$orderDetails->enquiry_name}}</td>
                    <td>{{$orderDetails->enquiry_email}}</td>
                    <td>{{$orderDetails->enquiry_mobile}}</td>
                    <td>{{$orderDetails->enquiry_message}}</td>
                   
					
					<td>{{$orderDetails->created_at}}</td>
                   
                  </tr>
				  
					@endforeach	
                  
                  </tbody>
                  <tfoot>
                  <tr>
                     <th>S.No</th>
                    <th>Category</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Message</th>
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