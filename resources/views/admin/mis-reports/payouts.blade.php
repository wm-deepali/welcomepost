@extends('admin.layout.layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User Commissions</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item">MIS-Report</li>
              <li class="breadcrumb-item active">User Commissions</li>
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
                                                                  <th>Date & Time</th>
                                                                   <th>Level</th>
                                                                   <th>Subscription Buyer</br>Name(ID)</th>
                                                                    <th>Commission Receiver</br>Name(ID)</th>
                                                                    <th>Commission %</th>
                                                                    <th>Subscription Amount</th>
                                                                    <th>Welcome Bonus</th>
                                                                    <th>Wallet Amount</th>
                                                                   <th>Total Commission</th>
                                                                   <th>TDS</th>
                                                                   <th>Admin Charge</th>
                                                                   <th>Other Chage</th>
                                                                   <th>Earned</th>
                                                                   <th>Status</th>
                                                                   <th>Action</th>
                                                               </tr>
                                                            </thead>
                                                            <tbody>
                                                                 @foreach ($commission as $items)
                                                            @php 
                                                            if(isset($items->customer) && isset($items->customer->id) && $items->customer->id !='')
                                                            {
                                                            
                                                                $totalWalletamount = \App\Models\WalletAmout::WelcomeBonus()->where('userid', $items->customer->id)->sum('amount');
                                                                $totalCashfreeAmount = \App\Models\WalletAmout::Cashfree()->where('userid', $items->customer->id)->sum('amount');
                                                             }
                                                             else
                                                             {
                                                             $totalWalletamount = '';
                                                             $totalCashfreeAmount = '';
                                                             }
                                                            if($items->level_transaction_id !=''){
                                                                $levelTran = \App\Models\LevelTransaction::where('id', $items->level_transaction_id)->first();
                                                                $level = !empty($levelTran) ? $levelTran->level : '';
                                                                $comm = !empty($levelTran) ? $levelTran->commission : '';
                                                             }
                                                             else
                                                             {
                                                                $level ='';
                                                                $comm ='';
                                                             }
                                                            @endphp
                                                            <tr>
                                                            <td class="myfontsize" >{{ date('d-M-Y', strtotime($items->created_at)) }}</td>
                                                             <td class="myfontsize" >{{ $level }}</td>
                                                             <td class="myfontsize" >{{ $items->customer !='' ? $items->customer->name.'('.$items->customer->id.')' : '' }}</td>
                                                             <td class="myfontsize" >{{ $items->customerp !='' ? $items->customerp->name.'('.$items->customerp->id.')' : '' }}</td>
                                                             <td class="myfontsize" >{{ $comm }}</td>
                                                             <td class="myfontsize" >{{ $items->subscription !='' ? $items->subscription->offered_price : '' }}</td>
                                                             <td class="myfontsize" >{{ $totalWalletamount }}</td>
                                                             <td class="myfontsize" >{{ $totalCashfreeAmount }}</td>
                                                            <td class="myfontsize" >{{ $items->total_commission }}</td>
                                                            <td class="myfontsize" >{{ $items->total_tds }}</td>
                                                            <td>{{$items->total_admin_charges}}</td>
                                                            <td>{{$items->total_other_charges}}</td>
                                                            <td>{{ $items->total_earned}}</td>
                                                            <td>
                                                               <span class="{{ $items->status == 'approved' ? 'badge badge-success' : 'badge badge-danger' }} p-2">
                                                                  {{$items->status}}
                                                               </span>
                                                            </td>
                                                            <td>
                                                               <button type="button" class="btn btn-secondary" 
                                                                     data-payment="{{ $items->payment_method }}" 
                                                                     data-reason="{{ $items->reason }}" 
                                                                     data-image="{{ asset('storage/app/public/' . $items->image) }}" 
                                                                     id="previewPayment" 
                                                                     {{ $items->status !== 'approved' ? 'disabled' : '' }}>
                                                                     <i class="ic-eye">View</i>
                                                               </button>
                                                            </td>
                                                           </tr>
                                                            @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                               <tr>
                                                                  <th>Date & Time</th>
                                                                   <th>Level</th>
                                                                   <th>Subscription Buyer</br>Name(ID)</th>
                                                                    <th>Commission Receiver</br>Name(ID)</th>
                                                                    <th>Commission %</th>
                                                                    <th>Subscription Amount</th>
                                                                    <th>Welcome Bonus</th>
                                                                    <th>Wallet Amount</th>
                                                                   <th>Total Commission</th>
                                                                   <th>TDS</th>
                                                                   <th>Admin Charge</th>
                                                                   <th>Other Chage</th>
                                                                   <th>Earned</th>
                                                                   <th>Status</th>
                                                                   <th>Action</th>
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

