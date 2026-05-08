@extends('admin.layout.layout')
@section('content')
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>View Earnings </h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">View Earnings </li>
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
                 
                  <!-- <div class="card-header">
                     <h3 class="card-title"><a href="{{url('add-vehicletypes')}}"><button type="button" class="btn btn-block bg-gradient-primary">Add Vehicle Types</button></a></h3>
                     </div> -->
                  <!-- /.card-header -->
                  <div class="card-body">
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
                                                      <th>Date & Time</th>
                                                      <th>Full Name</th>
                                                      <th>Mobile Number</th>
                                                      <th>Earn from</th>
                                                      <th>Earn From Mobile Number </th>
                                                      <th>Subscription Amount</th>
                                                      <th>Total Commission</th>
                                                      <th>TDS </th>
                                                      <th>Admin Charge</th>
                                                      <th>Other Charge</th>
                                                      <th>Final Earning</th>
                                                      <th>Wallet Balance</th>
                                                      <th>Status</th>
                                                      
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   @foreach($info as $items)
                                                   <tr>
                                                    
                                                      <td>{{$items->created_at}}</td>
                                                      <td>{{$items->customersparent->name ?? "NA"}}</td>
                                                      <td>{{$items->customersparent->mobile ?? "NA"}}</td>
                                                      <td>{{$items->customers->name ?? "NA"}}</td>
                                                      <td>{{$items->customers->mobile ?? "NA"}}</td>
                                                      <td>{{$items->offered_price ?? "NA"}}</td>
                                                      <td>{{$items->comission_paid_amount+$items->tds_amount_of_comission+$items->admin_charges_of_comission ?? "NA"}}</td>
                                                      <td>{{$items->tds_amount_of_comission ?? "NA"}}</td>
                                                      <td>{{$items->admin_charges_of_comission ?? "NA"}}</td>
                                                      <td>{{$items->other_charges_of_comission ?? "NA"}}</td>
                                                      <td>{{$items->comission_paid_amount ?? "NA"}}</td>
                                                      <td>₹{{$items->customers->wallet_amount ?? 0.00}}</td>
                                                      <td>
                                                          @if($items->subscription_expiry > date('Y-m-d'))
                                                            <button type="button" class="btn btn-success">Active</button>
                                                      @else
                                                       <button type="button" class="btn btn-danger">Expired</button>
                                                      @endif
                                                   </tr>
                                                  
                                                  @endforeach
                                                </tbody>
                                                <tfoot>
                                                   <tr>
                                                     <th>Date & Time </th>
                                                      <th>Full Name </th>
                                                      <th>Mobile Number </th>
                                                      <th>Earn from  </th>
                                                      <th>Member Id  </th>
                                                      <th>Subscription Amount </th>
                                                      <th>Total Commission  </th>
                                                      <th>TDS </th>
                                                      <th>Admin Charge</th>
                                                      <th>Other Charge</th>
                                                      <th>Final Earning</th>
                                                      <th>Wallet Balance</th>
                                                      <th>Status</th>
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