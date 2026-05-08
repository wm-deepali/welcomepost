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
			<h3 class="card-title">Personal Profile</h3>

			<div class="card-tools">
			<a href="javascript:void()" data-toggle="modal" data-target="#personal-profile" ><i class="fa fa-edit"></i></a>
			</div>

			</div>
			<!-- /.card-header -->
			<div class="card-body">
			<div class="row">
			<div class="col-md-4">
			<div class="form-group">
			<p><label>Full Name</label></p>
			<p><?php echo $info->name; ?></p>
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

			</div>

			</div>
			<!-- /.card-body -->


			<div class="card-header headertop">
			<h3 class="card-title">Logo</h3>
			<div class="card-tools">
			<a href="javascript:void()" data-toggle="modal" data-target="#modal-edit-image" ><i class="fa fa-edit"></i></a>
			</div>
			</div>

			<div class="card-body">
			<div class="row">
			<div class="col-md-3">
			<img src="<?php echo $info->logo; ?>" class="img-thumbnail" alt=""  />	
			</div>
			</div>
			</div>

			<div class="card-header headertop">
			<h3 class="card-title">Profile Pic</h3>

			<div class="card-tools">
			<a href="javascript:void()" data-toggle="modal" data-target="#modal-edit-profile" ><i class="fa fa-edit"></i></a>
			</div>
			</div>

			<div class="card-body">
			<div class="row">
			<div class="col-md-3">
			<img src="<?php echo $info->profile_pic; ?>" class="img-thumbnail" alt=""  />	              </div>
			</div>
			</div>
			
			
			<div class="card-header headertop">
			<h3 class="card-title">Forgot Password</h3>

			</div>

			<div class="card-body">
			 <div class="row">
			  <form action="{{url('check-mail-otp')}}" method="post">
			  @csrf
              <div class="col-md-12">
                <div class="form-group">
                    <label for="exampleInputEmail1">Enter Email</label>
                    <input type="text" class="form-control" name="email" id="exampleInputEmail1" >
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
		
 
	  <div class="modal fade" id="personal-profile">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Personal Profile</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="{{url('update-profile-details')}}" method="post">
			  @csrf
			  <section class="content">
           
            <div class="row">
			
              <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" class="form-control" name="name" id="exampleInputEmail1" value="<?php echo $info->name; ?>">
                  </div>
              </div>
			  
			  <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputEmail1">Mobile No</label>
                    <input type="text" name="mobile" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "10" class="form-control" id="exampleInputEmail1" value=<?php echo $info->mobile; ?>>
                  </div>
              </div>
			  
			  <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" class="form-control" name="email" id="exampleInputEmail1" value="<?php echo $info->email; ?>">
                  </div>
              </div>
			 
            </div>
			
			
           </section>
			 
			 
          </div>
		  
		   <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
			
			</form>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      </div>
	  
	  
	   <div class="modal fade" id="modal-edit-image">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4  class="modal-title">Logo</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
             
			  <section class="content">
        
            <div class="row">
			
		<div class="col-md-12">
		 <div class="form-group">
		<label for="exampleInputFile">Image</label>
		<form action="{{url('update-profile-logo')}}" method="post" enctype="multipart/form-data" >
		@csrf
		<div class="input-group">
		<div class="custom-file">
		<input type="file" name="file" class="custom-file-input" id="exampleInputFile">
		<label class="custom-file-label" for="exampleInputFile">Choose file</label>
		</div>
		<div class="input-group-append">
		</div>
		</div>
		</div> 

		</div>
		</div>
		</section>
		
		 </div>
           
             <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
		</form>
         
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      </div>
	  
	  
	  <div class="modal fade" id="modal-edit-profile">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4  class="modal-title">Profile Pic</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
             
			  <section class="content">
        
            <div class="row">
			
		<div class="col-md-12">
		 <div class="form-group">
		<label for="exampleInputFile">Image</label>
		<form action="{{url('update-profile-pic')}}" method="post" enctype="multipart/form-data" >
		@csrf
		<div class="input-group">
		<div class="custom-file">
		<input type="file" name="file" class="custom-file-input" id="exampleInputFile">
		<label class="custom-file-label" for="exampleInputFile">Choose file</label>
		</div>
		<div class="input-group-append">
		</div>
		</div>
		</div> 

		</div>
		</div>
		</section>
		
		 </div>
           
             <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
		</form>
         
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      </div>
	  
	 
	  
	  
  @endsection