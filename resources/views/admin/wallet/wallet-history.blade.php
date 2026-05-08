@extends('admin.layout.layout')
@section('content')
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Wallets History</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('user-wallet')}}">View User Wallets </a></li>
                  <li class="breadcrumb-item active">Wallets History</li>
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
                  <div class="card-body">
                    <section class="content">
                              <div class="container-fluid">
                                 <div class="row">
                                    <div class="col-10">
                                       <div class="card">
                                          <!-- /.card-header -->
                                          <div class="card-body">
                                          <table id="example3" class="table table-bordered table-striped">
                                                <thead>
                                                   <tr>
                                                      <th>S.No</th>
                                                      <th>Date & Time</th>
                                                      <th>Amount</th>
                                                      <th>Transaction Type</th>
                                                      <th>Created At</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                    $count = 1;
                                                @endphp
                                                @foreach ($transactions as $trans)
                                                    <tr>
                                                        <td>{{$count++}}</td>
                                                        <td>{{$trans->datetime}}</td>
                                                        <td>₹{{$trans->amount}}</td>
                                                        <td>{{$trans->status == 1 ? 'Credited' : 'Debited' }}</td>
                                                        <td>{{$trans->created_at }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                                <tfoot>
                                                   <tr>
                                                        <th>S.No</th>
                                                        <th>Date & Time</th>
                                                        <th>Amount</th>
                                                        <th>Transaction Type</th>
                                                        <th>Created At</th>
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