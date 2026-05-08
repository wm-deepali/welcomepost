@extends('admin.layout.layout')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>
            
              
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Contact Us Information</li>
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

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">
              <?php echo $info->name; ?>
            </h3>
          </div>
          <div class="card-body">
            <h4></h4>
            <div class="row">
             
			  
			<div class="col-12 col-sm-12">
		
			
			<div class="tab-content" id="vert-tabs-tabContent">

			<div class="tab-pane text-left fade show active" id="vert-tabs-home" role="tabpanel" aria-labelledby="vert-tabs-home-tab">

			<section class="content">
			<div class="container-fluid">
			<!-- SELECT2 EXAMPLE -->
			<div class="card card-default " >
			<div class="card-header headertop">
			<h3 class="card-title">User Details</h3>

			
			</div>
			<!-- /.card-header -->
			<div class="card-body">
			<div class="row">
			<div class="col-md-4">
			<div class="form-group">
			<p><label>Name</label></p>
			<p><?php echo $info->fullname; ?></p>
			</div>
			</div>

			<div class="col-md-4">



			<div class="form-group">
			<p><label>Mobile No</label></p>
			<p><?php echo $info->mobile; ?></p>
			</div>

			</div>

			<!-- /.col -->
			<div class="col-md-4">

			<div class="form-group">
			<p><label>Email</label></p>
			<p><?php echo $info->email; ?></p>
			</div>			
			</div>
			
			<div class="col-md-4">
			<div class="form-group">
			<p><label>Location</label></p>
			<p><?php echo $info->location; ?></p>
			</div>			
			</div>

			</div>

			</div>
			<!-- /.card-body -->


			<div class="card-header headertop">
			<h3 class="card-title">Ad Detail</h3>
			
			</div>

			<div class="card-body">
			<div class="row">
			
			<div class="col-md-4">
			<div class="form-group">
			<p><label>Ad Title</label></p>
			<p><?php echo $info->ad_title; ?></p>
			</div>
			</div>
			
			<div class="col-md-4">
			<div class="form-group">
			<p><label>Ad Type</label></p>
			<p><?php echo $info->ad_type; ?></p>
			</div>
			</div>
			
			<div class="col-md-4">
			<div class="form-group">
			<p><label>Description</label></p>
			<p><?php echo $info->description; ?></p>
			</div>
			</div>
			
			<div class="col-md-4">
			<div class="form-group">
			<p><label>Salary Period</label></p>
			<p><?php echo $info->salary_period; ?></p>
			</div>
			</div>

			<div class="col-md-4">
			<div class="form-group">
			<p><label>Position Type</label></p>
			<p><?php echo $info->position_type; ?></p>
			</div>

			</div>

			<!-- /.col -->
			<div class="col-md-4">

			<div class="form-group">
			<p><label>Salary From</label></p>
			<p><?php echo $info->salary_from; ?></p>
			</div>			
			</div>
			
			<div class="col-md-4">
			<div class="form-group">
			<p><label>Salary To</label></p>
			<p><?php echo $info->salary_to; ?></p>
			</div>			
			</div>

			</div>

			</div>
			<!-- /.card-body -->
			
			
			
			</div>

			<!-- /.row -->
			</div>

			</section>


			</div>

			</div>

			</div>
		  
		 
				 
                 
                
		   
        </div>
		
        </div>
		
		
      </div>
      </div>
	  
    </section>

			  
			
			</div>
		
 
	 
	  
  @endsection