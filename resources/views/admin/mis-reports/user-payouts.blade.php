@extends('admin.layout.layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User Payouts and Income</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item">MIS-Report</li>
              <li class="breadcrumb-item active">User Payouts and Income</li>
            </ol>
          </div> 
        </div>
      </div><!-- /.container-fluid -->
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
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle" id="dropdownMenuButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Reports</a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="{{route('mis-report')}}">Subscribed User Master Report</a></li>
                                    <li><a class="dropdown-item" href="{{url('mis-report/fail')}}">Fail transaction</a></li>
                                    <li><a class="dropdown-item" href="{{url('mis-report/reserve')}}">Reserve Seeds</a></li>
                                    <li><a class="dropdown-item" href="{{url('mis-report/active')}}">Active Seeds Data</a></li>
                                    <li><a class="dropdown-item" href="{{url('mis-report/user-income')}}">User  Income & Payout Data</a></li>
                                    <li><a class="dropdown-item" href="{{url('mis-report/daily-login')}}">Daily Login Report</a></li>
                                    <li><a class="dropdown-item" href="{{url('mis-report/delete-account')}}">Delete Account Report</a></li>
                                    <li><a class="dropdown-item" href="{{url('mis-report/block-user')}}">Block User</a></li>
                                    <li><a class="dropdown-item" href="{{url('mis-report/active-ad')}}">Active Ad Report</a></li>
                                    <li><a class="dropdown-item" href="{{url('mis-report/user-view')}}">User Views Report</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                                <section class="content">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-12">
                                               <div class="card">
                                                    <div class="card-body">
                                                        <table id="examples5Table" class="table table-bordered table-striped mis-table">
                                                            <thead>
                                                               <tr>
                                                                  <th>User ID</th>
                                                                  <th>Email</th>
                                                                  <th>Seed ID</th>
                                                                  <th>Seed Subscription MRP</th>
                                                                  <th>Seed Income</th>
                                                                  <th>TDS</th>
                                                                  <th>Other Charges</th>
                                                                  <th>Final Income</th>
                                                                  <th>Income Date</th>
                                                                  <th>Payment Method</th>
                                                                  <th>Payout Status</th>
                                                               </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($commission as $data)
                                                                @if(!empty($data)&&isset($data))
                                                                @php
                                                                    $parent = App\Models\Customer::where('id', $data->parent_id)->first();
                                                                @endphp
                                                                <tr>
                                                                    <td>{{$parent->member_id ??'N/A'}}</td>
                                                                    <td>{{$parent->email ??'N/A'}}</td>
                                                                    <td>{{$data->customer->member_id??'N/A'}}</td>
                                                                    <td>{{$data->customer->subscriptionhistory[0]->mrp?? 'N/A'}}</td>
                                                                    <td>{{$data->total_commission??'N/A'}}</td>
                                                                    <td>{{$data->tds}}</td>
                                                                    <td>{{$data->other_charges}}</td>
                                                                    <td>{{$data->total_earned}}</td>
                                                                    <td>{{$data->payment_date ?? 'N/A'}}</td>
                                                                    <td>{{$data->payment_method ?? 'N/A'}}</td>
                                                                    <td>{{$data->status??'N/A'}}</td>
                                                                </tr>
                                                                @endif
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                               <tr>
                                                                   <th>User ID</th>
                                                                  <th>Email</th>
                                                                  <th>Seed ID</th>
                                                                  <th>Seed Subscription MRP</th>
                                                                  <th>Seed Income</th>
                                                                  <th>TDS</th>
                                                                  <th>Other Charges</th>
                                                                  <th>Final Income</th>
                                                                  <th>Income Date</th>
                                                                  <th>Payment Method</th>
                                                                  <th>Payout Status</th>
                                                               </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                        </diV>                      
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function() {
        $('#examples5Table').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": true,
          "dom": 'Bfrtip',
          "buttons": [
            {
              extend: 'csvHtml5',
              text: 'Export CSV',
              className: 'btn btn-primary'
            },
            {
              extend: 'excelHtml5',
              text: 'Export Excel',
              className: 'btn btn-primary'
            }
          ]
        });
    });
 </script>
@endsection