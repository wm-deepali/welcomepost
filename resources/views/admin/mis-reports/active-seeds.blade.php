@extends('admin.layout.layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Active Seeds</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item"><a href="#">MIS-Report</a></li>
              <li class="breadcrumb-item active">Active Seeds</li>
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
                                                        <table id="examples4Table" class="table table-bordered table-striped">
                                                            <thead>
                                                               <tr>
                                                                  <th>Seed ID</th>
                                                                  <th>Seed Email</th>
                                                                  <th>Seed Active Date</th>
                                                                  <th>Seed Expiry Date</th>
                                                                  <th>Parent ID</th>
                                                                  <th>Parent Email</th>
                                                               </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($customer as $data)
                                                                @php
                                                                    $seeded = App\Models\Customer_child::where('child_id',$data->id)->first();
                                                                @endphp
                                                                @if(isset($data->parent_id)&&isset($data->reserve_expiry_at))
                                                                <tr>
                                                                    <td>{{$data->member_id}}</td>
                                                                    <td>{{$data->email ?? 'N/A'}}</td>
                                                                    <td>{{$seeded->joining_date ?? 'N/A'}}</td>
                                                                    <td>{{$seeded->reserve_expiry_at ?? 'N/A'}}</td>
                                                                    <td>{{$data->customerparent->member_id ??'N/A'}}</td>
                                                                    <td>{{$data->customerparent->email ??'N/A'}}</td>
                                                                </tr>
                                                                @endif
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                               <tr>
                                                                  <th>Seed ID</th>
                                                                  <th>Seed Email</th>
                                                                  <th>Seed Active Date</th>
                                                                  <th>Seed Expiry Date/th>
                                                                  <th>Parent ID</th>
                                                                  <th>Parent Email</th>
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
        $('#examples4Table').DataTable({
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
