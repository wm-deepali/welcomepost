@extends('admin.layout.layout')
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<div class="content-wrapper">

<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6">
<h1>Post Ads</h1>
</div>

<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="#">Home</a></li>
<li class="breadcrumb-item active">Post Ads</li>
</ol>
</div>


</div>
</div>
</section>

<section class="content">
<div class="container-fluid">
<div class="row">


<div class="col-md-12">
<div class="card">
<div class="card-header p-2">
<ul class="nav nav-pills">
<li class="nav-item"><a class="nav-link active" href="#pending" data-toggle="tab">Pending</a></li>
<li class="nav-item"><a class="nav-link" href="#published" data-toggle="tab">Published</a></li>
<li class="nav-item"><a class="nav-link" href="#rejected" data-toggle="tab">Rejected</a></li>
</ul>
</div>


<div class="card-body">
<div class="tab-content">

<div class="active tab-pane" id="pending">

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
					<th>Reject</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  
                  @foreach($pending as $key => $orderDetails)
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
                      <select name="document" id="status<?php echo $orderDetails->id;?>" class="form-control">
                          <option value="0" <?php if($orderDetails->status == 0){echo 'selected';}?>>Pending</option>
                          <option value="1" <?php if($orderDetails->status == 1){echo 'selected';}?> > Publish</option>
                          <option value="2" <?php if($orderDetails->status == 2){echo 'selected';}?> >Reject</option>
                               
                      </select>  
					</td>
					<input type="hidden" id="statusval<?php echo $orderDetails->id;?>" value="<?php echo $orderDetails->status; ?>">
                    <input type="hidden" id="proff_id<?php echo $orderDetails->id;?>" value="<?php echo $orderDetails->id; ?>">
                    
					<td>{{$orderDetails->created_at}}</td>
					<td>
					<button type="button" class="btn btn-danger"  data-toggle="modal" data-target="#rejectreason<?php echo $orderDetails->id; ?>">Reject</button>
					</td>
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
						
						if(status == 2){
							alert('Plz Click Reject Button For Rejection');
							exit();
						}
						
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
					  
					  
					  <div class="modal fade" id="rejectreason<?php echo $orderDetails->id; ?>">
						<div class="modal-dialog">
						<form action="{{url('reject-post')}}" role="form" id="quickForm" method="post">
						  <div class="modal-content">
							<div class="modal-header">
							  <h4 class="modal-title">Alert</h4>
							  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							  </button>
							</div>
							@csrf
							<div class="modal-body">
							  <p>Reason For Rejection ?</p>
							  <input type="hidden" name="proff_id" value="<?php echo $orderDetails->id; ?>">
							  <input type="hidden" name="status" value="2">
							  <textarea name="reason" class="form-control" required="" rows="4" style="width:450px;" ></textarea>
							</div>
							
							
							
							<div class="modal-footer justify-content-between">
							  <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
							  <button type="submit" class="btn btn-primary">Reject</button>
							</div>
						  </div>
						  </form>
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
					<th>Reject</th>
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


<div class="tab-pane" id="published">

	 <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
		  
		
            <div class="card">
              
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example3" class="table table-bordered table-striped">
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
					<th>Reject</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  
                  @foreach($published as $key => $orderDetails)
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
                      <select name="document" id="status<?php echo $orderDetails->id;?>" class="form-control">
                          <option value="0" <?php if($orderDetails->status == 0){echo 'selected';}?>>Pending</option>
                          <option value="1" <?php if($orderDetails->status == 1){echo 'selected';}?> > Publish</option>
                          <option value="2" <?php if($orderDetails->status == 2){echo 'selected';}?> >Reject</option>
                               
                      </select>  
					</td>
					<input type="hidden" id="statusval<?php echo $orderDetails->id;?>" value="<?php echo $orderDetails->status; ?>">
                    <input type="hidden" id="proff_id<?php echo $orderDetails->id;?>" value="<?php echo $orderDetails->id; ?>">
                    
					<td>{{$orderDetails->created_at}}</td>
					<td>
					<button type="button" class="btn btn-danger"  data-toggle="modal" data-target="#rejectreason<?php echo $orderDetails->id; ?>">Reject</button>
					</td>
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
						
						if(status == 2){
							alert('Plz Click Reject Button For Rejection');
							exit();
						}
						
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
					  
					  
					  <div class="modal fade" id="rejectreason<?php echo $orderDetails->id; ?>">
						<div class="modal-dialog">
						<form action="{{url('reject-post')}}" role="form" id="quickForm" method="post">
						  <div class="modal-content">
							<div class="modal-header">
							  <h4 class="modal-title">Alert</h4>
							  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							  </button>
							</div>
							@csrf
							<div class="modal-body">
							  <p>Reason For Rejection ?</p>
							  <input type="hidden" name="proff_id" value="<?php echo $orderDetails->id; ?>">
							  <input type="hidden" name="status" value="2">
							  <textarea name="reason" class="form-control" required="" rows="4" style="width:450px;" ></textarea>
							</div>
							
							
							
							<div class="modal-footer justify-content-between">
							  <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
							  <button type="submit" class="btn btn-primary">Reject</button>
							</div>
						  </div>
						  </form>
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
					<th>Reject</th>
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


<div class="tab-pane" id="rejected">

	 <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
		  
		
            <div class="card">
              
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example5" class="table table-bordered table-striped">
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
                  
                  @foreach($rejected as $key => $orderDetails)
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
                      <select name="document" id="status<?php echo $orderDetails->id;?>" class="form-control">
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
						
						if(status == 2){
							alert('Plz Click Reject Button For Rejection');
							exit();
						}
						
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
					  
					  
					  <div class="modal fade" id="rejectreason<?php echo $orderDetails->id; ?>">
						<div class="modal-dialog">
						<form action="{{url('reject-post')}}" role="form" id="quickForm" method="post">
						  <div class="modal-content">
							<div class="modal-header">
							  <h4 class="modal-title">Alert</h4>
							  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							  </button>
							</div>
							@csrf
							<div class="modal-body">
							  <p>Reason For Rejection ?</p>
							  <input type="hidden" name="proff_id" value="<?php echo $orderDetails->id; ?>">
							  <input type="hidden" name="status" value="2">
							  <textarea name="reason" class="form-control" required="" rows="4" style="width:450px;" ></textarea>
							</div>
							
							
							
							<div class="modal-footer justify-content-between">
							  <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
							  <button type="submit" class="btn btn-primary">Reject</button>
							</div>
						  </div>
						  </form>
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


</div>

</div>
</div>

</div>

</div>

</div>
</section>

</div>


  @endsection