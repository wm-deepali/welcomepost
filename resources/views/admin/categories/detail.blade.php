@extends('admin.layout.layout')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Categories</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Categories</li>
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
              <form action="{{url('post-edit-categories')}}" role="form" id="quickForm" method="post" enctype="multipart/form-data">
                <div class="card-body">
				
				<div class="form-group">
                    <label for="exampleInputEmail1">Form Type</label>
                    @php
                        	$result = DB::table('formtypes')->get();
                    @endphp
					<select name="formtype" class="form-control" id="formtype">
					    @foreach($result as $res)
					    @if($res->id==$info->formtype)
					        <option selected="selected" value="{{$res->id}}">{{$res->type}}</option>
				        @else
				            <option value="{{$res->id}}">{{$res->type}}</option>
				        @endif
					    @endforeach
					</select>
                  </div>
				  
				  
                  <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" name="name" class="form-control" value="{{$info->name}}">
                    <input type="hidden" name="id" class="form-control" value="{{$info->id}}">
                  </div>
				  @csrf
				  
				  
				  <div class="form-group">
                    <label for="exampleInputEmail1">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control"  value="{{$info->meta_title}}">
                  </div>
				  
				  
				   <div class="form-group">
                    <label for="exampleInputEmail1">Meta Keyword</label>
                    <input type="text" name="meta_keyword" class="form-control"  value="{{$info->meta_keyword}}">
                  </div>
				  
				   <div class="form-group">
                    <label for="exampleInputEmail1">Meta Description</label>
                    <input type="text" name="meta_description" class="form-control"  value="{{$info->meta_description}}">
                  </div>
				  
				<div class="form-group">
                    <label for="exampleInputFile">Image</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" name="file" class="custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                      </div>
                      <div class="input-group-append">
                        <span class="input-group-text" id="">Upload</span>
                      </div>
                    </div>
                  </div>
				  
				  <div class="form-group">
				    <img src="{{$info->image}}" style="height:50px;width:50px;">
					<input type="hidden" name="old_image" class="form-control"  value="{{$info->image}}">
					<input type="hidden" name="old_image2" class="form-control"  value="{{$info->icon}}">

                  </div>
				  
				  <div class="form-group">
                    <label for="exampleInputFile">Icon</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" name="filetwo" class="custom-file-input" id="exampleInputFile2">
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                      </div>
                      <div class="input-group-append">
                        <span class="input-group-text" id="">Upload</span>
                      </div>
                    </div>
                  </div>
				  
				  
				  <div class="form-group">
				    <img src="{{$info->icon}}" style="height:50px;width:50px;">

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