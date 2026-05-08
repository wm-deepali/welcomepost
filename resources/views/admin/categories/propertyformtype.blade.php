@extends('admin.layout.layout')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
   <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Property Form</h1>
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
                    <select name="property_type" id="user_type" class="form-control ca-check-plan" required="">
                      <option value="">Select Type </option>
                      <option value="Apartments">Apartments </option>
                      <option value="Builder Floors">Builder Floors</option>
                      <option value="Farm House">Farm House</option>
                      <option value="Houses And Villas">Houses And Villas</option>
                    </select>
                   
                </div>
				
				<div class="form-group ca-sh-user">
                    <select name="bedroom" id="bedroom" class="form-control ca-check-plan" required="">
                      <option value="">Bedrooms </option>
                      <option value="1">1 </option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="4 +">4 +</option>
                    </select>
                   
                </div>
				
				<div class="form-group ca-sh-user">
                    <select name="bathroom" id="bathrooms" class="form-control ca-check-plan" required="">
                      <option value="">Bathrooms </option>
                      <option value="1">1 </option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="4 +">4 +</option>
                    </select>
                   
                </div>
				
				 <div class="form-group ca-sh-user">
                    <select name="furnishing_status" id="user_type" class="form-control ca-check-plan" required="">
                      <option value="">Select Furnishing </option>
                      <option value="Furnished">Furnished </option>
                      <option value="Semi Furnished">Semi Furnished</option>
                      <option value="Un Furnished">Un Furnished</option>
                    </select>
                   
                  </div>
				  
				  <div class="form-group ca-sh-user">
                    <select name="construction_status" id="user_type" class="form-control ca-check-plan" required="">
                      <option value="">Construction Status </option>
                      <option value="New Launch">New Launch </option>
                      <option value="Ready to move">Ready to move</option>
                      <option value="Under construction">Under construction</option>
                    </select>
                   
                  </div>
				  
				  <div class="form-group ca-sh-user">
                    <select name="listed_by" id="user_type" class="form-control ca-check-plan" required="">
                      <option value="">Listed by </option>
                      <option value="Builder">Builder </option>
                      <option value="Dealer">Dealer </option>
                      <option value="Owner">Owner</option>
                    </select>
                   
                  </div>
				  
				
				  
                  <div class="form-group">
                    <input type="text"  name="builtup_area" id="builtup_area" class="form-control" placeholder="Super Builtup area"  required="" />
                  </div>
				  
				  <div class="form-group">
                    <input type="text"  name="carpet_area" id="carpet_area" class="form-control" placeholder="Carpet Area (ft²)"  required="" />
                  </div>
                 
				 <div class="form-group">
                    <input type="text"  name="maintenance" id="maintenance" class="form-control" placeholder="Maintenance (Monthly)"  required="" />
                  </div>
				  
				  <div class="form-group">
                    <input type="text"  name="total_floor" id="total_floor" class="form-control" placeholder="Total Floors"  required="" />
                  </div>
				  
				  <div class="form-group">
                    <input type="text"  name="floor_no" id="floor_no" class="form-control" placeholder="Floor No"  required="" />
                  </div>
				  
				   <div class="form-group ca-sh-user">
                    <select name="car_parking" id="user_type" class="form-control ca-check-plan" required="">
                      <option value="">Car Parking</option>
                      <option value="0">0</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="3 +">3 +</option>
                    </select>
                   
                  </div>
				  
				  <div class="form-group ca-sh-user">
                    <select name="facing" id="user_type" class="form-control ca-check-plan" required="">
                      <option value="">Facing </option>
                      <option value="Apartments">East </option>
                      <option value="North">North</option>
                      <option value="North-East">North-East</option>
                      <option value="North-West">North-West</option>
                      <option value="South">South</option>
                      <option value="South-East">South-East</option>
                      <option value="South-West">South-West</option>
                      <option value="West">West</option>
                    </select>
                  </div>
				  
				   <div class="form-group">
                    <input type="text"  name="project_name" id="email_id" class="form-control" placeholder="Project Name"  required="" />
                  </div>
				  
				  
				   <div class="form-group">
                    <input type="text"  name="ad_title" id="title" class="form-control" placeholder="Ad title"  required="" />
                  </div>
				   
				  <div class="form-group">
                    <textarea name="description" class="form-control"  placeholder="Description" rows="4" required=""></textarea>
				  </div>
                 
				 <div class="form-group">
                    <input type="text"  name="price" id="price" class="form-control" placeholder="Price"  required="" />
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