@extends('admin.layout.layout')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Free Trail Subscription</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Free Trail Subscription</li>
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
              <form action="{{url('post-edit-freetrail')}}" role="form" id="quickForm" method="post" enctype="multipart/form-data">
                <div class="card-body">
                 
				 <div class="form-group">
                    <label for="exampleInputEmail1">Category</label>
                    <input type="text" disabled name="categories" class="form-control"  
					
					value="<?php
					$result = DB::table('categories')->where('id',$info->category_id)->get();
					echo $result[0]->name;
					?>">
					
					
                  </div>
				 
				  
				   @csrf
				  
				   <div class="form-group">
                    <label for="exampleInputEmail1">Total No Of Ads</label>
                    <input type="text" name="no_of_ads" class="form-control"  value="{{$info->no_of_ads}}">
                    <input type="hidden" name="id" class="form-control" value="{{$info->id}}">
					<input type="hidden" name="category_id" class="form-control" value="{{$info->category_id}}">
				  </div>
				  
				  <div class="form-group">
                    <label for="exampleInputEmail1">Ads validity</label>
                    <input type="text" name="ads_validity" class="form-control"  value="{{$info->ads_validity}}">
                  </div>
				  
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