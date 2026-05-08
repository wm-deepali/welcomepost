@extends('admin.layout.layout')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit City</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit City</li>
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
	
	@if ($errors->any())
	<div class="card-body">
	<div class="alert alert-danger alert-dismissible">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

	@foreach ($errors->all() as $error)
	<p>{{ $error }}</p>
	@endforeach

	</div>


	</div>

	@endif
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Edit Details</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="{{url('post-edit-city')}}" role="form" id="quickForm" method="post" enctype="multipart/form-data">
                <div class="card-body">
				
				<div class="form-group">
                    <label for="exampleInputEmail1">Country</label>
                    <input type="text" disabled name="country_id" class="form-control"
					
					value="<?php
					$result = DB::table('countries')->where('id',$info->country_id)->get();
					echo $result[0]->name;
					?>">
					
                  </div>
				  
				  <div class="form-group">
                    <label for="exampleInputEmail1">State</label>
                    <input type="text" disabled name="state_id" class="form-control" 
					
					value="<?php
					$result = DB::table('states')->where('id',$info->state_id)->get();
					echo $result[0]->name;
					?>">
					
                  </div>
				  
                  <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" name="name" class="form-control" value="{{$info->name}}">
                    <input type="hidden" name="id" class="form-control" value="{{$info->id}}">
                    <input type="hidden" name="country" class="form-control" value="{{$info->country_id}}">
                    <input type="hidden" name="state" class="form-control" value="{{$info->state_id}}">
                  </div>
				  @csrf
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
            </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-6">

          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  
 
  @endsection