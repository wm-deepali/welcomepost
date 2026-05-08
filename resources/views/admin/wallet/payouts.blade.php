@extends('admin.layout.layout')
@section('content')
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>View Payouts </h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">View Payouts </li>
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
                  <div class="card-header p-2">
                     <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#current" data-toggle="tab">Payouts (Current Month)</a></li>
                        <li class="nav-item"><a class="nav-link" href="#pending" data-toggle="tab">Payouts (Pending)</a></li>
                        <li class="nav-item"><a class="nav-link" href="#released" data-toggle="tab">Payouts (Released)</a></li>
                     </ul>
                  </div>
                  <div class="card-body">
                     <div class="tab-content">
                        <div class="active tab-pane" id="current">
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
                                                      <th>Name</th>
                                                      <th>Mobile</th>
                                                      <th>Email</th>
                                                      <th>Total Earning</th>
                                                      <th>TDS</th>
                                                      <th>Admin Charges</th>
                                                      <th>Other Charges</th>
                                                      <th>Final Earning </th>
                                                      <th>Wallet Balance </th>
                                                      <th>Status</th>
                                                      <th>Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   @php
                                                      $count = 1;
                                                   @endphp
                                                   @foreach ($commission_sum as $commission)
                                                   @php
                                                      $customer = \App\Models\Customer::where('id', $commission->user_id)->first();
                                                   @endphp
                                                   
                                                   <tr>
                                                      <td>{{$count++}}</td>
                                                      <td>{{$customer->name ?? 'NA'}}</td>
                                                      <td>{{$customer->mobile ?? 'NA'}}</td>
                                                      <td>{{$customer->email ?? 'NA'}}</td>
                                                      <td>{{$commission->total_commission ?? '0.0'}}</td>
                                                      <td>{{$commission->total_tds_amount ?? '0.0'}}</td>
                                                      <td>{{$commission->total_admin_charges ?? '0.0'}}</td>
                                                      <td>{{$commission->total_other_charges ?? '0.0'}}</td>
                                                      <td>{{$commission->earnings ?? '0.0'}}</td>
                                                      <td>{{$customer->wallet_amount ?? '0.0'}}</td>
                                                      <td>{{$commission->comission_paid ?? '--'}}</td>
                                                      <td>
                                                         <a href="{{url('earnings-user/'.$commission->user_id)}}" class="btn btn-primary" style="font-size:10px;">All Earnings</a>
                                                      </td>
                                                   </tr> 
                                                   
                                                   @endforeach
                                                </tbody>
                                                <tfoot>
                                                   <tr>
                                                      <th>S.No</th>
                                                      <th>Name</th>
                                                      <th>Mobile</th>
                                                      <th>Email</th>
                                                      <th>Total Earning</th>
                                                      <th>TDS</th>
                                                      <th>Admin Charges</th>
                                                      <th>Other Charges</th>
                                                      <th>Final Earning </th>
                                                      <th>Wallet Balance </th>
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
           
                        <div class="tab-pane" id="pending">
                           <section class="content">
                              <div class="container-fluid">
                                 <div class="row">
                                    <div class="col-12">
                                       <div class="card">
                                          <!-- /.card-header -->
                                          <div class="card-body">
                                                <div class="d-flex justify-content-end mb-3">
                                                    <button type="button" class="btn btn-primary mr-2" id="openSwalDownload">Export Sample</button>
                                                    <input type="file" id="importFile" style="display: none;">
                                                    <button type="button" class="btn btn-success" id="importDataBtn">Import Data</button>
                                                </div>
                                             <table id="example3" class="table table-bordered table-striped">
                                                <thead>
                                                   <tr>
                                                      <th>S.No</th>
                                                      <th>Date & Time</th>
                                                      <th>Name</th>
                                                      <th>Mobile</th>
                                                      <th>Email</th>
                                                      <th>Total Earning</th>
                                                      <th>TDS</th>
                                                      <th>Admin Charges</th>
                                                      <th>Other Charges</th>
                                                      <th>Final Earning </th>
                                                      <th>Status</th>
                                                      <th>Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   @php
                                                      $count = 1;
                                                   @endphp
                                                @foreach ($pending_commission as $com)
                                                @php
                                                    $customer = \App\Models\Customer::where('id', $com->parent_id)->first();
                                                   
                                                @endphp
                                                 
                                                <tr>
                                                   <td>{{$count++}}</td>
                                                   <td>{{$com->created_at}}</td>
                                                   <td>{{$customer->name ?? 'NA'}}</td>
                                                   <td>{{$customer->mobile ?? 'NA'}}</td>
                                                   <td>{{$customer->email ?? 'NA'}}</td>
                                                   <td>{{$com->total_commission}}</td>
                                                   <td>{{$com->total_tds}}</td>
                                                   <td>{{$com->total_admin_charges}}</td>
                                                   <td>{{$com->total_other_charges}}</td>
                                                   <td>{{$com->total_earned}}</td>
                                                   <td> 
                                                      <span class="{{ $com->status == 'approved' ? 'badge badge-success' : 'badge badge-danger' }}">
                                                         {{$com->status}}
                                                      </span>
                                                   </td>
                                                   <td>
                                                      <a href="{{url('earnings-user/'.$com->parent_id)}}" class="btn btn-primary" style="font-size:10px;">All Earnings</a>
                                                      <button type="button" id="releasePaymentBtn" data-release-amount="{{$com->total_earned}}" data-commission-id="{{$com->id}}" class="btn btn-primary" style="font-size:10px;">Release Payment</button>
                                                   </td>
                                                </tr> 
                                                
                                                @endforeach
                                                </tbody>
                                                <tfoot>
                                                   <tr>
                                                      <th>S.No</th>
                                                      <th>Date & Time</th>
                                                      <th>Name</th>
                                                      <th>Mobile</th>
                                                      <th>Email</th>
                                                      <th>Total Earning</th>
                                                      <th>TDS</th>
                                                      <th>Admin Charges</th>
                                                      <th>Other Charges</th>
                                                      <th>Final Earning </th>
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
                        <div class="tab-pane" id="released">
                           <section class="content">
                              <div class="container-fluid">
                                 <div class="row">
                                    <div class="col-16">
                                       <div class="card">
                                          <div class="card-body">
                                          <table id="example3" class="table table-bordered table-striped">
                                                <thead>
                                                   <tr>
                                                      <th>S.No</th>
                                                      <th>Date & Time</th>
                                                      <th>Name</th>
                                                      <th>Mobile</th>
                                                      <th>Email</th>
                                                      <th>Transaction Id</th>
                                                      <th>Payment Date</th>
                                                      <th>Payment Method</th>
                                                      <th>Total Earning</th>
                                                      <th>TDS</th>
                                                      <th>Admin Charges</th>
                                                      <th>Other Charges</th>
                                                      <th>Final Earning</th>
                                                      <th>Status</th>
                                                      <th>Remarks</th>
                                                      <th>Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   @php
                                                      $count = 1;
                                                   @endphp
                                                   @foreach ($approved_commission as $com)
                                                   @php
                                                   $customer = \App\Models\Customer::where('id', $com->parent_id)->first();
                                                       
                                                   @endphp
                                                   
                                                   <tr>
                                                      <td>{{$count++}}</td>
                                                      <td>{{$com->created_at}}</td>
                                                      <td>{{$customer->name ?? 'NA'}}</td>
                                                      <td>{{$customer->mobile ?? 'NA'}}</td>
                                                      <td>{{$customer->email ?? 'NA'}}</td>
                                                      <td>{{$com->transaction_id}}</td>
                                                      <td>{{$com->payment_date}}</td>
                                                      <td>{{$com->payment_method}}</td>
                                                      <td>{{$com->total_commission}}</td>
                                                      <td>{{$com->total_tds}}</td>
                                                      <td>{{$com->total_admin_charges}}</td>
                                                      <td>{{$com->total_other_charges}}</td>
                                                      <td>{{$com->total_earned}}</td>
                                                      <td>
                                                         <span class="{{ $com->status == 'approved' ? 'badge badge-success' : 'badge badge-danger' }}">
                                                            {{$com->status}}
                                                         </span>
                                                      </td>
                                                      <td>{{$com->reason}}</td>
                                                      <td>
                                                        <a href="{{url('earnings-user/'.$com->parent_id)}}" class="btn btn-primary mb-1" style="font-size:10px;">All Earnings</a>
                                                        <a href="#" class="btn btn-primary" style="font-size:10px;">Payment Receipt</a>
                                                      </td>
                                                     
                                                   </tr>
                                                   
                                                   @endforeach
                                                </tbody>
                                                <tfoot>
                                                   <tr>
                                                      <th>S.No</th>
                                                      <th>Date & Time</th>
                                                      <th>Name</th>
                                                      <th>Mobile</th>
                                                      <th>Email</th>
                                                      <th>Transaction Id</th>
                                                      <th>Payment Date</th>
                                                      <th>Payment Method</th>
                                                      <th>Total Earning</th>
                                                      <th>TDS</th>
                                                      <th>Admin Charges</th>
                                                      <th>Other Charges</th>
                                                      <th>Final Earning</th>
                                                      <th>Status</th>
                                                      <th>Remarks</th>
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

                     </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
   // Click event handler for the button
   $('#openSwalDownload').on('click',function(){
        Swal.fire({
         title: 'Download sample payout data',
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Download',
         html: `
            <input type="month" id="monthPicker" class="form-control mb-3">
        `,
            
        }).then((result) => {
            
            if(result.isConfirmed){
                var selectedMonthYear = $('#monthPicker').val();
                window.location.href = '/customer-commission-export/'+selectedMonthYear;
                
            }
             
        });
   });
   $('#importDataBtn').on('click', function() {
        $('#importFile').click(); // Trigger file input click event
    });
    $('#importFile').on('change', function() {
        // Get the selected file
        var file = $(this)[0].files[0];
    
        // Check if a file is selected
        if (file) {
            // Create a FormData object to store the file
            var formData = new FormData();
            formData.append('importFile', file);
            formData.append('_token', "{{ csrf_token() }}");
    
            // Perform an AJAX request to upload the file
            $.ajax({
                url: '{{ route("customer-commission-import") }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                           Swal.fire(
                              'Released!',
                              'The bulk payments has been released.',
                              'success'
                           );
                           window.location.reload();
                        } else {
                           // Show error message if the update failed
                           alert('Failed to update status!');
                        }
                },
                error: function(xhr, status, error) {
                    // Handle error response, e.g., show an error message
                    console.error(xhr.responseText);
                }
            });
        }
    });
    $('#releasePaymentBtn').on('click', function() {
        var commissionId = $(this).data('commission-id');
        var releaseAmount = $(this).data('release-amount');
        Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, release it!',
            html:
                '<p class="swal2-text">You are releasing ₹' + releaseAmount + '</p>' +
                '<select id="swal-input-payment-method" class="swal2-input">' +
                '<option value="UPI">UPI</option>' +
                '<option value="Bank_Transfer">Bank Transfer(IMPS)</option>' +
                '</select>' +
                '<input id="swal-input-image" class="swal2-file" type="file" accept="image/*">' +
                '<input id="swal-input-transaction-id" class="swal2-input" placeholder="Transaction ID" type="text">' +
                '<input id="swal-input-payment-date" class="swal2-input" placeholder="Payment Date" type="date">' +
                '<input id="swal-input-reason" class="swal2-input" placeholder="Reason" type="textarea">'
        }).then((result) => {
            // If user confirms, perform the action
            if (result.isConfirmed) {
                var newStatus = 'approved';
                const reason = document.getElementById('swal-input-reason').value;
                const paymentMethod = document.getElementById('swal-input-payment-method').value;
                const imageFile = document.getElementById('swal-input-image').files[0];
                const transactionId = document.getElementById('swal-input-transaction-id').value;
                const paymentDate = document.getElementById('swal-input-payment-date').value;
    
                const formData = new FormData();
                formData.append('commissionId', commissionId);
                formData.append('newStatus', newStatus);
                formData.append('reason', reason);
                formData.append('paymentMethod', paymentMethod);
                formData.append('imageFile', imageFile);
                formData.append('transactionId', transactionId);
                formData.append('paymentDate', paymentDate);
                formData.append('_token', "{{ csrf_token() }}");
                $.ajax({
                    url: '{{route("commission-update-status")}}', // Specify the URL of your endpoint
                    method: 'POST',
                    data: formData,
                    contentType: false, // Set contentType to false when sending FormData
                    processData: false,
                    success: function(response) {
                        // Handle the response from the server
                        console.log(response);
                        if (response.success) {
                            Swal.fire(
                                'Released!',
                                'The payment has been released.',
                                'success'
                            );
                            window.location.reload();
                        } else {
                            // Show error message if the update failed
                            alert('Failed to update status!');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle AJAX errors
                        console.error(xhr.responseText);
                    }
                });
    
            }
        });
    });

});
</script>
@endsection