@extends('admin.layout.layout')
@section('content')
  <div class="content-wrapper">
  
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Raise Ticket</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Raise Ticket</li>
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
              
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S.No</th>
                     <th>Image</th>
                    <th>Subject</th>
                    <th>Query</th>
                    <th>Registered Date</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  
                  @foreach($ticket as $key => $orderDetails)
                  <tr>
                    <td>{{$key + 1}}</td>
                    <td><img src="{{ $orderDetails->image}}" width="50" height="50"/>
					
					</td>
                    <td>{{$orderDetails->subject}}</td>
                    <td>{{$orderDetails->subject_query}}</td>
                    <td>{{$orderDetails->created_at}}</td>
                    <td>
                        <select class="form-control status-dropdown" data-ticket-id="{{ $orderDetails->id }}">
                            <option value="0" {{ $orderDetails->isResolved == 0 ? 'selected' : '' }}>Under Processing</option>
                            <option value="1" {{ $orderDetails->isResolved == 1 ? 'selected' : '' }}>Closed</option>
                        </select>
                    </td>
                  </tr>
				  
					@endforeach	
                  
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>S.No</th>
                    <th>Image</th>
                    <th>Subject</th>
                    <th>Query</th>
                    <th>Registered Date</th>
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
<script>
$(document).ready(function() {
    $('#example1').on('change', '.status-dropdown', function() {
        var ticketId = $(this).data('ticket-id');
        var newStatus = $(this).val();
        
        $.ajax({
            url: '{{ route('updateTicketStatus') }}',
            method: 'POST',
            data: {
                ticket_id: ticketId,
                new_status: newStatus,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Handle success response if needed
            },
            error: function(xhr, status, error) {
                // Handle error response if needed
            }
        });
    });
});
</script>

  @endsection