@extends('website.layout.layout')
@section('title', $page)
@section('content')
@include('website.partials.user_sidebar')

<div class="col-sm-12 col-md-9">
   <div class="profile-cont" style="overflow:auto;">
      <h3>
         {{$page}}
      </h3>
      <table class="table">
         <thead>
            <tr>
               
               <th>Date & Time</th>
               <th>Pool Name</th>
               <th>Total Commission</th>
               <th>TDS</th>
               <th>Admin Charge</th>
               <th>Other Chage</th>
               <th>Earned</th>
               <th>Status</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody id="sort-ads-html">
            @foreach ($commission as $items)
            @php 
            if($items->level_transaction_id !=''){
                $levelTran = \App\Models\LevelTransaction::where('id', $items->level_transaction_id)->first();
                $level = !empty($levelTran) ? $levelTran->level : '';
             }
             else
             {
                $level ='';
             }
            @endphp
            <tr>
            <td class="myfontsize" >{{ date('d-M-Y', strtotime($items->created_at)) }}</td>
             <td class="myfontsize" >{{ $level }}</td>
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
      </table>
   </div>
</div>
</section>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).on('click', '#previewPayment', function() {
    var paymentMethod = $(this).data('payment');
    var reason = $(this).data('reason');
    var imageUrl = $(this).data('image');
    console.log(imageUrl);
    // Display the SweetAlert dialog
    Swal.fire({
        title: 'Payment Preview',
        html: '<div class="payment-preview">'+'<p><strong>Payment Method:</strong> ' + paymentMethod + '</p>'+
               '<p><strong>Reason:</strong> ' + reason + '</p>' + '<img src="' + imageUrl + '" alt="Payment Image" class="img-fluid">'+'</div>',
        showCloseButton: true,
    });
});
</script>
@stop