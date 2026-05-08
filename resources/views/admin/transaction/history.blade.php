@extends('admin.layout.layout')
@section('content')
<?php error_reporting(0); ?>
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Transaction History</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Transaction History</li>
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
               <div class="card-header p-2">
                     <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#normal" data-toggle="tab">All Transactions</a></li>
                        <li class="nav-item"><a class="nav-link" href="#premium" data-toggle="tab">Failed Transactions</a></li>
                        <li class="nav-item"><a class="nav-link" href="#prime" data-toggle="tab">Cancelled Transactions</a></li>
                        <li class="nav-item"><a class="nav-link" href="#expired" data-toggle="tab"> Refunds Request</a></li>
                        <li class="nav-item"><a class="nav-link" href="#delete" data-toggle="tab"> Refunds Approved</a></li>
                     </ul>
                  </div>

                  <!--<div class="card-header">
                     <h3 class="card-title"><a href="{{url('add-freetrail')}}"><button type="button" class="btn btn-block bg-gradient-primary">Add Subscription</button></a></h3>
                     </div>-->
                  <!-- /.card-header -->
                  <div class="card-body">
                     <table id="example1" class="table table-bordered table-striped">
                        <thead>
                           <tr>
                              <th>S.No</th>
                              <th>Date & Time</th>
                              <th>Customer Name</th>
                              <th>Mobile Number</th>
                              <th>Subscription ID</th>
                              <th>Bill Amount</th>
                              <th>Payment Status</th>
                              <th>Transction ID</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($transaction_history as $key => $orderDetails)
                           <tr>
                              <td>{{$key + 1}}</td>
                              <td>{{$orderDetails->created_at}}</td>
                              <td>
                                 <?php
                                    $result = DB::table('customers')->where('id',$orderDetails->user_id)->get();
                                    echo $result[0]->name;
                                    ?>
                              </td>
                              <td>
                                 <?php
                                    $result = DB::table('customers')->where('id',$orderDetails->user_id)->get();
                                    echo $result[0]->mobile;
                                    ?>
                              </td>
                              <td>
                                 <?php
                                    $result = DB::table('subscriptions')->where('id',$orderDetails->subscription_id)->get();
                                    echo $result[0]->package;
                                    ?>
                              </td>
                              <td>
                                 <?php
                                    $result = DB::table('subscriptions')->where('id',$orderDetails->subscription_id)->get();
                                    echo $result[0]->offered_price;
                                    ?>
                              </td>
                              <td>{{$orderDetails->payment_status}}</td>
                              <td>{{$orderDetails->transaction_id}}</td>
                              <td><a href="{{ url('view-transction-history')}}/{{$orderDetails->id}}"><button type="button" class="btn btn-success">View</button></a></td>
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
                                       <a href="{{url('delete-freetrail/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary">Yes</button></a>
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
                              <th>Date & Time</th>
                              <th>Customer Name</th>
                              <th>Mobile Number</th>
                              <th>Subscription ID</th>
                              <th>Bill Amount</th>
                              <th>Payment Status</th>
                              <th>Transction ID</th>
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