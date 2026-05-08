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
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Payment Gateway Setting</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Payment Gateway Setting</li>
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
              
                    <!-- /.card-header -->
                    <!-- form start -->
                        <form action="{{url('update-razorpay-setting')}}" role="form" id="quickForm" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <img src="https://razorpay.com/build/browser/static/razorpay-logo.5cdb58df.svg" style="width:80%;">
                                </div>
                                <div class="form-group">
                                    <label>Key Id</label>
                                    <input type="text" value="{{$setting->key_id}}" name="key_id" class="form-control"  placeholder="Enter Key Id" required>
                                </div>
				            @csrf
				  
    				            <div class="form-group">
                                    <label for="exampleInputEmail1">Secret Id</label>
                                    <input type="text" name="secret_id" value="{{$setting->secret_id}}" class="form-control"  placeholder="Enter Secret Id" required>
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
            {{--<div class="row">
                <!-- left column -->
                    <div class="col-md-12">
                    <!-- jquery validation -->
                        <div class="card card-primary">
                  
                        <!-- /.card-header -->
                        <!-- form start -->
                            <form action="{{url('update-cashfree-setting')}}" role="form" id="quickForm2" method="post" enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="form-group">
                                        <img src="https://cashfreelogo.cashfree.com/website/NavFooter/Cashfree-Dark.svg" style="width:80%;">
                                    </div>
                                    <div class="form-group">
                                        <label for="key_id_cash">Key Id</label>
                                        <input type="text" value="{{ $cashsetting->key_id ?? '' }}" name="key_id_cash" class="form-control"  placeholder="Enter Key Id" required>
                                    </div>
                                @csrf
                      
                                    <div class="form-group">
                                        <label for="secret_id_cash">Secret Id</label>
                                        <input type="text" name="secret_id_cash" value="{{ $cashsetting->secret_id ?? '' }}" class="form-control"  placeholder="Enter Secret Id" required>
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
                </div>--}}
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
@endsection