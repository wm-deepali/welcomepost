@extends('admin.layout.layout')
@section('content')
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Manage Default Notifications</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item active">Manage Default Notifications</li>
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
                                                      <th>S.No</th>
                                                      <th>Event</th>
                                                      <th>Title</th>
                                                      <th>Content</th>
                                                      <th>Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                    $count = 1;
                                                @endphp
                                                @foreach ($notifications as $notification)
                                                    <tr>
                                                        <td>{{$count++}}</td>
                                                        <td>{{$notification->event}}</td>
                                                        <td>{{$notification->title}}</td>
                                                        <td>{{$notification->content}}</td>
                                                        <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#messageUpdatemodal{{$notification->id}}">Update</button>
                                                            <div class="modal" tabindex="-1" role="dialog" id="messageUpdatemodal{{$notification->id}}">
                                                              <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                  <div class="modal-header">
                                                                    <h5 class="modal-title">Update Notification Content</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                      <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                  </div>
                                                                  <form action="{{route('update-notification-contents')}}" method="post">
                                                                      @csrf
                                                                        <input type="hidden" name="id" class="form-control" value="{{$notification->id}}">
                                                                      <div class="modal-body">
                                                                        <div class="form-group">
                                                                            <label for="title">Title</label>
                                                                            <input type="text" name="title" class="form-control" value="{{$notification->title}}" placeholder="Enter title" required>
                                                                        </div>
                                        				  
                                        				                <div class="form-group">
                                                                            <label for="content">Message</label>
                                                                            <textarea name="content" class="form-control" placeholder="Enter Message" required>{{$notification->content}}</textarea>
                                                                            <div style="color:red;font-size:14px;"><b>Note:</b> Please don't change "#variable" in message string.</div>
                                                                        </div>
                                                                      </div>
                                                                      <div class="modal-footer">
                                                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                      </div>
                                                                    </form>
                                                                </div>
                                                              </div>
                                                            </div>
                                                        
                                                        
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                                <tfoot>
                                                   <tr>
                                                      <th>S.No</th>
                                                      <th>Event</th>
                                                      <th>Title</th>
                                                      <th>Content</th>
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