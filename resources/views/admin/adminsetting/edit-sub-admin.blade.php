@extends('admin.layout.layout')
@section('content')
<style>
   .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
    top: 34px;
    left: 50px;
   }
   .switch input { 
   opacity: 0;
   width: 0;
   height: 0;
   }
   .slider {
   position: absolute;
   cursor: pointer;
   top: 0;
   left: 0;
   right: 0;
   bottom: 0;
   background-color: #bd1414;
   -webkit-transition: .4s;
   transition: .4s;
   }
   .slider:before {
   position: absolute;
   content: "";
   height: 26px;
   width: 26px;
   left: 4px;
   bottom: 4px;
   background-color: white;
   -webkit-transition: .4s;
   transition: .4s;
   }
   input:checked + .slider {
   background-color: #356211;
   }
   input:focus + .slider {
   box-shadow: 0 0 1px #356211;
   }
   input:checked + .slider:before {
   -webkit-transform: translateX(26px);
   -ms-transform: translateX(26px);
   transform: translateX(26px);
   }
   /* Rounded sliders */
   .slider.round {
   border-radius: 34px;
   }
   .slider.round:before {
   border-radius: 50%;
   }
</style>
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Edit Sub Admin</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Sub Admin and Roles</a></li>
                  <li class="breadcrumb-item active">Edit Sub Admin</li>
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
   @if ($errors->any())
   <div class="card-body">
      <div class="alert alert-danger alert-dismissible">
         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
         @foreach ($errors->all() as $error)
         <p>{{ $error }}</p>
         @endforeach
      </div>
   </div>
   @endif
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <!-- left column -->
            <div class="col-md-12">
               <!-- jquery validation -->
               <div class="card card-primary">
                  <div class="card-header">
                     <h3 class="card-title">Edit Details</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form action="{{route('sub-admin-update',$user->id)}}" role="form" id="quickForm" method="post" enctype="multipart/form-data">
                     @csrf
                     <input type="hidden" name="id" class="form-control" value="{{$user->id}}">
                     <div class="card-body">
                        <div class="form-group row">
                           <div class="col">
                              <label for="exampleInputEmail1">Name</label>
                              <input type="text" name="name" class="form-control" placeholder="Ram" value="{{$user->name}}">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Email</label>
                              <input type="email" name="email" class="form-control" placeholder="example@gamil.com" value="{{$user->email}}">
                           </div>
                        </div>
                        <div class="form-group row">
                           <div class="col">
                              <label for="exampleInputEmail1">Mobile</label>
                              <input type="number" name="mobile" class="form-control"  placeholder="123567890" value="{{$user->mobile}}">
                           </div>
                           <div class="col">
                                <label for="exampleInputEmail1">Privileges</label>
                               <select class="form-control" name="role_id" id="role_id">
                                   <option @if($user->role_id == "1") selected  @endif value="1">Read and Write</option>
                                   <option @if($user->role_ido == "2") selected  @endif value="2">Read Only</option>
                               </select>
                           </div>
                          
                        </div>
                        <div class="form-group row">
                            <div class="col">
                                <label for="role">Permissions:</label>
                                <div class="form-check">
                                  <input type="checkbox" class="form-check-input" id="masterEdit" name="masterEdit" {{ $user->permission->master_edit == 1 ? 'checked' : '' }}>
                                  <label class="form-check-label" for="masterEdit">Master Settings</label>
                                </div>
                                <div class="form-check">
                                  <input type="checkbox" class="form-check-input" id="userEdit" {{ $user->permission->users_edit == 1 ? 'checked' : '' }} name="userEdit">
                                  <label class="form-check-label" for="userEdit">Users</label>
                                </div>
                                <div class="form-check">
                                  <input type="checkbox" class="form-check-input" id="chatEdit" @if($user->permission->chat_edit==1){{"checked"}} @else {{ "" }} @endif name="chatEdit">
                                  <label class="form-check-label" for="chatEdit">Chat Support</label>
                                </div>
                                <div class="form-check">
                                  <input type="checkbox" class="form-check-input" id="invoiceEdit" @if($user->permission->invoice_order_edit==1){{"checked"}} @else {{ "" }} @endif name="invoiceEdit">
                                  <label class="form-check-label" for="invoiceEdit">Invoice and Orders</label>
                                </div>
                                <div class="form-check">
                                  <input type="checkbox" class="form-check-input" id="subscriptionEdit" @if($user->permission->subscription_edit==1){{"checked"}} @else {{ "" }} @endif name="subscriptionEdit">
                                  <label class="form-check-label" for="subscriptionEdit">Subscription</label>
                                </div>
                                <div class="form-check">
                                  <input type="checkbox" class="form-check-input" id="adsEdit" @if($user->permission->ads_edit==1){{"checked"}} @else {{ "" }} @endif name="adsEdit">
                                  <label class="form-check-label" for="adsEdit">Ads Inquiries</label>
                                </div>
                                <div class="form-check">
                                  <input type="checkbox" class="form-check-input" id="contentEdit" @if($user->permission->content_edit==1){{"checked"}} @else {{ "" }} @endif name="contentEdit">
                                  <label class="form-check-label" for="contentEdit">Content Management</label>
                                </div>
                                <div class="form-check">
                                  <input type="checkbox" class="form-check-input" id="helpEdit" @if($user->permission->help_edit==1){{"checked"}} @else {{ "" }} @endif name="helpEdit">
                                  <label class="form-check-label" for="helpEdit">Help and Support</label>
                                </div>
                                <div class="form-check">
                                  <input type="checkbox" class="form-check-input" id="walletEdit" @if($user->permission->wallet_payouts_edit==1){{"checked"}} @else {{ "" }} @endif name="walletEdit">
                                  <label class="form-check-label" for="walletEdit">Wallet & Payouts</label>
                                </div>
                                <div class="form-check">
                                  <input type="checkbox" class="form-check-input" id="misEdit" @if($user->permission->mis_report_edit==1){{"checked"}} @else {{ "" }} @endif name="misEdit">
                                  <label class="form-check-label" for="misEdit">MIS Reports</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                          </div>
                     </div>
                     <!-- /.card-body -->
                     <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                     </div>
                  </form>
               </div>
               <!-- /.card -->
            </div>
            <!--/.col (left) -->
            <!-- right column -->
            <div class="col-md-6">
            </div>
            <!--/.col (right) -->
         </div>
         <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
   </section>
   <!-- /.content -->
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>