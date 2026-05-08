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
               <h1>Edit Admin Setting</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Edit Admin Setting</li>
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
                  <form action="{{url('post-edit-admin-setting')}}" role="form" id="quickForm" method="post" enctype="multipart/form-data">
                     @csrf
                     <input type="hidden" name="id" class="form-control" value="{{$info->id}}">
                     <div class="card-body">
                        <hr>
                        <h6>TDS</h6>
                        <hr>
                        <div class="form-group row">
                           <div class="col">
                              <label for="exampleInputEmail1">With in Pan Card</label>
                              <input type="text" name="with_in_pan" class="form-control" placeholder="Enter With in Pan card" value="{{$info->with_in_pan}}">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">With out Pan Card</label>
                              <input type="text" name="with_out_pan" class="form-control" placeholder="Enter With out  Pan card" value="{{$info->with_out_pan}}">
                           </div>
                        </div>
                        <div class="form-group row">
                           <div class="col">
                              <label for="exampleInputEmail1">Admin Charges</label>
                              <input type="text" name="admin_charges" class="form-control"  placeholder="Enter Admin Charges %" value="{{$info->admin_charges}}">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">Other Charges %</label>
                              <input type="text" name="other_charges" class="form-control"  placeholder="Enter Other Charges %" value="{{$info->other_charges}}">
                           </div>
                        </div>
                        <div class="form-group row">
                             <div class="col">
                           <label for="exampleInputEmail1">User Expiry(Days)</label>
                           <input type="number" name="reserve_member_expiry" class="form-control"  placeholder="expiry Days" value="{{$info->reserve_member_expiry}}">
                           <input type="hidden" name="old_reserve_member_expiry" class="form-control"  placeholder="expiry Days" value="{{$info->reserve_member_expiry}}">
                        </div>
                         <div class="col">
                            <label for="exampleInputEmail1">Apply To</label>
                           <select class="form-control" name="apply_to" id="apply_to">
                               <option @if($info->apply_to == "new_users") selected  @endif value="new_users">New Users Only</option>
                               <option @if($info->apply_to == "all_users") selected  @endif value="all_users">All Users</option>
                           </select>
                            </div>
                            </div>
                            <div class="form-group row">
                             <div class="col">
                           <label for="exampleInputEmail1">User Seed Expiry(Days)</label>
                           <input type="number" name="reserve_expiry_timeline" class="form-control"  placeholder="expiry Days" value="{{$info->reserve_expiry_timeline}}">
                           <input type="hidden" name="old_reserve_expiry_timeline" class="form-control"  placeholder="expiry Days" value="{{$info->reserve_expiry_timeline}}">
                        </div>
                         <div class="col">
                            <label for="exampleInputEmail1">Apply To</label>
                           <select class="form-control" name="apply_to_reserve_expiry_timeline" id="apply_to_reserve_expiry_timeline">
                               <option @if($info->apply_to_reserve_expiry_timeline == "new_users") selected  @endif value="new_users">New Users Only</option>
                               <option @if($info->apply_to_reserve_expiry_timeline == "all_users") selected  @endif value="all_users">All Users</option>
                           </select>
                            </div>
                            </div>
                        <hr>
                        <h6>GST</h6>
                        <hr>
                        <div class="form-group row">
                           <div class="col">
                              <label for="exampleInputEmail1">IGST</label>
                              <input type="text" name="igst" class="form-control"  placeholder="Enter IGST" value="{{$info->igst}}">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">CGST</label>
                              <input type="text" name="cgst" class="form-control"  placeholder="Enter  CGST" value="{{$info->cgst}}">
                           </div>
                           <div class="col">
                              <label for="exampleInputEmail1">SGST</label>
                              <input type="text" name="sgst" class="form-control"  placeholder="Enter  SGST" value="{{$info->sgst}}">
                           </div>
                        </div>
                        <hr>
                        <h6>Welcome bonus setting</h6>
                        <hr>
                        
                        <div class="form-group row">
                            <div class="col">
                                <label for="welcome_amount">Welcome-Bonus Amount</label>
                                <input type="number" id="welcome_amount" name="welcome_amount" class="form-control"  placeholder="Enter amount name" value="{{$info->welcome_amount}}">
                            </div>
                            <div class="col">
                                <label for="wallet-limit">Wallet use limit(%)</label>
                                <input type="number" id="wallet-limit" name="wallet_limit" class="form-control"  placeholder="Enter wallet amount use limit" value="{{$info->wallet_limit}}">
                            </div>
                        </div>

                        <hr>
                        <h6>Invoice setting</h6>
                        <hr>

                        <div class="form-group row">
                            <div class="col">
                            <label for="exampleInputEmail1">Company Name</label>
                            <input type="text" name="company_name" class="form-control"  placeholder="Enter  Company name" value="{{$info->company_name}}">
                            </div>
                            <div class="col">
                            <label for="exampleInputEmail1">GST Number</label>
                            <input type="text" name="gstno" class="form-control"  placeholder="Enter  GST Number" value="{{$info->gstno}}">
                            </div>
                        </div>
                    <div class="form-group row">
                            <div class="col">
                            <label for="exampleInputEmail1">Country</label>
                           <select name="country" id="country" class="form-control ca-check-plan"  required="">
                           <option value="">Country</option>
                           <option @if($info->country == 1) selected @endif value="1">India</option>
                        </select>
                            </div>
                            <div class="col">
                            <label for="exampleInputEmail1">State</label>
                            <select name="state" id="state" class="form-control ca-check-plan state"  required="">
                           <option value="">State</option>
                           @foreach(\App\Models\States::all() as $key => $state)
                           <option @if($info->state == $state->id) selected @endif value="{{$state->id}}">{{$state->name}}</option>
                           @endforeach
                        </select>
                            </div>
                            <div class="col">
                            <label for="exampleInputEmail1">City</label>
                            <select name="city" id="city" class="form-control ca-check-plan city"  required="">
                           <option value="">City</option>
                           @foreach(\App\Models\City::where('state_id',$info->state)->get() as $key => $city)
                           <option @if($info->city == $city->id) selected @endif value="{{$city->id}}">{{$city->name}}</option>
                           @endforeach
                        </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col">
                            <label for="exampleInputEmail1">Full Address </label>
                            <input type="text" name="full_address" class="form-control"  placeholder="Enter Full Address" value="{{$info->full_address}}">
                            </div>
                            <div class="col">
                            <label for="exampleInputEmail1">Email Id</label>
                            <input type="text" name="email_id" class="form-control"  placeholder="Enter  Email Id" value="{{$info->email_id}}">
                            </div>
                            <div class="col">
                            <label for="exampleInputEmail1">Contact No</label>
                            <input type="text" name="contact_no" class="form-control"  placeholder="Enter  Contact No" value="{{$info->contact_no}}">
                            </div>
                        </div>

                        <hr>
                        <h6>Invoice Number</h6>
                        <hr>

                        <div class="form-group row">
                            <div class="col">
                            <label for="exampleInputEmail1">Prefix Number </label>
                            <input type="text" name="prefix_number" class="form-control"  placeholder="Enter Prefix Number" value="{{$info->prefix_number}}">
                            </div>
                            <div class="col">
                            <label for="exampleInputEmail1">Serial Number</label>
                            <input type="text" name="serial_number" class="form-control"  placeholder="Enter  Serial Number" value="{{$info->serial_number}}">
                            </div>
                          
                        </div>
                        

                        <div class="form-group row">
                            <div class="col">
                            <label for="exampleInputEmail1">Referal Join </label>
                          


                              <label class="switch">
                             <input type="checkbox" @if($info->referal_join==1){{"checked"}} @else {{ "" }} @endif id="changestatuss" onchange="toggleValue()">
                                 <span class="slider round "></span>
                           </label>

                            </div>
                            <input type="hidden" id="statusvalues" name="referal_join" value="{{ $info->referal_join ?? '0' }}">


                            <div class="col">
                            
                            <label for="exampleInputEmail1">Auto joining</label> 
                                 <label class="switch">
                             <input type="checkbox"  @if($info->auto_join==1){{"checked"}} @else {{ "" }} @endif id="changestatus" onchange="toggleValuea()">
                                 <span class="slider round "></span>
                           </label>
                                
                           </div>
                           
                             <input type="hidden" id="statusvalue" name="auto_join" value="{{ $info->auto_join ?? '0' }}">
                             
                             <div class="col">
                            
                            <label for="exampleInputEmail1">Referral on Active Subscription</label> 
                                 <label class="switch">
                             <input type="checkbox"  @if($info->is_active_ad_referral==1){{"checked"}} @else {{ "" }} @endif id="changestatus2" onchange="toggleValuea2()">
                                 <span class="slider round "></span>
                           </label>
                                
                           </div>
                           <input type="hidden" id="statusvalue2" name="is_active_ad_referral" value="{{ $info->is_active_ad_referral ?? '0' }}">
                            </div>

                            <div class="col">
                            <label for="exampleInputEmail1">Number Of View </label>
                            <input type="text" name="numer_of_view" class="form-control"  placeholder="Enter  Serial Number" value="{{$info->numer_of_view}}">
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
        function toggleValuea() {
            var checkbox = document.getElementById("changestatus");
            // If checked, set value to 1; otherwise, set value to 0
            var value = checkbox.checked ? 1 : 0;
            $("#statusvalue").val(value);
            console.log("Switch value:", value);
            // You can use 'value' as needed, for example, send it to the server or perform other actions.
        }
        
        function toggleValuea2() {
            var checkbox = document.getElementById("changestatus2");
            // If checked, set value to 1; otherwise, set value to 0
            var value = checkbox.checked ? 1 : 0;
            $("#statusvalue2").val(value);
            console.log("Switch value:", value);
            // You can use 'value' as needed, for example, send it to the server or perform other actions.
        }
        
        $(document).on("change", ".state", function() {
                $("#city").html("");
               let state_id = $(this).val();  
               $.ajax({
                   url: `{{ URL::to('cities-by-state') }}`,
                   type: "post",
                   dataType: "json",
                   data:{"state_id":state_id, "_token": "{{ csrf_token() }}",},
                   success: function(result) {
                       console.log(result);
                       $("#city").html(result);
                      
                   }
               });
           });
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