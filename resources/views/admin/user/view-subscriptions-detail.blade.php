@extends('admin.layout.layout')
@section('content')
<style>
    .add-heading {
       font-weight: bold;
   }
   .ui-tooltip-content {
      background-color: #3d3f94;
      color: #fff;
      border: 1px solid #007bff;
    }
</style>

  <div class="content-wrapper">
  
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>View Subscription Detail </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">View Subscription Detail </li>
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
              <!-- <div class="card-header">
                <h3 class="card-title"><a href="{{url('add-vehicletypes')}}"><button type="button" class="btn btn-block bg-gradient-primary">Add Vehicle Types</button></a></h3>
              </div> -->
              <!-- /.card-header -->
              <div class="card-body">
               <div class="row">
                   <div class="col-sm-12 col-lg-4 col-md-4 mb-2">
                    <div class="add-types">
                    <div class="add-heading">Subscription Name</div>
                    <div class="select-add-type">{{$info->subscriptions->package ?? "NA"}}</div>
                    </div>
                </div>
                   <div class="col-sm-12 col-lg-4 col-md-4 mb-2">
                    <div class="add-types">
                    <div class="add-heading">Purchasing Date</div>
                    <div class="select-add-type">{{date('d-F-Y',strtotime($info->created_at)) ?? "NA"}}</div>
                    </div>
                </div>
                   <div class="col-sm-12 col-lg-4 col-md-4 mb-2">
                    <div class="add-types">
                    <div class="add-heading">Subscription ID</div>
                    <div class="select-add-type">{{$info->subscriptions->package ?? "NA"}}</div>
                    </div>
                </div>
                   <div class="col-sm-12 col-lg-4 col-md-4 mb-2">
                    <div class="add-types">
                    <div class="add-heading">Valid Till</div>
                    <div class="select-add-type">{{$info->subscription_expiry ?? "NA"}}</div>
                    </div>
                </div>
                   <div class="col-sm-12 col-lg-4 col-md-4 mb-2">
                    <div class="add-types">
                    <div class="add-heading">Payment Mode</div>
                    <div class="select-add-type">{{$info->payment_mode ?? "NA"}}</div>
                    </div>
                </div>
                   <div class="col-sm-12 col-lg-4 col-md-4 mb-2">
                    <div class="add-types">
                    <div class="add-heading">Payment Method</div>
                    <div class="select-add-type">{{$info->payment_method ?? "NA"}}</div>
                    </div>
                </div>
                   <div class="col-sm-12 col-lg-4 col-md-4 mb-2">
                    <div class="add-types">
                    <div class="add-heading">Transaction ID</div>
                    <div class="select-add-type">{{$info->transaction_id ?? "NA"}}</div>
                    </div>
                </div>
                   <div class="col-sm-12 col-lg-4 col-md-4 mb-2">
                    <div class="add-types">
                    <div class="add-heading">Payment Status</div>
                    <div class="select-add-type">{{$info->payment_status ?? "NA"}}</div>
                    </div>
                </div>
                
                   <div class="col-sm-12 col-lg-4 col-md-4 mb-2">
                    <div class="add-types">
                    <div class="add-heading">Subscription Ads Category</div>
                    @php
                            $category_list = explode(',', $info->category_id);
                            $result = App\Models\Categories::whereIn('id',$category_list)->pluck('name');
                            $categoryall = $result->implode(',');
                        @endphp
                    <div class="select-add-type"><i data-toggle="tooltip" data-placement="top" data-html="true" title="{!! $categoryall !!}" style="font-size:24px" class="fa">&#xf05a;</i></div>
                    </div>
                </div>
                   <div class="col-sm-12 col-lg-4 col-md-4 mb-2">
                    <div class="add-types">
                    <div class="add-heading">Total Ads</div>
                    <div class="select-add-type">{{$info->remaining_ads ?? "NA"}}</div>
                    </div>
                </div>
                   <div class="col-sm-12 col-lg-4 col-md-4 mb-2">
                    <div class="add-types">
                    <div class="add-heading">Ad Validity</div>
                    <div class="select-add-type">{{$info->subscription_expiry ?? "NA"}}</div>
                    </div>
                </div>
                   <div class="col-sm-12 col-lg-4 col-md-4 mb-2">
                    <div class="add-types">
                    <div class="add-heading">Used Ads</div>
                    <div class="select-add-type">{{$info->used_ads ?? "NA"}}</div>
                    </div>
                </div>
                   <div class="col-sm-12 col-lg-4 col-md-4 mb-2">
                    <div class="add-types">
                    <div class="add-heading">Remaining Ads in Bucket</div>
                    <div class="select-add-type">{{$info->remaining_ads - $info->used_ads  ?? "NA"}}</div>
                    </div>
                </div>
               </div>
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