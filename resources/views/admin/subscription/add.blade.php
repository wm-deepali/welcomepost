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
            <h1>Add Subscription</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add Subscription</li>
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
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Details</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="{{url('post-add-subscription')}}" role="form" id="quickForm" method="post" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label>Category</label>
                    <select class="select2 fav_clr" name="category_id[]" multiple="multiple" data-placeholder="Select a Category" style="width: 100%;">
                       <option>All</option>
                        @foreach($categories as $key => $orderDetails)
							<option title="{{ $orderDetails->name }}" value="{{$orderDetails->id}}" >{{$orderDetails->name}}</option>
					    @endforeach
                    </select>

					
                  </div>
				  @csrf
				  
				  <div class="form-group">
                    <label for="exampleInputEmail1">Subscription Name</label>
                    <input type="text" name="package" class="form-control"  placeholder="Enter Package">
                  </div>
				  
				  <div class="form-group">
                    <label for="exampleInputEmail1">Subscription Validity</label>
                    <input type="text" name="package_validity" id="package_validity" class="form-control"  placeholder="Enter Subscription Validity">
                  </div>
				  
				   
				  
				   <div class="form-group">
                    <label for="exampleInputEmail1">Total No Of Ads</label>
                    <input type="text" name="no_of_ads" class="form-control"  placeholder="Enter Total No Of Ads">
                  </div>
				  
				  <div class="form-group">
                    <label for="exampleInputEmail1">Ads Validity</label>
                    <input type="text" name="ads_validity" id="ads_validity" class="form-control"  placeholder="Enter Validity">
                  </div>
				  
				  <div class="form-group">
                    <label for="exampleInputEmail1">Per Ads Costing</label>
                    <input type="text" name="ads_costing" class="form-control"  placeholder="Enter Per Ads Costing">
                  </div>
				  
				  <div class="form-group">
                    <label for="exampleInputEmail1">MRP</label>
                    <input type="text" name="mrp" id="mrp" class="form-control"  placeholder="Enter MRP">
                  </div>
				  
				  
				   <div class="form-group">
                    <label for="exampleInputEmail1">Discount (%)</label>
                    <input type="text" name="discount" id="discount" class="form-control"  placeholder="Enter Discount">
                  </div>
				  
				   <div class="form-group">
                    <label for="exampleInputEmail1">Offered Price</label>
                    <input type="text" name="offered_price" id="offer_price" class="form-control"  placeholder="Enter Offered Price">
                  </div>
                  
                  <div class="custom-control custom-switch">
                    <input type="checkbox" value="yes" name="is_free" class="custom-control-input" id="customSwitch1">
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
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });
    $('.fav_clr').on("select2:select", function (e) { 
       var data = e.params.data.text;
       if(data=='All')
       {
            $(".fav_clr > option").prop("selected","selected");
            $(".fav_clr").trigger("change");
       }
    });

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservationdate').datetimepicker({
        format: 'L'
    });
    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY hh:mm A'
      }
    })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'LT'
    })
    
    //Bootstrap Duallistbox
    $('.duallistbox').bootstrapDualListbox()

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    $('.my-colorpicker2').on('colorpickerChange', function(event) {
      $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
    });

    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });

  })
</script>
  
  @endsection