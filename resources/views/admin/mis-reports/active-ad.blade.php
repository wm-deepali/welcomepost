@extends('admin.layout.layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Active Ads</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item">MIS-Report</li>
              <li class="breadcrumb-item active">Active Ads</li>
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
                                                            <table id="examples10Table" class="table table-bordered table-striped mis-table">
                                                                <thead>
                                                                    <tr>
                                                                      <th>Ad ID</th>
                                                                      <th>Active date</th>
                                                                      <th>No of images use</th>
                                                                      <th>Posted by User id</th>
                                                                      <th>Category Name</th>
                                                                      <th>Sub Category Name</th>
                                                                      <th>City</th>
                                                                      <th>Country</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($active_ads as $ad)
                                                                    @php
                                                                        $ad_image_count = App\Models\AdPostingImage::where('ads_id',$ad->ad_id)->count();
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{$ad->ad_id}}</td>
                                                                        <td>{{$ad->published_date}}</td>
                                                                        <td>{{$ad_image_count}}</td>
                                                                        <td>{{$ad->subscriptionhistory->customers->member_id??"N/a"}}</td>
                                                                        <td>{{$ad->category->name}}</td>
                                                                        <td>{{$ad->subcategory->name}}</td>
                                                                        <td>{{$ad->ad_city->name??'N/a'}}</td>
                                                                        <td>India</td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                                <tfoot>
                                                                   <tr>
                                                                      <th>Ad ID</th>
                                                                      <th>Active date</th>
                                                                      <th>No of images use</th>
                                                                      <th>Posted by User id</th>
                                                                      <th>Category Name</th>
                                                                      <th>Sub Category Name</th>
                                                                      <th>City</th>
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
        $('#examples10Table').DataTable({
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