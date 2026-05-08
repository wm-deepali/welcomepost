@extends('admin.layout.layout')
@section('content')
  <div class="content-wrapper">
  
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Subscription</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Subscription</li>
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
                <h3 class="card-title"><a href="{{url('add-subscription')}}"><button type="button" class="btn btn-block bg-gradient-primary">Add Subscription</button></a></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Category</th>
                    <th>Package</th>
                    <th>Package Validity</th>
                    <th>No Of Ads</th>
                    <th>Ads Validity</th>
                    <th>Per Ads Costing</th>
                    <th>MRP</th>
                    <th>Discount</th>
                    <th>Offered Price</th>
                    <th>Status</th>
                    <th>Registered Date</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  
                  @foreach($subscription as $key => $orderDetails)
                 
                  <tr>
                    <td>{{$key + 1}}</td>
                    <td>
                        @php
                            $category_list = explode(',', $orderDetails->category_id);
                            
                        @endphp
                        @foreach($category_list as $category)
                            @php
                                $result = App\Models\Categories::find($category);
                               
                                
                            @endphp
                            @if(isset($result))
                                    @php echo ucfirst($result->name) . ',';  @endphp
                                @endif
                        @endforeach
                        
					    
					</td>
                    <td>{{$orderDetails->package}}</td>
                    <td>{{$orderDetails->package_validity}}</td>
                    <td>{{$orderDetails->no_of_ads}}</td>
                    <td>{{$orderDetails->ads_validity}}</td>
                    <td>{{$orderDetails->ads_costing}}</td>
                    <td>{{$orderDetails->mrp}}</td>
                    <td>{{$orderDetails->discount}}</td>
                    <td>{{$orderDetails->offered_price}}</td>
					<?php if($orderDetails->status == 0){ ?>
					 <td> <a href="{{url('status-update-subscription/'.$orderDetails->id)}}"><button type="button" class="btn btn-success">Active</button></a>	</td>              
					<?php }else{ ?>
					 <td> <a href="{{url('status-update-subscription/'.$orderDetails->id)}}"><button type="button" class="btn btn-danger">Deactive</button></a>	</td>
					<?php } ?>
					<td>{{$orderDetails->created_at}}</td>
                    <td>
						 
						<a href="{{url('edit-subscription/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary"><i class="fa fa-edit"></i></button></a>
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
              <a href="{{url('delete-subscription/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary">Yes</button></a>
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
                    <th>Category</th>
                    <th>Package</th>
					<th>Package Validity</th>
                    <th>No Of Ads</th>
                    <th>Ads Validity</th>
                    <th>MRP</th>
                    <th>Discount</th>
                    <th>Offered Price</th>
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