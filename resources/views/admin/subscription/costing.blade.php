@extends('admin.layout.layout')
@section('content')
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff !important;
        border : 1px solid #343a40 !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #343a40 !important;
    }
    .custom-control.custom-switch label {
	padding-left: 30px;
	padding-top: 4px;
}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1> Per Ads Costing</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Per Ads Costing</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
	
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
	
		@if (session('success'))
	<div class="card-body">
	<div class="alert alert-success alert-dismissible">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h5>{{ Session::get('success') }}</h5>
	<?php Session::forget('success');?>
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
                
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <form action="{{url('post-ads-costing')}}" role="form" id="quickForm" method="post" enctype="multipart/form-data">
                 @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Per Ads Costing</label>
                            <input type="text" name="ad_costing" value="{{ $costing->ad_costing}}" class="form-control"  placeholder="Enter Per Ads Costing" required>
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
    </div>
    <!-- /.row -->
     </div><!-- /.container-fluid -->
</section>
    <!-- /.content -->
  </div>
  @endsection