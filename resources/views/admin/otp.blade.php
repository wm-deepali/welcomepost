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
              <li class="breadcrumb-item active">Admin Profile</li>
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
              <div class="col-5 col-sm-3" style="background:#f2f2f2;">
                <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
					<a class="nav-link side-link active" id="vert-tabs-home-tab" data-toggle="pill" href="#vert-tabs-home" role="tab" aria-controls="vert-tabs-home" aria-selected="true">Personal Profile </a>
				</div>
              </div>
			  
			<div class="col-7 col-sm-9">
		
			
			<div class="tab-content" id="vert-tabs-tabContent">

			<div class="tab-pane text-left fade show active" id="vert-tabs-home" role="tabpanel" aria-labelledby="vert-tabs-home-tab">

			<section class="content">
			<div class="container-fluid">
			<!-- SELECT2 EXAMPLE -->
			<div class="card card-default " >
			
			<div class="card-header headertop">
			<h3 class="card-title">OTP</h3>

			</div>

			<div class="card-body">
			 <div class="row">
			  <form action="{{url('otp-validate')}}" method="post">
			  @csrf
              <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputEmail1">Enter OTP</label>
                    <input type="text" class="form-control" name="otp" id="exampleInputEmail1" value="<?php echo $info->otp; ?>" >
                </div>
              </div>
			  
			  <div class="col-md-12">
                <div class="form-group">
				 <button type="submit" class="btn btn-primary">send</button>
				 </a>
                </div>
              </div>
			  </form>
			 </div>
			</div>


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