@extends('admin.layout.layout')
@section('content')
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Custom Notification History</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item active">Custom Notification History</li>
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
                                    <div class="col-12">
                                       <div class="card">
                                          <!-- /.card-header -->
                                          <div class="card-body">
                                          <table id="example3" class="table table-bordered table-striped">
                                                <thead>
                                                   
                                                   <tr>
                                                      <th>Date & Time </th>
                                                      <th>Customer Name</th>
                                                      <th>Email ID</th>
                                                      <th>Mobile Number</th>
                                                      <th>Image</th>
                                                      <th>Title</th>
                                                      <th>Status</th>
                                                       <th>Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                    $count = 1;
                                                @endphp
                                                @foreach ($notifications as $notification)
                                                @if(isset($notification->customer) && !empty($notification->customer))
                                                    <tr>
                                                        <td>{{$notification->created_at}}</td>
                                                        <td>{{$notification->customer->name ?? '' }}</td>
                                                        <td>{{$notification->customer->email ?? ''}}</td>
                                                        <td>{{$notification->customer->mobile ?? ''}}</td>
                                                        <td><img src="{{$notification->image}}" style="width:50px;"></td>
                                                        <td>{{$notification->title}}</td>
                                                        <td>{{$notification->status}}</td>
                                                        <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#messagemodal{{$notification->id}}" title="View Message" style="color:#fff;"><i class="fas fa-eye"></i></button>
                                                        
                                                        <div class="modal" tabindex="-1" role="dialog" id="messagemodal{{$notification->id}}">
                                                          <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                              <div class="modal-header">
                                                                <h5 class="modal-title">Message</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                  <span aria-hidden="true">&times;</span>
                                                                </button>
                                                              </div>
                                                              <div class="modal-body">
                                                                <p>{{$notification->message}}</p>
                                                              </div>
                                                              <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                              </div>
                                                            </div>
                                                          </div>
                                                        </div>
                                                        
                                                        </td>
                                                        
                                                    </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                                <tfoot>
                                                   <tr>
                                                      <th>Date & Time </th>
                                                      <th>Customer Name</th>
                                                      <th>Email ID</th>
                                                      <th>Mobile Number</th>
                                                      <th>Image</th>
                                                      <th>Title</th>
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
      </div></div>
      <!-- /.container-fluid -->
   </section>
</div>
@endsection