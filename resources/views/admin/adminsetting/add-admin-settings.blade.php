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
                        <hr>
                        <h6>TDS</h6>
                        <hr>
                        <div class="form-group row">
                           <div class="col">
                              <label for="exampleInputEmail1">With in Pan Card</label>
                              <input type="text" name="with_in_pan" class="form-control" placeholder="Enter With in Pan card">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">With out Pan Card</label>
                              <input type="text" name="with_out_pan" class="form-control" placeholder="Enter With out  Pan card">
                           </div>
                        </div>
                        <div class="form-group row">
                           <div class="col">
                              <label for="exampleInputEmail1">Admin Charges</label>
                              <input type="text" name="admin_charges" class="form-control"  placeholder="Enter Admin Charges %">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Other Charges %</label>
                              <input type="text" name="other_charges" class="form-control"  placeholder="Enter Other Charges %">
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="exampleInputEmail1">Reserve Member expiry</label>
                           <input type="text" name="reserve_member_expiry" class="form-control"  placeholder="expiry Days">
                        </div>
                        <hr>
                        <h6>GST</h6>
                        <hr>
                        <div class="form-group row">
                           <div class="col">
                              <label for="exampleInputEmail1">IGST</label>
                              <input type="text" name="igst" class="form-control"  placeholder="Enter IGST">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">CGST</label>
                              <input type="text" name="cgst" class="form-control"  placeholder="Enter  CGST">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">SGST</label>
                              <input type="text" name="sgst" class="form-control"  placeholder="Enter  SGST">
                           </div>
                        </div>

                        <hr>
                        <h6>Invoice setting</h6>
                        <hr>

                        <div class="form-group row">
                            <div class="col">
                            <label for="exampleInputEmail1">Company Name</label>
                            <input type="text" name="company_name" class="form-control"  placeholder="Enter  Company name">
                            </div>
                            <div class="col">
                            <label for="exampleInputEmail1">GST Number</label>
                            <input type="text" name="gstno" class="form-control"  placeholder="Enter  GST Number">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col">
                            <label for="exampleInputEmail1">Full Address </label>
                            <input type="text" name="full_address" class="form-control"  placeholder="Enter Full Address">
                            </div>
                            <div class="col">
                            <label for="exampleInputEmail1">Email Id</label>
                            <input type="text" name="email_id" class="form-control"  placeholder="Enter  Email Id">
                            </div>
                            <div class="col">
                            <label for="exampleInputEmail1">Contact No</label>
                            <input type="text" name="contact_no" class="form-control"  placeholder="Enter  Contact No">
                            </div>
                        </div>

                        <hr>
                        <h6>Invoice Number</h6>
                        <hr>

                        <div class="form-group row">
                            <div class="col">
                            <label for="exampleInputEmail1">Prefix Number </label>
                            <input type="text" name="prefix_number" class="form-control"  placeholder="Enter Prefix Number">
                            </div>
                            <div class="col">
                            <label for="exampleInputEmail1">Serial Number</label>
                            <input type="text" name="serial_number" class="form-control"  placeholder="Enter  Serial Number">
                            </div>
                          
                        </div>
                        

                        <div class="form-group row">
                            <div class="col">
                            <label for="exampleInputEmail1">Referal Join </label>
                            <label class="switch">
                              <input type="checkbox" name="referal_join" id="changestatuss"  onchange="toggleValue()" >
                              <span class="slider round "></span>
                              </label>
                            </div>
                            <input type="hidden" id="statusvalues" name="referal_join" value="0">

                            <div class="col">
                            <label for="exampleInputEmail1">Auto Join</label>
                            <label class="switch">
                              <input type="checkbox" name="auto_join" id="changestatus" onchange="toggleValue()"   >
                              <span class="slider round"></span>
                              </label>
                            </div>
                            <input type="hidden" id="statusvalue" name="auto_join" value="0">
                            <div class="col">
                            <label for="exampleInputEmail1">Number Of View </label>
                            <input type="text" name="numer_of_view" class="form-control"  placeholder="Enter  Serial Number">
                            </div>
                            <div class="col">
                            <label for="exampleInputEmail1">Apply To</label>
                           <select class="form-control" name="apply_to" id="apply_to">
                               <option value="new_users">New Users Only</option>
                               <option value="all_users">All Users</option>
                           </select>
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
<script>
        function toggleValue() {
            var checkbox = document.getElementById("changestatus");
            // If checked, set value to 1; otherwise, set value to 0
            var value = checkbox.checked ? 1 : 0;
            $("#statusvalue").val(value);
            console.log("Switch value:", value);
            // You can use 'value' as needed, for example, send it to the server or perform other actions.
        }
    </script>

<script>
        function toggleValue() {
            var checkbox = document.getElementById("changestatuss");
            // If checked, set value to 1; otherwise, set value to 0
            var value = checkbox.checked ? 1 : 0;
            $("#statusvalues").val(value);
            console.log("Switch value:", value);
            // You can use 'value' as needed, for example, send it to the server or perform other actions.
        }
    </script>