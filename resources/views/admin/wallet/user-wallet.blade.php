@extends('admin.layout.layout')
@section('content')
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>User Wallets </h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">View User Wallets </li>
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
                                          <table id="example3" class="table table-bordered table-striped">
                                                <thead>
                                                   <tr>
                                                      <th>S.No</th>
                                                      <th>Full Name</th>
                                                      <th>Email Id</th>
                                                      <th>Mobile Number</th>
                                                      <th>Wallet Balance </th>
                                                      <th>Last Transaction Date </th>
                                                      <th>Status</th>
                                                      <th>Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($customers as $cus)
                                                @php
                                                $count = 1;
                                                $lastTransaction = \App\Models\WalletAmout::where('userid', $cus->id)->orderByDesc('created_at')->first();
                                                @endphp
                                                
                                                @if($lastTransaction)
                                                      <tr>
                                                         <td>{{$count++}}</td>
                                                         <td>{{$cus->name}}</td>
                                                         <td>{{$cus->email ?? 'NA'}}</td>
                                                         <td>{{$cus->mobile ?? 'NA'}}</td>
                                                         <td>₹{{$cus->wallet_amount}}</td>
                                                         <td>{{$lastTransaction->datetime}}</td>
                                                         <td>{{$lastTransaction->status == 1 ? 'Credited' : 'Debited' }}</td>
                                                         <td>
                                                            <a class="btn btn-primary" style="font-size:10px;" href="{{ route('wallet-history', ['id' => $cus->id]) }}">Wallet History</a>
                                                         </td>
                                                      </tr>
                                                @endif
                                                @endforeach
                                                </tbody>
                                                <tfoot>
                                                   <tr>
                                                   <th>S.No</th>
                                                      <th>Date & Time</th>
                                                      <th>Full Name</th>
                                                      <th>Email Id</th>
                                                      <th>Mobile Number</th>
                                                      <th>Wallet Balance </th>
                                                      <th>Last Transaction Date </th>
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