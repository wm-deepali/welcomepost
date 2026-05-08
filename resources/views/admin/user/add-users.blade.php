@extends('admin.layout.layout')
@section('content')
<style>
   .switch {
   position: relative;
   display: inline-block;
   width: 60px;
   height: 34px;
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
               <h1>Add Admin Setting</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Add Admin Setting</li>
               </ol>
            </div>
         </div>
      </div>
      <!-- /.container-fluid -->
   </section>
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
                     <h3 class="card-title">Add Details</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form action="{{url('postadminsetting')}}"  method="post" enctype="multipart/form-data">
                     @csrf
                     <div class="card-body">
                       
                        <div class="form-group row">
                           <div class="col">
                              <label for="exampleInputEmail1">Referred Id</label>
                              <input type="text" name="with_in_pan" class="form-control" >
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Referred Name</label>
                              <input type="text" name="with_out_pan" class="form-control" >
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">User Name</label>
                              <input type="text" name="admin_charges" class="form-control" >
                           </div>
                        </div>

                        <div class="form-group row">
                           <div class="col">
                              <label for="exampleInputEmail1">Email</label>
                              <input type="text" name="with_in_pan" class="form-control" >
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Password</label>
                              <input type="text" name="with_out_pan" class="form-control" >
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Phone No</label>
                              <input type="text" name="admin_charges" class="form-control" >
                           </div>
                        </div>

                        <div class="form-group row">
                           <div class="col">
                              <label for="exampleInputEmail1">Country</label>
                              <input type="text" name="with_in_pan" class="form-control" >
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">State</label>
                              <input type="text" name="with_out_pan" class="form-control" >
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">City</label>
                              <input type="text" name="admin_charges" class="form-control" >
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Pincode</label>
                              <input type="text" name="admin_charges" class="form-control" >
                           </div>
                        </div>

                        <div class="form-group row">
                           <div class="col">
                              <label for="exampleInputEmail1">Adhar Number</label>
                              <input type="text" name="with_in_pan" class="form-control" >
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Adhar Upload Image (Front)</label>
                              <input type="file" name="with_out_pan" class="form-control" >
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Adhar Upload Image(Back)</label>
                              <input type="file" name="admin_charges" class="form-control" >
                           </div>
                        </div>

                        <div class="form-group row">
                           <div class="col">
                              <label for="exampleInputEmail1">Pan Card Number</label>
                              <input type="text" name="with_in_pan" class="form-control" >
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Pan Card Upload Image</label>
                              <input type="file" name="with_out_pan" class="form-control" >
                           </div>
                           
                        </div>

                        <div class="form-group row">
                           <div class="col">
                              <label for="exampleInputEmail1"> Bank Name</label>
                              <input type="text" name="with_in_pan" class="form-control" >
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Bank Branch</label>
                              <input type="text" name="with_out_pan" class="form-control" >
                           </div>
                           
                        </div>

                        <div class="form-group row">
                         
                           <div class="col">
                              <label for="exampleInputEmail1"> Account Name</label>
                              <input type="text" name="admin_charges" class="form-control" >
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Account Number</label>
                              <input type="text" name="admin_charges" class="form-control" >
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Account IFSC </label>
                              <input type="text" name="admin_charges" class="form-control" >
                           </div>
                        </div>

                        <div class="form-group row">
                         
                           <div class="col">
                              <label for="exampleInputEmail1"> UPI ID</label>
                              <input type="text" name="admin_charges" class="form-control" >
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Upload QR Code </label>
                              <input type="text" name="admin_charges" class="form-control" >
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Account IFSC </label>
                              <input type="text" name="admin_charges" class="form-control" >
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

<script>
     const phoneInputField = document.querySelector("#mobile_number");
   const phoneInput = window.intlTelInput(phoneInputField, {
   initialCountry: "in",
	separateDialCode: true,
   utilsScript:
   "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
   });
</script>
@endsection