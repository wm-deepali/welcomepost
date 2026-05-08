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
            <h1>Edit Subscription</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Subscription</li>
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
              <form action="{{url('post-edit-subscription')}}" role="form" id="quickForm" method="post" enctype="multipart/form-data">
                <div class="card-body">
                 
				 <div class="form-group">
                    <label for="exampleInputEmail1">Category</label>
                    
                    <select class="select2 fav_clr" name="category_id[]" multiple="multiple" data-placeholder="Select a Category" style="width: 100%;">
                        <option value="All">All</option>
                        @php
                            $category_list = explode(',', $info->category_id);
                        @endphp
                        @foreach($categories as $key => $orderDetails)
                            <option title="{{ $orderDetails->name }}" value="{{ $orderDetails->id }}" {{ in_array($orderDetails->id, $category_list) ? 'selected' : '' }}>
                                {{ $orderDetails->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
				 
				  <div class="form-group">
                    <label for="exampleInputEmail1">Subscription Name</label>
                    <input type="text" name="package" class="form-control"  value="{{$info->package}}">
					<input type="hidden" name="id" class="form-control" value="{{$info->id}}">
                  </div>
				   @csrf
				  
				  <div class="form-group">
                    <label for="exampleInputEmail1">Subscription Validity</label>
                    <input type="text" id="package_validity" name="package_validity" value="{{$info->package_validity}}"class="form-control"  placeholder="Enter Subscription Validity">
                  </div>
                  
				   <div class="form-group">
                    <label for="exampleInputEmail1">Total No Of Ads</label>
                    <input type="text" name="no_of_ads" class="form-control"  value="{{$info->no_of_ads}}">
                  </div>
				  
				  <div class="form-group">
                    <label for="exampleInputEmail1">Ads validity</label>
                    <input type="text" id="ads_validity" name="ads_validity" class="form-control"  value="{{$info->ads_validity}}">
                  </div>
                  
                  <div class="form-group">
                    <label for="exampleInputEmail1">Per Ads Costing</label>
                    <input type="text" name="ads_costing" class="form-control" value="{{$info->ads_costing}}"  placeholder="Enter Per Ads Costing">
                  </div>
				  
				  
				  
				  <div class="form-group">
                    <label for="exampleInputEmail1">MRP</label>
                    <input type="text" name="mrp" id="mrp" class="form-control"  value="{{$info->mrp}}">
                  </div>
				  
				  
				   <div class="form-group">
                    <label for="exampleInputEmail1">Discount</label>
                    <input type="text" name="discount" id="discount" class="form-control"  value="{{$info->discount}}">
                  </div>
				  
				   <div class="form-group">
                    <label for="exampleInputEmail1">Offered Price</label>
                    <input type="text" name="offered_price" id="offer_price" class="form-control"  value="{{$info->offered_price}}">
                  </div>
				  
				  <div class="custom-control custom-switch">
                    <input type="checkbox" value="yes" name="is_free" class="custom-control-input" id="customSwitch1" @if($info->is_free == 'yes') checked @endif>
                    <label class="custom-control-label" for="customSwitch1">Is Free Subscription ?</label>
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  
<script>
     $(document).on('keyup', "#mrp", function(event) {
        let default_price   = $(this).val();
        let discount        = $('#discount').val();
        
        if((default_price !='' && default_price != 0) && (discount !='' && discount != 0))
        {
            let offer_price = default_price * (discount/100);
            $('#offer_price').val('');
            $('#offer_price').val(default_price - offer_price);
        }
    });
    
    $(document).on('keyup', "#discount", function(event) {
        let default_price   = $('#mrp').val();
        let discount        = $('#discount').val();
        if((default_price !='' && default_price != 0) && (discount !='' && discount != 0))
        {
            let offer_price = default_price * (discount/100);
            $('#offer_price').val('');
            $('#offer_price').val(default_price - offer_price);
        }
    });
    
    $(document).on('keyup', "#offer_price", function(event) {
        let offer_price   = $(this).val();
        let mrp        = $('#mrp').val();
        
        if((offer_price !='' && offer_price != 0) && (mrp !='' && mrp != 0))
        {
            let discount = mrp - offer_price;
            let disPercent = (discount /mrp) * 100;
           
            $('#discount').val(disPercent);
        }
    });
    
    $("#package_validity").on("keyup", function(){
	    let ads = $(this).val();
	    $("#ads_validity").val(ads);
    });

</script>
<script>
$(function () {
    $('.select2').select2();

    // Initialize Select2 with Bootstrap4 theme for elements with class 'select2bs4'
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });

    // Handle the 'select' event for elements with class 'fav_clr'
    $('.fav_clr').on("select2:select", function(e) {
        var data = e.params.data.text;
        if (data === 'All') {
            // Select all options if 'All' is selected
            $(".fav_clr > option").prop("selected", "selected");
            $(".fav_clr").trigger("change");
        }
    });

    // Handle the 'unselect' event to deselect 'All' if any other option is deselected
    $('.fav_clr').on("select2:unselect", function(e) {
        var data = e.params.data.text;
        if (data !== 'All') {
            // Deselect 'All' if any other option is deselected
            $(".fav_clr > option[value='All']").prop("selected", false);
            $(".fav_clr").trigger("change");
        }
    });
    
})
</script>
 
  @endsection