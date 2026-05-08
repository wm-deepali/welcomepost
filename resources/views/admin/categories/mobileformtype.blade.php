@extends('admin.layout.layout')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Mobile Form</h1>
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
                <h3 class="card-title">Form Details</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body">
				
				 <div class="form-group ca-sh-user">
                    <select name="brands" id="brands" class="form-control ca-check-plan" required="">
                      <option value="">Brand </option>
                      <option value="Hourly">iphone</option>
                      <option value="Monthly">samsung</option>
                    </select>
                   
                  </div>
				  
				
				  
                  <div class="form-group">
                    <input type="text"  name="ad_title" id="email_id" class="form-control" placeholder="Ad title"  required="" />
                  </div>
				  
				  <div class="form-group">
                    <textarea name="description" class="form-control"  placeholder="Description" rows="4" required=""></textarea>
				  </div>
                 
				 <div class="form-group">
                    <input type="text"  name="price" id="email_id" class="form-control" placeholder="Price"  required="" />
                  </div>
				  
				  <div class="form-group">
                    <input type="file"  name="file" id="ewqeweq" class="form-control" placeholder="Description"  required="" />
                  </div>
				   
				
				  
				  <h4>Confirm Your Location</h4>
				  <div class="form-group ca-sh-user">
                    <select name="state" id="state" class="form-control ca-check-plan" required="" >
                      <option value=""> ----------------- Select State ------------- </option>
						
					
					</select>
                   
                  </div>
				  
				  <script>
					$(document).ready(function(){
					$("#state").change(function(){
					var state_id = $('#state').val();
					//alert(state_id);
					$.ajax({
						url:'{{url("get-city")}}',
						method:'POST',
						data:{state_id:state_id,'_token':"{{csrf_token()}}"},
						success:function(data){
						//alert(data);	
						$('#city').html(data);
						}
						});

					});

					});
				</script>
				
				  <div class="form-group ca-sh-user">
                    <select name="city" id="city" class="form-control ca-check-plan" required="" >
                      <option value=""> ----------------- Select city ------------- </option>
					</select>
				  </div>
				  
				  <script>
					$(document).ready(function(){
					$("#city").change(function(){
					var city_id = $('#city').val();
					//alert(state_id);
					$.ajax({
						url:'{{url("get-location")}}',
						method:'POST',
						data:{city_id:city_id,'_token':"{{csrf_token()}}"},
						success:function(data){
						//alert(data);	
						$('#neibourhood').html(data);
						}
						});

					});

					});
				</script>
				
				  
				  <div class="form-group ca-sh-user">
                    <select name="neibourhood" id="neibourhood" class="form-control ca-check-plan">
                      <option value=""> ----------------- Select location ------------- </option>
					</select>
				  </div>
				  
				
				  
				  
				 
				  
                </div>
                <!-- /.card-body -->
              
              
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