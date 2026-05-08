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
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Manage Comission</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Manage Comission</li>
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
   @if (session('error'))
   <div class="card-body">
      <div class="alert alert-danger alert-dismissible">
         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
         <h5>{{ Session::get('error') }}</h5>
         <?php Session::forget('error');?>
      </div>
   </div>
   @endif
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12">
               <div class="card">
                  <div class="card-header">
                      <h3 class="card-title"><a href="{{url('add-manage-commission-setting')}}"  class="btn btn-block bg-gradient-primary">Add</a></h3>
                      <a href="{{url('manage-commission-temporary')}}"  class="btn btn-md bg-gradient-secondary ml-2">Temporary Delete</a>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                     <table id="example1" class="table table-bordered table-striped">
                        <thead>
                           <tr>
                              
                              <th>Date & Time</th>
                              <th>Subscription</th>
                              <th>Commission</th>
                              <th>Auto Join</th>
                              <th>Auto joining Members</th>
                              <th>Minimum Views</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                       
                        @foreach($data['managecommissions'] as $orderDetails)
                           <tr>
                           @php
                           $sub = DB::table('subscriptions')->where('id',$orderDetails->subscription_packge_id)->first();
                           @endphp
                           <td>
                              {{ $orderDetails->created_at ?? '' }}
                              </td>
                              <td>
                              {{ $sub->package ?? '' }}
                              </td>
                              <td>{{$orderDetails->commission}}</td>
                              <td>
                              @if(!empty($orderDetails->auto_join==0))
                              <label class="switch">
                                 <input type="checkbox" disabled>
                                 <span class="slider round "></span>
                                 </label>
                                 @else
                                 <label class="switch">
                                 <input type="checkbox" checked disabled>
                                 <span class="slider round "></span>
                                 </label>

                                 @endif

                              <!-- {{$orderDetails->auto_join}} -->
                               
                              </td>
                              <td>{{$orderDetails->auto_join_member}}</td>
                              <td>{{$orderDetails->minimum_views}}</td>
                              <td>
                                 <a href="{{url('edit-manage-commission-setting/'.$orderDetails->id)}}"><button type="button" class="btn btn-primary"><i class="fa fa-edit"></i></button></a>
                                  <button type="button" class="btn btn-danger"  data-toggle="modal" data-target="#modal-delete{{$orderDetails->id}}"><i class="fa fa-trash"></i></button> 
                              </td>
                           </tr>
                           <div class="modal fade" id="modal-delete{{$orderDetails->id}}">
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
                                       <a href="{{route('delete-manage-commission-setting',$orderDetails->id)}}"><button type="button" class="btn btn-primary">Yes</button></a>
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
                          
                              <th>Subscription</th>
                              <th>Commission</th>
                              <th>Auto Join</th>
                              <th>Auto joining Members</th>
                              <th>Minimum Views</th>
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