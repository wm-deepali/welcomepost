@extends('website.layout.layout')
@section('title', $page)
@section('content')
@include('website.partials.user_sidebar')
	 		<div class="col-sm-12 col-md-9">
	 			<div class="profile-cont table-responsive">
	 				<h3>{{$page}}</h3>
	 				<table class="table">
	 					<thead>
	 						<tr>
	 							<th scope="col">#</th>
	                            <th scope="col">Ad Title</th>
	                            <th scope="col">Seller Name</th>
	                            <th scope="col">Name</th>
	                            <!--<th scope="col">Email</th>-->
	                            <th scope="col">Mobile</th>
	                            <th scope="col">Registered Date</th>
	                            <th scope="col">Status</th>
	                            <th scope="col">Action</th>
	                        </tr>
	                    </thead>
	                    <tbody id="sort-ads-html">
	                    	@if(isset($my_enquiry) && count($my_enquiry)>0)
                            @foreach($my_enquiry as $index=>$ads)
                            <?php
					                $result = DB::table('ads_postings')->where('id',$ads->post_id)->get();
					                ?>
	                    	<tr>
	                    		<th scope="row">{{$index+1}}</th>
	                            <td>
	                                <?php echo $result[0]->ad_title; ?>
					            </td>
					            <td>
	                                <?php echo $result[0]->fullname; ?>
					            </td>
	                            <td>{{$ads->name}}</td>
                                <!--<td>{{$ads->email}}</td>-->
                                <td>{{$ads->mobile}}</td>
                                <td>{{$ads->created_at}}</td>
                                <td>{{$ads->status == 'approved' ? 'Read':'Not readed'}}</td>
                                <td>
                                    @if($ads->isBlocked==0)
                                        @if($ads->status=="approved")
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#viewReplyModal{{$ads->id}}">View Reply</button>
                                        @else
                                            <button type="button" class="btn btn-danger" disabled>No Reply</button>
                                        @endif
                                    @endif
                                    @if($ads->isBlocked==0)
                                        <button type="button" class="btn btn-primary block_chat m-2" data-enquiry-id="{{$ads->id}}" data-sender-id="{{$ads->receiver_id}}">Block User</button>
                                    @else
                                        <button type="button" class="btn btn-primary unblock_chat m-2" data-enquiry-id="{{$ads->id}}" data-sender-id="{{$ads->receiver_id}}">UnBlock</button>
                                    @endif
                                </td>
	                        </tr>
                            @if($ads->status=="approved")
                            @php
                                $customerReply = App\Models\Customer::findOrFail($ads->receiver_id);
                            @endphp
                            <div class="modal fade" id="viewReplyModal{{$ads->id}}" tabindex="-1" role="dialog" aria-labelledby="viewReplyModalLabel{{$ads->id}}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewReplyModalLabel{{$ads->id}}">View Reply</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Name:</strong> {{$customerReply->name}}</p>
                                            <p><strong>Reply:</strong> {{$ads->reply ?? "No message sent"}}</p>
                                            <!--<p><strong>Email:</strong> {{$customerReply->email ?? "No email provided"}}</p>-->
                                            <p><strong>Phone:</strong> {{$customerReply->mobile ?? "No phone provided"}}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
	                        @endforeach
	                        @else
	                            <tr>
	                                <td class="no_found" style="width:132px !important;"colspan="7">No Ads Enquiry found !</td>
	                              
	                            </tr>
	                        @endif
	                    </tbody>
	                </table>
	            </div>
	        </div>
    	</div>
	</div>
</section>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(".unblock_chat").click(function() {
        let sender_id = $(this).data('sender-id');
        let enquiry_id = $(this).data('enquiry-id');
        $.ajax({
            url: '{{ url('block-chat') }}',
            method: 'POST',
            data: {
                sender_id: sender_id,
                enquiry_id: enquiry_id,
                '_token': "{{ csrf_token() }}"
            },
            success: function(data) {
                console.log(data);
                if (data.success) {
                    Swal.fire(data.message);
                    setTimeout(function() {
                        location.reload();
                    }, 40);
                }
            }
        });
    });
    $(".block_chat").click(function() {
        let sender_id = $(this).data('sender-id');
        let enquiry_id = $(this).data('enquiry-id');
    
        // Show the Swal popup
        Swal.fire({
            title: 'Block User',
            html: `
                <div>
                    <label for="reason">Select a reason for blocking:</label>
                    <select id="block_reason" class="swal2-input">
                        <option value="spam">Spam</option>
                        <option value="abusive language">Abusive Language</option>
                        <option value="inappropriate content">Inappropriate Content</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div id="custom_reason_container" style="display: none;">
                    <label for="custom_reason">Please specify:</label>
                    <textarea id="custom_reason" class="swal2-textarea"></textarea>
                </div>
                <div>
                    <p><p style="color:red;">Note: </p>Company will verify your block user action,<br> if you are wrong your account can be <br>suspended by the company.</p>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Block',
            preConfirm: () => {
                const blockReason = Swal.getPopup().querySelector('#block_reason').value;
                const customReason = Swal.getPopup().querySelector('#custom_reason').value;
    
                if (blockReason === 'other' && !customReason) {
                    Swal.showValidationMessage('Please provide a reason for blocking');
                    return false;
                }
    
                return {
                    blockReason: blockReason === 'other' ? customReason : blockReason
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const blockReason = result.value.blockReason;
    
                // Send the AJAX request with the block reason
                $.ajax({
                    url: '{{ url('block-chat') }}',
                    method: 'POST',
                    data: {
                        sender_id: sender_id,
                        enquiry_id: enquiry_id,
                        block_reason: blockReason,
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        console.log(data);
                        if (data.success) {
                            Swal.fire(data.message);
                            setTimeout(function() {
                                location.reload();
                            }, 40);
                        }
                    }
                });
            }
        });
        // Show/hide the custom reason textarea based on the dropdown selection
        $(document).on('change', '#block_reason', function() {
            if ($(this).val() === 'other') {
                $('#custom_reason_container').show();
            } else {
                $('#custom_reason_container').hide();
            }
        });
    });
</script>
@stop