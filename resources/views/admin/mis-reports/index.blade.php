@extends('admin.layout.layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Subscription-Report</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item">MIS-Report</li>
              <li class="breadcrumb-item active">Subscription-Report</li>
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
                                                   <table id="examples1Table" class="table table-bordered table-striped mis-table">
                                                        <thead>
                                                            <tr>
                                                                <th>Sr.no</th>
                                                                <th>Customer ID</th>
                                                                <th>Customer Name</th>
                                                                <th>Parent ID</th>
                                                                <th>Parent Name</th>
                                                                <th>Account Created On</th>
                                                                <th>Email</th>
                                                                <th>Contact No</th>
                                                                <th>Subscription Name</th>
                                                                <th>Subscription ID</th>
                                                                <th>Subscription Purchased On</th>
                                                                <th>Expiry Date</th>
                                                                <th>No of Ads</th>
                                                                <th>No of Seeds</th>
                                                                <th>Active Ads</th>
                                                                <th>Pending Ads</th>
                                                                <th>Active Seeds</th>
                                                                <th>Remain Seed</th>
                                                                <th>Welcome bonus</th>
                                                                <th>Wallet</th>
                                                                <th>MRP</th>
                                                                <th>Transaction ID</th>
                                                                <th>Discount</th>
                                                                <th>City</th>
                                                                <th>State</th>
                                                                <th>Country</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $count = 1; @endphp
                                                            @foreach($customer as $data)
                                                            @php $pending = App\Models\Adposting::where('user_id', $data->id)->where('delete_status', '0')->where('status', '0')->orderby('id', 'desc')->count(); @endphp
                                                            <tr>
                                                                <td>{{ $count++ }}</td>
                                                                <td>{{ $data->member_id }}</td>
                                                                <td>{{ $data->name }}</td>
                                                                <td>{{ isset($data->customerparent)? $data->customerparent->member_id : '' }}</td>
                                                                <td>{{ isset($data->customerparent)? $data->customerparent->name : '' }}</td>
                                                                <td>{{ $data->created_at }}</td>
                                                                <td>{{ $data->email }}</td>
                                                                <td>{{ $data->mobile }}</td>
                                                                <td>{{ $data->subscriptionhistory[0]->subscriptions->package ?? 'N/A' }}</td>
                                                                <td>{{ $data->subscriptionhistory[0]->subscription_number ?? 'N/A' }}</td>
                                                                <td>{{ $data->subscriptionhistory[0]->created_at ?? 'NA' }}</td>
                                                                <td>{{ $data->subscriptionhistory[0]->subscription_expiry ?? 'NA' }}</td>
                                                                <td>{{ $data->subscriptionhistory[0]->remaining_ads ?? 'NA' }}</td>
                                                                <td>{{ $data->subscriptionhistory[0]->auto_join_member ?? 'NA' }}</td>
                                                                <td>{{ $data->subscriptionhistory[0]->used_ads ?? '0' }}</td>
                                                                <td>{{ $pending }}</td>
                                                                <td>{{ $data->subscriptionhistory[0]->total_joined ?? '0' }}</td>
                                                                <td>{{ ($data->subscriptionhistory[0]->auto_join_member ?? 0) - ($data->subscriptionhistory[0]->total_joined ?? 0) }}</td>
                                                                <td>{{ $data->wallet_bonus ?? '0.00' }}</td>
                                                                <td>{{ $data->wallet_amount ?? '0.00' }}</td>
                                                                <td>{{ $data->subscriptionhistory[0]->mrp ?? '0.00' }}</td>
                                                                <td>{{ $data->subscriptionhistory[0]->order_number ?? 'N/A' }}</td>
                                                                <td>{{ $data->subscriptionhistory[0]->discount_amount ?? 'N/A' }}</td>
                                                                <td>{{ $data->cities->name ?? 'N/A' }}</td>
                                                                <td>{{ $data->states->name ?? 'N/A' }}</td>
                                                                <td>{{ $data->countries->name ?? 'N/A' }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th>Sr.no</th>
                                                                <th>Customer ID</th>
                                                                <th>Customer Name</th>
                                                                <th>Account Created On</th>
                                                                <th>Email</th>
                                                                <th>Contact No</th>
                                                                <th>Subscription Name</th>
                                                                <th>Subscription ID</th>
                                                                <th>Subscription Purchased On</th>
                                                                <th>Expiry Date</th>
                                                                <th>No of Ads</th>
                                                                <th>No of Seeds</th>
                                                                <th>Active Ads</th>
                                                                <th>Pending Ads</th>
                                                                <th>Active Seeds</th>
                                                                <th>Remain Seed</th>
                                                                <th>Welcome bonus</th>
                                                                <th>Wallet</th>
                                                                <th>MRP</th>
                                                                <th>Transaction ID</th>
                                                                <th>Discount</th>
                                                                <th>City</th>
                                                                <th>State</th>
                                                                <th>Country</th>
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
        $('#examples1Table').DataTable({
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