@extends('admin.layout.layout')
@section('content')
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Edit User</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Edit User</li>
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
                  <form action="{{url('post-edit-user')}}" role="form"  method="post" enctype="multipart/form-data">
                     <div class="card-body">
                        @csrf
                        <input type="hidden" name="id" value="{{$info->id}}">

                        <!-- <div class="form-group">
                           <label for="exampleInputEmail1">Password</label>
                           <input type="text" name="password" class="form-control" value="{{$info->password}}" value="{{$info->password}}">
                           <input type="hidden" name="id" class="form-control" value="{{$info->password}}" value="{{$info->id}}">
                        </div> -->

                        <div class="form-group row">
                           <div class="col">
                              <label for="exampleInputEmail1">Referred Id</label>
                              <input type="text" name="referral_code" class="form-control" value="{{$info->referral_code}}" readonly>
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">User Type</label>
                              <input type="text" name="user_type" class="form-control" value="{{$info->user_type}}">
                           </div>
                           

                           <div class="col">
                              <label for="exampleInputEmail1">User Name</label>
                              <input type="text" name="name" class="form-control" value="{{$info->name}}">
                           </div>
                        </div>

                        <div class="form-group row">
                           <div class="col">
                              <label for="exampleInputEmail1">Email</label>
                              <input type="text" name="email" class="form-control" value="{{$info->email}}">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Password</label>
                              <input type="text" name="password" class="form-control" value="">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Phone No</label>
                              <input type="text" name="mobile" class="form-control" value="{{$info->mobile}}">
                           </div>
                        </div>


                        <div class="form-group row">
                           <div class="col">
                              <label for="exampleInputEmail1">Gender</label>
                              <input type="text" name="gender" class="form-control" value="{{$info->gender}}">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">DOB</label>
                              <input type="text" name="dob" class="form-control" value="{{$info->dob}}">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Upload User Image</label>
                              <input type="file" name="image" class="form-control" >
                           </div>
                        </div>

                        <div class="form-group row">
                           <div class="col">
                              <label for="exampleInputEmail1">Address</label>
                              <input type="text" name="address" class="form-control" value="{{$info->address}}">
                           </div>
                          
                        </div>


                        <div class="form-group row">
                           <div class="col">
                              <label for="exampleInputEmail1">Country</label>
                              <input type="text" name="country" class="form-control" value="{{$info->country}}">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">State</label>
                              <input type="text" name="state" class="form-control" value="{{$info->state}}">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">City</label>
                              <input type="text" name="city" class="form-control" value="{{$info->city}}">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Pincode</label>
                              <input type="text" name="pin" class="form-control" value="{{$info->pin}}">
                           </div>
                        </div>

                        <div class="form-group row">
                           <div class="col">
                              <label for="exampleInputEmail1">Adhar Number</label>
                              <input type="text" name="adhar_number" class="form-control" value="{{$info->adhar_number}}">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Adhar Upload Image (Front)</label>
                              <input type="file" name="aadharfront" class="form-control" value="">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Adhar Upload Image(Back)</label>
                              <input type="file" name="aadharback" class="form-control" value="">
                           </div>
                        </div>

                        <div class="form-group row">
                           <div class="col">
                              <label for="exampleInputEmail1">Pan Card Number</label>
                              <input type="text" name="pancard_num" class="form-control" value="{{$info->pancard_num}}">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Pan Card Upload Image</label>
                              <input type="file" name="pancard" class="form-control" value="">
                           </div>
                           
                        </div>

                        <div class="form-group row">
                           <div class="col">
                              <label for="exampleInputEmail1"> Bank Name</label>
                              <input type="text" name="bank_name" class="form-control" value="{{$info->bank_name}}">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Bank Branch</label>
                              <input type="text" name="bank_branch" class="form-control" value="{{$info->bank_branch}}">
                           </div>
                           
                        </div>

                        <div class="form-group row">
                         
                           <div class="col">
                              <label for="exampleInputEmail1"> Account Name</label>
                              <input type="text" name="account_name" class="form-control" value="{{$info->account_name}}">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Account Number</label>
                              <input type="text" name="account_number" class="form-control" value="{{$info->account_number}}">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Account IFSC </label>
                              <input type="text" name="account_ifsc" class="form-control" value="{{$info->account_ifsc}}">
                           </div>
                        </div>

                        <div class="form-group row">
                         
                           <div class="col">
                              <label for="exampleInputEmail1"> UPI ID</label>
                              <input type="text" name="upi_id" class="form-control" value="{{$info->upi_id}}">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Upload QR Code </label>
                              <input type="file" name="cheque" class="form-control" value="">
                           </div>
                          
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