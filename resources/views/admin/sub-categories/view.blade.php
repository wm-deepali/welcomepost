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
              <li class="breadcrumb-item active">Sub Category Details</li>
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
			<h3 class="card-title">Details</h3>
			</div>
			<!-- /.card-header -->
			<div class="card-body">
			<div class="row">
			
			<div class="col-md-3">
			<div class="form-group">
			<p><label>Category</label></p>
			<p><?php
					$result = DB::table('categories')->where('id',$info->category_id)->get();
					echo $result[0]->name;
					?></p>
			</div>
			</div>
			
			<div class="col-md-3">
			<div class="form-group">
			<p><label>Name</label></p>
			<p>{{$info->name}}</p>
			</div>
			</div>

			<div class="col-md-3">
			<div class="form-group">
			<p><label>Url</label></p>
			<p>{{$info->url}}</p>
			</div>
			</div>
			
			
			
			<div class="col-md-3">
			<div class="form-group">
			<p><label>Meta Title</label></p>
			<p>{{$info->meta_title}}</p>
			</div>
			</div>
			
			<div class="col-md-3">
			<div class="form-group">
			<p><label>Meta Keyword</label></p>
			<p>{{$info->meta_keyword}}</p>
			</div>
			</div>
			
			<div class="col-md-3">
			<div class="form-group">
			<p><label>Meta Description</label></p>
			<p>{{$info->meta_description}}</p>
			</div>
			</div>
			
			<div class="col-md-3">
			<div class="form-group">
			<p><label>Icon</label></p>
			<p><img src="{{$info->icon}}" style="height:50px;width:50px;" class="img-thumbnail" alt=""  />	</p>
			</div>
			</div>
			
			
			

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