<style>
.table-responsive {
    width: 100% !important;
    aspect-ratio: 0 / 1;
    overflow-x: scroll !important;
    overflow-y: visible !important;
    white-space: nowrap;
    scroll-snap-type: x mandatory;
}
</style>

@extends('admin.layout.layout')
@section('content')


<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>User</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">User</li>
               </ol>
            </div>
         </div>
      </div>
      <!-- /.container-fluid -->
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
                     {{--<h3 class="card-title"><a href="{{url('add-users')}}"><button type="button" class="btn btn-block bg-gradient-primary">Add User</button></a></h3>--}}
                  </div>
                  <div class="card-header p-2">
                     <ul class="nav nav-tabs">
                        <li class="nav-item"><a class="nav-link active" href="#alluser" data-toggle="tab">All Users</a></li>
                        <li class="nav-item"><a class="nav-link" href="#normal" data-toggle="tab">Normal Users</a></li>
                        <li class="nav-item"><a class="nav-link" href="#prime" data-toggle="tab">Prime Users</a></li>
                        <li class="nav-item"><a class="nav-link" href="#premium" data-toggle="tab">Premium Users</a></li>
                        <li class="nav-item"><a class="nav-link" href="#blocked" data-toggle="tab"> Blocked Users</a></li>
                        <li class="nav-item"><a class="nav-link" href="#expired" data-toggle="tab"> Expired Users</a></li>
                        <li class="nav-item"><a class="nav-link" href="#delete" data-toggle="tab"> Deleted Records</a></li>
                     </ul>
                  </div>
                  <!-- -->
                  <!-- /.card-header -->
                  <div class="card-body">
                     <div class="tab-content">
                        <div class="active tab-pane" id="alluser">
                           <section class="content">
                              <div class="container-fluid">
                                 <div class="row">
                                    <div class="col-12">
                                       <div class="card">
                                          <!-- /.card-header -->
                                          <div class="card-body">
                                              <!--<div id="AllUser" class="table-responsive">    -->
                                                <table id="examples2" class="table-responsive table table-bordered table-striped">
                                                <thead>
                                                   <tr>
                                                      <th>Date & Time</th>
                                                      <th>Full Name</th>
                                                      <th>Email Id </th>
                                                      <th>Mobile Number</th>
                                                      <th>User Type</th>
                                                      <th>Referred By</th>
                                                      <th>Subscription Id</th>
                                                      <th>Active Subscription</th>
                                                      <th>Member Expiry</th>
                                                      <th>Auto Joining Eligibility </th>
                                                      <th>Prime Eligibility </th>
                                                      <th>Premium Eligibility</th>
                                                      <th>Total Referrals</th>
                                                      <th>Auto Joinings </th>
                                                      <th>QR Code</th>
                                                       <th>Parent Id </th>
                                                      <th>Status</th>
                                                      <th>Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   @php
                                                   $alluser =  App\Models\Customer::latest()->get();
                                                   @endphp  
                                                   @foreach($alluser as $key => $orderDetails)
                                                   
                                                   <tr>
                                                      <td>{{$orderDetails->created_at}}</td>
                                                      <td>{{$orderDetails->name}}</td>
                                                      <td>{{$orderDetails->email}}</td>
                                                      <td>{{$orderDetails->mobile}}</td>
                                                      <td>
                                                         {{$orderDetails->user_type}}
                                                         
                                                      </td>
                                                      <td>{{$orderDetails->referralto}}</td>
                                                      <td>{{$orderDetails->subscriptionhistory->first()->subscription_number ?? "NA"}}</td>
                                                      <td>
                                                         
                                                         @if($orderDetails->subscriptionhistory->where('subscription_expiry','>=',date('Y-m-d'))->count() > 0)
                                                         Yes 
                                                         @else
                                                         No
                                                         @endif
                                                      </td>
                                                      <td>
                                                         {{date('d-M-Y',strtotime($orderDetails->membership_expiry_at))}}
                                                      </td>
                                                      <td> @if($orderDetails->subscriptionhistory->where('auto_join','1')->count() > 0)
                                                         Yes 
                                                         @else
                                                         No
                                                         @endif</td>
                                                      <td>  @if($orderDetails->subscriptionhistory->where('type','Prime')->count() > 0)
                                                         Yes 
                                                         @else
                                                         No
                                                         @endif
                                                      </td>
                                                      <td>
                                                         @if($orderDetails->subscriptionhistory->where('type','Premium')->count() > 0)
                                                         Yes 
                                                         @else
                                                         No
                                                         @endif
                                                      </td>
                                                      
                                                      <td>{{ $orderDetails->customerallchild->whereNotNull('referralto')->count() ?? 0 }}</td>
                                                      <td>
                                                         
                                                         {{ $orderDetails->customerallchild->whereNull('referralto')->count() ?? 0 }}
                                                      </td>
                                                      <td>
                                                          @if(isset($orderDetails->qr_code_image))
                                                        <a href="{{url('public/admin/images/'.$orderDetails->qr_code_image) }}" download="{{$orderDetails->name}}"><img src="{{url('public/admin/images/'.$orderDetails->qr_code_image) }}" alt="" style="width:45px;">Download</a>
                                                        @else
                                                            QR Code is not yet set by the User
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ optional($orderDetails->customerparent)->name ? $orderDetails->customerparent->name . '(' . optional($orderDetails->customerparent)->mobile . ')' : '-' }}
                                                    </td>
                                                      <td> <a href="{{ url('update-user/'.$orderDetails->id)}}">
                                                        @if($orderDetails->status == 0)
                                                        <button type="button" class="btn btn-success">Active</button>
                                                        @else
                                                        <button type="button" class="btn btn-danger">Deactive</button>
                                                        @endif
                                                        </a>	
                                                      </td>
                                                      
                                                      <td>
                                                          @if(isset($orderDetails->loginAttempt->is_account_locked)&&$orderDetails->loginAttempt->is_account_locked)
                                                          <a href="{{route('admin.unlock.account',$orderDetails->loginAttempt->id)}}" class="btn btn-primary">Unlock Account</a>
                                                          @endif
                                                          <a href="{{url('view-user/'.$orderDetails->id)}}" class="btn btn-primary"> View Profile</a>
                                                          <a href="{{url('view-my-referrals/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary"> View All Referrals</button></a>
                                                          <a href="{{url('view-subscriptions/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary"> View Subscriptions</button></a>
                                                         
                                                         <a href="{{url('view-auto-joining-members/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary">View Auto Members</button></a>
                                                        <a href="{{url('earnings-user/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary"> View Total Earnings </button></a>
                                                        <a href="{{url('edit-user/'.$orderDetails->id)}}" class="btn btn-primary">Edit profile</a>
                                                         <button type="button" class="btn btn-danger"  data-toggle="modal" data-target="#modal-delete<?php echo $orderDetails->id; ?>">Delete Member</button>
                                                         
                                                        
                                                         
                                                         
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
                                                               <a href="{{url('delete-user/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary">Yes</button></a>
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
                                                      <th>Date & Time</th>
                                                      <th>Full Name</th>
                                                      <th>Email Id </th>
                                                      <th>Mobile</th>
                                                      <th>User Type</th>
                                                      <th>Referred By</th>
                                                      <th>Subscription Id</th>
                                                      <th>Active Subscription</th>
                                                      <th>Member Expiry</th>
                                                      <th>Auto Joining Eligibility </th>
                                                      <th>Prime Eligibility </th>
                                                      <th>Premium Eligibility</th>
                                                      <th>Total Referrals</th>
                                                      <th>Auto Joinings </th>
                                                      <th>QR Code</th>
                                                       <th>Parent Id </th>
                                                      <th>Status</th>
                                                      <th>Action</th>
                                                   </tr>
                                                </tfoot>
                                             </table>
                                              <!--</div>-->
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
                        <div class="tab-pane" id="normal">
                           <section class="content">
                              <div class="container-fluid">
                                 <div class="row">
                                    <div class="col-12">
                                       <div class="card">  
                                          <!-- /.card-header --> 
                                          <div class="card-body">
                                             <table id="examples1" class="table-responsive table table-bordered table-striped" >
                                                <thead>
                                                   <tr>
                                                      <th>Date & Time</th>
                                                      <th>Full Name</th>
                                                      <th>Email Id</th>
                                                      <th>Mobile Number</th>
                                                      <th>Total Referrals</th>
                                                      <th>Referred By</th>
                                                      <th>Prime Eligibility</th>
                                                      <th>Status</th>
                                                      <th>Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   @php
                                                   $free =  App\Models\Customer::whereDoesntHave('subscriptionhistory', function($query) {
    $query->where('type', 'Premium')->orWhere('type', 'Prime');
})->get();
                                                   @endphp  
                                                   @foreach($free as $key => $orderDetails)
                                                   <tr>
                                                      <td>{{$orderDetails->datetime}}</td>
                                                      <td>{{$orderDetails->name}}</td>
                                                      <td>{{$orderDetails->email}}</td>
                                                      <td>{{$orderDetails->mobile}}</td>
                                                      <td>{{ $orderDetails->customerallchild->whereNotNull('referralto')->count() ?? 0  }}</td>
                                                      <td>{{$orderDetails->referralto ?? "NA"}}</td>
                                                      <td>
                                                         @if($orderDetails->subscriptionhistory->where('type','Prime')->count() > 0)
                                                         Yes 
                                                         @else
                                                         No
                                                         @endif
                                                      </td>
                                                      <td> <a href="{{ url('update-user/'.$orderDetails->id)}}">
                                                          @if($orderDetails->status == 0)
                                                         <button type="button" class="btn btn-success">Active</button>
                                                         @else
                                                         <button type="button" class="btn btn-danger">Deactive</button>
                                                         @endif
                                                         </a>
                                                      </td>
                                                      <td>
                                                         <a href="{{url('view-user/'.$orderDetails->id)}}" class="btn btn-primary"> View Profile</a>
                                                          <a href="{{url('view-my-referrals/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary"> View All Referrals</button></a>
                                                          <a href="{{url('view-subscriptions/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary"> View Subscriptions</button></a>
                                                         
                                                         <a href="{{url('view-auto-joining-members/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary">View Auto Members</button></a>
                                                        <a href="{{url('earnings/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary"> View Total Earnings </button></a>
                                                        <a href="{{url('edit-user/'.$orderDetails->id)}}" class="btn btn-primary">Edit profile</a>
                                                         <button type="button" class="btn btn-danger"  data-toggle="modal" data-target="#modal-delete<?php echo $orderDetails->id; ?>">Delete Member</button>
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
                                                               <a href="{{url('delete-user/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary">Yes</button></a>
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
                                                      <th>Date & Time</th>
                                                      <th>Full Name</th>
                                                      <th>Email Id</th>
                                                      <th>Mobile Number</th>
                                                      <th>Total Referrals</th>
                                                      <th>Referred By</th>
                                                      <th>Prime Eligibility</th>
                                                      <th>Status</th>
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
                        
                        <div class="tab-pane" id="prime">
                           <section class="content">
                              <div class="container-fluid">
                                 <div class="row">
                                    <div class="col-12">
                                       <div class="card">
                                          <!-- /.card-header -->
                                          <div class="card-body">
                                             <table id="examples3" class="table-responsive table table-bordered table-striped">
                                                <thead>
                                                   <tr>
                                                      <th>Date & Time</th>
                                                      <th>Full Name</th>
                                                      <th>Mobile Number</th>
                                                      <th>Referred By</th>
                                                      <th>Subscription Id</th>
                                                      <th>Active Subscription</th>
                                                      <th> Member Expiry </th>
                                                      <th>Auto Join Eligibility</th>
                                                      <th>Total Referrals</th>
                                                      <th> Auto Joinings </th>
                                                      <th>Status</th>
                                                      <th>Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   @php
                                                   $prime = App\Models\Customer::whereHas('subscriptionhistory', function ($query) {
                                                        $query->where('type', 'Prime');
                                                    })->get();
                                                   @endphp  
                                                   @foreach($prime as $key => $orderDetails)
                                                   
                                                   <tr>
                                                      <td>{{$orderDetails->created_at}}</td>
                                                      <td>{{$orderDetails->name}}</td>
                                                      <td>{{$orderDetails->mobile}}</td>
                                                      <td>{{$orderDetails->referralto ?? "NA"}}</td>
                                                        <td>{{$orderDetails->subscriptionhistory->first()->subscription_number ?? "NA"}}</td>
                                                      <td>
                                                         @if($orderDetails->subscriptionhistory->where('subscription_expiry','>=',date('Y-m-d'))->count() > 0)
                                                         Yes 
                                                         @else
                                                         No
                                                         @endif
                                                      </td>
                                                      <td>
                                                          {{date('d-M-Y',strtotime($orderDetails->membership_expiry_at))}}
                                                      </td>
                                                      
                                                      <td>
                                                        @if($orderDetails->subscriptionhistory->where('auto_join','1')->count() > 0)
                                                         Yes 
                                                         @else
                                                         No
                                                         @endif
                                                      </td> 
                                                      <td>{{ $orderDetails->customerallchild->whereNotNull('referralto')->count() ?? 0 }}</td>
                                                      <td>
                                                         
                                                         {{ $orderDetails->customerallchild->whereNull('referralto')->count() ?? 0 }}
                                                      </td>
                                                     
                                                      <td> <a href="{{ url('update-user/'.$orderDetails->id)}}">
                                                         @if($orderDetails->status == 0)
                                                         <button type="button" class="btn btn-success">Active</button>
                                                         @else
                                                         <button type="button" class="btn btn-danger">Deactive</button>
                                                         @endif	
                                                         </a>
                                                      </td>
                                                      
                                                      <td>
                                                        <a href="{{url('view-user/'.$orderDetails->id)}}" class="btn btn-primary"> View Profile</a>
                                                          <a href="{{url('view-my-referrals/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary"> View All Referrals</button></a>
                                                          <a href="{{url('view-subscriptions/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary"> View Subscriptions</button></a>
                                                         
                                                         <a href="{{url('view-auto-joining-members/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary">View Auto Members</button></a>
                                                        <a href="{{url('earnings/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary"> View Total Earnings </button></a>
                                                        <a href="{{url('edit-user/'.$orderDetails->id)}}" class="btn btn-primary">Edit profile</a>
                                                         <button type="button" class="btn btn-danger"  data-toggle="modal" data-target="#modal-delete<?php echo $orderDetails->id; ?>">Delete Member</button>
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
                                                               <a href="{{url('delete-user/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary">Yes</button></a>
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
                                                      
                                                      <th>Date & Time</th>
                                                      <th>Full Name</th>
                                                      <th>Mobile Number</th>
                                                      <th>Referred By</th>
                                                      <th>Subscription Id</th>
                                                      <th>Active Subscription</th>
                                                      <th> Member Expiry </th>
                                                      <th>Auto Join Eligibility</th>
                                                      <th>Total Referrals</th>
                                                      <th> Auto Joinings </th>
                                                      <th>Status</th>
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

                        <div class="tab-pane" id="premium">
                           <section class="content">
                              <div class="container-fluid">
                                 <div class="row">
                                    <div class="col-12">
                                       <div class="card">
                                          <!-- /.card-header -->
                                          <div class="card-body">
                                             <table id="examples4" class="table-responsive table table-bordered table-striped">
                                                <thead>
                                                   <tr>
                                                     <tr>
                                                      <th>Date & Time</th>
                                                      <th>Full Name</th>
                                                      <th>Mobile Number</th>
                                                      <th>Referred By</th>
                                                      <th>Subscription Id</th>
                                                      <th>Active Subscription</th>
                                                      <th> Member Expiry </th>
                                                      <th>Auto Join Eligibility</th>
                                                      <th>Total Referrals</th>
                                                      <th> Auto Joinings </th>
                                                      <th>Status</th>
                                                      <th>Action</th>
                                                   </tr>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   @php
                                                   $premium =  App\Models\Customer::whereHas('subscriptionhistory',function($query){ $query->where('type','Premium'); })->get();
                                                   @endphp  
                                                   @foreach($premium as $key => $orderDetails)
                                                   
                                                   <tr>
                                                      <td>{{$orderDetails->created_at}}</td>
                                                      <td>{{$orderDetails->name}}</td>
                                                      <td>{{$orderDetails->mobile}}</td>
                                                      <td>{{$orderDetails->referralto}}</td>
                                                        <td>{{$orderDetails->subscriptionhistory->first()->subscription_number ?? "NA"}}</td>
                                                      <td>
                                                         @if($orderDetails->subscriptionhistory->where('subscription_expiry','>=',date('Y-m-d'))->count() > 0)
                                                         Yes 
                                                         @else
                                                         No
                                                         @endif
                                                      </td>
                                                      <td>
                                                          {{date('d-M-Y',strtotime($orderDetails->membership_expiry_at))}}
                                                      </td>
                                                      
                                                      <td>
                                                        @if($orderDetails->subscriptionhistory->where('auto_join','1')->count() > 0)
                                                         Yes 
                                                         @else
                                                         No
                                                         @endif
                                                      </td> 
                                                      <td>{{ $orderDetails->customerallchild->whereNotNull('referralto')->count() ?? 0 }}</td>
                                                      <td>
                                                         
                                                         {{ $orderDetails->customerallchild->whereNull('referralto')->count() ?? 0 }}
                                                      </td>
                                                     
                                                      <td> <a href="{{ url('update-user/'.$orderDetails->id)}}">
                                                         @if($orderDetails->status == 0)
                                                         <button type="button" class="btn btn-success">Active</button>
                                                         @else
                                                         <button type="button" class="btn btn-danger">Deactive</button>
                                                         @endif	
                                                         </a>
                                                      </td>
                                                      
                                                      <td>
                                                         <a href="{{url('view-user/'.$orderDetails->id)}}" class="btn btn-primary"> View Profile</a>
                                                          <a href="{{url('view-my-referrals/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary"> View All Referrals</button></a>
                                                          <a href="{{url('view-subscriptions/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary"> View Subscriptions</button></a>
                                                         
                                                         <a href="{{url('view-auto-joining-members/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary">View Auto Members</button></a>
                                                        <a href="{{url('earnings/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary"> View Total Earnings </button></a>
                                                        <a href="{{url('edit-user/'.$orderDetails->id)}}" class="btn btn-primary">Edit profile</a>
                                                         <button type="button" class="btn btn-danger"  data-toggle="modal" data-target="#modal-delete<?php echo $orderDetails->id; ?>">Delete Member</button>
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
                                                               <a href="{{url('delete-user/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary">Yes</button></a>
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
                                                      <th>Date & Time</th>
                                                      <th>Full Name</th>
                                                      <th>Mobile Number</th>
                                                      <th>Referred By</th>
                                                      <th>Subscription Id</th>
                                                      <th>Active Subscription</th>
                                                      <th> Member Expiry </th>
                                                      <th>Auto Join Eligibility</th>
                                                      <th>Total Referrals</th>
                                                      <th> Auto Joinings </th>
                                                      <th>Status</th>
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
                        <div class="tab-pane" id="expired">
                           <section class="content">
                              <div class="container-fluid">
                                 <div class="row">
                                    <div class="col-12">
                                       <div class="card">
                                          <!-- /.card-header -->
                                          <div class="card-body">
                                             <table id="examples5" class="table-responsive table table-bordered table-striped">
                                                <thead>
                                                   <tr>
                                                      <th>Date & Time</th>
                                                      <th>Full Name</th>
                                                      <th>Email Id </th>
                                                      <th>Mobile Number</th>
                                                      <th>User Type</th>
                                                      <th>Referred By</th>
                                                      <th>Active Subscription</th>
                                                      <th>Member Expiry</th>
                                                      <th>Auto Joining Eligibility </th>
                                                      <th>Prime Eligibility </th>
                                                      <th>Premium Eligibility</th>
                                                      <th>Total Referrals</th>
                                                      <th>Auto Joinings </th>
                                                      <th>Status</th>
                                                      <th>Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   @php
                                                   $expireuser =  App\Models\Customer::where('membership_expiry_at','<',date('Y-m-d'))->latest()->get();
                                                   @endphp  
                                                   @foreach($expireuser as $key => $orderDetails)
                                                   
                                                   <tr>
                                                      <td>{{$orderDetails->created_at}}</td>
                                                      <td>{{$orderDetails->name}}</td>
                                                      <td>{{$orderDetails->email}}</td>
                                                      <td>{{$orderDetails->mobile}}</td>
                                                      <td>
                                                         {{$orderDetails->user_type}}
                                                         
                                                      </td>
                                                      <td>{{$orderDetails->referralto}}</td>
                                                      <td>
                                                         
                                                         @if($orderDetails->subscriptionhistory->where('subscription_expiry','>=',date('Y-m-d'))->count() > 0)
                                                         Yes 
                                                         @else
                                                         No
                                                         @endif
                                                      </td>
                                                      <td>
                                                         {{date('d-M-Y',strtotime($orderDetails->membership_expiry_at))}}
                                                      </td>
                                                      <td> @if($orderDetails->subscriptionhistory->where('auto_join','1')->count() > 0)
                                                         Yes 
                                                         @else
                                                         No
                                                         @endif</td>
                                                      <td>  @if($orderDetails->subscriptionhistory->where('type','Prime')->count() > 0)
                                                         Yes 
                                                         @else
                                                         No
                                                         @endif
                                                      </td>
                                                      <td>
                                                         @if($orderDetails->subscriptionhistory->where('type','Premium')->count() > 0)
                                                         Yes 
                                                         @else
                                                         No
                                                         @endif
                                                      </td>
                                                      
                                                      <td>{{ $orderDetails->customerallchild->whereNotNull('referralto')->count() ?? 0 }}</td>
                                                      <td>
                                                         
                                                         {{ $orderDetails->customerallchild->whereNull('referralto')->count() ?? 0 }}
                                                      </td>
                                                      
                                                      <td> <a href="{{ url('update-user/'.$orderDetails->id)}}">
                                                        @if($orderDetails->status == 0)
                                                        <button type="button" class="btn btn-success">Active</button>
                                                        @else
                                                        <button type="button" class="btn btn-danger">Deactive</button>
                                                        @endif
                                                        </a>	
                                                      </td>
                                                      
                                                      <td>
                                                         <a href="{{url('view-user/'.$orderDetails->id)}}" class="btn btn-primary"> View Profile</a>
                                                          <a href="{{url('view-my-referrals/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary"> View All Referrals</button></a>
                                                          <a href="{{url('view-subscriptions/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary"> View Subscriptions</button></a>
                                                         
                                                         <a href="{{url('view-auto-joining-members/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary">View Auto Members</button></a>
                                                        <a href="{{url('earnings/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary"> View Total Earnings </button></a>
                                                        <a href="{{url('edit-user/'.$orderDetails->id)}}" class="btn btn-primary">Edit profile</a>
                                                         <button type="button" class="btn btn-danger"  data-toggle="modal" data-target="#modal-delete<?php echo $orderDetails->id; ?>">Delete Member</button>
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
                                                               <a href="{{url('delete-user/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary">Yes</button></a>
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
                                                      <th>Date & Time</th>
                                                      <th>Full Name</th>
                                                      <th>Email Id </th>
                                                      <th>Mobile</th>
                                                      <th>User Type</th>
                                                      <th>Referred By</th>
                                                      <th>Active Subscription</th>
                                                      <th>Member Expiry</th>
                                                      <th>Auto Joining Eligibility </th>
                                                      <th>Prime Eligibility </th>
                                                      <th>Premium Eligibility</th>
                                                      <th>Total Referrals</th>
                                                      <th>Auto Joinings </th>
                                                      <th>Status</th>
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
                        <div class="tab-pane" id="blocked">
                           <section class="content">
                              <div class="container-fluid">
                                 <div class="row">
                                    <div class="col-9">
                                       <div class="card">
                                          <!-- /.card-header -->
                                          <div class="card-body">
                                             <table id="examples5" class="table-responsive table table-bordered table-striped">
                                                <thead>
                                                   <tr>
                                                      <th>Date & Time</th>
                                                      <th>Full Name</th>
                                                      <th>Email Id </th>
                                                      <th>Mobile Number</th>
                                                      <th>Reason</th>
                                                      <th>Blocked By</th>
                                                      <th>Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   @php
                                                   $blockedEnq =  App\Models\Adsenquiry::where('isBlocked',1)->get();
                                                   @endphp
                                                   @foreach($blockedEnq as $key => $orderDetails)
                                                   @php
                                                    $userReceiver = App\Models\Customer::findOrFail($orderDetails->receiver_id);
                                                   @endphp
                                                   <tr>
                                                      <td>{{$orderDetails->created_at}}</td>
                                                      <td>{{$orderDetails->name}}</td>
                                                      <td>{{$orderDetails->email}}</td>
                                                      <td>{{$orderDetails->mobile}}</td>
                                                      <td>{{$orderDetails->block_reason ?? ""}}</td>
                                                      <td>{{$userReceiver->name}}</td>
                                                      <td>
                                                         <a href="{{url('view-user/'.$orderDetails->user_id)}}" class="btn btn-primary"> View Profile</a>
                                                          <a href="{{route('unblock-user',$orderDetails->user_id)}}"><button type="button" class="btn btn-primary">Unblock</button></a>
                                                      </td>
                                                   </tr>
                                                   @endforeach	
                                                </tbody>
                                                <tfoot>
                                                   <tr>
                                                      <th>Date & Time</th>
                                                      <th>Full Name</th>
                                                      <th>Email Id </th>
                                                      <th>Mobile</th>
                                                      <th>Blocked Count</th>
                                                      <th>User Type</th>
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
                        <div class="tab-pane" id="delete">
                           <section class="content">
                              <div class="container-fluid">
                                 <div class="row">
                                    <div class="col-12">
                                       <div class="card">
                                          <!-- /.card-header -->
                                          <div class="card-body">
                                             <table id="examples6" class="table-responsive table table-bordered table-striped">
                                                <thead>
                                                   <tr>
                                                       <th>Date & Time</th>
                                                      <th>Full Name</th>
                                                      <th>Email Id </th>
                                                      <th>Mobile</th>
                                                      <th>Deleted By</th>
                                                      <th>User Type</th>
                                                      <th>Active Subscription</th>
                                                      <th>Action</th>
                                                   </tr>
                                                </thead> 
                                                <tbody>
                                                    @php
                                                   $deleteuser =  App\Models\Customer::where('delete_status','1')->withTrashed()->latest()->get();
                            
                                                   @endphp  
                                                   @foreach($deleteuser as $key => $orderDetails)
                                                   @php
                                                       if(isset($orderDetails->deleted_by)){
                                                            $admin = App\Models\User::find($orderDetails->deleted_by);
                                                       }
                                                   @endphp
                                                   <tr>
                                                      <td>{{$orderDetails->created_at}}</td>
                                                      <td>{{$orderDetails->name}}</td>
                                                      <td>{{$orderDetails->email}}</td>
                                                      <td>{{$orderDetails->mobile}}</td>
                                                      <td>{{$admin->name ?? '--'}}</td>
                                                      <td>
                                                         {{$orderDetails->user_type}}
                                                         
                                                      </td>
                                                     
                                                      <td>
                                                         @if($orderDetails->subscriptionhistory->where('subscription_expiry','>=',date('Y-m-d'))->count() > 0)
                                                         Yes 
                                                         @else
                                                         No
                                                         @endif
                                                      </td>
                                                      
                                                      <td>
                                                         <!--<a href="{{url('edit-user/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary"><i class="fa fa-key"></i></button></a>-->
                                                         <!--<a href="{{url('view-user/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary"><i class="fa fa-eye"></i></button></a>-->
                                                         <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#modal-restore<?php echo $orderDetails->id; ?>"><i class='fas fa-trash-restore-alt'></i></button>
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
                                                               <p>Are You Sure You Want To Delete This Item Permanently ?</p>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                               <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                                               <a href="{{url('deletep-user/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary">Yes</button></a>
                                                            </div>
                                                         </div>
                                                         <!-- /.modal-content -->
                                                      </div>
                                                      <!-- /.modal-dialog -->
                                                   </div>
                                                   <div class="modal fade" id="modal-restore<?php echo $orderDetails->id; ?>">
                                                      <div class="modal-dialog">
                                                         <div class="modal-content">
                                                            <div class="modal-header">
                                                               <h4 class="modal-title">Alert</h4>
                                                               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                               <span aria-hidden="true">&times;</span>
                                                               </button>
                                                            </div>
                                                            <div class="modal-body">
                                                               <p>Are You Sure You Want To Restore This Item ?</p>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                               <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                                               <a href="{{url('restore-user/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary">Yes</button></a>
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
                                                      <th>Date & Time</th>
                                                      <th>Full Name</th>
                                                      <th>Email Id </th>
                                                      <th>Mobile</th>
                                                      <th>Deleted By</th>
                                                      <th>User Type</th>
                                                      <th>Active Subscription</th>
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
<script>
    $(document).ready(function(){
        // Get the hash from the URL
        var hash = window.location.hash;
        // Activate the tab corresponding to the hash
        if (hash && $('.nav-tabs a[href="' + hash + '"]').length) {
            // Remove 'active' class from all tab links and panes
            $('.nav-tabs a').removeClass('active');
            $('.tab-pane').removeClass('active');
            
            // Add 'active' class to the tab link and pane corresponding to the hash
            $('.nav-tabs a[href="' + hash + '"]').addClass('active');
            $(hash).addClass('active');
        }
        $('.nav-tabs a').on('click', function() {
            var newHash = $(this).attr('href');
            history.replaceState(null, null, newHash);
        });
    });
</script>
@endsection