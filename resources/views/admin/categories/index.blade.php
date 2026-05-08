@extends('admin.layout.layout')
@section('content')
  <div class="content-wrapper">
  
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Categories</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Categories</li>
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
	
	
	
	 <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
		  
		
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><a href="{{url('add-categories')}}"><button type="button" class="btn btn-block bg-gradient-primary">Add Categories</button></a></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Image</th>
                    <th>Icon</th>
                    <th>Category</th>
                    <th>Sub Category</th>
                    <th>Total Ads</th>
                    <th>Status</th>
                    <th>Form Type</th>
                    <th>Registered Date</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  
                  @foreach($categories as $key => $orderDetails)
                  <tr>
                    <td>{{$key + 1}}</td>
                    <td><img src="{{$orderDetails->image}}" style="height:50px;width:50px;"></td>
                    <td><img src="{{$orderDetails->icon}}" style="height:50px;width:50px;"></td>
                    <td>{{$orderDetails->name}}</td>
                    <td>
					<?php
					$id_exists = DB::table('subcategories')->where('category_id',$orderDetails->id)->exists();
					if($id_exists){
					 
					echo $count_cat = DB::table('subcategories')->where('category_id',$orderDetails->id)->count('id');
						
					}else{
				    echo '0';		
					}
					
					?>
					
					</td>
					
					<td>
					<?php echo $count_cat = DB::table('ads_postings')->where('category_id',$orderDetails->id)->where('status','1')->count('id');?>
					</td>
					
					 <td>
					{{--<?php if($orderDetails->premium == 0){ ?>
					<a href="{{url('update-premium',['event'=>$orderDetails->id,'user'=>$orderDetails->premium])}}"><span class="badge badge-danger">Set as premium</span></a><br>
					<?php }else{ ?>
					<a href="{{url('update-premium',['event'=>$orderDetails->id,'user'=>$orderDetails->premium])}}"><span class="badge badge-success">Premium</span></a><br>
					<?php } ?>--}}
					
					<?php if($orderDetails->top == 0){ ?>
					<a href="{{url('update-top',['event'=>$orderDetails->id,'user'=>$orderDetails->top])}}"><span class="badge badge-danger">Set as top</span></a><br>
					<?php }else{ ?>
					<a href="{{url('update-top',['event'=>$orderDetails->id,'user'=>$orderDetails->top])}}"><span class="badge badge-success">Top</span></a><br>
					<?php } ?>
					
					
					<?php if($orderDetails->trending == 0){ ?>
					<a href="{{url('update-trending',['event'=>$orderDetails->id,'user'=>$orderDetails->trending])}}"><span class="badge badge-danger">Set as trending</span></a><br>
					<?php }else{ ?>
					<a href="{{url('update-trending',['event'=>$orderDetails->id,'user'=>$orderDetails->trending])}}"><span class="badge badge-success">Trending</span></a><br>
					<?php } ?>
					
					 </td> 

					<td>
					
					<?php
					$result = DB::table('formtypes')->where('id',$orderDetails->formtype)->get();
					echo $result[0]->type.'<br>';
					?>
					<a href="{{url('view-form/'.$orderDetails->formtype)}}"><span class="badge badge-info">View Form</span></a>

					</td>

					
					
					<td>{{$orderDetails->created_at}}</td>
                    <td>
						 
						<a href="{{url('view-categories/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary"><i class="fa fa-edit"></i></button></a>
						<button type="button" class="btn btn-danger"  data-toggle="modal" data-target="#modal-delete<?php echo $orderDetails->id; ?>"><i class="fa fa-trash"></i></button>
                       
					  </td>
                  </tr>
				  
					
				  <div class="modal fade" id="modal-delete<?php echo $orderDetails->id; ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Alert</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p>Are You Sure You Want To Delete This Item ?</p>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
              <a href="{{url('delete-categories/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary">Yes</button></a>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
				  
				  @endforeach	
                  
                  </tbody>
                  <tfoot>
                     <tr>
                    <th>S.No</th>
                    <th>Image</th>
                    <th>Icon</th>
                    <th>Category</th>
                    <th>Sub Category</th>
                    <th>Total Ads</th>
                    <th>Status</th>
                    <th>Form Type</th>
                    <th>Registered Date</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
			
			
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
	
	
  
  
   </div>
  @endsection