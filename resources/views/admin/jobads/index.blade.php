@extends('admin.layout.layout')
@section('content')
  <div class="content-wrapper">
  
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Ad Posting by Users</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Ad Posting by Users</li>
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
              
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Category</th>
                    <th>Sub Category</th>
                    <th>Ad Title</th>
                    <th>Ad type</th>
                    <th>Status</th>
                    <th>Registered Date</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  
                  @foreach($jobads as $key => $orderDetails)
                  <tr>
                    <td>{{$key + 1}}</td>
					<td><img src="{{$orderDetails->image}}" style="height:50px;width:50px;"></td>
                    <td>{{$orderDetails->fullname}}</td>
                    <td>{{$orderDetails->mobile}}</td>
                    <td>
					<?php
					$result = DB::table('categories')->where('id',$orderDetails->category_id)->get();
					echo $result[0]->name;
					?>
					</td>
					<td>
					<?php
					$result = DB::table('subcategories')->where('id',$orderDetails->sub_category_id)->get();
					echo $result[0]->name;
					?>
					</td>
                    <td>{{$orderDetails->ad_title}}</td>
                    <td>{{$orderDetails->ad_type}}</td>
                     
					<td width="15%"> 
                      <select name="demo" id="status<?php echo $orderDetails->id;?>" class="form-control">
                          <option value="0" <?php if($orderDetails->status == 0){echo 'selected';}?>>Pending</option>
                          <option value="1" <?php if($orderDetails->status == 1){echo 'selected';}?> > Publish</option>
                          <option value="2" <?php if($orderDetails->status == 2){echo 'selected';}?> >Reject</option>
                               
                      </select>  
					</td>
					<input type="hidden" id="statusval<?php echo $orderDetails->id;?>" value="<?php echo $orderDetails->status; ?>">
                    <input type="hidden" id="proff_id<?php echo $orderDetails->id;?>" value="<?php echo $orderDetails->id; ?>">
                    
					<td>{{$orderDetails->created_at}}</td>
                    <td>
						<a href="{{url('view-job-ads/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary"><i class="fa fa-eye"></i></button></a>
						<a href="{{url('edit-job-ads/'.$orderDetails->id)}}"><button type="button" class="btn btn-warning"><i class="fa fa-edit"></i></button></a>
						<button type="button" class="btn btn-danger"  data-toggle="modal" data-target="#modal-delete<?php echo $orderDetails->id; ?>"><i class="fa fa-trash"></i></button>
					</td>
                  </tr>
				  
				  
				  <script>
					$(document).ready(function(){
					$("#status<?php echo $orderDetails->id;?>").change(function(){
						
						
						
						var status = $('#status<?php echo $orderDetails->id;?>').val();
						var proff_id = $('#proff_id<?php echo $orderDetails->id;?>').val();
						
					
						
					$.ajax({
					url:'{{url("change-job-ad-status")}}',
					method:'POST',
					data:{status:status,proff_id:proff_id,'_token':"{{csrf_token()}}"},
					success:function(data){
					location.reload(true)
					}
					});
			
					

					});
					});
					</script>
				  
					
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
							  <a href="{{url('delete-job-ads/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary">Yes</button></a>
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
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Category</th>
					<th>Sub Category</th>
                    <th>Ad Title</th>
                    <th>Ad type</th>
                    <th>Status</th>            
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